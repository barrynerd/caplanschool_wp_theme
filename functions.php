<?php
if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

function understrap_remove_scripts()
{
    wp_dequeue_style('understrap-styles');
    wp_deregister_style('understrap-styles');

    wp_dequeue_script('understrap-scripts');
    wp_deregister_script('understrap-scripts');

    // Removes the parent themes stylesheet and scripts from inc/enqueue.php
}
add_action('wp_enqueue_scripts', 'understrap_remove_scripts', 20);

add_action('wp_enqueue_scripts', 'theme_enqueue_styles');
function theme_enqueue_styles()
{

    // Get the theme data
    $the_theme = wp_get_theme();
    wp_enqueue_style('child-understrap-styles', get_stylesheet_directory_uri() . '/css/child-theme.min.css', array(), $the_theme->get('Version'));
    wp_enqueue_script('jquery');
    wp_enqueue_script('popper-scripts', get_stylesheet_directory_uri() . '/js/popper.min.js', array(), false);
    wp_enqueue_script('child-understrap-scripts', get_stylesheet_directory_uri() . '/js/child-theme.min.js', array(), $the_theme->get('Version'), true);
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}

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
// remove roduct image zoom

// https://businessbloomer.com/woocommerce-disable-zoom-gallery-slider-lightbox-single-product/
add_action('after_setup_theme', 'xomli_remove_zoom_theme_support', 99);

