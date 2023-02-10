<?php

function bc_ce_classes_by_title($atts)
{

    global $product;
    

    $result = "";

    $atts = shortcode_atts(
        array(
          'title' => '',
          'month' => '',
          'year' => '',
          'category_id' => 0,

        ),
        $atts
      );

    $start_date = $atts['year']."-".$atts['month']."-00";
    $end_date = $atts['year']."-".$atts['month']."-99";
    #print "start date: $start_date end date: $end_date";

    $category_id = $atts['category_id'];

    // see https://wordpress.stackexchange.com/questions/188892/getting-a-taxonomys-thumbnail-url
    $taxonomies = array( 'product_cat' );
    $thumb_id = get_woocommerce_term_meta( $category_id, 'thumbnail_id', true );
    $term_img = wp_get_attachment_url(  $thumb_id );

    #query all continuing ed, all designations
    $args = array(
        'post_type'              => 'product',
        'post_status'            => 'publish',
        'order'                  => 'ASC',
        'orderby'                => 'meta_value',
        'meta_key'                               => 'start_date',
        'tax_query'                      => array(
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
                    'terms'    => array( $category_id ),
                ),
            ),
        ),
        'nopaging' => true,
        'posts_per_page' => -1,
    );

    // The Query
    $query = new WP_Query($args);

    // The Loop
    if ($query->have_posts()) {
        $result .= "<img src=\"$term_img\" class=\"d-block mx-auto\" >";
	if ($atts['title']){
        	$result .= "<h2 id=\"$start_date\">{$atts['title']}</h2>";
	}
        $result .= "<div class=\"bc_ce_classes_by_month woocommerce p-0\">";
        $result .= '<div class="products card col px-0 border-0">';
        $result .= '<div class="card-body px-0 px-md-4 my-0 mx-md-0" style="line-height: 1">';

        while ($query->have_posts()) {
            $query->the_post();
            #http://stackoverflow.com/questions/18957416/load-template-in-wordpress- without-echo-it
            ob_start();
            wc_get_template_part('content', 'product03');
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

add_shortcode('bc_ce_classes_by_title', 'bc_ce_classes_by_title');


