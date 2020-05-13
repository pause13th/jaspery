<?php
/*
Plugin Name: mu-default
Plugin URI:
Description: Default Must Use Plugin
Version: 1.0.0
Author:
Author URI:
License: GPLv2 or later
Text Domain:
 */

/**
 * check for ABSPATH for WordPress
 */
if (!defined('ABSPATH')) {
    die;
}

/**
 * composer autoload
 */
if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
    require_once dirname(__FILE__) . '/vendor/autoload.php';
}

/**
 * initiate services
 */
if (class_exists('Inc\\Init')) {
    Inc\Init::register_services();
}
