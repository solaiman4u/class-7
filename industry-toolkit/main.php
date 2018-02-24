<?php

/*
Plugin Name: Industry Toolkit
Plugin URI: http://wordpress.org/plugins/industry/
Description: This is not just a plugin, This plugin for industry  theme.
Author: Solaiman
Version: 1.0
Author URI: http://rrf.com/
*/

function industry_toolkit_include_files() {
    wp_enqueue_style('owl-carousel', plugins_url( '/assets/css/owl.carousel.min.css', __FILE__ ) ); 
    wp_enqueue_style('industry_toolkit', plugins_url( '/assets/css/industry-toolkit.css', __FILE__ ) );

    wp_enqueue_script('owl-carousel', plugins_url( '/assets/js/owl.carousel.min.js', __FILE__ ), array('jquery'), '2.2.1', 'true' );
}
add_action( 'wp_enqueue_scripts','industry_toolkit_include_files');

//Our ShortCode
function industry_demo_query($atts){
    extract( shortcode_atts( array(
        'count' => 2,
    ), $atts) );


    $arg = array(
    	'post_type' => 'page',
    	'posts_per_page' => $count,
    );


    $get_post = new WP_Query($arg);

    $plist_markup = '<div class="row">';
    	while($get_post->have_posts()) : $get_post->the_post();
    		$post_id = get_the_ID();
    		$plist_markup .= '<div class="col-md-4"><li>'.get_the_title($post_id).'</li></div>';
    	endwhile;
    $plist_markup .= '</div>'; 

    wp_reset_query();

    return $plist_markup;
   
}
add_shortcode('p_list', 'industry_demo_query');  


//Our ShortCode
function industry_section_title_shortcode($atts){
    extract( shortcode_atts( array(
        'sub_title' => '',
        'title' => '',
        'description' => '',
    ), $atts) );

	$industry_section_title_markup ='<div class="industry_section_title">';

	if(!empty($sub_title)){
		$industry_section_title_markup .= '<h4>'.esc_html( $sub_title ).'</h4>';
	}

	if(!empty($title)){
		$industry_section_title_markup .= '<h2>'.esc_html( $title ).'</h2>';
	}
	
	if(!empty($description)){
		$industry_section_title_markup .= ''.wpautop( esc_html( $description ) ).'';
	}

	$industry_section_title_markup .= '</div>';
   




    return $industry_section_title_markup;
   
}
add_shortcode('industry_section_title', 'industry_section_title_shortcode');  


//Our ShortCode
function industry_service_box_shortcode($atts){
    extract( shortcode_atts( array(
        'icon_type' => 1,
        'fa_icon' => 'fa fa-star',
        'img_icon' => '',
        'title' => '',
        'description' => '',
    ), $atts) );

    $industry_service_box_markup ='<div class="industry_service_box">';

    if($icon_type == 1){
        $industry_service_box_markup .= '<div class="service-icon">
            <i class="'.esc_attr( $fa_icon ).'"></i>
        </div>';
    }else{
        $service_icon_img_array = wp_get_attachment_image_src( $img_icon, 'thumbnail' );
        $industry_service_box_markup .= '<div class="service-img-icon">
            <img src="'.esc_url( $service_icon_img_array[0] ).'" alt="'.esc_html( $title ).'"/>
        </div>';
    }


    if(!empty($title)){
        $industry_service_box_markup .= '<h2>'.esc_html( $title ).'</h2>';
    }
    
    if(!empty($description)){
        $industry_service_box_markup .= ''.wpautop( esc_html( $description ) ).'';
    }

    $industry_service_box_markup .= '</div>';
   




    return $industry_service_box_markup;
   
}
add_shortcode('industry_service_box', 'industry_service_box_shortcode');  

//Slider register function
function industry_theme_custom_post(){
register_post_type('industry-slide', array(
    'label' => 'slides',
    'labels' => array(
        'name' => 'Slides',
        'singular_name' => 'slide',
    ),
    'public' => false,
    'menu_icon' => 'dashicons-images-alt',
    'show_ui' => true,
    'supports' => array('title','editor','thumbnail','custom-fields','excerpt'),


));

}
add_action('init', 'industry_theme_custom_post');


//Slider ShortCode
function industry_slider_shortcode($atts){
    extract( shortcode_atts( array(
        'count' => 3,
    ), $atts) );


    $arg = array(
        'post_type' => 'industry-slide',
        'posts_per_page' => 2,
    );




    $get_post = new WP_Query($arg);

    $slide_rendom_number = rand(630437,630438);

    $industry_slider_markup = '
    <script>
        jQuery(window).load(function ($) {
            jQuery("#industry-slide'.$slide_rendom_number.'").owlCarousel({
            items: 1,
            loop: true,
            smartSpeed: 1000,
            nav: true,
            autoplay: false,            
            navText: ["<i class=\'fa fa-angle-left\'></i>", "<i class=\'fa fa-angle-right\'></i>"],
            mouseDrag: true      
        });
    });
    </script>

    <div id="industry-slide'.$slide_rendom_number.'" class="owl-carousel industry_slider">';
        while($get_post->have_posts()) : $get_post->the_post();
            $post_id = get_the_ID();
            $industry_slider_markup .= '
            <div style="background-image:url('.get_the_post_thumbnail_url($post_id,'large').')" class="industry-single-slide">
                <div class="industry-single-slide-inner">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6">
                                <h2>'.get_the_title($post_id).'</h2>
                                '.wpautop(get_the_content($post_id)) .'
                            </div>
                        </div>
                    </div>
                </div>                
            </div>';
        endwhile;
    $industry_slider_markup .= '</div>'; 

    wp_reset_query();

    return $industry_slider_markup;
   
}
add_shortcode('industry_slider', 'industry_slider_shortcode');  