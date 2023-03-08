<?php 

#shortcode to show products only for a certain $month
// Add Shortcode
function xomli_show_products_for_one_month_shortcode($atts)
{
    // ugly, I know
    global $__xomli_month_num;
    global $__xomli_year;

    $atts = shortcode_atts(
        array(
            'month_num' => '',
            'year' => '',
            'category' => '',
        ),
        $atts
    );

    $content = "";
    $__xomli_month_num = $atts["month_num"];
    $__xomli_year = $atts["year"];

    $current_month = date("m");
    $current_year = date("y");
    $monthName = date('F', mktime(0, 0, 0, intval($__xomli_month_num), 10)); // March

    $last_date = "last day of $monthName 20$__xomli_year";

    //already past the requested month, so dont provide any output
    if (strtotime("now") >   strtotime($last_date)) {
        return ;
    }

    add_filter('woocommerce_shortcode_products_query', 'custom__shortcode_products_query', 10, 3);

    $my_shortcode = '[products category="'. $atts["category"]. '"]';
    $content = do_shortcode('[products category="'. $atts["category"]. '"]');

    remove_filter('woocommerce_shortcode_products_query', 'custom__shortcode_products_query', 10, 3);

    // default empty elemet from shortcode is "<div class="woocommerce columns-4 "></div>"
    $my_shortcode_len = strlen($content);
    if ($my_shortcode_len > 42) {
        $content = "<h2>$monthName"."20"."$__xomli_year $current_month $current_year"   .$atts['category']." </h2>".$content;
    } else { // there are no items to show for this month
        $content = "";
    }

    return $content;
}
add_shortcode('xomli_show_products_for_one_month', 'xomli_show_products_for_one_month_shortcode');
#----------------------------------------------------
