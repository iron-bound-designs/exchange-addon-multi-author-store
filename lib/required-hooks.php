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

	$class = $form->get_option( 'limit-author' ) ? '' : 'hide-if-js';
	?>

	<div class="field limit-author">
		<?php $form->add_check_box( 'limit-author' ); ?>
		<label for="limit-author">
			<?php _e( 'Limit to a specific author', ITEMAP::SLUG ); ?>
			<span class="tip" title="<?php esc_attr_e( "Check to limit the use of this coupon to products by a certain author.", ITEMAP::SLUG ); ?>">i</span>
		</label>
	</div>

	<div class="field author-limitations <?php echo esc_attr( $class ); ?>">
		<?php
		$users = itemap_get_product_authors();

		$authors = array(
			- 1 => __( "Select an author", ITEMAP::SLUG )
		);

		foreach ( $users as $user ) {
			$authors[ $user->ID ] = $user->display_name;
		}

		$form->add_drop_down( 'product-author', $authors );
		?>
		<span class="tip" title="<?php esc_attr_e( "Select the product author to limit this coupon to.", ITEMAP::SLUG ); ?>">i</span>
	</div>

	<script type="text/javascript">
		jQuery(document).ready(function ($) {
			$("#limit-author").click(function () {
				var options = $(".author-limitations");

				if ($(this).attr('checked') == 'checked')
					options.removeClass('hide-if-js').show();
				else
					options.hide();
			});
		});
	</script>

<?php

}

add_action( 'it_exchange_basics_coupon_coupon_edit_screen_end_fields', 'itemap_add_coupon_fields_to_edit', 5 );

/**
 * Add our new meta data to the coupon.
 *
 * @param array   $data
 * @param WP_Post $post
 *
 * @return array
 */
function itemap_add_coupon_meta_data_to_object( $data, $post ) {

	$val = absint( get_post_meta( $post->ID, '_it-basic-limit-author', true ) );

	if ( empty( $val ) ) {
		$val = false;
	}

	$data['product_author'] = $val;

	return $data;
}

add_filter( 'it_exchange_coupon_additional_data', 'itemap_add_coupon_meta_data_to_object', 10, 2 );

/**
 * Add our form values to the form object.
 *
 * @since 1.0
 *
 * @param ITForm $form
 */
function itemap_add_coupon_data_to_form( $form ) {
	$post_id = empty( $_GET['post'] ) ? false : $_GET['post'];

	if ( $post_id ) {
		$coupon = new IT_Exchange_Coupon( $post_id );

		$form->set_option( 'limit-author', (bool) $coupon->product_author );
		$form->set_option( 'product-author', false === $coupon->product_author ? - 1 : $coupon->product_author );
	}
}

add_action( 'it_exchange_basics_coupon_coupon_edit_screen_begin_fields', 'itemap_add_coupon_data_to_form' );

/**
 * Save the coupon fields.
 *
 * @since 1.0
 *
 * @param array $data
 *
 * @return array
 */
function itemap_save_coupon_fields( $data ) {

	$val = $data['product-author'] == - 1 ? "" : absint( $data['product-author'] );
	unset( $data['limit-author'] );
	unset( $data['product-author'] );

	$data['post_meta']['_it-basic-limit-author'] = $val;

	return $data;
}

add_filter( 'it_exchange_basic_coupons_save_coupon', 'itemap_save_coupon_fields' );

/**
 * Validate the coupon fields.
 *
 * @since 1.0
 *
 * @param array $data
 */
function itemap_validate_coupon_fields( $data ) {

	if ( ! empty( $data['limit-author'] ) && $data['product-author'] == - 1 ) {
		it_exchange_add_message( 'error', __( 'Please select an author limitation', ITEMAP::SLUG ) );
	}
}

add_action( 'it_exchange_basic_coupons_data_is_valid', 'itemap_validate_coupon_fields' );

/**
 * Validate the coupon when it is applied to the cart.
 *
 * @since 1.0
 *
 * @param bool               $result
 * @param array              $options
 * @param IT_Exchange_Coupon $coupon
 *
 * @return bool|null
 */
function itemap_validate_coupon_application( $result, $options, $coupon ) {

	if ( false === $result ) {
		return false;
	}

	if ( empty( $coupon->product_author ) ) {
		return null;
	}

	$author   = $coupon->product_author;
	$products = it_exchange_get_cart_products();

	foreach ( $products as $product ) {
		if ( it_exchange_get_product( $product['product_id'] )->post_author != $author ) {

			it_exchange_add_message( 'error', __( 'Invalid coupon', ITEMAP::SLUG ) );

			return false;
		}
	}

	return $result;
}

add_filter( 'it_exchange_basic_coupons_apply_coupon_to_cart', 'itemap_validate_coupon_application', 10, 3 );