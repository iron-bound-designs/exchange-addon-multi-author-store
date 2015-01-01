<?php
/**
 * Main plugin functions.
 *
 * @author Iron Bound Designs
 * @since  1.0
 */

/**
 * Retrieve the product authors.
 *
 * @param array $args
 *
 * @return WP_User[]
 */
function itemap_get_product_authors( $args = array() ) {

	$defaults = array();

	$args = ITUtility::merge_defaults( $args, $defaults );

	/**
	 * Filter the args that are passed to get_users()
	 *
	 * @param $args array
	 */
	$args = apply_filters( 'it_exchange_itemap_get_product_authors', $args );

	return get_users( $args );
}