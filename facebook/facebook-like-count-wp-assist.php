<?php

  /**
   *  Get Facebook Likes
   *  ------------------
   *  Requires a Graph API Access Token.
   *
   *  Basic Function with WordPress Additions
   *  ---------------------------------------
   *  _See `facebook-like-count.php` if not using WordPress_
   *
   *  This file adds two fields to the General Settings page so that the
   *  API Key and Channel ID can be added in the backend by an Admin.
   *
   *  get_facebook_likes()
   *
   *  @return integer | likes count for specfied page
   *
   */

  function get_facebook_likes() {
    $page_id = get_option('facebook_page_id');
    $access_token = get_option('facebook_access_token');

    $url = 'https://graph.facebook.com/v2.3/'.$page_id.'?access_token='.$access_token;
    $data = @file_get_contents($url);
    $json_data = json_decode($data, true);

    if(!$json_data)
        return false;


    $likes = $json_data['likes'];
    return $likes;
  }


  /**
   *  Add Fields to the General Settings Page
   *  ---------------------------------------
   *  If using these functions with other API setting fields, either combine
   *  them into one group, or make sure that each usage is unique.
   */


  // Add Facebook API Text Fields to Forms on General Settings
  add_action('admin_init', 'j2_fb_api_settings_section');

  // Add a new section to the General Settings Page
  function j2_fb_api_settings_section() {
    add_settings_section(
      'facebook_api_settings_section',  // Section ID
      'Facebook API Settings',          // Section Title
      'j2_fb_api_section_callback',     // Callback
      'general'                         // Show Settings on the `General Settings` Page
    );

    // Add a Form Field for the API key
    add_settings_field(
      'facebook_page_id',               // Option ID
      'Facebook Page ID',               // Label
      'j2_fb_textbox_callback',            // Set callback args
      'general',                        // Show Field on the `General Settings` Page
      'facebook_api_settings_section',  // Name of the section created above
      array(                            // $args
          'facebook_page_id'            // Should match Option ID
      )
    );

    // Add a Form Field for the Channel ID
    add_settings_field(
      'facebook_access_token',          // Option ID
      'Facebook Access Token',          // Label
      'j2_fb_textbox_callback',            // Set callback args
      'general',                        // Show Field on the `General Settings` Page
      'facebook_api_settings_section',  // Name of the section created above
      array(                            // $args
          'facebook_access_token'       // Should match Option ID
      )
    );

    // Register the fields
    register_setting('general','facebook_api_key', 'esc_attr');
    register_setting('general','facebook_api_channel_id', 'esc_attr');
  }

  // Facebook API Section Callback
  function j2_fb_api_section_callback() {
    // Add a message or some other callback to the section
    echo '<p>Enter Facebook API information</p>';
  }

  // Facebook API Textbox Callback
  function j2_fb_textbox_callback($args) {
    $option = get_option($args[0]);
    echo '<input type="text" id="'. $args[0] .'" name="'. $args[0] .'" value="' . $option . '" />';
  }

?>