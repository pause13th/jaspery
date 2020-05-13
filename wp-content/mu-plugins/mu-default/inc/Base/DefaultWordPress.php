<?php
/**
 * @package HakosPlugin
 *
 * Hide WordPress, restyle login page
 *
 */
namespace Inc\Base;

class DefaultWordPress
{

    public function register()
    {

        /**
         * dequeue default scripts/styles
         */
        // add_action('wp_enqueue_scripts', function () {
        //     // wp_dequeue_style('wp-block-library');
        // }, 12);
        // add_action('wp_print_styles', function () {
        //     wp_dequeue_style('wp-block-library');
        // }, 100);
        /**
         * remove jquery-migrate
         */
        add_action('wp_default_scripts', function ($scripts) {
            if (!is_admin() && isset($scripts->registered['jquery'])) {
                $script = $scripts->registered['jquery'];
                if ($script->deps) {
                    // Check whether the script has any dependencies
                    $script->deps = array_diff($script->deps, array('jquery-migrate'));
                }
            }
        });

        // fixes
        add_filter('admin_footer_text', array($this, 'admin_remove_footer_credit'));

        add_filter('login_headerurl', array($this, 'login_page_logourl'));

        add_action('wp_dashboard_setup', array($this, 'admin_remove_meta_boxes'));

        add_filter('login_errors', array($this, 'admin_login_errors'));

        $this->disable_comments();

        // clean up wordpress <head>
        //https://bhoover.com/remove-unnecessary-code-from-your-wordpress-blog-header/
        remove_action('wp_head', 'wp_generator');
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wlwmanifest_link');
        remove_action('wp_head', 'wp_shortlink_wp_head');
        remove_action('wp_head', 'feed_links', 2);
        remove_action('wp_head', 'wp_resource_hints', 2);

        //https://orbitingweb.com/blog/remove-unnecessary-tags-wp-head/
        remove_action('wp_head', 'rsd_link'); //removes EditURI/RSD (Really Simple Discovery) link.
        remove_action('wp_head', 'wlwmanifest_link'); //removes wlwmanifest (Windows Live Writer) link.
        remove_action('wp_head', 'wp_generator'); //removes meta name generator.
        remove_action('wp_head', 'wp_shortlink_wp_head'); //removes shortlink.
        remove_action('wp_head', 'feed_links', 2); //removes feed links.
        remove_action('wp_head', 'feed_links_extra', 3); //removes comments feed.
        /*Removes prev and next links*/
        // remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');

        //https://thomas.vanhoutte.be/miniblog/remove-api-w-org-rest-api-from-wordpress-header/
        remove_action('wp_head', 'rest_output_link_wp_head', 10);
        remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);

        // dequeue wp_embed - embed other wordpress post on your website
        // "if you want to embed other people's WordPress posts into your own WordPress posts, leave embed.min.js alone. If you don't care about this feature then you can safely remove it."
        // add_action('wp_footer', array($this, 'mustuse_deregister_scripts'), 99);

