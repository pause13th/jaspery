<?php
/**
 * Genesis Sample.
 *
 * This file adds functions to the Genesis Sample Theme.
 *
 * @package Genesis Sample
 * @author  StudioPress
 * @license GPL-2.0-or-later
 * @link    https://www.studiopress.com/
 */

require_once get_template_directory() . '/lib/init.php';

// Defines constants to help enqueue scripts and styles.
define('CHILD_THEME_HANDLE', sanitize_title_with_dashes(wp_get_theme()->get('Name')));
define('CHILD_THEME_VERSION', wp_get_theme()->get('Version'));

add_action('after_setup_theme', 'genesis_child_theme_support', 9);
/**
 * Add desired theme supports.
 *
 * See config file at `config/theme-supports.php`.
 *
 * @since 3.0.0
 */
function genesis_child_theme_support()
{
    $theme_supports = genesis_get_config('theme-support');
    foreach ($theme_supports as $feature => $args) {
        add_theme_support($feature, $args);
    }
}

add_action('after_setup_theme', 'genesis_sample_localization_setup');
/**
 * Sets localization (do not remove).
 *
 * @since 1.0.0
 */
function genesis_sample_localization_setup()
{
    load_child_theme_textdomain('genesis-sample', get_stylesheet_directory() . '/languages');
}

// add_action('after_setup_theme', 'genesis_child_gutenberg_support');
/**
 * Adds Gutenberg opt-in features and styling.
 *
 * @since 2.7.0
 */
function genesis_child_gutenberg_support()
{ // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound -- using same in all child themes to allow action to be unhooked.
    require_once get_stylesheet_directory() . '/lib/gutenberg/init.php';
}

// // Sets up the Theme.
// require_once get_stylesheet_directory() . '/lib/theme-defaults.php';
// // Adds helper functions.
require_once get_stylesheet_directory() . '/lib/helper-functions.php';
// // Adds image upload and color select to Customizer.
// require_once get_stylesheet_directory() . '/lib/customize.php';
// // Includes Customizer CSS.
// require_once get_stylesheet_directory() . '/lib/output.php';

// Adds image sizes
// 3x4
// 16x9
// 21x9
// add_image_size('portrait', 400, 550, true);
// add_image_size('square', 400, 300, true);

// // Removes header right widget area.
// unregister_sidebar('header-right');

// // Removes secondary sidebar.
// unregister_sidebar('sidebar-alt');

// // Removes site layouts.
// genesis_unregister_layout('content-sidebar-sidebar');
// genesis_unregister_layout('sidebar-content-sidebar');
// genesis_unregister_layout('sidebar-sidebar-content');

// // Removes output of primary navigation right extras.
// remove_filter('genesis_nav_items', 'genesis_nav_right', 10, 2);
// remove_filter('wp_nav_menu_items', 'genesis_nav_right', 10, 2);

// check(?)
add_action('genesis_theme_settings_metaboxes', 'genesis_sample_remove_metaboxes');
/**
 * Removes output of unused admin settings metaboxes.
 *
 * @since 2.6.0
 *
 * @param string $_genesis_admin_settings The admin screen to remove meta boxes from.
 */
function genesis_sample_remove_metaboxes($_genesis_admin_settings)
{
    remove_meta_box('genesis-theme-settings-header', $_genesis_admin_settings, 'main');
    remove_meta_box('genesis-theme-settings-nav', $_genesis_admin_settings, 'main');
}

// check(?)
add_filter('genesis_customizer_theme_settings_config', 'genesis_sample_remove_customizer_settings');
/**
 * Removes output of header and front page breadcrumb settings in the Customizer.
 *
 * @since 2.6.0
 *
 * @param array $config Original Customizer items.
 * @return array Filtered Customizer items.
 */
function genesis_sample_remove_customizer_settings($config)
{
    unset($config['genesis']['sections']['genesis_header']);
    unset($config['genesis']['sections']['genesis_breadcrumbs']['controls']['breadcrumb_front_page']);
    return $config;
}

/**
 * // force full width for all pages
 */
add_filter('genesis_pre_get_option_site_layout', '__genesis_return_full_width_content');


// // insert bootstrap layout
// add_filter('genesis_attr_site-inner', 'genesis_attr_site_inner_class');
// function genesis_attr_site_inner_class($attr){
//     $attr['class'] .= ' container';
//     return $attr;
// }
// add_filter('genesis_attr_content-sidebar-wrap', 'genesis_attr_container_sidebar_wrap_class');
// function genesis_attr_container_sidebar_wrap_class($attr){
//     $attr['class'] .= ' row';
//     return $attr;
// }
// add_filter('genesis_attr_content', 'genesis_attr_content_class');
// function genesis_attr_content_class($attr){
//     $attr['class'] .= ' col container-fluid';
//     return $attr;
// }
// add_action('genesis_before_loop', 'genesis_content_wrap');
// function genesis_content_wrap(){
//     echo '<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3">';
// }

// add_filter('genesis_attr_entry', 'genesis_attr_entry_class');
// function genesis_attr_entry_class($attr){
//     $attr['class'] .= ' col-auto px-1';
//     return $attr;
// }

// // reframe entry into 'card'
// add_action('genesis_entry_header', 'genesis_entry_wrap', 1);
// function genesis_entry_wrap(){
//     echo '<div class="card">';
// }

// add_filter('genesis_attr_entry-header', 'genesis_attr_entry_header_class');
// function genesis_attr_entry_header_class($attr){
//     $attr['class'] .= ' card-header';
//     return $attr;
// }
// add_filter('genesis_attr_entry-content', 'genesis_attr_entry_content_class');
// function genesis_attr_entry_content_class($attr){
//     $attr['class'] .= ' card-body';
//     return $attr;
// }

// // reposition entry-footer-meta
// remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_open', 5 );
// remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_close', 15 );
// remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
// add_action( 'genesis_entry_content', 'genesis_entry_footer_markup_open', 10 );
// add_action( 'genesis_entry_content', 'genesis_entry_footer_markup_close', 15 );
// add_action( 'genesis_entry_content', 'genesis_post_meta', 13);



// temp nav

add_action('genesis_after_header', 'add_login_logout_link');
function add_login_logout_link()
{
    echo '<a href="'. get_site_url() .'/wot">page-wot</a>';
    echo '<span class="d-inline-block mx-2">&middot;</span>';
    echo '<a href="'. get_site_url() .'/myco">page-myco</a>';
    echo '<span class="d-inline-block mx-2">&middot;</span>';
    wp_loginout('index.php');
}


