<?php
/**
 * Load the product feature.
 *
 * @author timothybjacobs
 * @since  9/11/14
 */

new ITEMAP_Product_Feature( array(
	'slug'          => 'ibd-multi-author-store',
	'description'   => __( 'Select an author for this product.', ITEMAP::SLUG ),
	'metabox_title' => __( 'Product Author', ITEMAP::SLUG )
) );