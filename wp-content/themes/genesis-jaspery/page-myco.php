<?php
/**
 * page-wot
 */
add_filter('body_class', function ($classes) {
    return array_merge($classes, array('page-wot'));
});

remove_action('genesis_header', 'genesis_do_header');
remove_action('genesis_header', 'genesis_header_markup_open', 5);
remove_action('genesis_header', 'genesis_header_markup_close', 15);

remove_action('genesis_footer', 'genesis_do_footer');
remove_action('genesis_footer', 'genesis_footer_markup_open', 5);
remove_action('genesis_footer', 'genesis_footer_markup_close', 15);

// remove default structure from functions.php
remove_filter('genesis_attr_site-inner', 'genesis_attr_site_inner_class');
remove_filter('genesis_attr_content-sidebar-wrap', 'genesis_attr_container_sidebar_wrap_class');
remove_filter('genesis_attr_content', 'genesis_attr_content_class');
remove_action('genesis_before_loop', 'genesis_content_wrap');
remove_filter('genesis_attr_entry', 'genesis_attr_entry_class');
remove_action('genesis_entry_header', 'genesis_entry_wrap', 1);
remove_filter('genesis_attr_entry-header', 'genesis_attr_entry_header_class');
remove_filter('genesis_attr_entry-content', 'genesis_attr_entry_content_class');

// remove default structure
remove_action('genesis_entry_header', 'genesis_do_post_format_image', 4);
remove_action('genesis_entry_header', 'genesis_entry_header_markup_open', 5);
remove_action('genesis_entry_header', 'genesis_entry_header_markup_close', 15);
remove_action('genesis_entry_header', 'genesis_do_post_title');
remove_action('genesis_entry_header', 'genesis_post_info', 12);

remove_action('genesis_entry_content', 'genesis_do_post_image', 8);
remove_action('genesis_entry_content', 'genesis_do_post_content');
remove_action('genesis_entry_content', 'genesis_do_post_content_nav', 12);
remove_action('genesis_entry_content', 'genesis_do_post_permalink', 14);

remove_action('genesis_entry_content', 'genesis_entry_footer_markup_open', 10);
remove_action('genesis_entry_content', 'genesis_entry_footer_markup_close', 15);
remove_action('genesis_entry_content', 'genesis_post_meta', 13);

remove_action('genesis_after_entry', 'genesis_do_author_box_single', 8);
remove_action('genesis_after_entry', 'genesis_adjacent_entry_nav');
remove_action('genesis_after_entry', 'genesis_get_comments_template');

// query - aside posts
add_action('genesis_entry_content', 'jaspery_do_wot_content');
function jaspery_do_wot_content()
{
    $wp_query = new WP_Query(array(
        'post_type' => 'post',
        'post_status' => array('publish', 'draft'),
        'posts_per_page' => 50,
        'tax_query' => array(
            array(
                'taxonomy' => 'post_tag',
                'field' => 'slug',
                'terms' => array('myco'),
            ),
        ),
    ));
    if ($wp_query->have_posts()):
        while ($wp_query->have_posts()):
            $wp_query->the_post();

            // print aside in card form
            ?>
				<div class="card card__wot card--<?php echo get_post_format(); ?>">
				  <div class="card-body pb-1">
				    <?php echo get_the_content(); ?>
				  </div>
				  <div class="card-header card-header--grid pl-2">
				    <h5 class="card-title text-right text-secondary m-0">
				      <?php echo !empty(get_the_title()) ? get_the_title() : 'untitled'; ?>
				    </h5>
				    <p class="card-text text-right m-0"><small class="text-muted"><?php echo get_the_date(); ?> &mdash;
				        <?php echo get_the_time(); ?></small></p>

				    <div class="dropright dropdown--upper-left">
				      <button class="btn dropdown-toggle dropdown-toggle--arrow-down" type="button" id="dropdownMenuButton"
				        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
				      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
				        <?php edit_post_link('Edit', null, null, null, 'dropdown-item');?>
				        <a class="dropdown-item disabled" href="#">Archive</a>
				      </div>
				    </div>

				  </div>

				</div>
				<?php
    endwhile;
    endif;
}

// add_action('genesis_after_entry', 'jaspery_aside_entry_form');
function jaspery_aside_entry_form()
{
    // echo do_shortcode('[cmb-form id="test_metabox"]');

    // add post
    // title
    // content
    // post-format

    // tags & category

    // submit-draft
    // submit-publish

    // after submit - refresh page
    ?>
<div class="container my-5">
  <div class="row">
    <div class="col-12">

      <form>
        <div class="form-group">
          <label for="exampleFormControlInput1">Aside Post entry</label>
          <input class="form-control" type="text" placeholder="aside" readonly>
        </div>
        <div class="form-group">
          <label for="exampleFormControlInput1">Email address</label>
          <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
        </div>
        <div class="form-group">
          <label for="exampleFormControlTextarea1">Example textarea</label>
          <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Publish</button>
        <button type="submit" class="btn btn-outline-secondary">Draft</button>
      </form>

    </div>
  </div>
</div>
<?php
}

genesis();