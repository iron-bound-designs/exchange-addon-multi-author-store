<?php


/**
 * Register our template paths
 *
 * @param array $paths existing template paths
 *
 * @return array
 */
function ite_multi_author_template_paths( $paths=array() ) {
    $paths[] = ITE_Multi_Author::$dir . 'lib/templates';

    return $paths;
}

add_filter( 'it_exchange_possible_template_paths', 'ite_multi_author_template_paths' );


function ite_multi_author_product_author( $parts ) {
    $parts[] = 'ibd-multi-author-store';

    return $parts;
}

add_filter( 'it_exchange_get_content_product_product_info_loop_elements', 'ite_multi_author_product_author' );