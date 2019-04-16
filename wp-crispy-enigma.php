<?php

/*
Plugin Name: Simple ToDo
Plugin URI:  https://developer.wordpress.org/plugins/the-basics/
Description: A Basic WordPress Plugin for illustrating CI and unit testing.   Creates a custom post type for todo items.
Version:     1.0
Author:      Ryan Cowan
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$todo = new CE_To_Do();
$todo->init();
