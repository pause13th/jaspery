<?php
/**
 * @package mu-default
 */

namespace Inc\Base;

class FilterWordPress
{
    public function register()
    {
        add_filter('edit_post_link', array($this, 'custom_edit_post_link'), 10, 3);
    }

    /**
     * Edit 'edit_post_link' to open in new window
     */
    public function custom_edit_post_link($link, $post_id, $text)
    {
        $link = str_replace('<a ', '<a target="_blank" ', $link);
        return $link;
    }

}
