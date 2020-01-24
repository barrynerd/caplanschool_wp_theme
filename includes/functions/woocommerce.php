<?php

//activate gutenberg for products
// https://wplook.com/how-to-enamble-gutenberg-for-woocommerce-products/`
function wplook_activate_gutenberg_products($can_edit, $post_type)
{
    if ($post_type == 'product') {
        $can_edit = true;
    }

    return $can_edit;
}
// add_filter('use_block_editor_for_post_type', 'wplook_activate_gutenberg_products', 10, 2);

function wplook_activate_gutenberg_products_cat($args, $taxonomy_name)
{
    if (‘product_cat’ === $taxonomy_name) {
        $args[‘show_in_rest’] = true;
    }

    return $args;
}
// add_filter( ‘register_taxonomy_args’, ‘wplook_activate_gutenberg_products_cat’, 10, 2 );
#----------------------------------------------------
// https://wordpress.org/support/topic/customizing-sale-badge-at-woocommerce/

// add_filter('woocommerce_sale_flash', 'my_custom_sales_badge');

function my_custom_sales_badge()
{
    $img = '<img width="75px" height="30px" src="https://misofis.com/wp-content/uploads/indirim.gif"></img>';
    return $img;
}
#----------------------------------------------------
add_filter('woocommerce_sale_flash', 'bcc_change_sales_flash_content', 10, 3);
function bcc_change_sales_flash_content($content, $post, $product)
{
    $message = get_post_meta($product->get_id(), 'sale_flash_message', true);
    if ($message) {
        $content = '<span class="onsale">'.__($message, 'woocommerce').'</span>';
    }

    return $content;
}
#----------------------------------------------------
function bcc_product_uses_gutenberg()
{
    $gutenberg_start_date = "2019-08-28"; //date we swtiched to gutenberg in  WooCommerce products

    $result = true;
    if (get_the_date('c') == $gutenberg_start_date) {
        $result = false;
    }

    return $result;
}
#----------------------------------------------------
function bcc_checkout_has_scholarship_coupon_applied(){
    print <<<EOT
    <div class="col-md-4 float-md-none offset-md-7 cart_totals alert alert-success" role="alert">
    <strong>Scholarship coupon has been applied. $100 Credit will be applied to remaining balance on first day of class</strong>
    </div>
EOT;
}
add_action( 'woocommerce_cart_collaterals', 'bcc_checkout_has_scholarship_coupon_applied',5 );
#----------------------------------------------------
#----------------------------------------------------
#----------------------------------------------------
#----------------------------------------------------
#----------------------------------------------------
#----------------------------------------------------
#----------------------------------------------------
#----------------------------------------------------
#----------------------------------------------------
#----------------------------------------------------
#----------------------------------------------------
#----------------------------------------------------
#----------------------------------------------------
#----------------------------------------------------
#----------------------------------------------------
#----------------------------------------------------
#----------------------------------------------------
#----------------------------------------------------
#----------------------------------------------------
#----------------------------------------------------
#----------------------------------------------------
