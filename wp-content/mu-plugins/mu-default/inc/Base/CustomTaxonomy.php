<?php
/**
 * @package mu-default
 */
namespace Inc\Base;

use \Inc\Base\BaseController;

class CustomTaxonomy extends BaseController
{
    public function register()
    {
        // add_action('init', array($this, 'miekl_taxonomy_entity_tag'));
    }

  

    
    public function miekl_taxonomy_entity_tag()
    {
        $labels = array(
            'name' => _x('Entity Tags', 'taxonomy general name', $this->textdomain),
            'singular_name' => _x('Entity Tags', 'taxonomy singular name', $this->textdomain),
            'search_items' => __('Search Entity Tags', $this->textdomain),
            'popular_items' => __('Popular Entity Tags', $this->textdomain),
            'all_items' => __('All Entity Tags', $this->textdomain),
            'parent_item' => null,
            'parent_item_colon' => null,
            'edit_item' => __('Edit Entity Tags', $this->textdomain),
            'update_item' => __('Update Entity Tags', $this->textdomain),
            'add_new_item' => __('Add New Entity Tags', $this->textdomain),
            'new_item_name' => __('New Entity Tags Name', $this->textdomain),
            'separate_items_with_commas' => __('Separate entity tags with commas', $this->textdomain),
            'add_or_remove_items' => __('Add or remove entity tags', $this->textdomain),
            'choose_from_most_used' => __('Choose from the most used entity tags', $this->textdomain),
            'not_found' => __('No entity tags found.', $this->textdomain),
            'menu_name' => __('Entity Tags', $this->textdomain),
        );
        $args = array(
            'public' => true,
            'show_in_rest' => true,
            'hierarchical' => true,
            'labels' => $labels,
            'show_ui' => true,
            'show_admin_column' => true,
            'update_count_callback' => '_update_post_term_count',
            'query_var' => true,
            'rewrite' => array('slug' => 'entities'),
        );
        register_taxonomy('entity_tags', 'entity', $args);
    }



}
