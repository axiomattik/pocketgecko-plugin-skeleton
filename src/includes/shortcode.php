<?php

add_shortcode( 'pocket_gecko_skeleton', 'pgps_skeleton_shortcode' );

function pgps_skeleton_shortcode($atts, $content, $shortcode_tag) {
  ob_start();

  $q = new WP_Query( array(
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'post_type' => 'pgps_skeleton'
  ) );

  echo '<fieldset>';

  if ( $q->have_posts() ) {
    while ( $q->have_posts() ) {
      $q->the_post();
      $id = get_the_ID();
      $title = get_the_title();
      $meta_value = get_post_meta($id, 'skeleton_meta', true);
      require __DIR__ . '/templates/update-skeleton.php';
      echo "<hr>";
    }
  }

  echo '</fieldset>';

  wp_reset_postdata();
  require __DIR__ . '/templates/create-skeleton.php';
  return ob_get_clean();
}


add_shortcode( 'pocket_gecko_form', 'pgps_email_form' );

function pgps_email_form($atts, $content, $shortcode_tag) {
  ob_start();
  require __DIR__ . '/templates/email-form.php';
  return ob_get_clean();
}


