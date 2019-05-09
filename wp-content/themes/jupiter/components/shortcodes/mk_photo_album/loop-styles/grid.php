<?php
    global $mk_options;
switch ( $view_params['column'] ) {
    case 1:
        $width = $mk_options['grid_width'];
        $column_class = 'mk--col mk--col--12-12';
        break;
    case 2:
        $width = round( $mk_options['grid_width'] / 2 );
        $column_class = 'mk--col mk--col--6-12';
        break;
    case 3:
        $width = round( $mk_options['grid_width'] / 3 );
        $column_class = 'mk--col mk--col--4-12';
        break;
    case 4:
        $width = round( $mk_options['grid_width'] / 4 );
        $column_class = 'mk--col mk--col--3-12';
        break;
}

        $image_src_original = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' )[0];

    $featured_image_src = Mk_Image_Resize::resize_by_id( get_post_thumbnail_id(), $view_params['image_size'], $width, $view_params['height'], $crop = true, $dummy = true );

    $images = explode( ',', get_post_meta( get_the_ID() , '_gallery_images', true ) );

foreach ( $images as $image ) {
    if ( ! empty( $image ) ) {
        $gallery_image_src[] = wp_get_attachment_image_src( $image, 'full' )[0];
    }
}

    // We need to reverse the array to keep the order from admin panel.
    $gallery_image_src = array_reverse( $gallery_image_src );

    $json_image = json_encode( $gallery_image_src );

    /* Dynamic class names to be added to article tag. */
    $item_classes[] = implode( ' ', mk_get_custom_tax( get_the_id(), 'photo_album', false, true ) );
    /* ---- */


    ?>

<article id="<?php the_ID(); ?>" class="mk-album-item mk-album-grid-item <?php echo esc_attr( $column_class ); ?> <?php echo esc_attr( implode( ' ', $item_classes ) ); ?>"  >
    <figure>
        <img alt="<?php the_title_attribute(); ?>" title="<?php the_title_attribute(); ?>" class="album-cover-image" src="<?php echo esc_attr( $featured_image_src ); ?>" width="<?php echo esc_attr( $width ); ?>" height="<?php echo esc_attr( $view_params['height'] ); ?>"  />
        <?php if ( 'true' == $view_params['overlay_preview'] ) : ?>
        <div class="overlay anim-<?php echo esc_attr( $view_params['overlay_hover_animation'] ); ?>"></div>
        <?php endif; ?>
        <figcaption>
            <?php if ( 'true' == $view_params['thumbnail_preview'] ) : ?>
            <span class="album-sneak-peak <?php echo esc_attr( $view_params['thumbnail_shape'] ); ?>">
                <?php
                    echo mk_get_shortcode_view(
                        'mk_photo_album', 'components/sneak-peak', true, [
                            'images' => $images,
                            'style' => $view_params['thumbnail_shape'],
                        ]
                    );
                ?>
            </span>
            <?php endif; ?>
            <div class="item-meta <?php echo esc_attr( $view_params['title_preview_style'] ); ?> anim-<?php echo esc_attr( $view_params['title_animation'] ); ?>">
                <?php
                    echo mk_get_shortcode_view( 'mk_photo_album', 'components/title', true );
                if ( 'true' == $view_params['description_preview'] ) {
                    echo mk_get_shortcode_view( 'mk_photo_album', 'components/description', true );
                }
                ?>

                </div><!-- item meta -->
        </figcaption>
        <a  href="<?php echo esc_url( $image_src_original ); ?>" class="mk-album-link js-el"
            data-mk-component="PhotoAlbum"
            data-photoalbum-images='<?php echo esc_attr( $json_image ); ?>'
            data-photoalbum-title="<?php the_title_attribute(); ?>"
            data-photoalbum-id="<?php the_ID(); ?>" 
            data-photoalbum-url="<?php echo esc_url( $view_params['album_url'] ); ?>" >
        </a>
    </figure><!-- Featured Image -->
</article><!-- Item Holder -->
