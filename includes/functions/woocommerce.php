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
function bcc_cart_has_scholarship_coupon_applied($message, $products, $show_qty)
{
    $coupon_id = 'scholarship'; #scholarship coupons
    $applied_coupons = WC()->cart->get_applied_coupons();


    if (in_array($coupon_id, $applied_coupons)) {
        $message = <<<EOT
            <span class="alert alert-success" role="alert">
            <strong>Scholarship coupon has been applied.
            </strong>
            </span>
EOT;
//            Scholarship coupon has been applied. $100 Credit will be applied to remaining balance on first   day of class
    }
    return $message;
};
add_filter('woocommerce_coupon_message', 'bcc_cart_has_scholarship_coupon_applied', 10, 3);
#----------------------------------------------------

function bcc_checkout_has_scholarship_coupon_applied()
{
    $coupon_id = 'scholarship'; #scholarship coupons
    $applied_coupons = WC()->cart->get_applied_coupons();


    if (in_array($coupon_id, $applied_coupons)) {
        $message = <<<EOT
            <div class="alert alert-success" role="alert">
            <strong>Scholarship coupon has been applied.
            </strong>
            </div>
EOT;
//            Scholarship coupon has been applied. $100 Credit will be applied to remaining balance on first day of class.
        echo $message;
    }
}
add_action('woocommerce_checkout_before_customer_details', 'bcc_checkout_has_scholarship_coupon_applied');
#----------------------------------------------------
/**
 * Add info to admin order email
 * from: https://www.sellwithwp.com/customizing-woocommerce-order-emails/
*/

add_action( 'woocommerce_email_before_order_table', 'add_order_email_special_comments_to_admin', 10, 2 );

function add_order_email_special_comments_to_admin( $order, $sent_to_admin ) {

    if ( $sent_to_admin ) { # only when sent to admin!

        if (is_member_conover_order($order)) {
            echo get_admin_email_order_string_conover($order);
            }

        if (is_member_degeorge_order($order)) {
            echo get_admin_email_order_string_degeorge($order);
            }

        if (is_neptune_order($order)) {
            echo get_admin_email_order_string_neptune($order). "\n";
            }

        echo "\n";
        if (has_coupons($order)){
            echo get_coupons_string($order). "\n";
            }
        else {
            echo "No Coupons Used\n";
            }
        }
    echo "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

    }
#---------------------------------------------------------
function is_member_conover_order($order){

    $pattern = '/conover/i';
    $source = $order->email_order_items_table( array('plain_text' => true,
                                                     'show_sku' => true,),
                                              true, '', '', '', true );

    $result = preg_match($pattern, $source);

    return $result;
    }
#---------------------------------------------------------
function get_admin_email_order_string_conover($order){

    $str = <<<END
            ===
            This is a CONOVER Members CE order
            ===
END;

    return $str;
    }

#---------------------------------------------------------
function is_member_degeorge_order($order){

    $pattern = '/degeorge/i';
    $source = $order->email_order_items_table( array('plain_text' => true,
                                                     'show_sku' => true,),
                                              true, '', '', '', true );

    $result = preg_match($pattern, $source);

    return $result;
    }
#---------------------------------------------------------
function get_admin_email_order_string_degeorge($order){

    $str = <<<END
            ===
            This is a DEGEORGE Members CE order
            ===
END;

    return $str;
    }

#---------------------------------------------------------
function is_neptune_order($order){

    $pattern = '/neptune/i';
    $source = $order->email_order_items_table( array('plain_text' => true,
                                                     'show_sku' => true,),
                                              true, '', '', '', true );

    $result = preg_match($pattern, $source);

    return $result;
    }

#---------------------------------------------------------
function get_admin_email_order_string_neptune($order){

    $str = sprintf( __( 'You have received a NEPTUNE order from %s.', 'woocommerce' ),
                        $order->get_formatted_billing_full_name()
                        );

    return $str;
    }

#---------------------------------------------------------
function has_coupons($order){

    return $order->get_used_coupons();
    }

#---------------------------------------------------------
function get_coupons_string($order){
/* see also
		sftp://caplanschool live/home2/caplansc/public_html/wordpress/wp-content/themes/ecaplan/woocommerce/emails/customer-processing-order.php
*/


	$used_coupons = $order->get_used_coupons();

    $result = "--- Be sure to reduce the total by the coupon amount on the first day of class for scholarship coupons ---\n\n";
    $result .= "Coupons Used:\n";
    $descriptions = array();
    foreach ($used_coupons as $coupon_code) {
            $coupon = new WC_Coupon( $coupon_code );
            $description = strip_tags( get_post_field('post_excerpt', $coupon->id) );
            $result .= "$coupon->code: $description\n";
            }

    return $result;
    }


#----------------------------------------------------
function bcc_customer_invoice_has_scholarship_coupon_applied($order, $sent_to_admin, $plain_text, $email)
{
    $coupon_id = 'scholarship'; #scholarship coupons

    $applied_coupons = $order->get_used_coupons();


    if (in_array($coupon_id, $applied_coupons)) {
        $message = <<<EOT
            <p>
            <strong>
            Scholarship coupon has been applied. $100 Credit will be applied to remaining balance on first day of class.
            </strong>
            </p>
EOT;
        echo $message;
    }
}
add_action('woocommerce_email_order_details', 'bcc_customer_invoice_has_scholarship_coupon_applied',10,4);

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
