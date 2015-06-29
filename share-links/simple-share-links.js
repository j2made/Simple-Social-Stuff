// Requires jQuery

(function($) {

  // This JS will return a Pinterest URL.
  var firstImage = $('img:first').attr('src'), // Default img, will grab the first img if featured does not exist.
  $pinLink       = $('a.pinterest'),
  pinURL         = $pinLink.attr('data-url'),
  pinTitle       = $pinLink.attr('data-description'),
  pinImage       = $pinLink.attr('data-image');

  var pinMedia;

  if(!pinImage) {
    pinMedia = firstImage;
  } else {
    pinMedia = pinImage;
  }

  var pinterestLink  = 'http://pinterest.com/pin/create/button/?url=' + pinURL + '&media=' + pinMedia + '&description=' + pinTitle;

  // Change the Pinterest Link
  $pinLink.attr('href', pinterestLink);


  // Open Share Link in new window
  $('.share-link').click(function(){
    newwindow=window.open($(this).attr('href'),'','height=300,width=600');
    if (window.focus) {newwindow.focus();}
    return false;
  });

})(jQuery);