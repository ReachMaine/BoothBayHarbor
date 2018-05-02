<?php
/* customizations for woocommerce */

// declare woocommerce support  - so that it stops yelling at me.
add_action( 'after_setup_theme', 'woocommerce_support' );
function woocommerce_support() {
    add_theme_support( 'woocommerce' );
}

// since only one product, no sense going to "shop page"
function skyverge_change_empty_cart_button_url() {
	return get_site_url();
}
//add_filter( 'woocommerce_return_to_shop_redirect', 'skyverge_change_empty_cart_button_url' );

// remove category on single product
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );

// replace default image
add_action( 'init', 'custom_fix_thumbnail' );

function custom_fix_thumbnail() {
  add_filter('woocommerce_placeholder_img_src', 'custom_woocommerce_placeholder_img_src');

	function custom_woocommerce_placeholder_img_src( $src ) {
	$upload_dir = wp_upload_dir();
	$uploads = untrailingslashit( $upload_dir['baseurl'] );
  $childdir = get_stylesheet_directory_uri();
	$src = $childdir . '/images/default-product.png';

	return $src;
	}
}

// remove additional information tab
add_filter( 'woocommerce_product_tabs', 'woo_remove_product_tabs', 98 );
function woo_remove_product_tabs( $tabs ) {
    unset( $tabs['additional_information'] );  	// Remove the additional information tab
    return $tabs;

}
// remove price if zero (used for membership product where all cost comes from add ons)
add_filter( 'woocommerce_get_price_html', 'reach_remove_zero_prices', 10, 2 );
function reach_remove_zero_prices( $price, $product ) {
  if (!$product->get_regular_price()) {
    $price = '';
  }
  return $price;
}

// remove related products.  - now that we have more than one product
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

// for ticket products, add the line to emails to print this email as ticket.

add_action( 'woocommerce_email_before_order_table', 'bbh_add_content_specific_email', 20, 4 );
function bbh_add_content_specific_email( $order, $sent_to_admin, $plain_text, $email )  {
  if (  in_array( $email->id, array('customer_processing_order', 'customer_on_hold_order', 'customer_invoice' ) ) ){
    $display_print_this = false;
    $ticket_product_ids   = array('19888',' 21276' ); // 19888 is test site, 21276 is live site ticket
     // get the order line items
      $line_items    = $order->get_items( apply_filters( 'woocommerce_admin_order_item_types', 'line_item' ) );
      if ( ! is_array( $line_items ) || empty( $line_items ) ) {
    		return;
    	}
      foreach ( $line_items as $line_item ) {
    		$product_id = absint( $line_item['item_meta']['_product_id'][0] );
        $product_id =  $line_item->get_product_id();
    		if ( in_array( $product_id, $ticket_product_ids ) ) {
          $display_print_this = true;
        }

  	} // end looping thought items.
    if ($display_print_this) {
      echo "<p>We look forward to seeing you.  You may print this email as your ticket.<p>";
    }
  }
}

add_action( 'woocommerce_thankyou', 'bbloomer_add_content_thankyou', 5 );

function bbloomer_add_content_thankyou() {
echo '<h5 class="h2thanks">Printing your Ticket</h5><p class="pthanks">Check your inbox and print out receipt as your ticket</p>';
}
