<?php
/**
 * Loop Add to Cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/add-to-cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;
global $post;

echo "ssss";

#barry
if (bcc_product_has_deposit($product)){
    $button_string = esc_html( bcc_woo_custom_cart_button_text_deposit());
    }
else {
    $button_string = esc_html( $product->add_to_cart_text());
    }

echo $button_string;

$terms = wp_get_post_terms( $post->ID, 'product_cat' );
#print_r($terms);
#

$is_members_course = false;
foreach ( $terms as $term ) {
    if (preg_match("/^members/",$term->slug) === 1){
        $is_members_course = true;
        }
    }

if (( $is_members_course) && !$product->is_in_stock()){
    print '<span class="out-of-stock">Sold Out!</span>';
    }
else {
    print "<span class='cart-buttons'>";


	echo apply_filters( 'woocommerce_loop_add_to_cart_link', // WPCS: XSS ok.
		sprintf( '<div class="add-to-cart-container"><a href="%s" data-quantity="%s" class="%s product_type_%s single_add_to_cart_button btn btn-outline-primary btn-block %s" %s> %s</a></div>',
			esc_url( $product->add_to_cart_url() ),
			esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
			$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
			esc_attr( $product->get_type() ),
			$product->get_type() == 'simple' ? 'ajax_add_to_cart' : '',
			isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
			esc_html( $button_string )
		),
	$product, $args );
	 print "</span>";
 }
