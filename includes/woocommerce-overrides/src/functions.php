<?php

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
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);

#----------------------------------------------------

// from old ecaplan-child theme
#see https://docs.woocommerce.com/document/change-add-to-cart-button-text/
#add_filter( 'woocommerce_product_single_add_to_cart_text', 'woo_custom_cart_button_text' );    // 2.1 +

function bcc_woo_custom_cart_button_text_deposit()
{
    return __('Pay Deposit', 'woocommerce');
}

#----------------------------------------------------
#----------------------------------------------------
/**
 * Add a standard $ value surcharge to all transactions in cart / checkout
 */
// add_action( 'woocommerce_cart_calculate_fees','wc_add_surcharge' );
function wc_add_surcharge()
{
    global $woocommerce;

    if (is_admin() && ! defined('DOING_AJAX')) {
        return;
    }

    $county = array('US');
    // change the $fee to set the surcharge to a value to suit
    $fee = 4.00;

    if (in_array(WC()->customer->get_shipping_country(), $county)) :
    $woocommerce->cart->add_fee('Surcharge', $fee, true, 'standard');
    endif;
}

#----------------------------------------------------
//  output form elements on checkout form using bootstrap

function bcc_woocommerce_form_field($key, $field, $fields, $checkout)
{
    if (isset($field['country_field'], $fields[ $field['country_field'] ])) {
        $field['country'] = $checkout->get_value($field['country_field']);
    }
    // echo "key; $key, field: $field";
    woocommerce_form_field($key, $field, $checkout->get_value($key));
}


#----------------------------------------------------
/**
 * Exclude products from a particular category on the shop page
 */
 // https://docs.woocommerce.com/document/exclude-a-category-from-the-shop-page/
function custom_pre_get_posts_query($q)
{
    if (is_shop()) {
        $tax_query = (array) $q->get('tax_query');

        $tax_query[] = array(
               'taxonomy' => 'product_cat',
               'field' => 'slug',
               'terms' => array( 'members' ), // Don't display products in the clothing category on the shop page.
               'operator' => 'NOT IN'
        );


        $q->set('tax_query', $tax_query);
    }
}
add_action('woocommerce_product_query', 'custom_pre_get_posts_query');


#----------------------------------------------------
/**
 * Adds the ability to sort products in the shop based on the SKU
 * Can be combined with tips here to display the SKU on the shop page: https://www.skyverge.com/blog/add-information-to-woocommerce-shop-page/
 */
 // https://gist.github.com/bekarice/1883b7e678ec89cc8f4d
 // see also: https://gist.github.com/mikejolley/1622323

function sv_add_sku_sorting($args)
{
    $orderby_value = isset($_GET['orderby']) ? wc_clean($_GET['orderby']) : apply_filters('woocommerce_default_catalog_orderby', get_option('woocommerce_default_catalog_orderby'));

    if ('sku' == $orderby_value) {
        $args['orderby'] = 'meta_value';
        $args['order'] = 'asc';
        // ^ lists SKUs alphabetically 0-9, a-z; change to desc for reverse alphabetical
        $args['meta_key'] = '_sku';
    }

    return $args;
}
add_filter('woocommerce_get_catalog_ordering_args', 'sv_add_sku_sorting');

function sv_sku_sorting_orderby($sortby)
{
    $sortby['sku'] = 'Sort by SKU';
    // Change text above as desired; this shows in the sorting dropdown
    return $sortby;
}
add_filter('woocommerce_catalog_orderby', 'sv_sku_sorting_orderby');
add_filter('woocommerce_default_catalog_orderby_options', 'sv_sku_sorting_orderby');

#----------------------------------------------------
#https://docs.woocommerce.com/document/woocommerce-shortcodes/
#https://stackoverflow.com/questions/28063244/woocommerce-shortcode-orderby-price-is-not-working
# note that you have rto make a selection in the cusotmizer for this to work:
#	https://wordpress.stackexchange.com/questions/321186/woocommerce-order-by-random-with-1-hour-seed-refresh
add_filter('woocommerce_shortcode_products_query', 'woocommerce_shortcode_products_orderby');

function woocommerce_shortcode_products_orderby($args)
{
    $standard_array = array('menu_order','title','date','rand','id');

    if (isset($args['orderby']) && !in_array($args['orderby'], $standard_array)) {
        $args['meta_key'] = (string) $args['orderby'];
        $args['orderby']  = 'meta_value';
    }

    return $args;
}

#----------------------------------------------------
// from old ecaplan-child theme
function bcc_product_has_deposit($product)
{
    $product_id = $product->get_id();
    $deposit_enabled = get_post_meta($product_id, "_enable_deposit");
    $has_deposit = false;
    if ($deposit_enabled[0] == "yes") {
        $has_deposit = true;
    }
    if (current_user_can('administrator')) {
        // echo "<pre> sdfasdfsa";
        // $print_r( $deposit_enabled);
        // echo "</pre>";
    }

    return ($has_deposit);
}

