<?php

add_action( 'admin_menu', 'pgps_settings_menu' );
function pgps_settings_menu() {

  add_menu_page(
    // Page Title
    'Plugin Skeleton',

    // Menu Title
    __( 'Plugin Skeleton', 'pg-plugin-skeleton' ),

    'manage_options', // capability
    'pgps_settings_menu', // menu slug

    // callback function that echoes page content
    'pgps_settings_menu_render'
  );
}

function pgps_settings_menu_render() {
  // check user capabilities 
  if ( ! current_user_can( 'manage_options' ) ) {
    wp_die( __( 'You do not have sufficient permissions to access this page.', 'pg-plugin-skeleton' ) );
  }

  // add error/update messages

  // check if the user have submitted the settings
  // WordPress will add the "settings-updated" $_GET parameter to the url
  if ( isset( $_GET['settings-updated'] ) ) {
    // add settings saved message with the class of "updated"
    add_settings_error( 'wporg_messages', 'wporg_message', __( 'Settings Saved', 'wporg' ), 'updated' );
  }

  // show error/update messages
  settings_errors( 'wporg_messages' );
  ?>

  <div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

    <form action="options.php" method="post">
      <?php
      // output security fields
      settings_fields( 'pgps_email_settings' );

      // output settings fields
      do_settings_sections( 'pgps_settings_menu' );

      // output save button
      submit_button( __( 'Save Changes', 'pg-plugin-skeleton' ) );
      ?>
    </form>
  </div>

<?php
}


add_action( 'admin_init', 'pgps_settings_menu_init' );
function pgps_settings_menu_init() {
  $option_group = 'pgps_email_settings';
  $page_slug = 'pgps_settings_menu';

  // Email Settings Section
  add_settings_section(
    $option_group,
    __( 'Email Settings', 'pg-plugin-skeleton' ),
    'pgps_render_email_settings_section',
    $page_slug,
  );

  function pgps_render_email_settings_section() {
    ?>
    <p>SMTP settings for sending emails.</p>
    <?php
  }

  _pgps_create_option( 
    'From Email Address', 'pgps_smtp_from_email', 
    $option_group, $page_slug, array( 'type' => 'text'),
    __( "The address emails will be sent from", 'pg-plugin-skeleton' )
  );

  _pgps_create_option( 
    'From Name', 'pgps_smtp_from_name', 
    $option_group, $page_slug, array( 'type' => 'text'),
    __( "The name of the sender", 'pg-plugin-skeleton' )
  );

  _pgps_create_option( 
    'Host', 'pgps_smtp_host', 
    $option_group, $page_slug, array( 'type' => 'text'),
    __( "Your mail sever", 'pg-plugin-skeleton' )
  );

  _pgps_create_option( 
    'Port', 'pgps_smtp_port', 
    $option_group, $page_slug, array( 'type' => 'number' ),
    __( "Port used by your mail server", 'pg-plugin-skeleton' )
  );

  _pgps_create_option( 
    'Type of Encryption', 'pgps_smtp_encryption_type', 
    $option_group, $page_slug, array( 
      'type' => 'radio',
      'values' => array(
        'None', 'SSL', 'TLS',
      ) ),
    __( "The encryption method used by your mail server", 'pg-plugin-skeleton' )
  );

  _pgps_create_option( 
    'Username', 'pgps_smtp_username', 
    $option_group, $page_slug, array( 'type' => 'text' ),
    __( "Username to login to your mail server", 'pg-plugin-skeleton' )
  );

  _pgps_create_option( 
    'Password', 'pgps_smtp_password', 
    $option_group, $page_slug, array( 'type' => 'password' ),
    __( "Password to login to your mail server", 'pg-plugin-skeleton' )
  );








}


