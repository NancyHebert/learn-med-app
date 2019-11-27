<?php

use TinCan\Statement;
use Pheanstalk\Pheanstalk;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

add_action('xapi_statements_receive', 'xapi_statements_receive_statements');
add_action('xapi_statements_parse',   'xapi_statements_parse_statements');
add_action('xapi_statements_notify',  'xapi_statements_send_notifications');

function xapi_statements_receive_statements() {

  $pheanstalk = new Pheanstalk('127.0.0.1');
  $statements_tube = 'statements';

  $status_code = 200;
  $messages = array();
  $error = false;

  if (isset($_POST['statements'])) {
    $statements_json = $_POST['statements'];
  } else {
    array_push($messages, "No statements provided");
    $error = true;
    $status_code = 500;
  }

  if (!$error) {
    try {
      $cfg = json_decode(stripcslashes($statements_json), true);

      if (is_null($cfg)) {
        $err = json_last_error_msg();
        throw new \InvalidArgumentException("Invalid JSON: $err");
      }
    } catch (Exception $e) {
      $status_code = 500;
      array_push($messages, sprintf("Caught exception: %s\n", $e->getMessage()));
      $error = true;
    }

    foreach($cfg as $statement_json) {
      try {
        $pheanstalk
          ->useTube($statements_tube)
          ->put(json_encode($statement_json) . "\n");
      } catch (Exception $e) {
        $status_code = 500;
        array_push($messages, sprintf("Caught exception: %s\n", $e->getMessage()));
        $error = true;
      }
    }

    array_push($messages, sprintf("Received %d statements", count($cfg)));
  }

  @http_response_code($status_code);
  @header('Content-Type: application/json; charset=' . get_option('blog_charset'), true);

  echo json_encode(array(
    'messages' => $messages
  ));
  die();
}

function xapi_statements_parse_statements() {
  // grassblade_debug("xapi_statements_parse_statements");
  $pheanstalk = new Pheanstalk('127.0.0.1');
  $statements_tube = 'statements';
  $timeout = 2; //seconds

  $pheanstalk->watch($statements_tube);

  $count = 0;
  $max = 500;

  while(($job = $pheanstalk->reserve($timeout)) && $count < $max) {
    $statement_json = json_decode($job->getData(), true);

    do_action('parse_xapi_statement', $statement_json);
    $pheanstalk->delete($job);
    $count++;
  }

  return;
}

function xapi_statements_send_notifications() {
  // grassblade_debug("xapi_statements_send_notifications");
  $pheanstalk = new Pheanstalk('127.0.0.1');
  $notifications_tube = 'notify-apis';
  $timeout = 2; //seconds

  $pheanstalk->watch($notifications_tube);
  $client = new Client();

  $admin_email_to = get_option('admin_email');
  $admin_email_subject = sprintf("[%s] Error notifying an API",
    wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES ));

  $count = 0;
  $max = 10;

  while(($job = $pheanstalk->reserve($timeout)) && $count < $max) {
    $notify_api = $job->getData();

    grassblade_debug(sprintf('notify_api: "%s"', trim($notify_api)));

    # call the API
    try {
      $response = $client->get(trim($notify_api));
    } catch (RequestException $e) {
      $error_message = sprintf("Communication with the API failed:\n\n%s", $notify_api);
      if ($e->hasResponse()) {
        $response = $e->getResponse();
        $error_message .= sprintf("\n\n [%d %s]", $response->getStatusCode(), $response->getReasonPhrase());
      }
      wp_mail($admin_email_to, $admin_email_subject, $error_message);

      $pheanstalk->delete($job);

      continue;
    }

    $pheanstalk->delete($job);
    $count++;
  }

  return;
}

