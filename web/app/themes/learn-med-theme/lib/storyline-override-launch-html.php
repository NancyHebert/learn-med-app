<?php

function storyline_override_launch_html() {

  $storyline_launch_page_pattern = '/app\/uploads\/grassblade\/.*\/story.html$/';

  $disable_html5_launch_pattern = '/g_bUseHtml5 = true/';
  $disable_html5_launch_replacement = 'g_bUseHtml5 = false';

  if (false === strpos($_SERVER["REQUEST_URI"], '?')) {
    $file_uri = $_SERVER["REQUEST_URI"];
  } else {
    $file_uri = substr($_SERVER["REQUEST_URI"], 0, strpos($_SERVER["REQUEST_URI"], '?'));
  }
  $file_path = $_SERVER["DOCUMENT_ROOT"] . $file_uri;

  # if the URL is that of a storyline launch page
  if (preg_match($storyline_launch_page_pattern, $file_uri) && file_exists($file_path)) {
    if($fh = fopen($file_path,"r")){
      while (!feof($fh)){
         $line = fgets($fh,9999);
         $line = preg_replace($disable_html5_launch_pattern, $disable_html5_launch_replacement, $line);
         echo $line;
      }
      fclose($fh);
      exit;
    }
  }

}

add_action('wp_loaded', 'storyline_override_launch_html');

?>
