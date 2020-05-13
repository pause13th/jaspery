<?php
/**
 * @package mu-default
 */
namespace Inc\Base;

use \Inc\Base\BaseController;
use \Inc\Base\CPTEntity;

class CPTActivity extends BaseController
{
    public $CPTEntity;

    public function register()
    {
        $this->CPTEntity = new CPTEntity();

        add_action('init', array($this, 'miekl_cpt_activity'));

        add_action('cmb2_admin_init', array($this, 'miekl_cmb2_activity'));

        add_action('init', array($this, 'miekl_taxonomy_activity_tag'));

        // add order for activity_tags
        add_action('activity_tags_edit_form_fields', array($this, 'term_order_field'), 10, 2);
        add_action('edited_activity_tags', array($this, 'save_term_order'));
        add_filter('manage_edit-activity_tags_columns', array($this, 'term_order_header'));
        add_filter('manage_activity_tags_custom_column', array($this, 'term_order_header_val'), 10, 3);

    }

    public function miekl_cpt_activity()
    {
        $labels = array(
            'name' => _x('Activities', 'post type general name', $this->textdomain),
            'singular_name' => _x('Activity', 'post type singular name', $this->textdomain),
            'menu_name' => _x('Activities', 'admin menu', $this->textdomain),
            'name_admin_bar' => _x('Activity', 'add new on admin bar', $this->textdomain),
            'add_new' => _x('Add New', 'activity', $this->textdomain),
            'add_new_item' => __('Add New Activity', $this->textdomain),
            'new_item' => __('New Activity', $this->textdomain),
            'edit_item' => __('Edit Activity', $this->textdomain),
            'view_item' => __('View Activity', $this->textdomain),
            'all_items' => __('All Activities', $this->textdomain),
            'search_items' => __('Search Activities', $this->textdomain),
            'parent_item_colon' => __('Parent Activities:', $this->textdomain),
            'not_found' => __('No activities found.', $this->textdomain),
            'not_found_in_trash' => __('No activities found in Trash.', $this->textdomain),
        );
        $args = array(
            'labels' => $labels,
            'description' => __('Description.', $this->textdomain),
            'public' => true,
            'publicly_queryable' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'activity'),
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => null,
            'show_in_rest' => true,
            'supports' => array('title', 'editor', 'thumbnail', 'excerpt' /*, 'author', 'genesis-seo', 'genesis-scripts' , 'genesis-layouts' */),
        );
        register_post_type('activity', $args);
    }

    public function miekl_cmb2_activity()
    {
        $prefix = 'activity_meta';

        $cmb = new_cmb2_box(array(
            'id' => $prefix,
            'title' => __('Activity\'s Metas', $this->textdomain),
            'object_types' => array('activity'), // Post type
            'context' => 'normal',
            'priority' => 'high',
            'show_names' => true, // Show field names on the left
            // 'cmb_styles' => false, // false to disable the CMB stylesheet
            // 'closed'     => true, // Keep the metabox closed by default
        ));

        // Activities Images
        $cmb->add_field(array(
            'name' => __('Images', $this->textdomain),
            'id' => $prefix . '_images',
            'desc' => $prefix . '_images',
            'type' => 'file_list',
            'preview_size' => array(100, 100), // Default: array( 50, 50 )
            'query_args' => array('type' => 'image'), // Only images attachment
            // Optional, override default text strings
            'text' => array(
                'add_upload_files_text' => 'Add Image', // default: "Add or Upload Files"
                'remove_image_text' => 'Remove Image', // default: "Remove Image"
                'file_text' => 'Images:', // default: "File:"
                'file_download_text' => 'Download', // default: "Download"
                'remove_text' => 'Remove', // default: "Remove"
            ),
        ));

        // Entity
        $cmb->add_field(array(
            'name' => __('Related to Entity?', $this->textdomain),
            'desc' => 'Select an option',
            'id' => $prefix . '_entity',
            'type' => 'select',
            'show_option_none' => true,
            // 'default' => 'custom',
            'options' => array($this->CPTEntity, 'get_all_entity_id'),
            // 'options' => array($this, 'get_all_entity_id'),
        ));

        // Opening time
        // $cmb->add_field(array(
        //     'name' => __('Opening Time', $this->textdomain),
        //     'id' => $prefix . '_opening',
        //     'type' => 'multicheck',
        //     'options' => $this->activity_opening,
        // ));

        // keywords
        $cmb->add_field(array(
            'name' => __('Keywords', $this->textdomain),
            'desc' => 'Auto-generated for query purposes',
            'id' => $prefix . '_keywords',
            'type' => 'textarea_small',
        ));

        $wysOptions = array(
            'wpautop' => true, // use wpautop?
            'media_buttons' => false, // show insert/upload button(s)
            // 'textarea_name' => $editor_id, // set the textarea name to something different, square brackets [] can be used here
            // 'textarea_rows' => get_option('default_post_edit_rows', 10), // rows="..."
            'textarea_rows' => 2,
            'tabindex' => '',
            // 'editor_css' => '', // intended for extra styles for both visual and HTML editors buttons, needs to include the `<style>` tags, can use "scoped".
            // 'editor_class' => '', // add extra class(es) to the editor textarea
            'teeny' => false, // output the minimal editor config used in Press This
            'dfw' => false, // replace the default fullscreen with DFW (needs specific css)
            'tinymce' => true, // load TinyMCE, can be used to pass settings directly to TinyMCE using an array()
            'quicktags' => true, // load Quicktags, can be used to pass settings directly to Quicktags using an array()
        );
        $cmb->add_field(array(
            'name' => __('Opening Time', $this->textdomain),
            'id' => $prefix . '_opening_time',
            'type' => 'wysiwyg',
            // 'options' => $this->activity_opening,
            'options' => $wysOptions,
        ));
        $cmb->add_field(array(
            'name' => __('Good for', $this->textdomain),
            'id' => $prefix . '_good_for',
            'type' => 'wysiwyg',
            // 'options' => $this->activity_opening,
            'options' => $wysOptions,
        ));
        $cmb->add_field(array(
            'name' => __('Suggested Time Spend', $this->textdomain),
            'id' => $prefix . '_suggest_time',
            'type' => 'wysiwyg',
            // 'options' => $this->activity_opening,
            'options' => $wysOptions,
        ));
        $cmb->add_field(array(
            'name' => __('Estimate Price', $this->textdomain),
            'id' => $prefix . '_estimate_price',
            'type' => 'wysiwyg',
            // 'options' => $this->activity_opening,
            'options' => $wysOptions,
        ));

        // wysiwyg

    }

    public function miekl_taxonomy_activity_tag()
    {
        $labels = array(
            'name' => _x('Activity Tags', 'taxonomy general name', $this->textdomain),
            'singular_name' => _x('Activity Tags', 'taxonomy singular name', $this->textdomain),
            'search_items' => __('Search Activity Tags', $this->textdomain),
            'popular_items' => __('Popular Activity Tags', $this->textdomain),
            'all_items' => __('All Activity Tags', $this->textdomain),
            'parent_item' => null,
            'parent_item_colon' => null,
            'edit_item' => __('Edit Activity Tags', $this->textdomain),
            'update_item' => __('Update Activity Tags', $this->textdomain),
            'add_new_item' => __('Add New Activity Tags', $this->textdomain),
            'new_item_name' => __('New Activity Tags Name', $this->textdomain),
            'separate_items_with_commas' => __('Separate activity tags with commas', $this->textdomain),
            'add_or_remove_items' => __('Add or remove activity tags', $this->textdomain),
            'choose_from_most_used' => __('Choose from the most used activity tags', $this->textdomain),
            'not_found' => __('No activity tags found.', $this->textdomain),
            'menu_name' => __('Activity Tags', $this->textdomain),
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
            'rewrite' => array('slug' => 'activities'),
        );
        register_taxonomy('activity_tags', 'activity', $args);
    }

    // activity_tag_order
    public function term_order_field($term, $taxonomy)
    {
        ?>
<tr class="form-field">
  <th scope="row" valign="top">
    <label for="meta-order"><?php _e('Category Order');?></label>
  </th>
  <td>
    <input type="text" name="_term_order" size="3" style="width:5%;"
      value="<?php echo (!empty($term->term_group)) ? $term->term_group : '0'; ?>" />
    <span class="description"><?php _e('Categories are ordered Smallest to Largest');?></span>
  </td>
</tr>
<?php
}

    /**
     * Save Term Order
     * @param int $term_id
     */
    public function save_term_order($term_id)
    {
        global $wpdb;

        if (isset($_POST['_term_order'])) {
            $wpdb->update(
                $wpdb->terms,
                array(
                    'term_group' => $_POST['_term_order'],
                ),
                array(
                    'term_id' => $term_id,
                )
            );
        }
    } // END Function

    /**
     * Add Column To Show 'Term Order' Field
     * @param array $columns
     * @return array $columns
     */
    public function term_order_header($columns)
    {
        $columns['order'] = '<center>Order</center>';
        return $columns;
    }

    /**
     * Give 'Term Order' Column A Value `term_group`
     * @param string $empty
     * @param string $col_name
     * @param int $term_id
     * @return string
     */
    public function term_order_header_val($empty = '', $col_name, $term_id)
    {
        if (isset($_GET['taxonomy']) && 'order' == $col_name) {
            $term = get_term($term_id, $_GET['taxonomy']);
            return "<center>{$term->term_group}</center>";
        } else {
            return '0';
        }
    }
}