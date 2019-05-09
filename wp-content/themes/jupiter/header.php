<?php
/**
 * The Header
 *
 * This is the template that displays all of the <head> section and everything up until
 * <div id="mk-theme-container">.
 *
 * @package Jupiter
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> >
<?php do_action( 'mk_theme_before_head' ); ?>
<head>
	<?php do_action( 'mk_theme_after_head_opening' ); ?>
	<?php wp_head(); ?>
	<?php do_action( 'mk_theme_before_head_closing' ); ?>
</head>

<body <?php body_class( mk_get_body_class( global_get_post_id() ) ); ?> <?php echo get_schema_markup( 'body' ); // WPCS: XSS OK. ?> data-adminbar="<?php echo esc_attr( is_admin_bar_showing() ); ?>">

	<?php do_action( 'mk_theme_after_body_opening' ); ?>

	<?php
		// Hook when you need to add content right after body opening tag. to be used in child themes or customisations.
		do_action( 'theme_after_body_tag_start' );
	?>

	<!-- Target for scroll anchors to achieve native browser bahaviour + possible enhancements like smooth scrolling -->
	<div id="top-of-page"></div>

		<div id="mk-boxed-layout">

			<div id="mk-theme-container" <?php echo is_header_transparent( 'class="trans-header"' ); ?>>

				<?php
				mk_get_header_view( 'styles', 'header-' . get_header_style() );
