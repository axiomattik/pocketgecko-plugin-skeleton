<?php

function pgps_create_skeleton_page() {

  if ( null === get_page_by_title( 'skeleton' ) ) {

    $page = array(
      'post_title' => __( 'Skeleton', 'pg-plugin-skeleton' ),
      'post_name' => 'skeleton',
      'post_status' => 'publish',
      'post_author' => get_current_user_id(),
      'post_type' => 'page',
      'post_content' => '[pocket_gecko_skeleton]<hr>[pocket_gecko_form]',
    );

    wp_insert_post( $page );
  }
}


function pgps_delete_skeleton_page() {
  wp_delete_post( get_page_by_title( 'skeleton' )->ID, true );
}

?>
