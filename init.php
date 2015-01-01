<?php
/**
 * Main init file.
 *
 * @author Iron Bound Designs
 * @since 1.0
 */

/**
 * Load all of the main plugin functions.
 */
require_once( ITEMAP::$dir . 'lib/functions.php' );

/**
 * Load all of the required hooks.
 */
require_once( ITEMAP::$dir . 'lib/required-hooks.php' );

/**
 * Load the product module
 */
require_once( ITEMAP::$dir . 'lib/product/load.php' );
