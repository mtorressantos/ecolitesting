<?php
/**
 * Export and Import API: MK_Export_Import base class
 *
 * @package Jupiter
 * @subpackage MK_Export
 * @since 6.0.3
 */

/**
 * Export/Import Site Content, Widgets, Customizer and Header Builder.
 *
 * @author Artbees Team
 * @since 6.0.3
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexitys)
 */
class MK_Export_Import {

	/**
	 * $mkfs instance.
	 *
	 * @since 6.0.3
	 * @var array
	 */
	private $mkfs;

	/**
	 * $template_management instance.
	 *
	 * @since 6.0.3
	 * @var array
	 */
	private $template_management;

	/**
	 * Export and Import directory’s path and url.
	 *
	 * @since 6.0.3
	 * @var array
	 */
	private $folder = array();

	/**
	 * Constructor.
	 *
	 * @since 6.0.3
	 */
	public function __construct() {
		$upload_dir                 = wp_upload_dir();
		$this->folder['export_url'] = $upload_dir['baseurl'] . '/mk_export';
		$this->folder['export_dir'] = $upload_dir['basedir'] . '/mk_export';
		$this->folder['import_url'] = $upload_dir['baseurl'] . '/mk_import';
		$this->folder['import_dir'] = $upload_dir['basedir'] . '/mk_import';

		// Instantiate template management.
		$this->template_management = new mk_template_managememnt();

		add_action( 'wp_ajax_mk_cp_export_import', array( $this, 'ajax_handler' ) );
	}

	/**
	 * Map the requests to proper methods.
	 *
	 * @since 6.0.3
	 */
	public function ajax_handler() {
		check_ajax_referer( 'mk_control_panel', 'nonce' );

		$type          = filter_input( INPUT_POST, 'type' );
		$step          = filter_input( INPUT_POST, 'step' );
		$attachment_id = filter_input( INPUT_POST, 'attachment_id' );

		if ( empty( $type ) ) {
			wp_send_json_error(
				__( 'Type param is missing.', 'mk_framework' )
			);
		}

		if ( empty( $step ) ) {
			wp_send_json_error(
				__( 'Step param is missing.', 'mk_framework' )
			);
		}

		if ( 'Export' === $type ) {
			$this->mkfs = new Mk_Fs(
				array(
					'context' => $this->folder['export_dir'],
				)
			);
			return $this->export( $step );
		}

		if ( 'Import' === $type ) {

			if ( empty( $attachment_id ) ) {
				wp_send_json_error(
					__( 'Attachment ID param is missing.', 'mk_framework' )
				);
			}

			$this->mkfs = new Mk_Fs(
				array(
					'context' => $this->folder['import_dir'],
				)
			);
			return $this->import( $step, $attachment_id );
		}

		wp_send_json_error(
			sprintf( __( 'Type param (%s) is not valid.', 'mk_framework' ), $type )
		);
	}

	/**
	 * Run proper export method based on step.
	 *
	 * @since 6.0.3
	 * @param string $step The export step.
	 * @return void
	 */
	private function export( $step ) {
		switch ( $step ) {
			case 'Start':
				$this->export_start();
				break;

			case 'Content':
				$this->export_content();
				break;

			case 'Widgets':
				$this->export_widgets();
				break;

			case 'Theme Options':
				$this->export_theme_options();
				break;

			case 'Customizer':
				$this->export_customizer();
				break;

			case 'End':
				$this->export_end();
				break;

			case 'Discard':
				$this->discard( $this->folder['export_dir'] );
				break;
		}

		wp_send_json_error(
			sprintf( __( 'Step param (%s) is not valid.', 'mk_framework' ), $step )
		);
	}

	/**
	 * Start export process by cleaning the export directory.
	 *
	 * @throws Exception If can not clean export folder.
	 *
	 * @since 6.0.3
	 */
	private function export_start() {
		try {
			if ( $this->mkfs->rmdir( $this->folder['export_dir'], true ) ) {
				return wp_send_json_success( array(
					'step' => 'Start',
				) );
			}

			throw new Exception( __( 'A problem occurred in cleaning export directory.', 'mk_framework' ) );
		} catch ( Exception $e ) {
			return wp_send_json_error( $e->getMessage() );
		}
	}

