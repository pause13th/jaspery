<?php
/**
 * @package mu-default
 */
namespace Inc\Base;

use \Inc\Base\BaseController;

class CPTEntity extends BaseController
{
    public function register()
    {
        add_action('init', array($this, 'miekl_cpt_entity'));
        add_action('cmb2_admin_init', array($this, 'miekl_cmb2_entity'), 3);

        add_filter('rest_prepare_entity', array($this, 'filter_cpt_entity_json'), 10, 3);

    }

    public function miekl_cpt_entity()
    {
        $labels = array(
            'name' => _x('Entities', 'post type general name', $this->textdomain),
            'singular_name' => _x('Entity', 'post type singular name', $this->textdomain),
            'menu_name' => _x('Entities', 'admin menu', $this->textdomain),
            'name_admin_bar' => _x('Entity', 'add new on admin bar', $this->textdomain),
            'add_new' => _x('Add New', 'entity', $this->textdomain),
            'add_new_item' => __('Add New Entity', $this->textdomain),
            'new_item' => __('New Entity', $this->textdomain),
            'edit_item' => __('Edit Entity', $this->textdomain),
            'view_item' => __('View Entity', $this->textdomain),
            'all_items' => __('All Entities', $this->textdomain),
            'search_items' => __('Search Entities', $this->textdomain),
            'parent_item_colon' => __('Parent Entities:', $this->textdomain),
            'not_found' => __('No entities found.', $this->textdomain),
            'not_found_in_trash' => __('No entities found in Trash.', $this->textdomain),
        );
        $args = array(
            'labels' => $labels,
            'description' => __('Description.', $this->textdomain),
            'public' => true,
            'publicly_queryable' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'entity'),
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => null,
            'show_in_rest' => true,
            'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'page-attributes', 'genesis-seo', 'genesis-scripts', 'genesis-layouts'),
        );
        register_post_type('entity', $args);
    }

    public function miekl_cmb2_entity()
    {
        $prefix = 'entity_meta';

        /**
         * multiple cmb2_box not possible?
         */
        // $cmb_entity_reference = new_cmb2_box(array(
        //     'id' => $prefix . '_references',
        //     'title' => __('Entity\'s references', $this->textdomain),
        //     'object_types' => array('entity'), // Post type
        //     'context' => 'normal',
        //     'priority' => 'high',
        //     'show_names' => true, // Show field names on the left
        //     // 'cmb_styles' => false, // false to disable the CMB stylesheet
        //     // 'closed'     => true, // Keep the metabox closed by default
        // ));


        $cmb = new_cmb2_box(array(
            'id' => $prefix,
            'title' => __('Entity\'s Metas', $this->textdomain),
            'object_types' => array('entity'), // Post type
            'context' => 'normal',
            'priority' => 'high',
            'show_names' => true, // Show field names on the left
            // 'cmb_styles' => false, // false to disable the CMB stylesheet
            // 'closed'     => true, // Keep the metabox closed by default
        ));

        // Logo
        $cmb->add_field(array(
            'name' => __('Logo', $this->textdomain),
            'id' => $prefix . '_logo',
            'desc' => $prefix . '_logo',
            'type' => 'file',
            'options' => array(
                'url' => false, // Hide the text input for the url
            ),
            'text' => array(
                'add_upload_file_text' => 'Add Logo', // Change upload button text. Default: "Add or Upload File"
            ),
            'query_args' => array(
                'type' => array(
                    'image/gif',
                    'image/jpeg',
                    'image/png',
                ),
            ),
            'preview_size' => 'medium', // Image size to use when previewing in the admin.
        ));

        // Entity Images
        $cmb->add_field( array(
            'name' => __('Images', $this->textdomain),
            'id' => $prefix . '_images',
            'desc' => $prefix . '_images',
            'type' => 'file_list',
            'preview_size' => array( 100, 100 ), // Default: array( 50, 50 )
            'query_args' => array( 'type' => 'image' ), // Only images attachment
            // Optional, override default text strings
            'text' => array(
                'add_upload_files_text' => 'Add Image', // default: "Add or Upload Files"
                'remove_image_text' => 'Remove Image', // default: "Remove Image"
                'file_text' => 'Images:', // default: "File:"
                'file_download_text' => 'Download', // default: "Download"
                'remove_text' => 'Remove', // default: "Remove"
            ),
        ) );

        // Address
        $cmb->add_field(array(
            'name' => __('Address', $this->textdomain),
            'id' => $prefix . '_address',
            'desc' => $prefix . '_address',
            'type' => 'wysiwyg',
            'options' => array(
                'wpautop' => true, // use wpautop?
                'media_buttons' => false, // show insert/upload button(s)
                // 'textarea_name' => $editor_id, // set the textarea name to something different, square brackets [] can be used here
                'textarea_rows' => get_option('default_post_edit_rows', 5), // rows="..."
                // 'tabindex' => '',
                // 'editor_css' => '', // intended for extra styles for both visual and HTML editors buttons, needs to include the `<style>` tags, can use "scoped".
                // 'editor_class' => '', // add extra class(es) to the editor textarea
                'teeny' => true, // output the minimal editor config used in Press This
                // 'dfw' => false, // replace the default fullscreen with DFW (needs specific css)
                'tinymce' => true, // load TinyMCE, can be used to pass settings directly to TinyMCE using an array()
                // 'quicktags' => true // load Quicktags, can be used to pass settings directly to Quicktags using an array()
            ),
        ));

        // Contact Email
        $cmb->add_field(array(
            'name' => __('Email', $this->textdomain),
            'id' => $prefix . '_email',
            'desc' => $prefix . '_email',
            'type' => 'text_email',
        ));

        // Contact Phone
        $cmb->add_field(array(
            'name' => __('Phone', $this->textdomain),
            'id' => $prefix . '_phone',
            'desc' => $prefix . '_phone',
            'type' => 'text_small',
        ));

        // Link to website
        $cmb->add_field(array(
            'name' => __('Website URL', $this->textdomain),
            'id' => $prefix . '_website_url',
            'desc' => $prefix . '_website_url',
            'type' => 'text_url',
            'protocols' => array('http', 'https'),
        ));

        
        $cmb->add_field(array(
            'name' => __('Activities', $this->textdomain),
            'desc' => 'For reference only.<br>Entity\'s activities are highlighted in bold.',
            'id' => $prefix . '_activities',
            'type' => 'multicheck',
            'select_all_button' => false,
            'options' => array($this, 'get_all_entity_activities'),
        ));

        $cmb->add_field(array(
            'name' => __('Promotions', $this->textdomain),
            'desc' => 'For reference only.<br>Entity\'s promotions are highlighted in bold.',
            'id' => $prefix . '_promotions',
            'type' => 'multicheck',
            'select_all_button' => false,
            'options' => array($this, 'get_all_entity_promotions'),
        ));

        $cmb->add_field(array(
            'name' => __('Events', $this->textdomain),
            'desc' => 'For reference only.<br>Entity\'s events are highlighted in bold.',
            'id' => $prefix . '_events',
            'type' => 'multicheck',
            'select_all_button' => false,
            'options' => array($this, 'get_all_entity_events'),
        ));
    }

    public function get_all_entity_activities()
    {
        $args = array(
            'post_type' => 'activity',
            'posts_per_page' => -1,
            'post_status' => 'publish',
        );
        $query = new \WP_Query($args);
        $output = array();
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();

                // highlight activities that related to this entity
                $checked = get_post_meta(get_the_ID(), 'activity_meta_entity', true);
                if ($checked == $_GET['post']) {
                    $checked = true;
                }

                // output the activity as multicheck (checkbox disabled via css)
                $output[get_the_id()] = '<a href="' . get_the_permalink() . '">' . ($checked ? '<b>' : '') . get_the_title() . ($checked ? '</b>' : '') . '</a> <a href="./post.php?post=' . get_the_id() . '&action=edit">(Edit)</a>';
            }
        }
        wp_reset_postdata();
        return $output;
    }

    public function get_all_entity_promotions()
    {
        $args = array(
            'post_type' => 'post',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'category_name' => "promotions",
        );
        $query = new \WP_Query($args);
        $output = array();
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();

                // highlight activities that related to this entity
                $checked = get_post_meta(get_the_ID(), 'promotions_meta_entity', true);
                if ($checked == $_GET['post']) {
                    $checked = true;
                }

                // output the activity as multicheck (checkbox disabled via css)
                $output[get_the_id()] = '<a href="' . get_the_permalink() . '">' . ($checked ? '<b>' : '') . get_the_title() . ($checked ? '</b>' : '') . '</a> <a href="./post.php?post=' . get_the_id() . '&action=edit">(Edit)</a>';
            }
        }
        wp_reset_postdata();
        return $output;
    }

    public function get_all_entity_events()
    {
        $args = array(
            'post_type' => 'post',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'category_name' => "events",
        );
        $query = new \WP_Query($args);
        $output = array();
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();

                // highlight activities that related to this entity
                $checked = get_post_meta(get_the_ID(), 'events_meta_entity', true);
                if ($checked == $_GET['post']) {
                    $checked = true;
                }

                // output the activity as multicheck (checkbox disabled via css)
                $output[get_the_id()] = '<a href="' . get_the_permalink() . '">' . ($checked ? '<b>' : '') . get_the_title() . ($checked ? '</b>' : '') . '</a> <a href="./post.php?post=' . get_the_id() . '&action=edit">(Edit)</a>';
            }
        }
        wp_reset_postdata();
        return $output;
    }

    // cmb2 functions
    public function get_all_entity_id()
    {
        $args = array(
            'post_type' => 'entity',
            'posts_per_page' => -1,
            'post_status' => 'publish',
        );
        $query = new \WP_Query($args);
        $output = array();
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $output[get_the_id()] = get_the_title();
            }
        }
        wp_reset_postdata();

        return $output;
    }

    
    /**
     * filter projects restapi
     */
    public function filter_cpt_entity_json($data, $post, $context)
    {
        $entity_images = get_post_meta($post->ID, 'entity_meta_images');

        // $featured_image = get_the_post_thumbnail_url($post->ID, 'medium');

        // filter taxonomy
        // $raw = wp_get_post_terms($post->ID, 'project-type');
        // foreach ($raw as $key => $value) {
        //     $value = (array) $value;
        //     $projectType[$key] = [
        //         'term_id' => $value['term_id'],
        //         'name' => $value['name'],
        //         'slug' => $value['slug'],
        //         'term_taxonomy_id' => $value['term_taxonomy_id'],
        //     ];
        // }

        // $raw = wp_get_post_terms($post->ID, 'project-tag');
        // foreach ($raw as $key => $value) {
        //     $value = (array) $value;
        //     $projectTag[$key] = [
        //         'term_id' => $value['term_id'],
        //         'name' => $value['name'],
        //         'slug' => $value['slug'],
        //         'term_taxonomy_id' => $value['term_taxonomy_id'],
        //     ];
        // }

        if ($entity_images) {
            $data->data['entity_images'] = $entity_images;
        }

        // $data->data['test'] = 'hello_world';

        // if ($site_url) {
        //     $data->data['site_url'] = $site_url;
        // }
        // if ($featured_image) {
        //     $data->data['featured_image'] = $featured_image;
        // }
        // if ($projectType) {
        //     $data->data['project-type'] = $projectType;
        // }
        // if ($projectTag) {
        //     $data->data['project-tag'] = $projectTag;
        // }

        return $data;
    }
}