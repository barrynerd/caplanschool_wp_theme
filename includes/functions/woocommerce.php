<?php

//activate gutenberg for products
// https://wplook.com/how-to-enamble-gutenberg-for-woocommerce-products/`
function wplook_activate_gutenberg_products($can_edit, $post_type){
	if($post_type == 'product'){
		$can_edit = true;
	}

	return $can_edit;
}
add_filter('use_block_editor_for_post_type', 'wplook_activate_gutenberg_products', 10, 2);

#----------------------------------------------------
// https://wordpress.org/support/topic/customizing-sale-badge-at-woocommerce/

// add_filter('woocommerce_sale_flash', 'my_custom_sales_badge');

function my_custom_sales_badge() {
	$img = '<img width="75px" height="30px" src="https://misofis.com/wp-content/uploads/indirim.gif"></img>';
	return $img;
}
#----------------------------------------------------
add_filter('woocommerce_sale_flash', 'bcc_change_sales_flash_content', 10, 3);
function bcc_change_sales_flash_content($content, $post, $product){

	$message = get_post_meta( $product->get_id(), 'sale_flash_message',true );
	if ($message) {
		$content = '<span class="onsale">'.__( $message, 'woocommerce' ).'</span>';
	}

return $content;
}
#----------------------------------------------------
#----------------------------------------------------
#----------------------------------------------------
#----------------------------------------------------
#----------------------------------------------------
#----------------------------------------------------
#----------------------------------------------------
#----------------------------------------------------
#----------------------------------------------------
#----------------------------------------------------
#----------------------------------------------------
#----------------------------------------------------
#----------------------------------------------------
#----------------------------------------------------
#----------------------------------------------------
#----------------------------------------------------
#----------------------------------------------------
#----------------------------------------------------
#----------------------------------------------------
#----------------------------------------------------
#----------------------------------------------------
#----------------------------------------------------
#----------------------------------------------------
