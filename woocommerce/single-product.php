<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

get_header('shop');
$container = get_theme_mod( 'understrap_container_type' );

?>

	<?php
        /**
         * woocommerce_before_main_content hook.
         *
         * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
         * @hooked woocommerce_breadcrumb - 20
         */
        //do_action( 'woocommerce_before_main_content' );
    ?>

	<div class="wrapper" id="product-wrapper">
		<div class="<?php echo esc_attr($container); ?>" id="content" tabindex="-1">
			<div class="row">
				<div class="col-10 col-md-8 content-area p-0 mx-auto" id="primary">
					<?php while (have_posts()) : the_post(); ?>
                        <?php
                            wc_get_template_part('content', 'single-product');
//                             if (bcc_product_uses_gutenberg()) {
// 				print "aaa";
//                                 wc_get_template_part('content', 'single-product-2');
// #wc_get_template_part('content', 'single-product');
//
//                             }
//                             else {
// 				print "bbb";
//                                 wc_get_template_part('content', 'single-product');
//                             }
                        ?>
					<?php endwhile; // end of the loop.?>

					</div>
				</div>
			</div>
		</div>

	<?php
        /**
         * woocommerce_after_main_content hook.
         *
         * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
         */
        // do_action( 'woocommerce_after_main_content' );
    ?>

	<?php
        /**
         * woocommerce_sidebar hook.
         *
         * @hooked woocommerce_get_sidebar - 10
         */
        // do_action( 'woocommerce_sidebar' );

    ?>


<?php get_footer('shop');

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
