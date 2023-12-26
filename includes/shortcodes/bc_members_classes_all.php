<?php
    if (! defined('ABSPATH')) {
        exit; // Exit if accessed directly.
    }

function bc_members_classes_all($atts)
{

    global $product;
    

    $atts = shortcode_atts(
        array(
          'title' => '',
          'month' => '',
          'year' => '',
          'member_cat' => '',
        ),
        $atts
      );

    $start_date = $atts['year']."-01-00";
    $end_date = $atts['year']."-12-99";
    // print "start date: $start_date end date: $end_date";


    $member_cat = $atts['member_cat'];
    // print "member_cat= $member_cat";

    #query all continuing ed, all designations
    $args = array(
        'post_type'              => 'product',
        'post_status'            => 'publish',
        'order'                  => 'ASC',
        'orderby'                => 'meta_value',
        'meta_key'               => 'start_date',
        #'product_cat'           => 'continuing-education, designations',
        'tax_query'              => array(
            array(
                array(
                    'taxonomy' => 'product_cat',
                    'field'    => 'term_id',
                    'terms'    => array( $member_cat ),
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
        $result .= "<div class=\"bc_ce_classes_by_month woocommerce p-0\">";
        $result .= '<div class="products card col px-0 border-0">';
        $result .= '<div class="card-body px-0 px-md-4 my-0 mx-md-auto mx-4" style="line-height: 1">';

        while ($query->have_posts()) {
            $query->the_post();
            // print "id: " . $product->id . "-";
            #http://stackoverflow.com/questions/18957416/load-template-in-wordpress- without-echo-it
            ob_start();
            wc_get_template_part('content', 'product02');
            $result .= ob_get_clean();
        }
        $result .=  "</div>";
        $result .=  "</div>";
        $result .= "</div>";
    } else {
        // no posts found
        $result = '<div class="woocommerce "></div>';
    }


    // Restore original Post Data
        wp_reset_postdata();

    return $result;
}

add_shortcode('bc_members_classes_all', 'bc_members_classes_all');
