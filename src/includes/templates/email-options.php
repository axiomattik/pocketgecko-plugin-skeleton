<div class="wrap">
  <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
  <form action="options.php" method="post">
    <?php
    // output security fields 
    settings_fields( 'pg-plugin-skeleton-settings-menu' );
    // output setting sections and their fields
    do_settings_sections( 'pg-plugin-skeleton-email-settings' );

    submit_button( __( 'Save Settings', 'pg-plugin-skeleton' ) );
    ?>
  </form>
</div>
