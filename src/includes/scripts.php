<?php

add_action( 'wp_enqueue_scripts', 'pgps_enqueue_public_scripts' );
function pgps_enqueue_public_scripts() {
  // css
    wp_enqueue_style( 'pgps-style',
      plugins_url( '/../public/css/style.css', __FILE__ ) );

  // js
  wp_enqueue_script( 'pgps-skeleton',
      plugins_url( '/../public/js/pg-plugin-skeleton-min.js', __FILE__ ) );

  // pass variables to js 
  wp_localize_script( 'pgps-skeleton', 'pgps', array(
    'api' => array (
      'root' => esc_url_raw( rest_url() ) . 'pgps/v1/',
      'nonce' => wp_create_nonce( 'wp_rest' ),
    ),
  ) );
}



function pgps_enqueue_admin_scripts() {

}

?>
