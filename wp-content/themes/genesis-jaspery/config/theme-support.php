<?php
/**
 * Theme supports.
 *
 * @package Genesis Sample
 * @author  StudioPress
 * @license GPL-2.0-or-later
 * @link    https://my.studiopress.com/themes/genesis-sample/
 */

return [
    'post-formats' => [
        'aside', 'image', 'gallery', 'video', 'audio', 'link', 'quote', 'status',
    ],
    // Opt-in theme supports
    // ref: https://studiopress.github.io/genesis/developer-features/theme-support/
    'html5' => [
        'caption',
        'comment-form',
        'comment-list',
        'gallery',
        'search-form',
        'script',
        'style',
    ],

    'genesis-accessibility' => [
        'drop-down-menu',
        'headings',
        'search-form',
        'skip-links',
    ],

    'genesis-menus' => [
        'primary' => __('Primary Menu', 'genesis-child'),
        'secondary' => __('Secondary Menu', 'genesis-child'),
    ],

    'genesis-after-entry-widget-area' => '',

    'genesis-footer-widgets' => 3,

    'genesis-lazy-load-images' => '',

    'genesis-responsive-viewport' => '',

    'genesis-custom-logo' => [
        'height' => 120,
        'width' => 700,
        'flex-height' => true,
        'flex-width' => true,
        // 'height'      => 100,
        // 'width'       => 400,
        // 'flex-height' => true,
        // 'flex-width'  => true,
        // 'header-text' => array( 'site-title', 'site-description' ),
    ],

    /* 'custom-background' => [
'default-image' => '',
'default-preset' => 'default',
'default-size' => 'cover',
'default-repeat' => 'no-repeat',
'default-attachment' => 'scroll',
], */

];

/**
 * list of theme_support genesis added by default
 * ref: https://studiopress.github.io/genesis/developer-features/theme-support/
 */
// add_theme_support( 'menus' );
// add_theme_support( 'post-thumbnails' );
// add_theme_support( 'title-tag' );
// add_theme_support( 'automatic-feed-links' );
// add_theme_support( 'body-open' );
// add_theme_support( 'genesis-inpost-layouts' );
// add_theme_support( 'genesis-archive-layouts' );
// add_theme_support( 'genesis-admin-menu' );
// add_theme_support( 'genesis-seo-settings-menu' );
// add_theme_support( 'genesis-import-export-menu' );
// add_theme_support( 'genesis-customizer-theme-settings' );
// add_theme_support( 'genesis-customizer-seo-settings' );
// add_theme_support( 'genesis-auto-updates' );
// add_theme_support( 'genesis-breadcrumbs' );

/*
// $post_formats = array('aside','image','gallery','video','audio','link','quote','status');
// add_theme_support( 'post-formats', $post_formats);
 */

/*
// add_theme_support( 'post-thumbnails' );
// add_theme_support( 'post-thumbnails', array( 'post' ) );          // Posts only
// add_theme_support( 'post-thumbnails', array( 'page' ) );          // Pages only
// add_theme_support( 'post-thumbnails', array( 'post', 'movie' ) ); // Posts and Movies
 */

/*
$defaults = array(
'default-image'          => '',
'default-preset'         => 'default', // 'default', 'fill', 'fit', 'repeat', 'custom'
'default-position-x'     => 'left',    // 'left', 'center', 'right'
'default-position-y'     => 'top',     // 'top', 'center', 'bottom'
'default-size'           => 'auto',    // 'auto', 'contain', 'cover'
'default-repeat'         => 'repeat',  // 'repeat-x', 'repeat-y', 'repeat', 'no-repeat'
'default-attachment'     => 'scroll',  // 'scroll', 'fixed'
'default-color'          => '',
'wp-head-callback'       => '_custom_background_cb',
'admin-head-callback'    => '',
'admin-preview-callback' => '',
);
add_theme_support( 'custom-background', $defaults );
 */

/*
$defaults = array(
'default-image'          => '',
'random-default'         => false,
'width'                  => 0,
'height'                 => 0,
'flex-height'            => false,
'flex-width'             => false,
'default-text-color'     => '',
'header-text'            => true,
'uploads'                => true,
'wp-head-callback'       => '',
'admin-head-callback'    => '',
'admin-preview-callback' => '',
'video'                  => false,
'video-active-callback'  => 'is_front_page',
);
add_theme_support( 'custom-header', $defaults );
 */

/*
// add_theme_support( 'custom-logo', array(
'height'      => 100,
'width'       => 400,
'flex-height' => true,
'flex-width'  => true,
'header-text' => array( 'site-title', 'site-description' ),
// ) );
 */

/*
// add_theme_support( 'automatic-feed-links' );
 */

/*
// $html5 = array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' )
// add_theme_support( 'html5', $html5 );
 */

/*
// add_theme_support( 'title-tag' );
 */

/*
// refresh widgest
// add_theme_support( 'customize-selective-refresh-widgets' );
 */

/*
// options for block editor
// add_theme_support('responsive-embeds');
// add_theme_support('align-wide');
// add_theme_support('align-left');
// add_theme_support('dark-editor-style');
// add_theme_support('disable-custom-colors');
// add_theme_support('disable-custom-font-sizes');
// add_theme_support('editor-color-palette');
// add_theme_support('editor-font-sizes');
// add_theme_support('editor-styles');
// add_theme_support('wp-block-styles');
 */

// for advance usage
// starter-content: https://make.wordpress.org/core/2016/11/30/starter-content-for-themes-in-4-7/
// starter-content reference: https://developer.wordpress.org/reference/functions/get_theme_starter_content/
// add_theme_support( 'starter-content', array( /*...*/ ) )