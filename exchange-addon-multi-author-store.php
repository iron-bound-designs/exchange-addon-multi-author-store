<?php

/*
 * Plugin Name: iThemes Exchange Multi-author Store
 *
 * Description: Creates a user interface for selecting an author in the product edit screen.
 *
 * Version: 0.1
 *
 * Author: Iron Bound Designs
 * Author URI: http://ironbounddesigns.com
 *
 * License: GPL2
 */


if ( !defined('IT_EXCHANGE_MULTI_AUTHOR_DIR') )
    define('IT_EXCHANGE_MULTI_AUTHOR_DIR', plugin_dir_path( __FILE__ ));

if ( !defined('IT_EXCHANGE_MULTI_AUTHOR_URL') )
    define('IT_EXCHANGE_MULTI_AUTHOR_URL', plugin_dir_url( __FILE__ ));


class IT_EXCHANGE_MultiAuthorStore {
    
    /**
     * Add required actions.
     */
    function __construct() {
        add_action( 'init', array( $this, 'init' ) );

        add_action( 'it_exchange_register_addons', array( $this, 'multi_author_addon' ) );

        spl_autoload_register( array( $this, 'autoload' ) );
    }

    /**
     * Initialize the plugin.
     */
    public function init() {
        require_once( IT_EXCHANGE_MULTI_AUTHOR_DIR . 'init.php' );
    }


    /**
     * Registers the multi-author add-on.
     */
    function multi_author_addon() {
        $options = array(
            'name'        => __( 'Multi-author Store' ),
            'description' => __( 'Creates a user interface for selecting an author in the product edit screen.' ),
            'author'      => 'Iron Bound Designs',
            'author_url'  => 'http://www.ironbounddesigns.com',
            'file'        => IT_EXCHANGE_MULTI_AUTHOR_DIR . 'init.php',
            'category'    => 'product-feature',
            'basename'    => plugin_basename( __FILE__ ),
            'labels'      => array(
                'singular_name' => __( 'Multi-author Store' ),
            )
        );

        it_exchange_register_addon( 'multi-author-store', $options );
    }

    /**
     * The autoloader.
     *
     * @param $class_name string
     */
    public function autoload( $class_name ) {
        if ( substr( $class_name, 0, 11 ) !== 'IT_EXCHANGE' )
            return;

        // Get rid of the "IT_EXCHANGE_" prefix.
        $class = strtolower( substr( $class_name, 12 ) );

        $parts = explode( '_', $class );

        // Get the file name.
        $name = array_pop( $parts );

        // Get the directory path.
        $path = implode( '/', $parts );

        $prefixes = array( 'class', 'abstract', 'interface' );

        foreach ( $prefixes as $prefix ) {
            $fullpath = IT_EXCHANGE_MULTI_AUTHOR_DIR  . "lib/{$path}/{$prefix}.{$name}.php";

            if ( file_exists( $fullpath ) ) {
                require( $fullpath );
                return;
            }
        }
    }

}

new IT_EXCHANGE_MultiAuthorStore;