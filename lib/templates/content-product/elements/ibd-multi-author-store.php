<?php

$product = it_exchange_get_product( $GLOBALS['it_exchange']['product']->ID );
$author = get_userdata( $product->post_author );

?>
<div>
    Author: <?php printf('%s %s', $author->first_name, $author->last_name); ?>
</div>

