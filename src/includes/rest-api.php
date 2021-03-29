<?php
add_action( 'rest_api_init', 'pgps_rest_api_init' );

function pgps_rest_api_init() {
  $namespace = "pgps/v1";

  # GET /wp-json/pgps/v1/skeletons
  register_rest_route( $namespace, '/skeletons', array(
    'methods' => 'GET',
    'callback' => 'pgps_rest_api_get_skeletons',
    'permission_callback' => '__return_true'
  ) );

  # GET /wp-json/pgps/v1/skeletons/[id]
  register_rest_route( $namespace, '/skeletons/(?P<id>[0-9]+)', array(
    'methods' => 'GET',
    'callback' => 'pgps_rest_api_get_skeleton',
    'permission_callback' => '__return_true'
  ) );

  # POST /wp-json/pgps/v1/skeletons
  register_rest_route( $namespace, '/skeletons', array(
    'methods' => 'POST',
    'callback' => 'pgps_rest_api_post_skeleton',
    'permission_callback' => '__return_true'
  ) );

  # PATCH /wp-json/pgps/v1/skeletons/[id]
  register_rest_route( $namespace, '/skeletons/(?P<id>[0-9]+)', array(
    'methods' => 'PATCH',
    'callback' => 'pgps_rest_api_patch_skeleton',
    'permission_callback' => '__return_true'
  ) );


  # DELETE /wp-json/pgps/v1/skeletons/[id]
  register_rest_route( $namespace, '/skeletons/(?P<id>[0-9]+)', array(
    'methods' => 'DELETE',
    'callback' => 'pgps_rest_api_delete_skeleton',
    'permission_callback' => '__return_true'
  ) );


  # POST /wp-json/pgps/v1/messages
  register_rest_route( $namespace, '/messages', array(
    'methods' => 'POST',
    'callback' => 'pgps_rest_api_send_message',
    'permission_callback' => '__return_true'
  ) );

}


function pgps_rest_api_get_skeletons($request) {
  // get all the post ids of type pgps_skeleton
  $results = array();
  $posts = get_posts(array(
    'post_type' => 'pgps_skeleton',
    'posts_per_page' => -1,
  ));
  foreach ( $posts as $p ) {
    array_push($results, $p->ID);
  }
  return rest_ensure_response( $results );
  
}


function pgps_rest_api_get_skeleton($request) {
  // get a json representation of a post and its meta data
  $id = $request['id'];
  $post = get_post($id);

  if ( $post->post_type != "pgps_skeleton" ) {
    return new WP_Error(
      'not_found',
      __('sorry not found', 'pg-plugin-skeleton'),
      array( 'status' => 404 ) );
  }

  $post->post_meta = get_post_meta( $id );

  return rest_ensure_response( $post );
}


function pgps_rest_api_post_skeleton($request) {
  $params = $request->get_json_params();
  $title = $params["title"];
  $value = $params["value"];

  $postarr = array(
    'post_author' => get_current_user_id(),
    'post_type' => 'pgps_skeleton',
    'post_title' => $title,
    'post_status' => 'publish',
  );

  $id = wp_insert_post( $postarr );
  add_post_meta( $id, 'skeleton_meta', $value, true );

  return rest_ensure_response( $id );
}


function pgps_rest_api_patch_skeleton($request) {
  // a bare minimum functional PATCH that does not conform to standards:
  // https://tools.ietf.org/html/rfc5789
  $id = $request['id'];
  $changes = $request->get_json_params();
  foreach ( $changes as $change ) {
    $op = $change['op']; // currenty assuming it is 'replace'
    $path = $change['path'];
    $value = $change['value'];

    if ( $path == "/post_title" ) {
      $postarr = array(
        'ID' => $id,
        'post_title' => $value
      );
      wp_update_post( $postarr );

    } elseif ( $path == '/post_meta/skeleton_meta' ) {
      update_post_meta( $id, 'skeleton_meta', $value );
    }
  }
  return 'ok';
}


function pgps_rest_api_delete_skeleton($request) {
  $id = $request['id'];
  $post = get_post($id);

  if ( $post->post_type != "pgps_skeleton" ) {
    return new WP_Error(
      'not_found',
      __('sorry not found', 'pg-plugin-skeleton'),
      array( 'status' => 404 ) );
  }

  if ( wp_delete_post($id, true) ) {
    return rest_ensure_response('ok');
  }

  return new WP_Error(
    'could_not_delete',
    __('failed to delete', 'pg-plugin-skeleton'),
    array( 'status' => 500 ) );
}


function pgps_rest_api_send_message($request) {
  $params = $request->get_json_params();
  $message = $params['message'];
  $subject = $params['name'];
  $to = $params['email'];

  if ( wp_mail( $to, $subject, $message ) ) {
    return rest_ensure_response( 'ok' );
  }
  return new WP_Error(
    'message_send_failed',
    __( 'failed to send message', 'pg-plugin-skeleton' ),
    array( 'status' => 500 ) );
}

