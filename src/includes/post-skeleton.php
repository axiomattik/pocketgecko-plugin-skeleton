<?php

add_action( 'init', 'pgps_skeleton_post_type' );
function pgps_skeleton_post_type() {
  register_post_type( 'pgps_skeleton',
  // the name of the post type must be prefixed and be <= 20 characters
  // and may only contain lowercase alphanumerics, dashes, underscores
    array(
      'labels' => array(
        'name' => __( 'Skeletons' , 'pg-plugin-skeleton' ),
        'singular_name' => __( 'Skeleton', 'pg-plugin-skeleton' ) ),
      'description' => 'a skeleton custom post type',
      'public' => true,
      ) );
}

?>
