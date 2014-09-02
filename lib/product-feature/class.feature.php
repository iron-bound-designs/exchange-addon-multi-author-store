<?php

class IT_EXCHANGE_Feature extends IT_Exchange_Product_Feature_Abstract {
    /**
     * Register our product feature for selecting a Gravity Form
     *
     * @param array $args
     */
    function __construct( $args = array() ) {
        parent::IT_Exchange_Product_Feature_Abstract( $args );
    }

    /**
     * This echos the feature metabox.
     *
     * @param $post WP_Post
     */
    function print_metabox( $post ) {
        $author_id = it_exchange_get_product_feature( $post->ID, $this->slug, array( 'field' => 'author_id' ) );

        $users = get_users( array( 'orderby' => 'last_name' ) );

        ?>
        <p><?php echo $this->description; ?></p>

        <label for="ibd_author_select">
            <?php _e( 'Select the author' ); ?>
        </label>
        <select id="ibd_author_select" name="ibd_author_select">
            <?php foreach ( $users as $user ): ?>
                <option value="<?php echo $user->ID; ?>" <?php selected( $user->ID, $author_id ); ?>><?php echo $user->last_name . ', ' . $user->first_name; ?></option>
            <?php endforeach; ?>
        </select>
    <?php
    }

    /**
     * This saves the value
     *
     * @return void
     */
    function save_feature_on_product_save() {
        // Abort if we don't have a product ID
        if ( !isset( $_POST['ID'] ) || empty( $_POST['ID'] ) )
            return;
        else
            $product_id = $_POST['ID'];

        $author_id = (int)$_POST['ibd_author_select'];

        it_exchange_update_product_feature( $product_id, $this->slug, array(
            'author_id' => $author_id
        ) );
    }

    /**
     * This updates the feature for a product
     *
     * @param integer $product_id the product id
     * @param mixed $new_value the new value
     * @param array $options
     *
     * @return boolean
     */
    function save_feature( $product_id, $new_value, $options = array() ) {
        return update_post_meta( $product_id, '_it_exchange_product_feature_' . $this->slug, $new_value );
    }

    /**
     * Return the product's features
     *
     * @param mixed $existing the values passed in by the WP Filter API. Ignored here.
     * @param integer $product_id the WordPress post ID
     * @param array $options
     *
     * @return string product feature
     */
    function get_feature( $existing, $product_id, $options = array() ) {

        $raw_meta = get_post_meta( $product_id, '_it_exchange_product_feature_' . $this->slug, true );

        $defaults = array(
            'author_id' => false
        );

        $raw_meta = wp_parse_args( $raw_meta, $defaults );

        if ( !isset( $options['field'] ) ) // if we aren't looking for a particular field
            return $raw_meta;

        $field = $options['field'];

        if ( isset( $raw_meta[$field] ) ) { // if the field exists with that name just return it
            return $raw_meta[$field];
        } else if ( strpos( $field, "." ) !== false ) { // if the field name was passed using array dot notation
            $pieces = explode( '.', $field );
            $context = $raw_meta;
            foreach ( $pieces as $piece ) {
                if ( !is_array( $context ) || !array_key_exists( $piece, $context ) )
                    return null;
                $context = &$context[$piece];
            }

            return $context;
        } else {
            return null; // we didn't find the data specified
        }
    }

    /**
     * Check if the product have the feature.
     *
     * @param mixed $result Not used by core
     * @param integer $product_id
     * @param array $options
     *
     * @return boolean
     */
    function product_has_feature( $result, $product_id, $options = array() ) {
        if ( false === it_exchange_product_supports_feature( $product_id, $this->slug ) )
            return false;

        return (boolean) it_exchange_get_product_feature( $product_id, $this->slug, array( 'field' => 'author_id' ) );
    }

    /**
     * Does the product support this feature?
     *
     * This is different than if it has the feature, a product can
     * support a feature but might not have the feature set.
     *
     * @param mixed $result Not used by core
     * @param integer $product_id
     * @param array $options
     *
     * @return boolean
     */
    function product_supports_feature( $result, $product_id, $options = array() ) {
        $product_type = it_exchange_get_product_type( $product_id );
        if ( !it_exchange_product_type_supports_feature( $product_type, $this->slug ) )
            return false;

        return true;
    }

}

new IT_EXCHANGE_Feature( array(
    'slug'          => 'ibd-multi-author-store',
    'description'   => __( 'Select an author for this product.' ),
    'metabox_title' => __( 'Product Author' )
) );