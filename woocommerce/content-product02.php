<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
        return;
}
?>
  
<div class="list-wrapper d-flex justify-content-between align-items-center" style="height:75px;">
<div class="card-title col mb-0"> <?php print $product->get_name(); ?></div>
    <?php
        $add_to_cart_text = "Register";
        printf(
            '<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" class="btn btn-primary %s product_type_%s col-3">%s</a>',
            esc_url( $product->add_to_cart_url() ),
            esc_attr( $product->get_id() ),
            esc_attr( $product->get_sku() ),
            $product->is_purchasable() ? 'add_to_cart_button' : '',
            esc_attr( $product->product_type ),
            esc_html( $add_to_cart_text )
        );
    ?>
</div>
