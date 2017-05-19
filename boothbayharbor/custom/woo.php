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
add_filter( 'woocommerce_return_to_shop_redirect', 'skyverge_change_empty_cart_button_url' );

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
