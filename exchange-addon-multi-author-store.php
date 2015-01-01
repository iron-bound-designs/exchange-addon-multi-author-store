<?php
/*
 * Plugin Name: iThemes Exchange Multi-Author Store Add-on
 * Plugin URI: http://ironbounddesigns.com
 * Description: Allows for specification of an iThemes Exchange product author.
 * Version: 0.9
 * Author: Iron Bound Designs
 * Author URI: http://ironbounddesigns.com
 * License: GPL2
 */

/**
 * Class ITEMAP
 */
class ITEMAP {

	/**
	 * @var string
	 */
	static $dir;

	/**
	 * @var string
	 */
	static $url;

	/**
	 * @var string
	 */
	const SLUG = 'ibd-multi-author';

	/**
	 * Add required actions.
	 */
	function __construct() {
		self::$dir = plugin_dir_path( __FILE__ );
		self::$url = plugin_dir_url( __FILE__ );

		add_action( 'plugins_loaded', array( $this, 'load_text_domain' ) );
		add_action( 'it_exchange_register_addons', array( $this, 'multi_author_addon' ) );

		spl_autoload_register( array( $this, 'autoload' ) );
	}

	/**
	 * Loads the translation data for WordPress
	 */
	function load_text_domain() {
		load_plugin_textdomain( 'ibd_multi_author', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
	}

	/**
	 * Registers the multi-author add-on.
	 */
	function multi_author_addon() {
		$options = array(
			'name'        => __( 'Multi-author Store', self::SLUG ),
			'description' => __( 'Creates a user interface for selecting an author in the product edit screen.', self::SLUG ),
			'author'      => 'Iron Bound Designs',
			'author_url'  => 'http://www.ironbounddesigns.com',
			'file'        => self::$dir . 'init.php',
			'category'    => 'product-feature',
			'basename'    => plugin_basename( __FILE__ ),
			'labels'      => array(
				'singular_name' => __( 'Multi-author Store', self::SLUG ),
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
		if ( substr( $class_name, 0, 6 ) !== 'ITEMAP' ) {
			return;
		}

		// Get rid of the "ITE_Multi_Author" prefix.
		$class = strtolower( substr( $class_name, 6 ) );

		$parts = explode( '_', $class );

		// Get the file name.
		$name = array_pop( $parts );

		// Get the directory path.
		$path = implode( '/', $parts );

		$prefixes = array( 'class', 'abstract', 'interface' );

		foreach ( $prefixes as $prefix ) {
			$fullpath = self::$dir . "lib/{$path}/{$prefix}.{$name}.php";

			if ( file_exists( $fullpath ) ) {
				require( $fullpath );

				return;
			}
		}
	}
}

new ITEMAP;
