<?php

/**
 * Template part for blog single about author single.php. views/blog/components
 *
 * @author  Artbees
 * @package jupiter/views
 * @since   5.0.0
 * @since   5.9.1 Removed Author box if condition.
 */

global $mk_options;

?>
<div class="mk-about-author-wrapper">
	<div class="mk-about-author-meta" <?php echo get_schema_markup('author'); ?>>
		<?php if (mk_get_blog_single_style() != 'bold') : ?>
		<div class="avatar-wrapper"><?php global $user; echo get_avatar( get_the_author_meta('email'), '65',false ,get_the_author_meta('display_name', $user['ID'])); ?></div>
		<?php endif; ?>
		<?php if (mk_get_blog_single_style() == 'bold') : ?>
		<div class="about-author-title"><?php esc_html_e( 'About', 'mk_framework' ); ?></div>
		<?php endif; ?>
		<a class="about-author-name" href="<?php echo esc_url( get_author_posts_url(get_the_author_meta( 'ID' )) ); ?>" <?php echo get_schema_markup('url'); ?>><span <?php echo get_schema_markup('name'); ?>><?php the_author_meta('display_name'); ?></span></a>
		<div class="about-author-desc"><?php the_author_meta('description'); ?></div>
		<ul class="about-author-social">

			<?php if(get_the_author_meta( 'twitter' )) { ?>
				<li><a class="twitter-icon" title="<?php esc_html_e( 'Follow me on Twitter','mk_framework' ); ?>" href="<?php echo esc_url( get_the_author_meta( 'twitter' ) ); ?>" target="_blank"><?php Mk_SVG_Icons::get_svg_icon_by_class_name(true, 'mk-moon-twitter'); ?></a></li>
			<?php } ?>
			<?php if(get_post_meta( $post->ID, '_show_author_email', true ) != 'false') : ?>
				<?php if(get_the_author_meta('email')) { ?>
					<li><a class="email-icon" title="<?php esc_html_e( 'Get in touch with me via email','mk_framework' ); ?>" href="mailto:<?php echo sanitize_email( get_the_author_meta('email') ); ?>" target="_blank"><?php Mk_SVG_Icons::get_svg_icon_by_class_name(true, 'mk-moon-envelop'); ?></a></li>
				<?php } ?>
			<?php endif; ?>
			<?php if(get_the_author_meta( 'facebook' )) { ?>
			   <li><a class="facebook-icon" title="<?php esc_html_e( 'Follow me on Facebook','mk_framework' ); ?>" href="<?php echo esc_url( get_the_author_meta( 'facebook' ) ); ?>" target="_blank"><?php Mk_SVG_Icons::get_svg_icon_by_class_name(true, 'mk-moon-facebook'); ?></a></li>
			<?php } ?>

			<?php if(get_the_author_meta( 'googleplus' )) { ?>
			   <li><a class="googleplus-icon" title="<?php esc_html_e( 'Follow me on Google+','mk_framework' ); ?>" href="<?php echo esc_url( get_the_author_meta( 'googleplus' ) ); ?>" target="_blank"><?php Mk_SVG_Icons::get_svg_icon_by_class_name(true, 'mk-moon-google-plus'); ?></a></li>
			<?php } ?>
		</ul>
	</div>
	<div class="clearboth"></div>
</div>
    