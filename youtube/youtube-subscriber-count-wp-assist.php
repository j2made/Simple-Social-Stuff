<?php

  /**
   *  Get YouTube Subscribers (Youtube API v3)
   *  ----------------------------------------
   *  Uses Basic Auth to get Subscriber count.
   *
   *  Create a Google Developer account, and login to the developer console
   *  (https://console.developers.google.com). Create a project and connect
   *  the YouTube API. Under APIs & Auth, click Credentials. Here you can
   *  create a new Public API Access key. Select Browser, and add your url
   *  referrers. An API key for use below will be generated for you.
   *
   *  To get the channel ID, log in to the proper YouTube account, and
   *  visit `http://www.youtube.com/account_advanced`
   *
   *
   *
   * Basic Function with WordPress Additions
   * ---------------------------------------
   * _See `youtube-subscriber-count.php` if not using WordPress_
   *
   * This file adds two fields to the General Settings page so that the
   * API Key and Channel ID can be added in the backend by an Admin.
   *
   * get_yt_subs description()
   *
   * @return integer | subcriber count for specfied channel
   *
   */


  // Basic Function to call
  function j2_get_yt_subs() {
    $api_key = get_option('youtube_api_key');
    $channel_id = get_option('youtube_api_channel_id');

    // Return if the option fields do not exist
    if(!$api_key || !$channel_id)
        return false;

    $youtube_url = 'https://www.googleapis.com/youtube/v3/channels?part=statistics&id='.$channel_id.'&key='.$api_key;
    $data = @file_get_contents($youtube_url);
    $json_data = json_decode($data);

    if(!$json_data)
        return false;

    $subs = $json_data->items
  }

  /**
   *  Add Fields to the General Settings Page
   *  ---------------------------------------
   *  If using these functions with other API setting fields, either combine
   *  them into one group, or make sure that each usage is unique.
   */


  // Add YouTube API Text Fields to Forms on General Settings
  add_action('admin_init', 'j2_yt_api_settings_section');

  // Add a new section to the General Settings Page
  function j2_yt_api_settings_section() {
    add_settings_section(
      'youtube_api_settings_section',   // Section ID
      'API Settings',                   // Section Title
      'j2_yt_api_section_callback',     // Callback
      'general'                         // Show Settings on the `General Settings` Page
    );

    // Add a Form Field for the API key
    add_settings_field(
      'youtube_api_key',                // Option ID
      'Youtube API Key',                // Label
      'j2_yt_textbox_callback',         // Set callback args
      'general',                        // Show Field on the `General Settings` Page
      'youtube_api_settings_section',   // Name of the section created above
      array(                            // $args
          'youtube_api_key'             // Should match Option ID
      )
    );

    // Add a Form Field for the Channel ID
    add_settings_field(
      'youtube_api_channel_id',         // Option ID
      'Youtube API Channel ID',         // Label
      'j2_yt_textbox_callback',         // Set callback args
      'general',                        // Show Field on the `General Settings` Page
      'youtube_api_settings_section',   // Name of the section created above
      array(                            // $args
          'youtube_api_channel_id'      // Should match Option ID
      )
    );

    // Register the fields
    register_setting('general','youtube_api_key', 'esc_attr');
    register_setting('general','youtube_api_channel_id', 'esc_attr');
  }

  // YouTube API Section Callback
  function j2_yt_api_section_callback() {
    // Add a message or some other callback
    echo '<p>Enter the YouTube API information</p>';
  }

  // YouTube API Textbox Callback
  function j2_yt_textbox_callback($args) {
    $option = get_option($args[0]);
    echo '<input type="text" id="'. $args[0] .'" name="'. $args[0] .'" value="' . $option . '" />';
  }

?>