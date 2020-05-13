<?php
/**
 * @package mu-default
 */
namespace Inc\Base;

use \Inc\Base\BaseController;
use \Inc\Base\CPTEntity;

class CustomPosts extends BaseController
{
    private $CPTEntity;

    public function register()
    {
        $this->CPTEntity = new CPTEntity();

        add_filter('cmb2_show_on', array($this, 'be_taxonomy_show_on_filter'), 10, 2);

        add_action('cmb2_admin_init', array($this, 'miekl_post_event_cmb2'));
        add_action('cmb2_admin_init', array($this, 'miekl_post_promotion_cmb2'));

        add_action('init', array($this, 'miekl_post_unregister_post_tag'));

    }
    // Remove tags support from posts
    public function miekl_post_unregister_post_tag()
    {
        unregister_taxonomy_for_object_type('post_tag', 'post');
    }

    public function miekl_post_event_cmb2()
    {
        $prefix = 'events_meta';

        $cmb = new_cmb2_box(array(
            'id' => $prefix,
            'title' => __('Event\'s Metas', $this->textdomain),
            'object_types' => array('post'),
            'show_on' => array(
                'key' => 'taxonomy',
                'value' => array(
                    'category' => array('events'),
                ),
            ),
            'context' => 'normal',
            'priority' => 'high',
            'show_names' => true,
        ));

        // Entity
        $cmb->add_field(array(
            'name' => __('Related Entity', $this->textdomain),
            'desc' => 'Select an option',
            'id' => $prefix . '_entity',
            'type' => 'select',
            'show_option_none' => true,
            'options' => array($this->CPTEntity, 'get_all_entity_id'),
        ));
    }

    public function miekl_post_promotion_cmb2()
    {
        $prefix = 'promotions_meta';

        $cmb = new_cmb2_box(array(
            'id' => $prefix,
            'title' => __('Promotion\'s Metas', $this->textdomain),
            'object_types' => array('post'),
            'show_on' => array(
                'key' => 'taxonomy',
                'value' => array(
                    'category' => array('promotions'),
                ),
            ),
            'context' => 'normal',
            'priority' => 'high',
            'show_names' => true,
        ));

        // Entity
        $cmb->add_field(array(
            'name' => __('Related Entity', $this->textdomain),
            'desc' => 'Select an option',
            'id' => $prefix . '_entity',
            'type' => 'select',
            'show_option_none' => true,
            'options' => array($this->CPTEntity, 'get_all_entity_id'),
        ));
    }

    public function be_taxonomy_show_on_filter($display, $meta_box)
    {
        if (!isset($meta_box['show_on']['key'], $meta_box['show_on']['value'])) {
            return $display;
        }

        if ('taxonomy' !== $meta_box['show_on']['key']) {
            return $display;
        }

        $post_id = 0;

        // If we're showing it based on ID, get the current ID
        if (isset($_GET['post'])) {
            $post_id = $_GET['post'];
        } elseif (isset($_POST['post_ID'])) {
            $post_id = $_POST['post_ID'];
        }

        if (!$post_id) {
            return $display;
        }

        foreach ((array) $meta_box['show_on']['value'] as $taxonomy => $slugs) {
            if (!is_array($slugs)) {
                $slugs = array($slugs);
            }

            $display = false;
            $terms = wp_get_object_terms($post_id, $taxonomy);
            foreach ($terms as $term) {
                if (in_array($term->slug, $slugs)) {
                    $display = true;
                    break;
                }
            }

            if ($display) {
                break;
            }
        }

        return $display;
    }

}
