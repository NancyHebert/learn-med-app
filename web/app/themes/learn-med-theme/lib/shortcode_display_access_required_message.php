<?php

function display_access_required_message() {

  return
    '<p class="restricted">' .
      __("It looks like we didn't give you access to see this page.", 'learn.med') .
    '<br /><a class="btn btn-primary" href="' .
      __("http://www.med.uottawa.ca/medtech/help/", 'learn.med') .
    '">' .
      __("Request permission to see this page", 'learn.med') .
    ' <span class="fa fa-chevron-right"></span></a></p>'
  ;

}

add_shortcode("access_required_message", "display_access_required_message");

?>
