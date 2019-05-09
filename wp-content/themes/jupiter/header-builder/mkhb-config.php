<?php
/**
 * Header Builder: Configuation file.
 *
 * @since 5.9.0
 * @since 5.9.3 Add HB_ASSETS_URI and HB_ASSETS_URI.
 * @since 6.0.0 Rename filename and remove unused global header constant.
 *
 * @package Header_Builder
 */

/**
 * After 5.9.6, HB_DB_VERSION should be updated if the data structure changed.
 *
 * @see HB_Migration run_migration()
 * @see header-builder/includes/libs/migration.php
 */
define( 'HB_DB_VERSION', 2 );

define( 'HB_DIR', dirname( __FILE__ ) );
define( 'HB_INCLUDES_DIR', HB_DIR . '/includes/' );
define( 'HB_ADMIN_DIR', HB_DIR . '/admin/' );
define( 'HB_URI', get_template_directory_uri() . '/header-builder/' );
define( 'HB_ASSETS_URI', HB_URI . 'includes/assets/' );
