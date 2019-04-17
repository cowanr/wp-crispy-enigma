<?php
/**
 * PHPUnit bootstrap file
 *
 * @package Wp_Crispy_Enigma
 */

// define test environment
define( 'CE_PHPUNIT', true );

// define fake ABSPATH
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', sys_get_temp_dir() );
}
// define fake PLUGIN_ABSPATH
if ( ! defined( 'CE_ABSPATH' ) ) {
	define( 'CE_ABSPATH', dirname( dirname( dirname( __FILE__ ) ) ) );
}

// Include the mock library
require_once __DIR__ . '/../../vendor/autoload.php';

// Include the class for PluginTestCase
require_once __DIR__ . '/PluginTestCase.php';
