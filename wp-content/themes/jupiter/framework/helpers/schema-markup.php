<?php

if ( ! defined( 'THEME_FRAMEWORK' ) ) {
	exit( 'No direct script access allowed' );
}


/**
 * Schema.org addtions for better SEO
 *
 * @param   string  Type of the element
 * @return  string  HTML Attribute
 */

function get_schema_markup( $type, $echo = false ) {

	if ( empty( $type ) ) {
		return false;
	}

		$attributes = '';
	$attr = array();

	switch ( $type ) {
		case 'body':
			$attr['itemscope'] = 'itemscope';
			$attr['itemtype'] = 'https://schema.org/WebPage';
			break;

		case 'header':
			$attr['role'] = 'banner';
			$attr['itemscope'] = 'itemscope';
			$attr['itemtype'] = 'https://schema.org/WPHeader';
			break;

		case 'nav':
			$attr['role'] = 'navigation';
			$attr['itemscope'] = 'itemscope';
			$attr['itemtype'] = 'https://schema.org/SiteNavigationElement';
			break;

		case 'title':
			$attr['itemprop'] = 'headline';
			break;

		case 'sidebar':
			$attr['role'] = 'complementary';
			$attr['itemscope'] = 'itemscope';
			$attr['itemtype'] = 'https://schema.org/WPSideBar';
			break;

		case 'footer':
			$attr['role'] = 'contentinfo';
			$attr['itemscope'] = 'itemscope';
			$attr['itemtype'] = 'https://schema.org/WPFooter';
			break;

		case 'main':
			$attr['role'] = 'main';
			$attr['itemprop'] = 'mainContentOfPage';
			if ( is_search() ) {
				$attr['itemtype'] = 'https://schema.org/SearchResultsPage';
			}

			break;

		case 'author':
			$attr['itemprop'] = 'author';
			$attr['itemscope'] = 'itemscope';
			$attr['itemtype'] = 'https://schema.org/Person';
			break;

		case 'person':
			$attr['itemscope'] = 'itemscope';
			$attr['itemtype'] = 'https://schema.org/Person';
			break;

		case 'comment':
			$attr['itemprop'] = 'comment';
			$attr['itemscope'] = 'itemscope';
			$attr['itemtype'] = 'https://schema.org/Comment';
			break;

		case 'comment_author':
			$attr['itemprop'] = 'creator';
			$attr['itemscope'] = 'itemscope';
			$attr['itemtype'] = 'https://schema.org/Person';
			break;

		case 'comment_author_link':
			$attr['itemprop'] = 'creator';
			$attr['itemscope'] = 'itemscope';
			$attr['itemtype'] = 'https://schema.org/Person';
			$attr['rel'] = 'external nofollow';
			break;

		case 'comment_time':
			$attr['itemprop'] = 'datePublished';
			$attr['itemscope'] = 'itemscope';
			$attr['datetime'] = get_the_time( 'c' );
			break;

		case 'comment_text':
			$attr['itemprop'] = 'text';
			break;

		case 'author_box':
			$attr['itemprop'] = 'author';
			$attr['itemtype'] = 'https://schema.org/Person';
			break;

		case 'video':
			$attr['itemprop'] = 'video';
			$attr['itemtype'] = 'https://schema.org/VideoObject';
			break;

		case 'audio':
			$attr['itemscope'] = 'itemscope';
			$attr['itemtype'] = 'https://schema.org/AudioObject';
			break;

		case 'blog':
			$attr['itemscope'] = 'itemscope';
			$attr['itemtype'] = 'https://schema.org/Blog';
			break;

		case 'blog_posting':
			$attr['itemscope'] = 'itemscope';
			$attr['itemprop'] = 'blogPost';
			$attr['itemtype'] = 'http://schema.org/BlogPosting';
			break;

		case 'name':
			$attr['itemprop'] = 'name';
			break;

		case 'url':
			$attr['itemprop'] = 'url';
			break;

		case 'email':
			$attr['itemprop'] = 'email';
			break;

		case 'job':
			$attr['itemprop'] = 'jobTitle';
			break;

		case 'post_time':
						$attr['itemprop'] = 'datePublished';
			$attr['datetime'] = get_the_time( 'c', $args['id'] );
			break;

		case 'post_title':
			$attr['itemprop'] = 'headline';
			break;

		case 'post_content':
			$attr['itemprop'] = 'text';
			break;
		case 'publisher':
			$attr['itemprop'] = 'publisher';
			$attr['itemtype'] = 'https://schema.org/Organization';
			break;
	}

	foreach ( $attr as $key => $value ) {
		$attributes .= $key . '="' . $value . '" ';
	}

	if ( $echo ) {
		echo $attributes;
	} else {
		return $attributes;
	}
}
if ( ! function_exists( 'mk_structured_data_img_attr' ) ) {

	/**
	 * This function adds itemprop = image proprety to image attachments and by doing so improves structured data of a page
	 *
	 * @author      Zeljko Dzafic
	 * @copyright   Artbees LTD (c)
	 * @link        http://artbees.net
	 * @since       Version 5.3
	 */

	function mk_structured_data_img_attr( $attr ) {

		$attr['itemprop'] = 'image';
		return $attr;
	}
}

