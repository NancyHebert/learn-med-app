<?php

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

function mcpost_send_mistreatment_completion() {

  global $post;

  if (!$post) {
    return;
  }

  $client = new Client();

  $course_id = learndash_get_course_id($post->ID);
  $course = get_post($course_id);

  $course_name = $course->post_title;

  $error_message = sprintf("The completion of \"%s\" couldn't be sent to MCPOST: ", $course_name);

  $admin_email_to = get_option('admin_email');
  $admin_email_subject = sprintf("[%s] McPost Mistreatment API error",
    wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES ));

  # make sure we have the configurations stored in the .env file
  if (   !getenv('MISTREATMENT_COMPLETION_MCPOST_ENDPOINT')
      || !getenv('MISTREATMENT_EN_COURSE_ID')
      || !getenv('MISTREATMENT_EN_COURSE_ID')) {
    # send an email to the site admin with the problem
    $error_message .= sprintf("missing the configurations on host %s", getenv('WP_HOME'));
    wp_mail($admin_email_to, $admin_email_subject, $error_message);
    return;
  }

  if (   $course_id != getenv('MISTREATMENT_EN_COURSE_ID')
      && $course_id != getenv('MISTREATMENT_FR_COURSE_ID')) {
    # ignore the shortcode
    return;
  }

  $user_id = get_current_user_id();
  $employeeid = get_user_meta($user_id, 'adi_employeeid', true);

  if ('' == $employeeid) {
    # don't attempt sending completion to the API
    return;
  }
  $data = array(
    'EmployeeNumber' => $employeeid,
    'Date' => date("Y-m-d")
  );

  # get the API's URL from the .env
  $endpoint_url = getenv('MISTREATMENT_COMPLETION_MCPOST_ENDPOINT');

  # call the API
  try {
    $response = $client->post($endpoint_url, [
      'form_params' => $data
    ]);
  } catch (RequestException $e) {
    $error_message .= sprintf("Communication with the API failed for user ID %d", $user_id);
    if ($e->hasResponse()) {
      $response = $e->getResponse();
      $error_message .= sprintf("\n\n [%d %s]", $response->getStatusCode(), $response->getReasonPhrase());
    }
    wp_mail($admin_email_to, $admin_email_subject, $error_message);
    return;
  }

}

add_shortcode("mcpost_send_mistreatment_completion", "mcpost_send_mistreatment_completion");

?>
