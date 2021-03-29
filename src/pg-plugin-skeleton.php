<?php
/**
 * Plugin Name: PocketGecko Plugin Skeleton
 * Plugin URI:
 * Description:
 * Text Domain: pg-plugin-skeleton
 * Domain Path: /i18n/languages
 * Version: 0.0.1
 * Requires at least:
 * Requires PHP:
 * Author: axiomattik
 * Author URI: 
 * License: GPLv2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'PGPS_PLUGIN_BASE' ) ) {
  define ( 'PGPS_PLUGIN_BASE', plugin_basename(__FILE__) );
}

if ( is_admin() ) {
  // the request is for an admin page
  require_once __DIR__ . '/pg-plugin-skeleton-admin.php';
}

require_once __DIR__ . '/includes/post-skeleton.php';
require_once __DIR__ . '/includes/page-skeleton.php';
require_once __DIR__ . '/includes/rest-api.php';
require_once __DIR__ . '/includes/email.php';
require_once __DIR__ . '/includes/shortcode.php';
require_once __DIR__ . '/includes/scripts.php';


register_activation_hook( __FILE__, 'pgps_activate' );
function pgps_activate() {
  // create a new Page
  pgps_create_skeleton_page();
  pgps_create_email_options();
}


register_deactivation_hook( __FILE__, 'pgps_deactivate' );
function pgps_deactivate() {
  // delete Page
  pgps_delete_skeleton_page();
}

?>
