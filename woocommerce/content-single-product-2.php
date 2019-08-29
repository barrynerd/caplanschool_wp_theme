<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked wc_print_notices - 10
 */
do_action( 'woocommerce_before_single_product' );
if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class("product"); ?>>
      <?php
        /**
         * Hook: woocommerce_before_single_product_summary.
         *
         * @hooked woocommerce_show_product_sale_flash - 10
         * @hooked woocommerce_show_product_images - 20
         */
//	remove_action("woocommerce_before_single_product_summary", "woocommerce_show_product_images", 20);

  //      do_action( 'woocommerce_before_single_product_summary' );
        ?>

<style scoped>
.bcc_sale_flash {
    min-height: 3.236em;
    min-width: 3.236em;
    font-weight: 700;
    position: absolute;
    text-align: center;
    line-height: 3.236;
    top: -1.5rem;
    left: -.5rem;
    margin: 0;
    border-radius: 100%;
    z-index: 9;
	
	background: orange;
    	color: black;
   	margin: 0px auto;
        padding: 1rem;
        font-size: .8rem;
	max-width: 21rem;
    	line-height: 1.2;
	}
        
@media (min-width: 768px) {
	.bcc_sale_flash {
        	padding: 1.5rem;
        	font-size: 1.3rem;
        	}
}
</style>

<span class="xonsale bcc_sale_flash">Early Bird Special!</br>$50 before Sep 20</br>Regular $99</span>
	<?php
			$image_size = array(740, 386);
			the_post_thumbnail($image_size);
	?>

	<div class="summary entry-summary">
		<?php
			// move price on single product page to be after the priduct description
			remove_action("woocommerce_single_product_summary", "woocommerce_template_single_price", 10);
			add_action("woocommerce_single_product_summary", "woocommerce_template_single_price", 25);

			// remove the meta data from display
			remove_action("woocommerce_single_product_summary","woocommerce_template_single_meta", 40);

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
add_action( 'woocommerce_single_product_summary', 'the_content', 20 );
			/**
			 * Hook: woocommerce_single_product_summary.
			 *
			 * @hooked woocommerce_template_single_title - 5
			 * @hooked woocommerce_template_single_rating - 10
			 * @hooked woocommerce_template_single_price - 10
			 * @hooked woocommerce_template_single_excerpt - 20
			 * @hooked woocommerce_template_single_add_to_cart - 30
			 * @hooked woocommerce_template_single_meta - 40
			 * @hooked woocommerce_template_single_sharing - 50
			 * @hooked WC_Structured_Data::generate_product_data() - 60
			 */
			do_action( 'woocommerce_single_product_summary' );
		?>

	</div>

	<?php
		/**
		 * Hook: woocommerce_after_single_product_summary.
		 *
		 * @hooked woocommerce_output_product_data_tabs - 10
		 * @hooked woocommerce_upsell_display - 15
		 * @hooked woocommerce_output_related_products - 20
		 */
		do_action( 'woocommerce_after_single_product_summary' );
	?>
</div>

<?php do_action( 'woocommerce_after_single_product' ); ?>