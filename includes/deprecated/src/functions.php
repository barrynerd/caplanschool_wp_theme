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
#----------------------------------------------------
// based on answer #1 here:
// https://stackoverflow.com/questions/48302186/woocommerce-only-show-products-between-start-and-end-dates

function custom__shortcode_products_query($query_args, $atts, $loop_name)
{
    //ugly, I know
    global $__xomli_month_num;
    global $__xomli_year;

    $month = $__xomli_month_num;
    $year = $__xomli_year;

    $query_args['meta_query'] = array(
        'meta_query' => array(
            array(
                'key'=>'_sku',
                'value' => "-$year-$month",
                'compare'=> 'REGEXP',
                ),
        ));

    // echo ("<pre>");
    // print_r( $query_args);
    // echo ("</pre>");

    return $query_args;
}

#----------------------------------------------------
// see: https://lorut.no/responsive-vimeo-youtube-embed-wordpress/
// Hook onto 'oembed_dataparse' and get 2 parameters
// add_filter( 'oembed_dataparse','responsive_wrap_oembed_dataparse',0,2);

function responsive_wrap_oembed_dataparse($html, $data)
{
    print "ccc";
    // Verify oembed data (as done in the oEmbed data2html code)
    if (! is_object($data) || empty($data->type)) {
        return $html;
    }

    // Verify that it is a video
    if (!($data->type == 'video')) {
        return $html;
    }

    // Calculate aspect ratio
    $ar = $data->width / $data->height;

    // Set the aspect ratio modifier
    $ar_mod = (abs($ar-(4/3)) < abs($ar-(16/9)) ? 'embed-responsive-4by3' : 'embed-responsive-16by9');

    // Strip width and height from html
    $html = preg_replace('/(width|height)="\d*"\s/', "", $html);

    // Return code
    return '<div class="embed-responsive '.$ar_mod.'" data-aspectratio="'.number_format($ar, 5, '.').'">'.$html.'</div>';
}
#----------------------------------------------------
# see https://stackoverflow.com/questions/30254833/how-to-get-masonry-and-imagesloaded-to-work-with-wordpress
function my_masonry()
{
    // wp_enqueue_script('jquery-masonry');
    wp_enqueue_script('masonry');
    wp_enqueue_script('masonryloader', get_stylesheet_directory_uri() . '/js/TaS-masonryInitializer.js', array( 'masonry', 'jquery' ));
}
// add_action('wp_enqueue_scripts', 'my_masonry');

#----------------------------------------------------
# add search to menus
# from https://www.isitwp.com/add-search-form-to-specific-wp-nav-menu/
//  commented out to remove from menu - barry - 10/24/20
// add_filter('wp_nav_menu_items', 'add_search_form', 10, 2);
function add_search_form($items, $args)
{
    if ($args->theme_location == 'secondary') {
        $new_item = '<li class="search-menu"><form role="search" method="get" id="searchform" class="searchform" action="'.home_url('/').'">     	<div> 		<label class="screen-reader-text" for="s">Search for:</label> 		<input type="text" id="searchbox" value="" name="s" id="s"> 		<input type="submit" id="searchsubmit" class="btn btn-primary btn-sm" value="'. esc_attr__('Search') .'"> 	</div> </form></li>';

        $items .= $new_item;
    }
    return $items;
}#----------------------------------------------------
/**
 * Jetpack development mode.
 *
 * Note: Required for infinite scroll to work on localhost.
 */
#add_filter('jetpack_development_mode', '__return_true');


#----------------------------------------------------
/**
 * Jetpack development mode.
 *
 * Note: Required for infinite scroll to work on localhost.
 */
#add_filter('jetpack_development_mode', '__return_true');


