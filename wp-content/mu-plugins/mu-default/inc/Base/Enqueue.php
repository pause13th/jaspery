<?php
/**
 * @package mu-default
 */
namespace Inc\Base;

use \Inc\Base\BaseController;

class Enqueue extends BaseController
{
    public function register()
    {
        add_action('login_enqueue_scripts', array($this, 'login_enqueue'));
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue'));
        add_action('wp_enqueue_scripts', array($this, 'wp_enqueue'));
    }

    public function login_enqueue()
    {
        wp_enqueue_style('login-style', $this->plugin_url . 'dist/style.login.css', array(), '1.0.0');
    }

    public function admin_enqueue()
    {
        wp_enqueue_style('admin-style', $this->plugin_url . 'dist/style.admin.css', array(), '1.0.0');
    }

    public function wp_enqueue()
    {
        // check if development
        if (defined('MODE') == 'development') {
            wp_enqueue_script('muplugin', 'http://localhost:3000/script.muplugin.js', array('jquery'), '1.0.' . date("ymdhis"), true);
        } else {
            wp_enqueue_script('muplugin', $this->plugin_url . 'dist/script.muplugin.js', array('jquery'), '1.0.' . date("ymdhis"), true);
        }

        // wp_localize_script('jquery', 'secret', array(
        //     'site_url' => get_site_url(),
        //     'nonce' => wp_create_nonce('wp_rest'),
        //     'ajax_url' => admin_url('admin-ajax.php'),
        //     'rest_url' => esc_url_raw(rest_url()),
        //     'get_style_dir_uri' => get_stylesheet_directory_uri(),
        //     'get_style_dir' => get_stylesheet_directory(),
        //     'slug' => basename(get_bloginfo('url')),
        // ));
    }

}