<?php
if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
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

#----------------------------------------------------
// from old ecaplan-child theme
#see https://docs.woocommerce.com/document/change-add-to-cart-button-text/
#add_filter( 'woocommerce_product_single_add_to_cart_text', 'woo_custom_cart_button_text' );    // 2.1 +

function bcc_woo_custom_cart_button_text_deposit()
{
    return __('Pay Deposit', 'woocommerce');
}

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
function bc_ce_classes_by_month($atts)
{
    $atts = shortcode_atts(
        array(
          'title' => '',
          'month' => '',
          'year' => '',
        ),
        $atts
      );

    $start_date = $atts['year']."-".$atts['month']."-00";
    $end_date = $atts['year']."-".$atts['month']."-99";
    #print "start date: $start_date end date: $end_date";


    #query all continuing ed, all designations
    $args = array(
        'post_type'              => 'product',
        'post_status'            => 'publish',
        'order'                  => 'ASC',
        'orderby'                => 'meta_value',
        'meta_key'				 => 'start_date',
        #'product_cat' 			 => 'continuing-education, designations',
        'tax_query' 			 => array(
            array(
                'relation' => 'AND',
                array(
                    'taxonomy' => 'product_cat',
                    'field'    => 'term_id',
                    'terms' => array(89),
                    'operator' => "NOT IN",
                ),
                array(
                    'taxonomy' => 'product_cat',
                    'field'    => 'term_id',
                    'terms'    => array( 69, 70 ),
                ),
            ),
        ),
        'meta_query'             => array(
            'relation' => 'AND',
            array(
                'key'       => 'start_date',
                'value'     => $start_date,
                'compare'   => '>=',
            ),

            array(
                'key'       => 'start_date',
                'value'     => $end_date,
                'compare'   => '<=',
            ),
        ),
        'nopaging' => true,
        'posts_per_page' => -1,
    );

    // The Query
    $query = new WP_Query($args);

    // The Loop
    if ($query->have_posts()) {
        $result .= "<h2 id=\"$start_date\">{$atts['title']}</h2>";
        $result .= "<div class=\"bc_ce_classes_by_month woocommerce columns-1\">";
        $result .= '<ul class="products">';
        while ($query->have_posts()) {
            $query->the_post();
            #http://stackoverflow.com/questions/18957416/load-template-in-wordpress-without-echo-it
            ob_start();
            wc_get_template_part('content', 'product');
            $result .= ob_get_clean();
        }
        $result .=  "</ul>";
        $result .= "</div>";
    } else {
        // no posts found
        $result = '<div class="woocommerce "></div>';
    }


    // Restore original Post Data
    wp_reset_postdata();

    return $result;
}

add_shortcode('bc_ce_classes_by_month', 'bc_ce_classes_by_month');




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
/**
 * Jetpack development mode.
 *
 * Note: Required for infinite scroll to work on localhost.
 */
#add_filter('jetpack_development_mode', '__return_true');
#----------------------------------------------------
#see: https://wordpress.stackexchange.com/questions/175793/get-first-video-from-the-post-both-embed-and-video-shortcodes
function theme_oembed_videos()
{
    $post = get_the_ID();

    if ($post && get_post($post)) {
        $result="";
        $pattern1 ="https:\/\/youtu.be\/[a-zA-Z0-9_-]*";
        $pattern2 ="(http[sv]*:\/\/www.youtube.com\/.+?)\&";
        $pattern3 ="(http[sv]*:\/\/www.youtube.com\/watch\?v=[A-Za-z0-9_-]*)";

        $regex = "/$pattern1|$pattern2|$pattern3/i";
        // if (current_user_can('administrator')) {
        //     print_r($pattern);
        //     print "<pre> Content:";
        //     print get_the_content();
        //     print "</pre>";
        // }

        if (preg_match($regex, get_the_content(), $matches)) {
            // if (current_user_can('administrator')) {
            //     print "Matches:";
            //     print_r($matches);
            // }

            $my_embed = $matches[0];
            if (!empty($matches[1])) {
                $my_embed = $matches[1];
            } elseif (!empty($matches[2])) {
                $my_embed = $matches[2];
            }

            $my_embed = str_replace("httpv:", "https:", $my_embed);
            $my_embed = str_replace("http:", "https:", $my_embed);

            $my_embed = str_replace("www.youtube.com/watch?v=", "youtu.be/", $my_embed);

            // if (current_user_can('administrator')) {
            //     print "my_embed:";
            //     print $my_embed;
            // }

            $embed = wp_oembed_get($my_embed, array('width'=>"100%", 'class'=>'embed-responsive-item'));
            $result =<<<END
				<div  class="embed-responsive embed-responsive-16by9">$embed</div>
END;
        }
    }
    return $result;
}
#----------------------------------------------------
#----------------------------------------------------
// refactor to include files here
require_once dirname(__FILE__) . '/includes/functions/nulling.php';
require_once dirname(__FILE__) . '/includes/functions/membership.php';
require_once dirname(__FILE__) . '/includes/functions/woocommerce.php';

require_once dirname(__FILE__) . '/includes/functions/dequeue.php';
require_once dirname(__FILE__) . '/includes/functions/enqueue_base.php';

require_once dirname(__FILE__) . '/includes/shortcodes/index.php';

require_once dirname(__FILE__) . '/includes/deprecated/index.php';

require_once dirname(__FILE__) . '/includes/acf-overrides/index.php';
require_once dirname(__FILE__) . '/includes/theme-setup/index.php';
require_once dirname(__FILE__) . '/includes/woocommerce-overrides/index.php';
require_once dirname(__FILE__) . '/includes/woocommerce-bootstrap-overrides/index.php';


