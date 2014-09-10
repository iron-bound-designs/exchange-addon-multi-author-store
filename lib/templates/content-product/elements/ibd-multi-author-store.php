<?php

$product = it_exchange_get_product( $GLOBALS['it_exchange']['product']->ID );
$author = get_userdata( $product->post_author );

?>
<div class="it-exchange-product-author">
    <p>
        <?php printf( __( 'Author: %s %s', 'ibd_multi_author' ), $author->first_name, $author->last_name ); ?>
    </p>
</div>

