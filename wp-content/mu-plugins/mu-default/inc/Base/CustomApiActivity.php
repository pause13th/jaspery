<?php
/**
 * @package mu-default
 */
namespace Inc\Base;

use \Inc\Base\BaseController;

class CustomApiActivity extends BaseController
{

    public function register()
    {
        add_action('rest_api_init', array($this, 'miekl_activity_register_route'));

    }

    public function miekl_activity_register_route()
    {
        register_rest_route('miekl/v1', 'activity', array(
            'methods' => \WP_REST_SERVER::READABLE,
            'callback' => array($this, 'miekl_activity_custom_rest_api'),
        ));
    }

    public function miekl_activity_custom_rest_api($data)
    {
        $activities = new \WP_Query(array(
            'post_type' => 'activity',
            'meta_query' => array(
                array(
                    'key'     => 'activity_meta_keywords',
                    'value'   => $data['term'],
                    'compare'   => 'LIKE'
                ),
            ),
        ));
        $output = array();
        while ($activities->have_posts()) {
            $activities->the_post();

            $opening = get_post_meta(get_the_ID(), 'activity_meta_opening', true);
            $special = get_post_meta(get_the_ID(),
                'activity_meta_special', true);
            $keywords = get_post_meta(get_the_ID(), 'activity_meta_keywords', true);

            if ($keywords != $this->generate_activity_keywords($opening, $special)) {
                update_post_meta(get_the_ID(), 'activity_meta_keywords', $this->generate_activity_keywords($opening, $special));
                $keywords = $this->generate_activity_keywords($opening, $special);
            }

            array_push($output, array(
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'opening' => $opening,
                'special' => $special,
                'keywords' => $keywords,
                'temporary' => array(
                    'edit' => get_site_url() . '/wp-admin/post.php?post=' . get_the_ID() . '&action=edit',
                    'all_meta' => get_post_meta(get_the_ID()),
                ),
            ));

        }
        return $output;
    }

    private function generate_activity_keywords($opening, $special)
    {
        $output = array();

        $open_list = $this->activity_opening;
        $special_list = $this->activity_special_attributes;

        if ($opening) {
            foreach ($opening as $key => $value) {
                array_push($output, $open_list[$value]);
            }
        }

        if ($special) {
            foreach ($special as $key => $value) {
                array_push($output, $special_list[$value]);
            }
        }
        if ($output > 1) {
            $output = implode(", ", $output);
        } else {
            $output = $output[0];
        }
        return strtolower($output);
    }
}
