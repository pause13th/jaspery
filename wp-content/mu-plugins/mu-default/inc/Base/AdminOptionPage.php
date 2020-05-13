<?php
/**
 * @package mu-default
 */
namespace Inc\Base;

use \Inc\Base\BaseController;

class AdminOptionPage extends BaseController
{
    public function register()
    {
        add_action('cmb2_admin_init', array($this, 'yourprefix_register_theme_options_metabox'));
    }

/**
 * Hook in and register a metabox to handle a theme options page and adds a menu item.
 */
    public function yourprefix_register_theme_options_metabox()
    {
        $cmb_options = new_cmb2_box(array(
            'id' => 'yourprefix_theme_options_page',
            'title' => 'Theme Options',
            'object_types' => array('options-page'),
            'option_key' => 'yourprefix_theme_options',
            'icon_url' => 'dashicons-palmtree',
            'display_cb' => array($this, 'yourprefix_theme_options_page_output'), // Override the options-page form output (CMB2_Hookup::options_page_output()).
            'description' => 'Custom description!', // Will be displayed via our display_cb.
        ));
        $cmb_options->add_field(array(
            'name' => 'Site Background Color',
            'desc' => 'field description (optional)',
            'id' => 'bg_color',
            'type' => 'colorpicker',
            'default' => '#ffffff',
        ));
        
        $cmb_options->add_field(array(
          'name' => 'Site Background Color',
          'desc' => 'field description (optional)',
          'id' => 'bg_color2',
          'type' => 'colorpicker',
          'default' => '#ffffff',
      ));
    }
    public function yourprefix_theme_options_page_output($hookup)
    {
        // Output custom markup for the options-page.
        ?>
<div class="wrap cmb2-options-page option-<?php echo $hookup->option_key; ?>">
  <?php if ($hookup->cmb->prop('title')): ?>
  <h2><?php echo wp_kses_post($hookup->cmb->prop('title')); ?></h2>
  <?php endif;?>
  <?php if ($hookup->cmb->prop('description')): ?>
  <h2><?php echo wp_kses_post($hookup->cmb->prop('description')); ?></h2>
  <?php endif;?>

  <form class="cmb-form" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="POST"
    id="<?php echo $hookup->cmb->cmb_id; ?>" enctype="multipart/form-data" encoding="multipart/form-data">
    <input type="hidden" name="action" value="<?php echo esc_attr($hookup->option_key); ?>">
    <?php $hookup->options_page_metabox();?>
    <?php submit_button(esc_attr($hookup->cmb->prop('save_button')), 'primary', 'submit-cmb');?>
  </form>
</div>
<?php
}
}