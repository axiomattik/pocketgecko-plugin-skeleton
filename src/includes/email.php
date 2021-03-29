<?php

function pgps_create_email_options() {
  $user = wp_get_current_user();
  $prefix = 'pgps_';
  $options = array(
    'smtp_from_email' => $user->user_email,
    'smtp_from_name' => $user->display_name,
    'smtp_host' => 'smtp.gmail.com',
    'smtp_port' => 465,
    'smtp_encryption_type' => 'ssl',
    'smtp_username' => $user->user_email,
    'smtp_password' => '',
  );
  foreach ($options as $opt => $val ) {
    add_option( $prefix . $opt, $val );
  }
}


add_action( 'phpmailer_init', 'pgps_phpmailer_init' );
function pgps_phpmailer_init($phpmailer) {
  $phpmailer->setFrom( get_option( 'pgps_smtp_from_email' ), 
                       get_option( 'pgps_smtp_from_name' ) );
  $phpmailer->Host = get_option( 'pgps_smtp_host' );
  $phpmailer->SMTPSecure = get_option( 'pgps_smtp_encryption_type' );
  $phpmailer->Port = get_option( 'pgps_smtp_port' );
  $phpmailer->SMTPAuth = true;
  $phpmailer->Username = get_option( 'pgps_smtp_username' );
  $phpmailer->Password = get_option( 'pgps_smtp_password' );
  $phpmailer->isSMTP();
}


