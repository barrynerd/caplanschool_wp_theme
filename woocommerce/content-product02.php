<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
        return;
}
?>

<?php

    $count = 1;
    $name = $product->get_name();
    $name = str_replace('Fair Housing - Then', 'Fair Housing: Then', $name, $count);
    $limit = -1;
    $pieces = explode(" - ", $name);
    print("<pre>");
    // print_r($pieces);
    print("</pre>");

    $permalink = get_permalink( $product->get_id() );
?>
  
<div class="list-wrapper d-flex flex-row justify-content-center align-items-center my-3">


    <div class="course-wrapper d-flex flex-column col-7 pl-0">
        <div class="card-title mb-0 px-0 font-weight-bold"> <?php print $pieces[0]; ?></div>
        <div class="card-title mb-0 pl-0"> <?php print $pieces[1]; ?></div>
        <div class="card-title mb-0 pl-0"> <?php print "$pieces[2]-$pieces[3]"; ?></div>
        <div class="card-title mb-0 pl-0"> <a href="<?php echo $permalink ?>">Course Details</a></div>
    </div>
    <?php
        $add_to_cart_text = "Register";
        printf(
            '<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" class="btn btn-primary %s product_type_%s col col-md-3" style="max-height: 136.67px">%s</a>',
            esc_url( $product->add_to_cart_url() ),
            esc_attr( $product->get_id() ),
            esc_attr( $product->get_sku() ),
            $product->is_purchasable() ? 'add_to_cart_button' : '',
            esc_attr( $product->product_type ),
            esc_html( $add_to_cart_text )
        );
    ?>
</div>
