<?php

function add_child_theme_textdomain()
{
    load_child_theme_textdomain('understrap-child', get_stylesheet_directory() . '/languages');
}
add_action('after_setup_theme', 'add_child_theme_textdomain');

#----------------------------------------------------

# add widget area for above the header
add_action('widgets_init', 'understrap_child_widgets_init');
function understrap_child_widgets_init()
{
    register_sidebar(array(
        'name' 			=> __('Announce Sidebar', 'theme-slug'),
        'id' 			=> 'announce-sidebar',
        'description' 	=> __('Widgets in this area will be shown above the header.', 'theme-slug'),
        'before_widget'	=> '<div id="%1$s" class="widget %2$s col-12 text-center">',
    'after_widget'  	=> '</div>',
    'before_title'  	=> '<h2 class="widgettitle">',
    'after_title'   	=> '</h2>',
    ));
}

#----------------------------------------------------
// remove product image zoom

// https://businessbloomer.com/woocommerce-disable-zoom-gallery-slider-lightbox-single-product/
add_action('after_setup_theme', 'xomli_remove_zoom_theme_support', 99);

function xomli_remove_zoom_theme_support()
{
    remove_theme_support('wc-product-gallery-zoom');
}
#----------------------------------------------------
//stop spurious insertions of <br> tags after several shortcodes in a row
// https://stackoverflow.com/questions/32570351/shortcode-output-adding-br-after-new-line
// remove_filter( 'the_content', 'wpautop' );
// add_filter( 'the_content', 'wpautop' , 12);
