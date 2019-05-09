<?php
/**
 * Render 'Navigation' element to the front end.
 *
 * @todo  Validate GET 'element' request.
 *
 * @package Header_Builder
 * @subpackage Elements_Generator
 * @since 5.9.0
 */

?>
<!DOCTYPE html>
<?php require_once( HB_INCLUDES_DIR . '/elements/class-hb-element.php' ); ?>
<?php require_once( HB_INCLUDES_DIR . '/elements/class-hb-element-navigation.php' ); ?>
<html class="html" <?php language_attributes(); ?>>
	<head>
		<?php wp_head(); ?>
		<?php $element = $_GET['element']; // WPCS: XSS ok, CSRF ok. ?>
		<?php $element = new HB_Element_Navigation( $element, 0, 0, 0 ); ?>
	</head>

	<body style="background: transparent;">
		<?php $element->render(); ?>
		<style>
			html.html {
				margin-top: 0 !important;
			}
			.mk-main-navigation {
				margin-left: -1px !important;
			}
		</style>
	</body>
	<script type="text/javascript">
		jQuery('a').on('click', function(e) {
			e.preventDefault();
		})
	</script>
	<?php show_admin_bar( false ); ?>
	<?php wp_footer(); ?>
</html>
