<?php
global $product, $mk_options;
 ?>

<div class="mk-modal is-active close-inside _ flex flex-center flex-items-center">
    <div class="mk-modal-container">
        <div class="mk-modal-header">
            <a href="#" class="modal-close js-modal-close">
                <svg xmlns="http://www.w3.org/2000/svg" width="15px" height="15px">
                    <g>
                        <line stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" x1="0.5" y1="0.5" x2="14.5" y2="14.5"/>
                        <line stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" x1="14.5" y1="0.5" x2="0.5" y2="14.5"/>
                    </g>
                </svg>
            </a>
        </div>
        <div class="mk-modal-content woocommerce single-product">
            <div class="product-quick-view product clearfix" itemscope itemtype="http://schema.org/Product" id="product-<?php the_ID(); ?>" >
                <div class="mk-product-image images">
                        <?php
                            $attachment_ids = $product->get_gallery_attachment_ids();
                            
                            $images[] = get_post_thumbnail_id(); 
                            foreach( $attachment_ids as $attachment_id ) {
                                $images[] = $attachment_id;
                            }

                            $images_as_string = implode(' ,', $images);

                            if(count($images) > 1) {
                                echo do_shortcode( '[mk_image_slideshow 
                                                        images="'.$images_as_string.'" 
                                                        effect="slide" 
                                                        displayTime="3000" 
                                                        transitionTime="700" 
                                                        hasNav="true" 
                                                        image_width="550"
                                                        image_height="550" 
                                                        smooth_height="false" 
                                                    ]' );
                            }
                            else {
                                // Product featured image
                                $featured_image_src = Mk_Image_Resize::resize_by_id_adaptive( get_post_thumbnail_id(), 'crop', 550, 550, $crop = false, $dummy = true);
  
                                echo '<img src="'.$featured_image_src['dummy'].'" '.$featured_image_src['data-set'].' alt="'.get_the_title(get_post_thumbnail_id()).'">';
                            }
                            
                        ?>
                </div>
                <div class="entry-summary summary">
                        <?php

                        if($mk_options['woocommerce_catalog'] == 'true') {
                            remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
                        }

                        do_action( 'woocommerce_single_product_summary');

                        ?>
                </div>
            </div>
        </div>
        <div class="mk-modal-footer"></div>
    </div>
</div>