	/**
	 * Export content.
	 *
	 * @throws Exception If can not export Content.
	 *
	 * @since 6.0.3
	 */
	private function export_content() {
		try {
			require_once ABSPATH . 'wp-admin/includes/export.php';

			ob_start();
			export_wp();
			$content = ob_get_clean();
			ob_end_clean();
			header_remove();

			$file_name = 'theme_content.xml';
			$file_path = $this->folder['export_dir'] . '/' . $file_name;

			if ( ! $this->mkfs->put_contents( $file_path, $content ) ) {
				throw new Exception( __( 'A problem occurred in exporting Content.', 'mk_framework' ) );
			}

			if ( ! $this->export_header_builder() ) {
				throw new Exception( __( 'A problem occurred in exporting Header Builder.', 'mk_framework' ) );
			}

			return wp_send_json_success( array(
				'step' => 'Content',
			) );

		} catch ( Exception $e ) {
			return wp_send_json_error( $e->getMessage() );
		}
	}

	/**
	 * Export widgets.
	 *
	 * @throws Exception If can not export Widgets.
	 *
	 * @since 6.0.3
	 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
	 */
	private function export_widgets() {
		try {
			$available_widgets = $this->template_management->availableWidgets();

			// Get all widget instances for each widget.
			$widget_instances = array();

			// Loop widgets.
			foreach ( $available_widgets as $widget_data ) {
				// Get all instances for this ID base.
				$instances = get_option( 'widget_' . $widget_data['id_base'] );
				// Have instances.
				if ( ! empty( $instances ) ) {
					// Loop instances.
					foreach ( $instances as $instance_id => $instance_data ) {
						// Key is ID (not _multiwidget).
						if ( is_numeric( $instance_id ) ) {
							$unique_instance_id                      = $widget_data['id_base'] . '-' . $instance_id;
							$widget_instances[ $unique_instance_id ] = $instance_data;
						}
					}
				}
			}

			// Gather sidebars with their widget instances.
			$sidebars_widgets    = get_option( 'sidebars_widgets' );
			$sidebars_widget_ins = array();
			foreach ( $sidebars_widgets as $sidebar_id => $widget_ids ) {

				// Skip inactive widgets.
				if ( 'wp_inactive_widgets' === $sidebar_id ) {
					continue;
				}

				// Skip if no data or not an array (array_version).
				if ( ! is_array( $widget_ids ) || empty( $widget_ids ) ) {
					continue;
				}

				// Loop widget IDs for this sidebar.
				foreach ( $widget_ids as $widget_id ) {
					// Is there an instance for this widget ID?
					if ( isset( $widget_instances[ $widget_id ] ) ) {
						// Add to array.
						$sidebars_widget_ins[ $sidebar_id ][ $widget_id ] = $widget_instances[ $widget_id ];
					}
				}
			}

			$content = wp_json_encode( $sidebars_widget_ins );

			$file_name = 'widget_data.wie';
			$file_path = $this->folder['export_dir'] . '/' . $file_name;

			if ( $this->mkfs->put_contents( $file_path, $content ) ) {
				return wp_send_json_success( array(
					'step' => 'Widgets',
				) );
			}

			throw new Exception( __( 'A problem occurred in exporting widgets.', 'mk_framework' ) );
		} catch ( Exception $e ) {
			return wp_send_json_error( $e->getMessage() );
		} // End try().
	}

