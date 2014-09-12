<?php
/**
 * File Description
 *
 * @author timothybjacobs
 * @since  9/11/14
 */

new ITE_Multi_Author_Feature( array(
	'slug'          => 'ibd-multi-author-store',
	'description'   => __( 'Select an author for this product.', ITE_Multi_Author::SLUG ),
	'metabox_title' => __( 'Product Author', ITE_Multi_Author::SLUG )
) );