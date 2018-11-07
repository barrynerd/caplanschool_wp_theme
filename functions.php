<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

function understrap_remove_scripts() {
    wp_dequeue_style( 'understrap-styles' );
    wp_deregister_style( 'understrap-styles' );

    wp_dequeue_script( 'understrap-scripts' );
    wp_deregister_script( 'understrap-scripts' );

    // Removes the parent themes stylesheet and scripts from inc/enqueue.php
}
add_action( 'wp_enqueue_scripts', 'understrap_remove_scripts', 20 );

add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {

	// Get the theme data
	$the_theme = wp_get_theme();
    wp_enqueue_style( 'child-understrap-styles', get_stylesheet_directory_uri() . '/css/child-theme.min.css', array(), $the_theme->get( 'Version' ) );
    wp_enqueue_script( 'jquery');
	wp_enqueue_script( 'popper-scripts', get_template_directory_uri() . '/js/popper.min.js', array(), false);
    wp_enqueue_script( 'child-understrap-scripts', get_stylesheet_directory_uri() . '/js/child-theme.min.js', array(), $the_theme->get( 'Version' ), true );
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}

function add_child_theme_textdomain() {
    load_child_theme_textdomain( 'understrap-child', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'add_child_theme_textdomain' );
#----------------------------------------------------
# add widget area for above the header
add_action( 'widgets_init', 'understrap_child_widgets_init' );
function understrap_child_widgets_init() {
    register_sidebar( array(
        'name' 			=> __( 'Announce Sidebar', 'theme-slug' ),
        'id' 			=> 'announce-sidebar',
        'description' 	=> __( 'Widgets in this area will be shown above the header.', 'theme-slug' ),
        'before_widget'	=> '<div id="%1$s" class="widget %2$s col-12 text-center">',
	'after_widget'  	=> '</div>',
	'before_title'  	=> '<h2 class="widgettitle">',
	'after_title'   	=> '</h2>',
    ) );
}
#----------------------------------------------------
// woocommerce hooks

// remove breadcrumbs from woocommerce pages

// https://www.isitwp.com/remove-woocommerce-breadcrumbs/
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);
#----------------------------------------------------
// remove tabls

remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
#----------------------------------------------------
// Remove related products output

// https://docs.woocommerce.com/document/remove-related-posts-output/
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

#----------------------------------------------------
// remove roduct image zoom

// https://businessbloomer.com/woocommerce-disable-zoom-gallery-slider-lightbox-single-product/
add_action( 'after_setup_theme', 'xomli_remove_zoom_theme_support', 99 );

function xomli_remove_zoom_theme_support() {
	remove_theme_support( 'wc-product-gallery-zoom' );
}
#----------------------------------------------------
// from old ecaplan-child theme
function bcc_product_has_deposit($product){
    $product_id = $product->get_id();
    $deposit_enabled_array = get_post_meta( $product_id, "_enable_deposit" );
    $has_deposit = false;
    if ($deposit_enabled_array[0] == "yes"){
        $has_deposit = true;
        }
    if (current_user_can('administrator')){
        // echo "<pre>";
        // $print_r( $deposit_enabled_array[0]);
        // echo "</pre>";
    }

    return ($has_deposit);
    }

#----------------------------------------------------
// from old ecaplan-child theme
#see https://docs.woocommerce.com/document/change-add-to-cart-button-text/
#add_filter( 'woocommerce_product_single_add_to_cart_text', 'woo_custom_cart_button_text' );    // 2.1 +

function bcc_woo_custom_cart_button_text_deposit() {

        return __( 'Deposit', 'woocommerce' );

}

#----------------------------------------------------
/**
 * Add a standard $ value surcharge to all transactions in cart / checkout
 */
// add_action( 'woocommerce_cart_calculate_fees','wc_add_surcharge' );
function wc_add_surcharge() {
global $woocommerce;

if ( is_admin() && ! defined( 'DOING_AJAX' ) )
return;

$county = array('US');
// change the $fee to set the surcharge to a value to suit
$fee = 4.00;

if ( in_array( WC()->customer->get_shipping_country(), $county ) ) :
    $woocommerce->cart->add_fee( 'Surcharge', $fee, true, 'standard' );
endif;
}
#----------------------------------------------------
//  output form elements on checkout form using bootstrap

function bcc_woocommerce_form_field ($key,$field, $fields, $checkout){

	if ( isset( $field['country_field'], $fields[ $field['country_field'] ] ) ) {
		$field['country'] = $checkout->get_value( $field['country_field'] );
	}
	// echo "key; $key, field: $field";
	woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
}
#----------------------------------------------------
#----------------------------------------------------
#----------------------------------------------------
#----------------------------------------------------
#----------------------------------------------------
#----------------------------------------------------
#----------------------------------------------------
#----------------------------------------------------
#----------------------------------------------------