	/**
	 * Export Theme Options.
	 *
	 * @throws Exception If can not export Theme Options.
	 *
	 * @since 6.0.3
	 */
	private function export_theme_options() {
		try {
			$file_name = 'options.txt';
			$content = base64_encode( serialize( get_option( THEME_OPTIONS ) ) ); // @codingStandardsIgnoreLine
			$file_path = $this->folder['export_dir'] . '/' . $file_name;

			if ( ! $this->mkfs->put_contents( $file_path, $content ) ) {
				throw new Exception( __( 'A problem occurred in exporting Theme Options.', 'mk_framework' ) );
			}

			return wp_send_json_success( array(
				'step' => 'Theme Options',
			) );

		} catch ( Exception $e ) {
			return wp_send_json_error( $e->getMessage() );
		}
	}

	/**
	 * Export Customizer.
	 *
	 * @throws Exception If can not export Customizer.
	 *
	 * @since 6.0.3
	 */
	private function export_customizer() {
		try {
			$file_name = 'customizer.json';
			$content   = get_option( 'mk_cz' );
			$file_path = $this->folder['export_dir'] . '/' . $file_name;

			if ( ! is_array( $content ) ) {
				throw new Exception( __( 'All settings in Customizer are set to default. Uncheck the Customizer option or change one setting in Customizer then export.', 'mk_framework' ) );
			}

			if ( ! $this->mkfs->put_contents( $file_path, wp_json_encode( $content ) ) ) {
				throw new Exception( __( 'A problem occurred in exporting Customizer.', 'mk_framework' ) );
			}

			return wp_send_json_success( array(
				'step' => 'Customizer',
			) );

		} catch ( Exception $e ) {
			return wp_send_json_error( $e->getMessage() );
		}
	}

	/**
	 * Export Header Builder. It only includes global header option.
	 *
	 * @throws Exception If can not export Header Builder.
	 *
	 * @since 6.0.3
	 */
	private function export_header_builder() {
		try {
			$file_name = 'header-builder.json';
			$content   = get_option( 'mkhb_global_header' );
			$file_path = $this->folder['export_dir'] . '/' . $file_name;

			if ( ! $content ) {
				return true;
			}

			if ( $this->mkfs->put_contents( $file_path, wp_json_encode( $content ) ) ) {
				return true;
			}

			throw new Exception( __( 'A problem occurred in exporting header builder.', 'mk_framework' ) );
		} catch ( Exception $e ) {
			return wp_send_json_error( $e->getMessage() );
		}
	}

	/**
	 * End export process by creating the zip file and download url.
	 *
	 * @since 6.0.3
	 */
	private function export_end() {
		try {
			$sitename = sanitize_key( get_bloginfo( 'name' ) );

			if ( empty( $sitename ) ) {
				$sitename = 'package';
			}

			$date       = date( 'Y-m-d' );
			$foldername = $this->folder['export_dir'] . '/' . $sitename . '.' . $date . '.zip';

			$this->mkfs->zip_custom( array(
				'theme_content.xml'   => $this->folder['export_dir'] . '/theme_content.xml',
				'widget_data.wie'     => $this->folder['export_dir'] . '/widget_data.wie',
				'options.txt'         => $this->folder['export_dir'] . '/options.txt',
				'customizer.json'     => $this->folder['export_dir'] . '/customizer.json',
				'header-builder.json' => $this->folder['export_dir'] . '/header-builder.json',
			), $foldername );

			return wp_send_json_success( array(
				'step'         => 'End',
				'download_url' => $this->folder['export_url'] . '/' . $sitename . '.' . $date . '.zip',
			) );

		} catch ( Exception $e ) {
			return wp_send_json_error( $e->getMessage() );
		}
	}

	/**
	 * Run proper import method based on step.
	 *
	 * @since 6.0.3
	 * @param string  $step          The import step.
	 * @param integer $attachment_id The uploaded zip file ID.
	 * @return void
	 */
	private function import( $step, $attachment_id ) {
		switch ( $step ) {
			case 'Start':
				$this->import_start( $attachment_id );
				break;

			case 'Content':
				$this->import_content();
				$this->import_header_builder();
				break;

			case 'Widgets':
				$this->import_widgets();
				break;

			case 'Theme Options':
				$this->import_theme_options();
				break;

			case 'Customizer':
				$this->import_customizer();
				break;

			case 'End':
				$this->import_end();
				break;

			case 'Discard':
				$this->discard( $this->folder['import_dir'] );
				break;
		}

		wp_send_json_error(
			sprintf( __( 'Step param (%s) is not valid.', 'mk_framework' ), $step )
		);
	}

