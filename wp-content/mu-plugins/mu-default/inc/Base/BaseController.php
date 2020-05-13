<?php
/**
 * @package mu-default
 */

namespace Inc\Base;

class BaseController
{
    public $plugin_path;
    public $plugin_url;
    public $plugin;

    public $textdomain;

    public function __construct()
    {
        $this->plugin_path = plugin_dir_path(dirname(__FILE__, 2));
        $this->plugin_url = plugin_dir_url(dirname(__FILE__, 2));
        $this->plugin = plugin_basename(dirname(__FILE__, 3)) . '/mu-default.php';

        $this->textdomain = 'miekl-textdomain';

        // for activity
        $this->activity_opening = array(
            '1' => 'Morning',
            '2' => 'Afternoon',
            '3' => 'Evening',
        );

        $this->activity_special_attributes = array(
            '1' => 'Family friendly',
            '2' => 'Kids friendly',
            '3' => 'Handicapped friendly',
        );
    }

}