function xomli_remove_zoom_theme_support()
{
    remove_theme_support('wc-product-gallery-zoom');
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
/**
 * @param WP_Query|null $wp_query
 * @param bool $echo
 *
 * @return string
 * Accepts a WP_Query instance to build pagination (for custom wp_query()),
 * or nothing to use the current global $wp_query (eg: taxonomy term page)
 * - Tested on WP 4.9.5
 * - Tested with Bootstrap 4.1
 * - Tested on Sage 9
 *
 * USAGE:
 *     <?php echo bootstrap_pagination(); ?> //uses global $wp_query
 * or with custom WP_Query():
 *     <?php
 *      $query = new \WP_Query($args);
 *       ... while(have_posts()), $query->posts stuff ...
 *       echo bootstrap_pagination($query);
 *     ?>
 */

// see https://gist.github.com/mtx-z/f95af6cc6fb562eb1a1540ca715ed928
function bootstrap_pagination(\WP_Query $wp_query = null, $echo = true)
{
    if (null === $wp_query) {
        global $wp_query;
    }
    $pages = paginate_links(
        [
            'base'         => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
            'format'       => '?paged=%#%',
            'current'      => max(1, get_query_var('paged')),
            'total'        => $wp_query->max_num_pages,
            'type'         => 'array',
            'show_all'     => false,
            'end_size'     => 3,
            'mid_size'     => 1,
            'prev_next'    => true,
            'prev_text'    => __('« Prev'),
            'next_text'    => __('Next »'),
            'add_args'     => false,
            'add_fragment' => ''
        ]
    );
    if (is_array($pages)) {
        //$paged = ( get_query_var( 'paged' ) == 0 ) ? 1 : get_query_var( 'paged' );
        $pagination = '<div class="pagination"><ul class="pagination">';
        foreach ($pages as $page) {
            // see sevmusic's comment
            $pagination .= '<li class="page-item '.(strpos($page, 'current') !== false ? 'active' : '').'"> ' . str_replace('page-numbers', 'page-link', $page) . '</li>';
        }
        $pagination .= '</ul></div>';
        if ($echo) {
            echo $pagination;
        } else {
            return $pagination;
        }
    }
    return null;
}
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
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => array( 69, 70 ),
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
//stop spurious insertions of <br> tags after several shortcodes in a row
// https://stackoverflow.com/questions/32570351/shortcode-output-adding-br-after-new-line
// remove_filter( 'the_content', 'wpautop' );
// add_filter( 'the_content', 'wpautop' , 12);
#----------------------------------------------------
// restore custom fields edit box that acf plugin is hiding
// see https://www.wpbeginner.com/wp-tutorials/how-to-fix-custom-fields-not-showing-in-wordpress/
add_filter('acf/settings/remove_wp_meta_box', '__return_false');

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
add_filter('jetpack_development_mode', '__return_true');
#----------------------------------------------------
/**
 * Add theme support for infinite scroll.
 *
 * @uses add_theme_support
 * @return void
 */
// function primer_infinite_scroll_init() {
//
//     add_theme_support( 'infinite-scroll', array(
//         'container'      => 'main',
//         'wrapper'        => '.post',
//         'footer_widgets' => array(
//             'footer-1',
//             'footer-2',
//             'footer-3',
//         ),
//     ) );
//
// }
// add_action( 'after_setup_theme', 'primer_infinite_scroll_init' );
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
// modified from parent theme
function understrap_posted_on()
{
    $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
    if (get_the_time('U') !== get_the_modified_time('U')) {
        $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s"></time>';
    }
    $time_string = sprintf(
            $time_string,
                esc_attr(get_the_date('c')),
                esc_html(get_the_date()),
                esc_attr(get_the_modified_date('c')),
                esc_html(get_the_modified_date())
        );
    $posted_on   = apply_filters(
                'understrap_posted_on',
            sprintf(
                        '<span class="posted-on">%1$s %2$s</span>',
                        esc_html_x('Posted on', 'post date', 'understrap'),
                        apply_filters('understrap_posted_on_time', $time_string)
                )
        );
    $byline      = apply_filters(
                'understrap_posted_by',
            sprintf(
                        '<span class="byline"> %1$s<span class="author vcard"><a class="url fn n" href="%2$s"> %3$s</a></span></span>',
                        $posted_on ? esc_html_x('by', 'post author', 'understrap') : esc_html_x('Posted by', 'post author', 'understrap'),
                        esc_url(get_author_posts_url(get_the_author_meta('ID'))),
                        esc_html(get_the_author())
                )
        );
    echo $posted_on . $byline; // WPCS: XSS OK.
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
# based on https://premium.wpmudev.org/blog/one-category-wordpress-homepage/
function my_home_category($query)
{
    $cat_name = "Elliott's Industry News";
    if ($query->is_home() && $query->is_main_query()) {
        $id = get_cat_ID($cat_name);
        // print "aaa";
        $query->set('cat', $id);
    }
}
add_action('pre_get_posts', 'my_home_category');
#----------------------------------------------------
#override parent theme
function understrap_entry_footer()
{

    // Hide category and tag text for pages.
    if ('post' === get_post_type()) {
        /* translators: used between list items, there is a space after the comma */
        $categories_list = get_the_category_list(esc_html__(', ', 'understrap'));
        if ($categories_list && understrap_categorized_blog()) {
            /* translators: %s: Categories of current post */
            printf('<span class="cat-links">' . esc_html__('%s', 'understrap') . '</span>', $categories_list); // WPCS: XSS OK.
        }
        /* translators: used between list items, there is a space after the comma */

        //barry - never show the tags
        // $tags_list = get_the_tag_list('', esc_html__(', ', 'understrap'));
        // if ($tags_list) {
        //     /* translators: %s: Tags of current post */
        //     printf('<span class="tags-links">' . esc_html__('Tagged %s', 'understrap') . '</span>', $tags_list); // WPCS: XSS OK.
        // }
    }
    if (! is_single() && ! post_password_required() && (comments_open() || get_comments_number())) {
        echo '<span class="comments-link">';
        comments_popup_link(esc_html__('Leave a comment', 'understrap'), esc_html__('1 Comment', 'understrap'), esc_html__('% Comments', 'understrap'));
        echo '</span>';
    }
    // edit_post_link(
    // 	sprintf(
    // 		/* translators: %s: Name of current post */
    // 		esc_html__( 'Edit %s', 'understrap' ),
    // 		the_title( '<span class="screen-reader-text">"', '"</span>', false )
    // 	),
    // 	'<span class="edit-link">',
    // 	'</span>'
    // );
}
#----------------------------------------------------
#https://wordpress.stackexchange.com/questions/179585/remove-category-tag-author-from-the-archive-title
add_filter('get_the_archive_title', function ($title) {
    if (is_category()) {
        $title = single_cat_title('', false);
    } elseif (is_tag()) {
        $title = single_tag_title('', false);
    } elseif (is_author()) {
        $title = '<span class="vcard">' . get_the_author() . '</span>' ;
    }

    return $title;
});
#----------------------------------------------------
function bcc_widgets_init()
{
    register_sidebar(array(
        'name'          => 'BCC widget area 01',
        'id'            => 'bcc_widget_area_01',
        'before_widget' => '<div>',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="rounded">',
        'after_title'   => '</h2>',
    ));

    register_sidebar(array(
        'name'          => 'BCC widget area 02',
        'id'            => 'bcc_widget_area_02',
        'before_widget' => '<div>',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="rounded">',
        'after_title'   => '</h2>',
    ));
}
add_action('widgets_init', 'bcc_widgets_init');
#----------------------------------------------------
function ask_elliott_bold_title($title, $id = null)
{
    $result = $title;
    if (in_category('ask-elliott', $id)) {
        $limit=1;
        $pattern = '/(Ask Elliott [0-9:]+)(\s[a-zA-Z0-9_])*/i';
        $replacement = '<strong>${1}</strong>${2}';
        $result = preg_replace($pattern, $replacement, $result, $limit);
        return $result;
    }

    return $result;
}
add_filter('the_title', 'ask_elliott_bold_title', 10, 2);
#----------------------------------------------------
# from https://www.wpbeginner.com/plugins/add-excerpts-to-your-pages-in-wordpress/
add_post_type_support('page', 'excerpt');
#----------------------------------------------------
# add search to menus
# from https://www.isitwp.com/add-search-form-to-specific-wp-nav-menu/
add_filter('wp_nav_menu_items', 'add_search_form', 10, 2);
function add_search_form($items, $args)
{
    if ($args->theme_location == 'secondary') {
        $new_item = '<li class="search-menu"><form role="search" method="get" id="searchform" class="searchform" action="'.home_url('/').'">     	<div> 		<label class="screen-reader-text" for="s">Search for:</label> 		<input type="text" id="searchbox" value="" name="s" id="s"> 		<input type="submit" id="searchsubmit" class="btn btn-primary btn-sm" value="'. esc_attr__('Search') .'"> 	</div> </form>';

        $items .= $new_item;
    }
    return $items;
}

#----------------------------------------------------
// refactor to include files here
require_once dirname(__FILE__) . '/includes/functions/nulling.php';

#----------------------------------------------------
#force coupon usage for purchasing membership
function bcc_force_coupon_usage_when_purchasing_membership()
{
    // print "<pre>";
    // print "aaa";
    // print "----------Coupon:";
    $applied_coupons = WC()->cart->get_applied_coupons();
    // print_r ($applied_coupons);
    foreach (WC()->cart->get_cart() as $cart_item) {
        // print_r($cart_item);
        $product_id  = wc_get_product($cart_item['product_id']);
        $product = $cart_item['data'];
        $quantity = $cart_item['quantity'];
        $item_name = $product->get_title();
        $price = $product->get_price();
        $my_id = $product->get_id();
        $sku = $product->get_sku();
        // print join("\n:", array($item_name, $quantity,$price, $sku));
        $category_ids = $product->get_category_ids();
        // print_r($category_ids);
        // print "\n------";
        // print "myid: " . $my_id;
        // print_r($product_id);
        $cat_names = get_the_terms($my_id, 'product_cat');
        // print_r($cat_names);
        // foreach ($cat_names as $cat_name) {
        //     print $cat_name->slug . "\n";
        // }
        // print "\n------meta\n";
        // $formatted_meta_data = $item->get_formatted_meta_data();
        // $item_meta_data = $product->get_meta_data();
        $item_meta_data = $product->get_meta("required_coupon");
        // echo '<pre>'; print_r($item_meta_data); echo '</pre>';
        // print "\n------end meta\n";

        $result = true;

        if (isset($item_meta_data)) {
            // print "Coupon is required";
            if (in_array($item_meta_data, $applied_coupons)) {
                $msg =  "we have applied the correct coupon";
                // print $msg;
                wc_print_notice($msg, "success");
                $result = true;
            } else {
                print "item_meta_data: " . $item_meta_data;
                print "applied coupons:";
                print_r($applied_coupons);
                $msg =  "we have NOT applied the correct coupon";
                // print $msg;
                wc_print_notice($msg, "error");
                $result = false;
            }
        }

        // if (empty($item_meta_data)){
        //     print "empty";
        // }
        // if (is_null($item_meta_data)){
        //     print "is_null";
        // }
    }


    print "</pre>";
    return $result;
}

// add_action('woocommerce_review_order_before_submit', 'bcc_force_coupon_usage_when_purchasing_membership', 10, 0);
#----------------------------------------------------
// Replacing the Place order button when total volume exceed 68 m3
add_filter('woocommerce_order_button_html', 'replace_order_button_html', 10, 2);
function replace_order_button_html($order_button)
{
    // Only when total volume is up to 68

    if (bcc_force_coupon_usage_when_purchasing_membership()) {
        return $order_button;
    }

    $order_button_text = __("Return to Shopping Cart", "woocommerce");

    $style = ' style="color:#fff;background-color:#999;"';
    return '<a class="button alt"'.$style.' name="woocommerce_checkout_place_order" id="place_order" href="/cart">' . esc_html($order_button_text) . '</a>';
}
#----------------------------------------------------
add_action('woocommerce_before_calculate_totals', 'custom_cart_items_prices', 10, 1);
function custom_cart_items_prices($cart)
{
    if (is_admin() && ! defined('DOING_AJAX')) {
        return;
    }

    if (did_action('woocommerce_before_calculate_totals') >= 2) {
        return;
    }

    if (!is_cart()){
        return;
    }

    // Loop through cart items
    foreach ($cart->get_cart() as $cart_item) {

        // Get an instance of the WC_Product object
        $product = $cart_item['data'];
        // print "<pre>";
        // print_r($product);
        // print "</pre>";

        $sku = method_exists($product, 'get_sku') ? $product->get_sku() : $product->post->sku;
        print "SKU: $sku";

        if (preg_match('/^membership-/', $sku)) {
            // Get the product name (Added Woocommerce 3+ compatibility)
            $original_name = method_exists($product, 'get_name') ? $product->get_name() : $product->post->post_title;

            // SET THE NEW NAME
            $new_name = $original_name . ': <div class="coupon-required">Coupon required to order this item</div>';

            // Set the new name (WooCommerce versions 2.5.x to 3+)
            if (method_exists($product, 'set_name')) {
                $product->set_name($new_name);
            } else {
                $product->post->post_title = $new_name;
            }
        }
    }
}
#----------------------------------------------------
#----------------------------------------------------
#----------------------------------------------------
#----------------------------------------------------
#----------------------------------------------------
#----------------------------------------------------
#----------------------------------------------------
