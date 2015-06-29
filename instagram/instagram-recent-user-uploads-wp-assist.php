<?php

  /**
   *  Get Recent User Uploads
   *  -----------------------
   *
   *  How to obtain a Instagram Access Token
   *  --------------------------------------
   *  Register a new client by visiting http://instagram.com/developer/.
   *  Make sure you are logged in as the target account.
   *
   *  Once the client is created, change the following url to match the
   *  parameters of the client:
   *    https://api.instagram.com/oauth authorize/?client_id=CLIENT-ID&redirect_uri=REDIRECT-URI&response_type=token
   *  This will respond in a redirect with the access token in the url.
   *
   *  How to obtain User ID
   *  ---------------------
   *  Enter the following into your broswer url, changing the values to
   *  match the parameters of the user:
   *  https://api.instagram.com/v1/users/search?q=USERNAME&access_token=ACCESS TOKEN
   *
   *  This will return some json data, including your user ID
   *
   *  This file will create a cache that lasts for 1 hour. This helps
   *  keep calls to the IG servers down and prevents a lockout.
   *
   *
   *
   * Basic Function with WordPress Additions
   * ---------------------------------------
   * _See `instagram-recent-user-uploads.php` if not using WordPress_
   *
   * This file adds two fields to the General Settings page so that the
   * Access Token and User ID can be added in the backend by an Admin.
   *
   *
   *  get_the_gram($count, $cache_time)
   *
   *  @param  $count      | int how many results to display per request
   *  @param  $cache_time | time string. see http://php.net/manual/en/datetime.formats.time.php
   *
   *  @return array       | data: recent user uploads. Use in foreach
   *
   */


  function get_the_gram(
    $count = 10,                                // How many results display per request
    $cache_time = '-1 hour',                    // Negative time that can be converted via strtotime()
  ) {

    $access_token = get_option('youtube_api_key');
    $user_id = get_option('youtube_api_channel_id');

    if(!$access_token || !$user_id)
      return false;

    $url = 'https://api.instagram.com/v1/users/'.$user_id.'/media/recent/?access_token='.$access_token.'&count='.$count;
    $cache_directory = './app/cache/'; // path to cache folder, name should end in slash, such as './app/cache/'
    $cache_file = $cache_directory.sha1($url).'.json';


    if (file_exists($cache_file) && filemtime($cache_file) > strtotime($cache_time)) {
      // If a cache file exists, and it is newer than 1 hour, use it
      $jsonData = json_decode(file_get_contents($cache_file));
    } else {
      // If file does not exist, create directory and file, or update file with newer json data
      $jsonData = json_decode( file_get_contents($url) );
      $jsonEncode = json_encode($jsonData);

      if( !file_exists($cache_file) ) {
        $method = 'a';
        mkdir($cache_directory, 0755, true);
      } else {
        $method = 'w';
      }

      $fh = fopen($cache_file,$method);
      fwrite($fh, $jsonEncode."\n");
    }

    return $jsonData;
  }


  /**
   *  Add Fields to the General Settings Page
   *  ---------------------------------------
   *  If using these functions with other API setting fields,
   *  consider combining these functions into one.
   */


  // Add Instagram API Text Fields to Forms on General Settings
  add_action('admin_init', 'j2_ig_api_settings_section');

  // Add a new section to the General Settings Page
  function j2_ig_api_settings_section() {
    add_settings_section(
      'instagram_api_settings_section', // Section ID
      'API Settings',                   // Section Title
      'j2_ig_api_section_callback',     // Callback
      'general'                         // Show Settings on the `General Settings` Page
    );

    // Add a Form Field for the API key
    add_settings_field(
      'instagram_api_key',              // Option ID
      'Instagram API Key',              // Label
      'j2_ig_textbox_callback',         // Set callback args
      'general',                        // Show Field on the `General Settings` Page
      'instagram_api_settings_section', // Name of the section created above
      array(                            // $args
          'instagram_api_key'           // Should match Option ID
      )
    );

    // Add a Form Field for the Channel ID
    add_settings_field(
      'instagram_api_channel_id',       // Option ID
      'Instagram API Channel ID',       // Label
      'j2_ig_textbox_callback',         // Set callback args
      'general',                        // Show Field on the `General Settings` Page
      'instagram_api_settings_section', // Name of the section created above
      array(                            // $args
          'instagram_api_channel_id'    // Should match Option ID
      )
    );

    // Register the fields
    register_setting('general','instagram_api_key', 'esc_attr');
    register_setting('general','instagram_api_channel_id', 'esc_attr');
  }

  // Instagram API Section Callback
  function j2_ig_api_section_callback() {
    // Add a message or some other callback
    echo '<p>Enter the Instagram API information</p>';
  }

  // Instagram API Textbox Callback
  function j2_ig_textbox_callback($args) {
    $option = get_option($args[0]);
    echo '<input type="text" id="'. $args[0] .'" name="'. $args[0] .'" value="' . $option . '" />';
  }

?>