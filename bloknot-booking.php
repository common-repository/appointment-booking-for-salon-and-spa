<?php
/*
Plugin Name: Appointment booking for salon and SPA
Description: Appointment booking widget for your salon or SPA business. Clients can book online, pick a service provider, check available time slots and create an appointment. Reduce number of phone calls and make it easy for your customers..
Version: 1.0
Author: BloknotApp
Author URI: https://bloknotapp.com/en?utm_source=wp-widget
Text Domain: bloknot-booking
Domain Path: /languages/
*/

  //Loading localisation
  load_plugin_textdomain( 'bloknot-booking', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
  add_action( 'plugins_loaded', 'load_plugin_textdomain' );

  //Adding pages to settings
  add_action('admin_menu','blknt_settings_add');
  function blknt_settings_add()
  {
    add_options_page( __( 'Widget settings', 'bloknot-booking' ), __( 'BloknotApp widget', 'bloknot-booking' ), 'manage_options', 'bloknot_settings', 'blknt_settings_output' );
  }

  //Load settings CSS
  function blknt_settings_style($hook) {
          if($hook = 'bloknot_settings') {
                  wp_enqueue_style( 'blknt_settings_css', plugins_url('settings-style.css', __FILE__) );;
          }
          return;
  }
  add_action( 'admin_enqueue_scripts', 'blknt_settings_style' );

  //Plugin activation
  register_activation_hook( __FILE__, 'blknt_default_values' );
  function blknt_default_values() {
    update_option( 'blknt_widgetlink', 'mylink' );
    update_option( 'blknt_widgetposition', 'left' );
    update_option( 'blknt_buttoncolor', 'red' );
  }

  //Output settings HTML
  function blknt_settings_output()
  {
    ?>
    <div class="wrap">
      <h2><?php echo get_admin_page_title() ?></h2>
      <div class="postbox" style="padding: 10px 25px; margin-top:15px;">
        <h3><?php _e('Widget setup guide','bloknot-booking') ?></h3>
        <p class="about-description"><h4><?php _e('In order to set up your widget, please follow step-by-step guide.','bloknot-booking') ?></h4></p>
        <ul>
          <li><?php _e('1. Create an account at <a href="https://bloknotapp.com/en?utm_source=wp-widget">BloknotApp.com','bloknot-booking') ?></a></li>
          <li><?php _e('2. Go to Settings -> Online bookings in BloknotApp admin panel','bloknot-booking') ?></li>
          <li><?php _e('3. Enable online bookings (click on switcher)','bloknot-booking') ?></li>
          <li><?php _e('4. Copy the <b>beginning of your link</b> (e.g. <a><b>mylink</b></a>.bloknotapp.com/booking)','bloknot-booking') ?></li>
          <li><?php _e('5. Paste your link in widget settings','bloknot-booking') ?></li>
          <li><?php _e('6. Save preferences','bloknot-booking') ?></li>
        </ul>
        <p><span class="dashicons dashicons-yes"></span><?php _e('You’re done! Now your widget is working.','bloknot-booking') ?></p>
      </div>
      <form action="options.php" method="POST">
        <?php settings_fields( 'blknt_option_group' ); do_settings_sections( 'blknt_settings_page' ); submit_button();?>
      </form>
    </div>
    <?php
  }


  //Settings register
  add_action('admin_init','blknt_settings_register');
  function blknt_settings_register()
  {
    add_settings_section( 'section_id', __( 'General settings', 'bloknot-booking'), '', 'blknt_settings_page' );
    register_setting( 'blknt_option_group', 'blknt_widgetlink', 'sanitize_callback' );
    register_setting( 'blknt_option_group', 'blknt_widgetposition', 'sanitize_callback' );
    register_setting( 'blknt_option_group', 'blknt_buttoncolor', 'sanitize_callback' );
    add_settings_field('blknt_widgetlink', __( 'Booking link', 'bloknot-booking' ), 'blknt_adress_field_html', 'blknt_settings_page', 'section_id' );
    add_settings_field('blknt_widgetposition', __( 'Widget position', 'bloknot-booking' ), 'blknt_position_field_html', 'blknt_settings_page', 'section_id' );
    add_settings_field('blknt_buttoncolor',__( 'Button color', 'bloknot-booking' ), 'blknt_color_field_html', 'blknt_settings_page', 'section_id' );
  }

  function blknt_adress_field_html() {
  	$value = get_option( 'blknt_widgetlink', '' );
    if (empty($value))
    {
      update_option( 'blknt_widgetlink', 'mylink' );
      printf( '<input type="text" id="blknt_widgetlink" name="blknt_widgetlink" value="mylink" /><span class="description" id="tagline-description">.bloknotapp.com/booking/</span>
              <p style="color:#DC3232" class="description" id="tagline-description">'. __('Link cannot be empty', 'bloknot-booking') .'</p>');
    }
    else
    {
      printf( '<input type="text" id="blknt_widgetlink" name="blknt_widgetlink" value="%s" /><span class="description" id="tagline-description">.bloknotapp.com/booking/</span>', esc_attr( $value ) );
    }
  }

  function blknt_position_field_html() {
    $value = get_option( 'blknt_widgetposition', '' );
    ?><label class="blknt_pospicker blknt_pospicker--left"><input name="blknt_widgetposition" type="radio" value="left" <?php checked( 'left', get_option( 'blknt_widgetposition' ) ); ?> /><br><?php _e('Left', 'bloknot-booking') ?></label><?php
    ?><label class="blknt_pospicker blknt_pospicker--right"><input name="blknt_widgetposition" type="radio" value="right" <?php checked( 'right', get_option( 'blknt_widgetposition' ) ); ?> /><br><?php _e('Right', 'bloknot-booking') ?></label><?php
  }

  function blknt_color_field_html() {
    $value = get_option( 'blknt_buttoncolor', '' );
    ?><label><input name="blknt_buttoncolor" class="blknt_colorpicker blknt_colorpicker--red" type="radio" value="red" <?php checked( 'red', get_option( 'blknt_buttoncolor' ) ); ?> /></label><?php
    ?><label><input name="blknt_buttoncolor" class="blknt_colorpicker blknt_colorpicker--blue" type="radio" value="blue" <?php checked( 'blue', get_option( 'blknt_buttoncolor' ) ); ?> /></label><?php
    ?><label><input name="blknt_buttoncolor" class="blknt_colorpicker blknt_colorpicker--green" type="radio" value="green" <?php checked( 'green', get_option( 'blknt_buttoncolor' ) ); ?> /></label><?php
    ?><label><input name="blknt_buttoncolor" class="blknt_colorpicker blknt_colorpicker--orange" type="radio" value="orange" <?php checked( 'orange', get_option( 'blknt_buttoncolor' ) ); ?> /></label><?php
    ?><label><input name="blknt_buttoncolor" class="blknt_colorpicker blknt_colorpicker--pink" type="radio" value="pink" <?php checked( 'pink', get_option( 'blknt_buttoncolor' ) ); ?> /></label><?php
    ?><label><input name="blknt_buttoncolor" class="blknt_colorpicker blknt_colorpicker--purple" type="radio" value="purple" <?php checked( 'purple', get_option( 'blknt_buttoncolor' ) ); ?> /></label><?php
    ?><label><input name="blknt_buttoncolor" class="blknt_colorpicker blknt_colorpicker--black" type="radio" value="black" <?php checked( 'black', get_option( 'blknt_buttoncolor' ) ); ?> /></label><?php
    ?><label><input name="blknt_buttoncolor" class="blknt_colorpicker blknt_colorpicker--white" type="radio" value="white" <?php checked( 'white', get_option( 'blknt_buttoncolor' ) ); ?> /></label><?php
  }


  //Widget output
  add_action('wp_head','blknt_loadwidget');
  function blknt_loadwidget()
  {
    wp_enqueue_script('widgetjs', plugin_dir_url(__FILE__) . 'bloknot-widget.js');
    wp_enqueue_style('blknt_widget_css', plugin_dir_url(__FILE__) . 'widget-style.css');

    if (get_option('blknt_widgetlink','' ) == 'mylink')
    {
    echo '
      <div id="bloknot-widget-backdrop" class="hide" onclick="widgetShow()"></div>
      <div id="bloknotapp-widget-container" style="overflow: visible;" class="blknt_widget--'.get_option( 'blknt_widgetposition', '' ).' bloknotapp-widget-container--guide hide">
      <h2 style="text-align:center;">'. __('Widget setup guide','bloknot-booking') .'</h2>
      <div class="img-container"><img src="'.plugin_dir_url(__FILE__).'empty_state.jpg"></img></div>
      <p class="about-description"><h4>'. __('In order to set up your widget, please follow step-by-step guide.','bloknot-booking') .'</h4></p>
      <ul>
        <li>'. __('1. Create an account at <a href="https://bloknotapp.com/en?utm_source=wp-widget">BloknotApp.com','bloknot-booking') .'</a></li>
        <li>'. __('2. Go to Settings -> Online bookings in BloknotApp admin panel','bloknot-booking') .'</li>
        <li>'. __('3. Enable online bookings (click on switcher)','bloknot-booking') .'</li>
        <li>'. __('4. Copy the <b>beginning of your link</b> (e.g. <a><b>mylink</b></a>.bloknotapp.com/booking)','bloknot-booking') .'</li>
        <li>'. __('5. Paste your link in widget settings','bloknot-booking') .'</li>
        <li>'. __('6. Save preferences','bloknot-booking') .'</li>
      </ul>
        <a id="close-bloknot-widget" onclick="widgetShow()"></a>
      </div>
      <a id="bloknot-widget-button" class="bloknotbtn--'.get_option( 'blknt_widgetposition', '' ).'-bottom bloknotbtn--'.get_option( 'blknt_buttoncolor', '' ).'" onclick="widgetShow()"><span>'.__('Book<br>now', 'bloknot-booking').'</span></a>';
    }
    echo '
      <div id="bloknot-widget-backdrop" class="hide" onclick="widgetShow()"></div>
      <div id="bloknotapp-widget-container" class="blknt_widget--'.get_option( 'blknt_widgetposition', '' ).' hide">
       <iframe id="bloknotapp-widget-iframe" src="https://'.get_option( 'blknt_widgetlink', '' ).'.bloknotapp.com/booking/"></iframe>
        <a id="close-bloknot-widget" onclick="widgetShow()"></a>
      </div>
      <a id="bloknot-widget-button" class="bloknotbtn--'.get_option( 'blknt_widgetposition', '' ).'-bottom bloknotbtn--'.get_option( 'blknt_buttoncolor', '' ).'" onclick="widgetShow()"><span>'.__('Book<br>now', 'bloknot-booking').'</span></a>';
  }
  ?>