	/**
	 * Start import process by cleaning import directory and
	 * unzipping file to directory Import directory.
	 *
	 * @since 6.0.3
	 * @param integer $attachment_id The uploaded zip file ID.
	 */
	private function import_start( $attachment_id ) {
		try {
			$this->mkfs->rmdir( $this->folder['import_dir'], true );

			$this->mkfs->unzip_custom(
				get_attached_file( $attachment_id ),
				$this->folder['import_dir']
			);

			return wp_send_json_success( array(
				'step' => 'Start',
			) );
		} catch ( Exception $e ) {
			return wp_send_json_error( $e->getMessage() );
		}
	}

	/**
	 * Import Content
	 *
	 * @throws Exception If required file is missing.
	 * @throws Exception If can not parse file..
	 *
	 * @since 6.0.3
	 */
	private function import_content() {
		try {
			$file_name         = 'theme_content.xml';
			$file              = $this->folder['import_dir'] . '/' . $file_name;
			$fetch_attachments = true;

			if ( ! file_exists( $file ) ) {
				throw new Exception(
					sprintf( __( 'A required file (%s) is missing in the selected zip file.', 'mk_framework' ), $file_name )
				);
			}

			// Include wordpress-importer class.
			Abb_Logic_Helpers::include_wordpress_importer();

			$options = array(
				'fetch_attachments' => filter_var( $fetch_attachments, FILTER_VALIDATE_BOOLEAN ),
				'default_author'    => get_current_user_id(),
			);

			// Create new instance for Importer.
			$importer = new WXR_Importer( $options );
			$logger   = new WP_Importer_Logger_ServerSentEvents();
			$importer->set_logger( $logger );

			$data = $importer->get_preliminary_information( $file );

			if ( is_wp_error( $data ) ) {
				throw new Exception(
					sprintf( __( 'Error in parsing %s.', 'mk_framework' ), $file_name )
				);
			}

			// Run import process.
			ob_start();
			$importer->import( $file );
			ob_end_clean();

			return wp_send_json_success( array(
				'step' => 'Content',
			) );

		} catch ( Exception $e ) {
			return wp_send_json_error( $e->getMessage() );
		} // End try().
	}

	/**
	 * Import Widgets.
	 *
	 * @throws Exception If required file is missing.
	 * @throws Exception If can not import Widgets.
	 *
	 * @since 6.0.3
	 */
	private function import_widgets() {
		try {
			$file_name = 'widget_data.wie';

			if ( ! file_exists( $this->folder['import_dir'] . '/' . $file_name ) ) {
				throw new Exception(
					sprintf( __( 'A required file (%s) is missing in the selected zip file.', 'mk_framework' ), $file_name )
				);
			}

			$import_data = Abb_Logic_Helpers::getFileBody(
				$this->folder['import_url'] . '/' . $file_name,
				$this->folder['import_dir'] . '/' . $file_name
			);

			$data = json_decode( $import_data );

			if ( ! $this->template_management->import_widget_data( $data ) ) {
				throw new Exception( __( 'A problem occurred in importing Widgets.', 'mk_framework' ) );
			}

			return wp_send_json_success( array(
				'step' => 'Widgets',
			) );

		} catch ( Exception $e ) {
			return wp_send_json_error( $e->getMessage() );
		}
	}

