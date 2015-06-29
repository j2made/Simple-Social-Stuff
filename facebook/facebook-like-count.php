<?php

  /**
   *  Get Facebook Likes
   *  ------------------
   *  Requires a Graph API Access Token.
   *
   *  Basic Function
   *  --------------
   *  See `facebook-like-count-wp-assist.php` if using WordPress
   *
   *  get_facebook_likes()
   *
   *  @return integer | likes count for specfied page
   *
   */

  function get_facebook_likes() {
    $page_id = '123456789';
    $access_token = '123456789.101112.123456789';

    $url = 'https://graph.facebook.com/v2.3/'.$page_id.'?access_token='.$access_token;
    $data = @file_get_contents($url);
    $json_data = json_decode($data, true);

    // Return false if error
    if(!$json_data)
        return false;

    $likes = $json_data['likes'];
    return $likes;
  }

?>