function check_xapi_statement_for_mcpost_student_record($statement_json) {

  $endpoint_urls = array(
    'development' => 'https://stagingmedservices.med.uottawa.ca/api/ModuleCompletions/UpdateModuleCompletions',
    'staging' => 'https://stagingmedservices.med.uottawa.ca/api/ModuleCompletions/UpdateModuleCompletions',
    'production' => 'https://medservices.med.uottawa.ca/api/ModuleCompletions/UpdateModuleCompletions',
  );

  try {
    $statement = new Statement($statement_json);
  } catch (Exception $e) {
    grassblade_debug(sprintf("Caught exception for statement ID %s: %s\n", $statements_json["id"], $e->getMessage()));
    return;
  }

  $object = $statement->getObject();
  if ($object instanceof TinCan\Activity) {
    $activity_id = $object->getId();
  } else {
    grassblade_debug(sprintf("statement doesn't have valid activity"));
    return;
  }

  # if the statement's activity ID matches a module that should notify Mcpost or Student Record, return the API call to notify those systems

  # find the module id
  $module_id = get_post_id_from_activity_id($activity_id);

  if (!$module_id) {
    return;
  }

  # check if module is set to notify Mcpost or Student Record
  $notify_apis = get_post_meta($module_id, 'notify_apis', true);

  if (!is_array($notify_apis) || !isset($notify_apis['mcpost_student_record']) || $notify_apis['mcpost_student_record'] !== true) {
    return;
  }

  # check if the completion requirement is met

  # check if verb is 'passed' or 'completed'
  $verb = $statement->getVerb();
  if (!($verb instanceof TinCan\Verb) || ("http://adlnet.gov/expapi/verbs/passed" != $verb->getId() && "http://adlnet.gov/expapi/verbs/completed" != $verb->getId())) {
    return;
  }
  # check if result.completion and result.success are set to true
  $result = $statement->getResult();
  if (!($result instanceof TinCan\Result) || true !== $result->getCompletion() || false === $result->getSuccess()) { // allow success to be null
    return;
  }


  # check if the user for that email address is in our records
  $actor = $statement->getActor();
  if ($actor instanceof TinCan\Agent) {
    $mbox = $actor->getMbox();
    $student_email = substr($mbox, strlen('mailto:'));
  } else {
    grassblade_debug(sprintf("statement doesn't have valid agent"));
    return;
  }

  $wp_user_id = get_user_by('email', $student_email)->ID;

  # if the student email isn't found, then check if the email is used in the grassblade_email user_meta parameter

  if (empty($wp_user_id)) {

    $grassblade_email_query = (
      Array(
        'meta_key' => 'grassblade_email',
        'meta_value' => $student_email,
      )
    );
    $users_with_grassblade_email = get_users($grassblade_email_query);

    if ( !empty($users_with_grassblade_email) && is_array($users_with_grassblade_email)) {
      $student_email = $users_with_grassblade_email[0]->user_email; // TODO: Deal with the case where there's more than just one
      $wp_user_id = get_user_by('email', $student_email)->ID;
    }
  }

  if (empty($wp_user_id)) {
    return;
  }

  # get the user's employeeNumber
  $employeeid = get_user_meta($wp_user_id, 'adi_employeeid', true);

  if ('' == $employeeid || !is_numeric($employeeid)) {
    # don't attempt sending completion to the API
    $error_message = sprintf("no employeeid found for user id %d %s", $wp_user_id, $employeeid);
    grassblade_debug($error_message);
    $admin_email_to = get_option('admin_email');
    $admin_email_subject = sprintf("[%s] Error for mcpost_student_record completion tracking",
      wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES ));
    wp_mail($admin_email_to, $admin_email_subject, $error_message);
    return;
  }

  # get the statement's timestamp
  try {
    $statement_timestamp = $statement->getTimestamp();
  } catch (Exception $e) {
    grassblade_debug(sprintf("statement doesn't have valid timestamp"));
    return;
  }

  # assemble the API url
  if (isset($endpoint_urls[WP_ENV])) {
    $notify_url = add_query_arg(array(
      'EmployeeNumber' => $employeeid,
      'Date' => $statement_timestamp,
      'Lms_Module_Id' => urlencode($activity_id)
    ), $endpoint_urls[WP_ENV]);
  } else {
    grassblade_debug(sprintf("no endpoint_url defined for ENV %s", WP_ENV));
    return;
  }

  grassblade_debug($notify_url);

  $pheanstalk = new Pheanstalk('127.0.0.1');
  $tube = 'notify-apis';

  try {
    $pheanstalk
      ->useTube($tube)
      ->put($notify_url . "\n");
  } catch (Exception $e) {
    grassblade_debug(sprintf("Caught exception: %s\n", $e->getMessage()));
    return;
  }

  grassblade_debug(sprintf("Added to %s queue\n%s", $tube, $notify_url));
  return;
}

add_action('parse_xapi_statement', 'check_xapi_statement_for_mcpost_student_record', 10, 1);

function get_post_id_from_activity_id($activity_id) {

  global $envs;

  $course_url = $activity_id;
  if ('development' == WP_ENV || 'staging' == WP_ENV) {
    $course_url = preg_replace('/https?:\/\//', '', $course_url);
    $course_url = str_replace($envs['en']['staging'], '', $course_url);
    $course_url = str_replace($envs['fr']['staging'], '', $course_url);
    $course_url = str_replace($envs['en']['production'], '', $course_url);
    $course_url = str_replace($envs['fr']['production'], '', $course_url);
  }

  # check if it's the URL of a Learndash course
  $learndash_course_id = url_to_postid($course_url);

  if ($learndash_course_id && 'sfwd-courses' == get_post_type($learndash_course_id)) {
    return $learndash_course_id;
  } else {
    $grassblade_modules_query_args = array(
    	'post_type'  => 'gb_xapi_content',
      'posts_per_page' => 1,
    	'meta_query' => array(
    		array(
          'key'    => 'xapi_activity_id',
    			'value'  => $activity_id
    		)
    	)
    );
    $grassblade_modules_query = new WP_Query( $grassblade_modules_query_args );

    $grassblade_modules = $grassblade_modules_query->get_posts();

    if (1 <= count($grassblade_modules)) {
      if (isset($grassblade_modules[0]) && isset($grassblade_modules[0]->ID)) {
        return $grassblade_modules[0]->ID;
      }
    }
  }
  return 0;
}