	/**
	 * Import Theme Options.
	 *
	 * @throws Exception If required file is missing.
	 * @throws Exception If can not import Theme Options.
	 *
	 * @since 6.0.3
	 */
	private function import_theme_options() {
		try {
			$file_name = 'options.txt';

			if ( ! file_exists( $this->folder['import_dir'] . '/' . $file_name ) ) {
				throw new Exception(
					sprintf( __( 'A required file (%s) is missing in the selected zip file.', 'mk_framework' ), $file_name )
				);
			}

			$import_data = Abb_Logic_Helpers::getFileBody(
				$this->folder['import_url'] . '/' . $file_name,
				$this->folder['import_dir'] . '/' . $file_name
			);

			$data = unserialize( base64_decode( $import_data ) ); // @codingStandardsIgnoreLine

			delete_option( THEME_OPTIONS );

			if ( ! update_option( THEME_OPTIONS, $data ) ) {
				throw new Exception( __( 'A problem occurred in importing Theme Options.', 'mk_framework' ) );
			}

			return wp_send_json_success( array(
				'step' => 'Theme Options',
			) );

		} catch ( Exception $e ) {
			return wp_send_json_error( $e->getMessage() );
		}
	}

	/**
	 * Import Customizer.
	 *
	 * @throws Exception If required file is missing.
	 * @throws Exception If can not import Customizer.
	 *
	 * @since 6.0.3
	 */
	private function import_customizer() {
		try {
			$file_name = 'customizer.json';

			if ( ! file_exists( $this->folder['import_dir'] . '/' . $file_name ) ) {
				throw new Exception(
					sprintf( __( 'A required file (%s) is missing in the selected zip file.', 'mk_framework' ), $file_name )
				);
			}

			$import_data = Abb_Logic_Helpers::getFileBody(
				$this->folder['import_url'] . '/' . $file_name,
				$this->folder['import_dir'] . '/' . $file_name
			);

			$data = json_decode( $import_data, true );

			delete_option( 'mk_cz' );

			if ( ! update_option( 'mk_cz', $data ) ) {
				throw new Exception( __( 'A problem occurred in importing Customizer.', 'mk_framework' ) );
			}

			return wp_send_json_success( array(
				'step' => 'Customizer',
			) );

		} catch ( Exception $e ) {
			return wp_send_json_error( $e->getMessage() );
		}
	}

	/**
	 * Import Header Builder.
	 *
	 * @throws Exception If can not import Header Builder.
	 *
	 * @since 6.0.3
	 */
	private function import_header_builder() {
		try {
			$file_name = 'header-builder.json';

			if ( ! file_exists( $this->folder['import_dir'] . '/' . $file_name ) ) {
				return true;
			}

			$import_data = Abb_Logic_Helpers::getFileBody(
				$this->folder['import_url'] . '/' . $file_name,
				$this->folder['import_dir'] . '/' . $file_name
			);

			$data = json_decode( $import_data, true );

			delete_option( 'mkhb_global_header' );

			if ( ! update_option( 'mkhb_global_header', $data ) ) {
				throw new Exception( __( 'A problem occurred in importing Header Builder.', 'mk_framework' ) );
			}

			return true;
		} catch ( Exception $e ) {
			return wp_send_json_error( $e->getMessage() );
		}
	}

	/**
	 * End Import process by deleting Import directory and clearing theme cache.
	 *
	 * @since 6.0.3
	 */
	private function import_end() {
		try {

			$this->mkfs->rmdir( $this->folder['import_dir'], true );

			$this->clear_theme_cache();

			return wp_send_json_success( array(
				'step' => 'End',
			) );

		} catch ( Exception $e ) {
			return wp_send_json_error( $e->getMessage() );
		}
	}

	/**
	 * Clear theme cache.
	 *
	 * @since 6.0.3
	 */
	private function clear_theme_cache() {
		$static = new Mk_Static_Files( false );
		$static->DeleteThemeOptionStyles( true );
	}

	/**
	 * Discard Export/Import process by deleting the the directory.
	 *
	 * @since 6.0.3
	 * @param string $dir The Export/Import directory.
	 */
	private function discard( $dir ) {
		try {
			$this->mkfs->rmdir( $dir, true );

			return wp_send_json_success( array(
				'step' => 'Discard',
			) );

		} catch ( Exception $e ) {
			return wp_send_json_error( $e->getMessage() );
		}
	}

}

new MK_Export_Import();
