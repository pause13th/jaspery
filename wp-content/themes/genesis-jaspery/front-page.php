<?php
/**
 * front-page
 */

// basic app
// remove_action('genesis_loop', 'genesis_do_loop');
// add_action('genesis_loop', 'basic_do_loop');
function basic_do_loop()
{
    ?>
  <section id="basic-app"></section>
  <?php
}

genesis();