if ( ! function_exists( 'mk_structured_data_meta_tags' ) ) {
	/**
	 * This function will add structured markup data in form of meta tags
	 * data that is added contain (author,datePublished, dateModified,publisher)
	 *
	 * @author      Zeljko Dzafic
	 * @copyright   Artbees LTD (c)
	 * @link        http://artbees.net
	 * @since       Version 5.3
	 */

	function mk_structured_data_meta_tags() {

		echo '<meta itemprop="author" content="' . get_the_author() . '" />';
		echo '<meta itemprop="datePublished" content="' . get_the_date() . '" />';
		echo '<meta itemprop="dateModified" content="' . get_the_modified_date() . '" />';
		echo '<meta itemprop="publisher" content="' . get_bloginfo( 'name' ) . '" />';

	}
}
if ( ! function_exists( 'mk_structured_data_meta' ) ) {
	/**
	 * This function will
	 * a) create image filter that will call helper function mk_structured_data_img_attr
	 * b) add action that will be called on wp_head and call mk_structured_data_meta_tags
	 *
	 * @author      Zeljko Dzafic
	 * @copyright   Artbees LTD (c)
	 * @link        http://artbees.net
	 * @since       Version 5.3
	 */
	function mk_structured_data_meta() {
		add_action( 'blog_single_after_the_content', 'mk_structured_data_post_meta_hidden' );
		add_filter( 'wp_get_attachment_image_attributes', 'mk_structured_data_img_attr', 10, 2 );
		add_action( 'wp_head', 'mk_structured_data_meta_tags' );
	}
}

if ( ! function_exists( 'mk_structured_data_post_meta_hidden' ) ) {

	/**
	 * This function will add structured markup data to blog post
	 * all data is wrapped with single hidden container
	 * data that is added contain (author,datePublished, dateModified,publisher,logo, image)
	 *
	 * @author      Zeljko Dzafic
	 * @copyright   Artbees LTD (c)
	 * @link        http://artbees.net
	 * @since       Version 5.3
	 */
	function mk_structured_data_post_meta_hidden() {
		global $mk_options;
		global $post;
		global $structured_data_headline;
		global $structured_data_datePublished;
		global $structured_data_dateModified;
		global $structured_data_publisher;
		global $structured_data_image;
		$post_id = $post->ID;

		if ( function_exists( 'has_post_thumbnail' ) ) {
			if ( has_post_thumbnail( $post_id ) ) {
				$thumbnail = get_the_post_thumbnail_url( $post_id );
			}
		}

		$thumbnail = ( ! empty( $thumbnail )) ? $thumbnail : $mk_options['logo'];

		echo '<div class="mk-post-meta-structured-data" style="display:none;visibility:hidden;">';
		if ( empty( $structured_data_headline ) ) {
			echo '<span itemprop="headline">' . get_the_title() . '</span>';
			$structured_data_headline = true;
		}
		if ( empty( $structured_data_datePublished ) ) {
			echo '<span itemprop="datePublished">' . get_the_date( 'Y-m-d' ) . '</span>';
			$structured_data_datePublished = true;
		}
		if ( empty( $structured_data_dateModified ) ) {
			echo '<span itemprop="dateModified">' . get_the_modified_date( 'Y-m-d' ) . '</span>';
			$structured_data_dateModified = true;
		}
		if ( empty( $structured_data_publisher ) ) {
			echo  '<span itemprop="publisher" itemscope itemtype="https://schema.org/Organization">';
				echo  '<span itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">';
					echo  '<span itemprop="url">' . $mk_options['logo'] . '</span>';
				echo  '</span>';
				echo  '<span itemprop="name">' . get_bloginfo( 'name' ) . '</span>';
			echo  '</span>';
			$structured_data_publisher = true;
		}
		if ( empty( $structured_data_image ) ) {
			echo '<span itemprop="image" itemscope itemtype="https://schema.org/ImageObject">';
				echo '<span itemprop="contentUrl url">' . $thumbnail . '</span>';
			echo '<span  itemprop="width">200px</span>';
			echo '<span itemprop="height">200px</span>';
			echo  '</span>';
			$structured_data_image = true;
		}
		echo '</div>';
	}
}
mk_structured_data_meta();