function _pgps_create_option($title, $name, $group, $page, $attributes=array(), $description='') {
  /** 
   * A helper function that simplifies creating basic WordPress settings.
   *
   * @param String $title Formatted title of the option; shown as html label.
   * @param String $name Name of the option to retrieve/save.
   * @param String $group A settings group name.
   * @param String $page The slug-name of the settings page that $group belongs to.
   * @param Array $attributes Attribute-value pairs used to render the html input element.
   * If 'type' is not provided, then 'text' is assumed. 
   * The value of 'type' is used to determine the values of 
   * 'type' and 'santize_callback' passed to 'register_setting'.
   * @param String $description The contents of the tagline for the field.
   */

  if ( ! array_key_exists( 'type', $attributes ) ) {
    $attributes['type'] = 'text';
  }

  if ( ! array_key_exists( 'name', $attributes ) ) {
    $attributes['name'] = $name;
  }

  if ( ! array_key_exists( 'value', $attributes ) ) {
    $attributes['value'] = get_option( $name );
  }

  $type_lookup = array(
    'text' => array(
      'setting_type' => 'string',
      'sanitize_callback' => 'sanitize_text_field',
      'render_callback' => 'gen_render_input' ),

		'textarea' => array(
      'setting_type' => 'string',
      'sanitize_callback' => 'sanitize_textarea_field',
      'render_callback' => 'gen_render_textarea_input' ),

    'password' => array(
      'setting_type' => 'string',
      'sanitize_callback' => 'sanitize_text_field',
      'render_callback' => 'gen_render_input' ),

    'email' => array(
      'setting_type' => 'string',
      'sanitize_callback' => 'sanitize_email',
      'render_callback' => 'gen_render_input' ),

    'number' => array(
      'setting_type' => 'number',
      'sanitize_callback' => 'sanitize_text_field',
      'render_callback' => 'gen_render_input' ),

    'url' => array(
      'setting_type' => 'string',
      'sanitize_callback' => 'esc_url_raw',
      'render_callback' => 'gen_render_input' ),

    'radio' => array(
      'setting_type' => 'string',
      'sanitize_callback' => 'sanitize_text_field',
      'render_callback' => 'gen_render_radio' ),
  );

  $setting_type = $type_lookup[$attributes['type']]['setting_type'];
  $sanitize_callback_name = $type_lookup[$attributes['type']]['sanitize_callback'];
  $render_callback = $type_lookup[$attributes['type']]['render_callback']( $attributes, $description );

  register_setting( $group, $name, array(
    'type' => $setting_type,
    'sanitize_callback' => $sanitize_callback_name ) );


  add_settings_field(
    $name . '_field',
    __( $title, 'pg-plugin-skeleton' ),
    $render_callback, $page, $group
  );
}

function gen_render_input($attributes, $description='') {

  return function() use ( $attributes, $description ) {
    ob_start();
    ?>
    <input <?php 
      foreach ($attributes as $attr => $val) { 
        echo $attr . '="' . esc_attr( $val ) . '"';
      } ?>
    >
    <?php
    if ( $description ) {
      ?>
      <p class="description"><?php echo $description; ?></p>
      <?php
    }
    echo ob_get_clean();
  };

}


function gen_render_textarea($attributes, $description='') {

  return function() use ( $attributes, $description ) {
		$content = $attributes["value"];
		unset( $attributes["type"] );
		unset( $attributes["value"] );
    ob_start();
    ?>
    <textarea <?php 
      foreach ($attributes as $attr => $val) { 
        echo $attr . '="' . esc_attr( $val ) . '"';
      } ?>
    ><?php echo $content;?></textarea>
    <?php
    if ( $description ) {
      ?>
      <p class="description"><?php echo $description; ?></p>
      <?php
    }
    echo ob_get_clean();
  };

}



function gen_render_radio($attributes, $description='') {

  return function() use ( $attributes, $description ) {
    ob_start();
    ?>
    <p>
    <?php

    foreach ($attributes['values'] as $value) { 
      ?>
      <label>
      <input 
        type="radio" 
        name="<?php echo $attributes['name']; ?>" 
        value="<?php echo $value; ?>"
        <?php if ( $value == $attributes['value'] ) {
          ?>checked<?php
        }
        ?>
      >
        <?php echo $value; ?>
      </label><br>
      <?php 
    }
    if ( $description ) {
      ?>
      <p class="description"><?php echo $description; ?></p>
      <?php
    }
    ?>
    </p>
    <?php

    echo ob_get_clean();
  };

}


