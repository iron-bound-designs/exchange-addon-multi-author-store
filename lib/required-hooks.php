<?php

/**
 * Register our template paths
 *
 * @param array $paths existing template paths
 *
 * @return array
 */
function ite_multi_author_template_paths( $paths = array() ) {
	$paths[] = ITEMAP::$dir . 'lib/templates';

	return $paths;
}

add_filter( 'it_exchange_possible_template_paths', 'ite_multi_author_template_paths' );


/**
 * Display the product author in the product detail page.
 *
 * @param array $parts The product information to display.
 *
 * @return array
 */
function ite_multi_author_product_author( $parts ) {
	$new_parts = array();

	foreach ( $parts as $part ) {
		$new_parts[] = $part;

		// Insert the author info just below the price info.
		if ( $part === 'base-price' ) {
			$new_parts[] = 'ibd-multi-author-store';
		}
	}

	return $new_parts;
}

add_filter( 'it_exchange_get_content_product_product_info_loop_elements', 'ite_multi_author_product_author' );

/**
 * Add fields to the coupon edit screen.
 *
 * @since 1.0
 *
 * @param ITForm $form
 */
function itemap_add_coupon_fields_to_edit( $form ) {

}

add_action( 'it_exchange_basics_coupon_coupon_edit_screen_end_fields', 'itemap_add_coupon_fields_to_edit' );