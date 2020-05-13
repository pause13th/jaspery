<?php

add_filter('body_class', function ($classes) {
    global $post;
    $page_slug = $post->post_name;
    return array_merge($classes, array('page-' . $page_slug));
});

if (tag_exist(get_the_tags(), ['css', 'dailies'])) {
    add_action('genesis_entry_header', function () {
        echo '<div class="wrap">';
    }, 1);
}

function tag_exist($tags, $target)
{
    $count = 0;
    for ($i = 0; $i < count($tags); $i++) {
        foreach ($target as $check) {
            if ($tags[$i]->slug == $check) {
                $count++;
            }
        }
    }
    return $count == count($target);
}

remove_action('genesis_entry_header', 'genesis_entry_wrap', 1);
remove_filter('genesis_attr_entry-header', 'genesis_attr_entry_header_class');
remove_filter('genesis_attr_entry-content', 'genesis_attr_entry_content_class');
remove_action('genesis_entry_content', 'genesis_entry_footer_markup_open', 10);
remove_action('genesis_entry_content', 'genesis_entry_footer_markup_close', 15);
remove_action('genesis_entry_content', 'genesis_post_meta', 13);

remove_action('genesis_entry_header', 'genesis_do_post_format_image', 4);
remove_action('genesis_entry_header', 'genesis_entry_header_markup_open', 5);
remove_action('genesis_entry_header', 'genesis_entry_header_markup_close', 15);
remove_action('genesis_entry_header', 'genesis_do_post_title');
remove_action('genesis_entry_header', 'genesis_post_info', 12);

remove_action('genesis_entry_footer', 'genesis_entry_footer_markup_open', 5);
remove_action('genesis_entry_footer', 'genesis_entry_footer_markup_close', 15);
remove_action('genesis_entry_footer', 'genesis_post_meta');

genesis();
