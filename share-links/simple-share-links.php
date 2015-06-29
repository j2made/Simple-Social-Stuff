<?php
  /**
   *
   *  Simple Share Links using WordPress information.
   *
   *  -------------------------------------------------------------
   *  Make sure to include `simple-share-links.js` in your js file.
   *  -------------------------------------------------------------
   *
   *  HTML below uses [FontAwesome] (http://fontawesome.io/) for icons
   *
   *  Change TWITTER_HANDLE below to user handle.
   *
   *
   */

  // Setup Vars for share links
  $twitter_handle = 'TWITTER_HANDLE'
  $title = get_the_title() . ' by @' . $twitter_handle;
  $titleURI = urlencode($title);
  $url = get_permalink();
  $urlURI = urlencode($url);
  $excerpt = get_the_excerpt();
  $excerptURI = urlencode($excerpt);
  $ftdImage = get_the_post_thumbnail();
  $ftdImageURI = urldecode($ftdImage);

?>

<ul class="social-share">
  <li class="share-title">Share This Post</li>
  <li>
    <a href="http://twitter.com/home?status=<?= $titleURI ?>+<?php echo $urlURI; ?>" class="share-link" target="blank">
      <i class="fa fa-twitter fa-fw"></i>
    </a>
  </li>
  <li>
    <a href="http://www.facebook.com/share.php?u=<?php echo $urlURI; ?>&title=<?php echo $titleURI; ?>" class="share-link" target="blank">
      <i class="fa fa-facebook fa-fw"></i>
    </a>
  </li>
  <li>
    <a href="http://pinterest.com" data-url="<?php echo $urlURI; ?>" data-image="<?php if($ftdImageURI) { echo $ftdImageURI; } ?>" data-description="<?php if($excerpt){ echo $excerptURI; } ?>" class="pinterest share-link" target="blank">
      <i class="fa fa-pinterest fa-fw"></i>
    </a>
  </li>
  <li class="share-email">
    <a href="mailto:?subject=A Great Post from Bartrams Garden!&body=<?php echo get_the_title() . ' - ' . $url; ?>" target="blank">
      <i class="fa fa-envelope"></i>
    </a>
  </li>
</ul>