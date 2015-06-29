<?php

  /**
   *
   * Documentation and WP assist file to come.
   *
   * This function currently only works with WP as it
   * stores the current number of followers as an option
   * rather than creating a cache file.
   *
   */

  // Get Twitter Followers
  function getTwitterFollowers($screenName) {

    $consumerKey = 'XXXXXX';
    $consumerSecret = 'XXXXXXXXXXXX';
    $token = get_option('cfTwitterToken');

    // Get follower count from cache
    $numberOfFollowers = get_transient('cfTwitterFollowers');

    // cache version does not exist or expired
    if (false === $numberOfFollowers) {
      // getting new auth bearer only if we don't have one
      if(!$token) {
        // preparing credentials
        $credentials = $consumerKey . ':' . $consumerSecret;
        $toSend = base64_encode($credentials);

        // http post arguments
        $args = array(
          'method' => 'POST',
          'httpversion' => '1.1',
          'blocking' => true,
          'headers' => array(
            'Authorization' => 'Basic ' . $toSend,
            'Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8'
          ),
          'body' => array( 'grant_type' => 'client_credentials' )
        );

        add_filter('https_ssl_verify', '__return_false');

        $response = wp_remote_post('https://api.twitter.com/oauth2/token', $args);
        $keys = json_decode(wp_remote_retrieve_body($response));

        if($keys) {
          // saving token to wp_options table
          update_option('cfTwitterToken', $keys->access_token);
          $token = $keys->access_token;
        }
      }

      // we have bearer token wether we obtained it from API or from options
      $args = array(
        'httpversion' => '1.1',
        'blocking' => true,
        'headers' => array(
          'Authorization' => "Bearer $token"
        )
      );

      add_filter('https_ssl_verify', '__return_false');
      $api_url = "https://api.twitter.com/1.1/users/show.json?screen_name=$screenName";
      $response = wp_remote_get($api_url, $args);

      if (!is_wp_error($response)) {
        $followers = json_decode(wp_remote_retrieve_body($response));
        $numberOfFollowers = $followers->followers_count;
      } else {
        // get old value and break
        $numberOfFollowers = get_option('cfNumberOfFollowers');
        // uncomment below to debug
        //die($response->get_error_message());
      }

      // cache for an hour
      set_transient('cfTwitterFollowers', $numberOfFollowers, 1*60*60);
      update_option('cfNumberOfFollowers', $numberOfFollowers);
    }

    return $numberOfFollowers;
  }

?>