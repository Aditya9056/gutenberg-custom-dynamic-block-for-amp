<?php
/**
 * PHPUnit bootstrap file
 *
 * @package Dynamic_Block
 */

/**
 * Manually load the plugin being tested.
 */

require_once __DIR__ . '/../vendor/autoload.php';
WP_Mock::bootstrap();

require dirname( dirname( __FILE__ ) ) . '/dynamic-block.php';
require dirname( dirname( __FILE__ ) ) . '/php/Plugin.php';
