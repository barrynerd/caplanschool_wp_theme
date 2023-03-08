<?php

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
// remove product image zoom

// https://businessbloomer.com/woocommerce-disable-zoom-gallery-slider-lightbox-single-product/
add_action('after_setup_theme', 'xomli_remove_zoom_theme_support', 99);

function xomli_remove_zoom_theme_support()
{
    remove_theme_support('wc-product-gallery-zoom');
}

#----------------------------------------------------
//stop spurious insertions of <br> tags after several shortcodes in a row
// https://stackoverflow.com/questions/32570351/shortcode-output-adding-br-after-new-line
// remove_filter( 'the_content', 'wpautop' );
// add_filter( 'the_content', 'wpautop' , 12);

#----------------------------------------------------
//based on https://tipsnfreeware.com/how-to-disable-the-widget-block-editor-in-wordpress-5-8/
add_filter( 'use_widgets_block_editor', '__return_true' );

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