        // disable wpemoji
        add_action('init', array($this, 'remove_emoji'), 99);
    }

    public function admin_login_errors()
    {
        return "Invalid username or password.";
    }

    public function admin_remove_meta_boxes()
    {
        remove_meta_box('dashboard_browser_nag', 'dashboard', 'normal');
        remove_meta_box('dashboard_right_now', 'dashboard', 'normal');
        remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
        remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');
        remove_meta_box('dashboard_plugins', 'dashboard', 'normal');

        remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
        remove_meta_box('dashboard_recent_drafts', 'dashboard', 'side');
        remove_meta_box('dashboard_primary', 'dashboard', 'side');
        remove_meta_box('dashboard_secondary', 'dashboard', 'side');
        remove_meta_box('dashboard_activity', 'dashboard', 'normal');

    }

    /**
     * Admin footer modification
     * @todo disable admin page footer
     */
    public function admin_remove_footer_credit()
    {
        return '<span id="footer-thankyou">Developed by Fishermen</span>';
    }

    /**
     * Update link to redirect user to homepage
     * @todo enqueue_login styling under Inc\Base\Enqueue
     */
    public function login_page_logourl()
    {
        return esc_url(site_url('/'));
    }

    // public function setSubpages(){
    //     $this->subpages = array(
    //         array(
    //             'parent_slug'   => 'hakos_plugin',
    //             'page_title'    => 'Taxonomy Manager',
    //             'menu_title'    => 'Taxonomy Manager',
    //             'capability'    => 'manage_options',
    //             'menu_slug'     => 'hakos_taxonomy',
    //             'callback'      => array( $this->callbacks, 'taxonomyDashboard' ),
    //         ),
    //     );
    // }

    /**
     * disable comments
     */
    public function disable_comments()
    {
        // clean SQL - wp_comments
        // TRUNCATE wp_commentmeta; TRUNCATE wp_comments;

        add_action('admin_init', function () {
            // Redirect any user trying to access comments page
            global $pagenow;
            if ($pagenow === 'edit-comments.php') {
                wp_redirect(admin_url());
                exit;
            }

            // Remove comments metabox from dashboard
            remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');

            // Disable support for comments and trackbacks in post types
            foreach (get_post_types() as $post_type) {
                if (post_type_supports($post_type, 'comments')) {
                    remove_post_type_support($post_type, 'comments');
                    remove_post_type_support($post_type, 'trackbacks');
                }
            }
        });

        // Close comments on the front-end
        add_filter('comments_open', '__return_false', 20, 2);
        add_filter('pings_open', '__return_false', 20, 2);

        // Hide existing comments
        add_filter('comments_array', '__return_empty_array', 10, 2);

        // Remove comments page in menu
        add_action('admin_menu', function () {
            remove_menu_page('edit-comments.php');
        });

        // Remove comments links from admin bar
        add_action('init', function () {
            if (is_admin_bar_showing()) {
                remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
            }
        });

        //* remove comments from genesis
        remove_action('genesis_after_entry', 'genesis_get_comments_template', 12);
    }

    public function remove_emoji()
    {
        // Disables Pesky Emojis
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action('admin_print_styles', 'print_emoji_styles');
        remove_filter('the_content_feed', 'wp_staticize_emoji');
        remove_filter('comment_text_rss', 'wp_staticize_emoji');
        remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

        /// Disables Embeds
        function cb_disable_peskies_disable_embeds_rewrites($rules)
        {
            foreach ($rules as $rule => $rewrite) {
                if (false !== strpos($rewrite, 'embed=true')) {
                    unset($rules[$rule]);
                }
            }
            return $rules;
        }

        function cb_disable_peskies_disable_embeds_tiny_mce_plugin($plugins)
        {
            return array_diff($plugins, array('wpembed'));
        }

        function cb_disable_peskies_disable_embeds_remove_rewrite_rules()
        {
            add_filter('rewrite_rules_array', 'cb_disable_peskies_disable_embeds_rewrites');
            flush_rewrite_rules();
        }

        function cb_disable_peskies_disable_embeds_flush_rewrite_rules()
        {
            remove_filter('rewrite_rules_array', 'cb_disable_peskies_disable_embeds_rewrites');
            flush_rewrite_rules();
        }

        function cb_disable_peskies_disable_embeds()
        {

            // Remove the REST API endpoint.
            remove_action('rest_api_init', 'wp_oembed_register_route');

            // Turn off oEmbed auto discovery.
            add_filter('embed_oembed_discover', '__return_false');

            // Don't filter oEmbed results.
            remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);

            // Remove oEmbed discovery links.
            remove_action('wp_head', 'wp_oembed_add_discovery_links');

            // Remove oEmbed-specific JavaScript from the front-end and back-end.
            remove_action('wp_head', 'wp_oembed_add_host_js');

            add_filter('tiny_mce_plugins', 'cb_disable_peskies_disable_embeds_tiny_mce_plugin');

            // Remove all embeds rewrite rules.
            add_filter('rewrite_rules_array', 'cb_disable_peskies_disable_embeds_rewrites');

        }

    }
}
