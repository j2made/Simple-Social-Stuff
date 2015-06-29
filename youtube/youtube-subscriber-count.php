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
   * Basic Function
   * --------------
   * See `youtube-subscriber-count-wp-assist.php` if using WordPress
   *
   * get_yt_subs description()
   * @return integer | subcriber count for specfied channel
   */

  function j2_get_yt_subs() {
    $api_key = '123456789';
    $channel_id = '123456789';

    if(!$api_key || !$channel_id)
        return false;

    $youtube_url = 'https://www.googleapis.com/youtube/v3/channels?part=statistics&id='.$channel_id.'&key='.$api_key;
    $data = @file_get_contents($youtube_url);
    $json_data = json_decode($data);

    if(!$json_data)
        return false;

    $subs = $json_data->items[0]->statistics->subscriberCount;
    return($subs);
  }


?>