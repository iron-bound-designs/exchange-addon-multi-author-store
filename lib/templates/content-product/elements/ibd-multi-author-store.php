<?php

$product = it_exchange_get_product( $GLOBALS['it_exchange']['product']->ID );
$author = get_userdata( $product->post_author );

?>
<?php do_action( 'it_exchange_content_product_before_product_author_element' ); ?>
<div class="it-exchange-product-author">
    <p>
        <?php printf( __( 'Author: %s %s', ITE_Multi_Author::SLUG ), $author->first_name, $author->last_name ); ?>
    </p>
</div>
<?php do_action( 'it_exchange_content_product_after_product_author_element' ); ?>

