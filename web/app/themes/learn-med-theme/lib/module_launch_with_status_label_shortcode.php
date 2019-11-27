<?php

require_once locate_template('/lib/lrs.php');

// Add a shortcode for generating a link with the status label to a grassblade
// module or a native module

define('MODULE_GRASSBLADE',  'gb_xapi_content');
define('MODULE_LEARNDASH',   'sfwd-courses');

define('STATUS_COMPLETED',   'completed');
define('STATUS_STARTED',     'started');
define('STATUS_NOT_STARTED', 'not-started');

define('COMPLETION_TRANSIENT_DURATION', 20 * MINUTE_IN_SECONDS);
define('COMPLETION_TRANSIENT_KEY_PATTERN', 'user_%d_completed_module_%d_on');

class Module {

  public $ID;
  public $post;
  public $activity_id;

  public $lrs_endpoint;
  public $lrs_username;
  public $lrs_password;

  private $lrs_response = null;

  private $API_completion_endpoint = '_api/completionreport';

  function __construct ($module_id) {

    $this->ID = $module_id;
    //echo $module_id;  -> Gives me all the module IDs of the modules on the page

    $this->post = get_post($this->ID);

    $module_options = get_post_meta($this->ID, 'xapi_content', true) ?: array();
    //print_r($module_options); -> Gives me all the options-data of the modules on the page
    //Module with no attempts comes up as an empty array

    $this->lrs_endpoint = $module_options['endpoint'] ?: get_option('grassblade_tincan_endpoint');
    $this->lrs_username = $module_options['user']     ?: get_option('grassblade_tincan_user');
    $this->lrs_password = $module_options['password'] ?: get_option('grassblade_tincan_password');

    if (MODULE_GRASSBLADE == $this->post->post_type) {
      # get the activity id attribute from the gb_xapi_content object
        $this->activity_id = get_post_meta($this->ID, 'xapi_activity_id', true);
    } else if (MODULE_LEARNDASH == $this->post->post_type) {
      # get the module's URL as the activity ID
        $this->activity_id = $this->get_content_url();
    }

    //print_r($this->activity_id);
    //Shows the activity ID (which is a URL)

  }

  public function is_module_on_untrusted_domain() {

    if ($this->post->post_type != MODULE_GRASSBLADE) {
      return false;
    }

    if (!defined('XAPI_TRUST_DOMAINS_REGEX') || empty(XAPI_TRUST_DOMAINS_REGEX)) {
      return false;
    }

    $content_url = $this->get_content_url();

    if (!$content_url) {
      return false;
    }

    $content_url_parts = parse_url($content_url);

    if (empty($content_url_parts['host']) || null == $content_url_parts['host']) {
      return false;
    }

    if (0 === preg_match(XAPI_TRUST_DOMAINS_REGEX, $content_url_parts['host'])) {
      return true;
    }

    return false;
  }

  public function get_content_url() {

    if ($this->post->post_type != MODULE_GRASSBLADE) {
      return preg_replace('/http:/', 'https:', get_permalink($this->ID));
    }

    $xapi_content = new grassblade_xapi_content();
    $module_params = $xapi_content->get_params($this->ID);

    if (isset($module_params['src'])) {
      return $module_params['src'];
    }

    return false;
  }

  public function has_user_completed($user_id) {
    # check first whether the completion was saved in user meta data

    $completion_transient_key = sprintf(COMPLETION_TRANSIENT_KEY_PATTERN, $user_id, $this->ID);

    if (get_transient($completion_transient_key) !== false) {
      return true;
    }

    $status = $this->get_user_status($user_id);

    if (STATUS_COMPLETED == $status['status']) {
      # save as transient for 20 minutes
      set_transient($completion_transient_key, $status['completion_date'], COMPLETION_TRANSIENT_DURATION);
      return true;
    }

    return false;
  }

  public function get_completion_date($user_id) {
    # check first whether the completion was saved in user meta data

    $completion_transient_key = sprintf(COMPLETION_TRANSIENT_KEY_PATTERN, $user_id, $this->ID);

    $completion_transient_val = get_transient($completion_transient_key);

    if ((bool)strtotime($completion_transient_val)) {
      return $completion_transient_val;
    }

    $status = $this->get_user_status($user_id);

    if (STATUS_COMPLETED == $status['status']) {
      # save as transient for 20 minutes
      set_transient($completion_transient_key, $status['completion_date'], COMPLETION_TRANSIENT_DURATION);
      return $status['completion_date'];
    }

    return null;
  }

  public function has_user_started($user_id) {
    if ($this->has_user_completed($user_id)) {
      return true;
    }

    $status = $this->get_user_status($user_id);
    //print_r($status);

    if (STATUS_STARTED == $status['status']) {
      return true;
    }

    return false;

  }

  private function get_user_status($user_id) {
      //user_id == '4686'
      $status = $completion_date = null;

      # get the user's email

      if (function_exists('grassblade_user_email')) {
        $learner_email = grassblade_user_email($user_id);
        //all these
      } else {
        $user = get_user_by("id", $user_id);
        $learner_email = $user->user_email;
      }

    # retrieve the list of users from the LRS who have data on that activity id

      # call the LRS

        $this->lrs_response = $this->lrs_response ?: CallAPI(
          "GET",
          $this->lrs_endpoint . $this->API_completion_endpoint,
          $this->lrs_username,
          $this->lrs_password,
          array(
            'activity' => $this->activity_id,
            'all' => 'true'
          )
        );

      # parse the returning JSON

        $response = json_decode($this->lrs_response, true);

        ///////JAMEY CODE
        /*if(debug_backtrace()[1]['function'] == 'has_user_started'){
          debug_to_console('<p>ACT ID: '.$this->activity_id.'</p>');
          debug_to_console($response);
        } else {
          debug_to_console('NEIN!');
        }*/
        ///////END JAMEY CODE

    # check the status of the current user

      $learner_found = false;
      $learner_data = array();

      # check if the user is in the array
        foreach($response as $learner) {
          if (isset($learner['agent']['mbox']) && "mailto:".strtolower($learner_email) == strtolower($learner['agent']['mbox'])) {
            $learner_found = true;
            $learner_data = $learner;
            break;
          }
        }

    # determine which status label to attach to the launch link

      # if the user is not in the array, mark as not started



      if (!$learner_found) {
        $status = STATUS_NOT_STARTED;
      }

      # if the user is in the array and has an entry in the completion property or in the success property, mark as completed

      else if ($learner_found
               && isset($learner_data['completion']) && true == $learner_data['completion']) {
        $status = STATUS_COMPLETED;
        $completion_date = $learner_data['completion_date'];

      }

      # if the user is in the array and has no entries in the completion property, mark as started

      else if ($learner_found) {
        $status = STATUS_STARTED;
      }

      return array(
        'status' => $status,
        'completion_date' => $completion_date
      );

  }

  public function save_attempted() {

    $grassblade_tincan_endpoint = $this->lrs_endpoint;
  	$grassblade_tincan_user = $this->lrs_username;
  	$grassblade_tincan_password = $this->lrs_password;

  	$xapi = new NSS_XAPI($grassblade_tincan_endpoint, $grassblade_tincan_user, $grassblade_tincan_password);
  	$actor = grassblade_getactor(false);

  	if(empty($actor))
  	{
  		grassblade_debug("No Actor. Shutting Down.");
  		return false;
     
  	}

  	$xapi->set_verb('attempted');
  	$xapi->set_actor_by_object($actor);
  	$xapi->set_parent($this->activity_id, $this->post->post_title, $this->post->post_title, 'http://adlnet.gov/expapi/activities/course','Activity');
  	$xapi->set_grouping($this->activity_id, $this->post->post_title, $this->post->post_title, 'http://adlnet.gov/expapi/activities/course','Activity');
  	$xapi->set_object($this->activity_id, $this->post->post_title, $this->post->post_title, 'http://adlnet.gov/expapi/activities/course','Activity');
  	$statement = $xapi->build_statement();

    // grassblade_debug($statement);

  	$xapi->new_statement();

    // grassblade_debug(sprintf('Sending %d attempts for %s', count($xapi->statements), $this->activity_id));

  	foreach($xapi->statements as $statement) {
  		$ret = $xapi->SendStatements(array($statement));
  	}

    if (isset($ret['error'])) {
      grassblade_debug($ret['error']);
    }

    return true;
  }

  public function save_completed() {

    $grassblade_tincan_endpoint = $this->lrs_endpoint;
  	$grassblade_tincan_user = $this->lrs_username;
  	$grassblade_tincan_password = $this->lrs_password;

  	$xapi = new NSS_XAPI($grassblade_tincan_endpoint, $grassblade_tincan_user, $grassblade_tincan_password);
  	$actor = grassblade_getactor(false);

  	if(empty($actor))
  	{
  		grassblade_debug("No Actor. Shutting Down.");
  		return false;
   
  	}

  	$xapi->set_verb('completed');
  	$xapi->set_actor_by_object($actor);
  	$xapi->set_parent($this->activity_id, $this->post->post_title, $this->post->post_title, 'http://adlnet.gov/expapi/activities/course','Activity');
  	$xapi->set_grouping($this->activity_id, $this->post->post_title, $this->post->post_title, 'http://adlnet.gov/expapi/activities/course','Activity');
  	$xapi->set_object($this->activity_id, $this->post->post_title, $this->post->post_title, 'http://adlnet.gov/expapi/activities/course','Activity');
    $result = array(
  		'completion' => true,
      'success' => true
  	);
  	$xapi->set_result_by_object($result);

  	$statement = $xapi->build_statement();

    // grassblade_debug($statement);

  	$xapi->new_statement();

    // grassblade_debug(sprintf('Sending %d completions for %s', count($xapi->statements), $this->activity_id));

  	foreach($xapi->statements as $statement) {
  		$ret = $xapi->SendStatements(array($statement));
  	}

    if (isset($ret['error'])) {
      grassblade_debug($ret['error']);
    }

    return true;
  }
}



function module_launch_with_status_label_shortcode( $atts ) {
  /////////////JAMEY CODE
  $jid = get_the_ID();
  $jarr = get_post_meta($jid);
  //echo $jarr['_wp_page_template'][0];
  /////////////END JAMEY CODE
  $args = shortcode_atts( array(
    'show_completion_date' => 'false',
    'id' => null
  ), $atts, 'module' );

  # allow on/yes/1 for show_completion_date
  $args['show_completion_date'] = filter_var( $args['show_completion_date'], FILTER_VALIDATE_BOOLEAN );

  # if no post_id is set, return an empty string

  if (isset($args['id'])) {
    $module_id = $args['id'];
  } else {
    return "";
  }

  # get the post_id's post_type to detect whether the post_id is to a module
  # made in learndash or to a module launched via grassblade

  $module = new Module($module_id);
  $module_type = $module->post->post_type;

  # if the post_id's post_type is to neither a grassblade nor a learndash module
  # return an empty string

  if (MODULE_GRASSBLADE != $module_type && MODULE_LEARNDASH != $module_type) {
    return "";
  }

  # get the default link to generate

  if (MODULE_GRASSBLADE == $module_type) {
    if ($module->is_module_on_untrusted_domain()) {
      $default_link_output = '<a href="' . $module->get_content_url() . '" target="_blank" rel="external" class="grassblade_launch_link register_click">' . $module->post->post_title . '</a>';
    } else {
      $default_link_output = do_shortcode('[grassblade id=' . $module_id . ']');
    }
  } else if (MODULE_LEARNDASH == $module_type) {
    $default_link_output = '<a href="' . $module->get_content_url() . '">' . $module->post->post_title . '</a>';
  }

  # if LRS settings aren't set, return the default grassblade shortcode
  if (!$module->lrs_endpoint || !$module->lrs_username || !$module->lrs_password) {
    return $default_link_output;
  }

  # get the current user's status

    # if user is not logged in, return the default grassblade shortcode

      if (!is_user_logged_in()) {
        //return $default_link_output;
        return  "<a href='" . __( 'https://www.learn.med.uottawa.ca/login', 'learn.med' ) . "' target='_self'>" . __( 'Please login.', 'grassblade' ) . "</a>";
  
      } 

    # get the current user id

      $user_id = get_current_user_id();

    # get current user status, completion date on module
      $completion_date = null;

      /////////////JAMEY CODE CONDITION
      if($jarr['_wp_page_template'][0] != 'template-noflags.php'){
        if ($module->has_user_completed($user_id)) {
          $status = STATUS_COMPLETED;
          if ($args["show_completion_date"]) {
            $completion_date = $module->get_completion_date($user_id);
          }
        } else if ($module->has_user_started($user_id)) {
          $status = STATUS_STARTED;
        } else {
          $status = STATUS_NOT_STARTED;
        }
      }
      /////////////END JAMEY CODE CONDITION

  # return the launch link

    # wrap the output in a span.xapi-activity

    $html = sprintf('<span class="xapi-activity" data-post-id="%d">', $module_id);

    # generate the link using the grassblade shortcode

    $html .= $default_link_output;

    # append the status label

    /////////////JAMEY CODE CONDITION
    if($jarr['_wp_page_template'][0] != 'template-noflags.php'){
      $html .= get_module_status_label($status, $module, $completion_date);
    }
    /////////////END JAMEY CODE CONDITION

    # /span.xapi-activity
    $html .= '</span>';

    wp_enqueue_script(
      'module_launch_update_labels',
      get_template_directory_uri() . '/assets/js/plugins/module_launch/update_labels.js',
      1, // version
      true // footer
    );
    if ($module->is_module_on_untrusted_domain()) {
      wp_enqueue_script(
        'module_launch_register_clicks',
        get_template_directory_uri() . '/assets/js/plugins/module_launch/register_clicks.js',
        1, // version
        true // footer
      );
    }

    return $html;
}

function get_module_status_label($status, $module, $completion_date = null) {
  $status_label_pattern = ' <span class="xapi-activity-status xapi-activity-status-%s" data-status="%s">%s</span>';

  $completion_date_label = ' <time class="timeago xapi-activity-completion-date" data-completion-date="%s" datetime="%s">%s</time>';
  if (defined('ICL_LANGUAGE_CODE')) {
    $lang_code = (3 < strlen(ICL_LANGUAGE_CODE))? ICL_LANGUAGE_CODE: ICL_LANGUAGE_CODE . "_CA.utf8";
    setlocale(LC_TIME, $lang_code);
  }

  switch($status) {
    case STATUS_COMPLETED:
      $label = sprintf($status_label_pattern, STATUS_COMPLETED, STATUS_COMPLETED, __("Completed", 'learn.med'));
      if (!is_null($completion_date)) {
        $label .= sprintf($completion_date_label, $completion_date, $completion_date, strftime(__("on %b %e, %Y", 'learn.med'), strtotime($completion_date)));
      }
      break;
    case STATUS_STARTED:
      $label = sprintf($status_label_pattern, STATUS_STARTED, STATUS_STARTED, __("Started", 'learn.med'));
      break;
    case STATUS_NOT_STARTED:
      $label = sprintf($status_label_pattern, STATUS_NOT_STARTED, STATUS_NOT_STARTED, __("Not started", 'learn.med'));
      break;
    default:
      $label = '';
  }

  if ($module->is_module_on_untrusted_domain()) {
    $label .= get_external_module_attestation_note($status, $module->ID, $module->post->post_title);
  }
  return sprintf('<span class="xapi-activity-labels">%s  <span class="spinner"><i class="icon-spin icon-refresh"></i></span></span>', $label);
}

function wp_ajax_get_module_status_labels() {
  $current_statuses = $_POST['current_statuses'];
  $updated_labels = array();

  if (!is_user_logged_in()) {
    $status_code = 403;
    @http_response_code($status_code);
    die();
  }

  # get the current user id

  $user_id = get_current_user_id();

  foreach($current_statuses as $module_id => $current_status) {
    $module = new Module($module_id);
    $completion_date = null;

    if ($module->has_user_completed($user_id)) {
      $new_status = STATUS_COMPLETED;
      $completion_date = $module->get_completion_date($user_id);
    } else if ($module->has_user_started($user_id)) {
      $new_status = STATUS_STARTED;
    } else {
      $new_status = STATUS_NOT_STARTED;
    }

    if ($new_status != $current_status) {
      $updated_labels[$module_id] = get_module_status_label($new_status, $module, $completion_date);
    }
  }

  $status_code = 200;
  @http_response_code($status_code);
  @header('Content-Type: application/json; charset=' . get_option('blog_charset'), true);

  echo json_encode(array(
    'updated_labels' => $updated_labels
  ));
  die();
}

function wp_ajax_external_module_register_click() {
  $module_id = $_POST['module_id'];

  if (!is_user_logged_in()) {
    $status_code = 403;
    @http_response_code($status_code);
    die();
  }

  # get the current user id

  $module = new Module($module_id);
  $attempted_sent = $module->save_attempted();

  $status_code = 200;
  @http_response_code($status_code);
  @header('Content-Type: application/json; charset=' . get_option('blog_charset'), true);

  echo json_encode(array());
  die();
}

function wp_ajax_external_module_register_completion_attestation() {
  $module_id = $_POST['module_id'];

  if (!is_user_logged_in()) {
    $status_code = 403;
    @http_response_code($status_code);
    die();
  }

  # get the current user id

  $module = new Module($module_id);
  $completion_sent = $module->save_completed();

  $status_code = 200;
  @http_response_code($status_code);
  @header('Content-Type: application/json; charset=' . get_option('blog_charset'), true);

  echo json_encode(array());
  die();
}

function module_launch_head_config() {
  global $post;

  if (is_null($post)) { return; }

  $config = array(
    'ajax_url' => preg_replace('/http:/', 'https:', admin_url('admin-ajax.php')),
    'action' => array(
      'update_labels' => 'get_module_status_labels',
      'register_click' => 'external_module_register_click',
      'register_completion_attestation' => 'external_module_register_completion_attestation'
    )
  );
  echo "<script>var module_launch = " . json_encode($config, JSON_UNESCAPED_SLASHES) . ";</script>";
}

function get_external_module_attestation_note($status, $module_id, $module_title) {
  $module_title_escaped = htmlentities($module_title);

  $not_started_note_label = __('Will require that you attest completion', 'learn.med');
  $not_started_note_popover = sprintf(__('After you complete the module, please come back to this page where you will be able to attest that you completed "%s" for our records. You\'ll then see a "COMPLETED" label appear next to the link to the module.', 'learn.med'), $module_title_escaped);

  $not_started_note = <<<NOTSTARTED
    <span class="attest-note attest-note-instructions">$not_started_note_label 
      <a href="#" class="importantLink " aria-controls="attest-note-instructions-details-$module_id" data-trigger="focus" role="button" tabindex="0" data-placement="bottom" title="" data-toggle="popover" data-popover-content="attest-note-instructions-details-$module_id">
      <span class="fa fa-question-circle" aria-hidden="false"></span></a>
    </span>
    <div id="attest-note-instructions-details-$module_id" aria-hidden="true" class="hide">
      $not_started_note_popover
    </div>
NOTSTARTED;

  $started_note_label = __('I attest having completed this module&hellip;', 'learn.med');
  $started_note_popover = sprintf(__('I attest that I have completed the module "%s".', 'learn.med'), $module_title_escaped);
  $started_note_confirm_button = __('I attest', 'learn.med');

  $started_note = <<<STARTED
    <span class="attest-note">
      <a href="#" class="attest-note-link" aria-controls="attest-note-confirm-$module_id" data-trigger="focus" role="button" tabindex="0" data-placement="bottom" title="" data-toggle="popover" data-popover-content="attest-note-confirm-$module_id">$started_note_label</a>
    </span>
    
    <div id="attest-note-confirm-$module_id" aria-hidden="true" class="hide">
      $started_note_popover
      <div class="actions">
        <a class="btn btn-primary attest-completion" href="#" role="button">$started_note_confirm_button</a>
      </div>
    </div>
STARTED;

  switch($status) {
    case STATUS_NOT_STARTED:
      $note = $not_started_note;
      break;
    case STATUS_STARTED;
      $note = $started_note;
      break;
    default:
      $note = '';
  }
  return $note;
}
add_shortcode( 'storyline', 'module_launch_with_status_label_shortcode' );
add_shortcode( 'module', 'module_launch_with_status_label_shortcode' );

add_action('wp_ajax_get_module_status_labels', 'wp_ajax_get_module_status_labels');
add_action('wp_ajax_external_module_register_click', 'wp_ajax_external_module_register_click');
add_action('wp_ajax_external_module_register_completion_attestation', 'wp_ajax_external_module_register_completion_attestation');
add_action('wp_head', 'module_launch_head_config');

///////JAMEY CODE
function debug_to_console($data) {
  if(is_array($data) || is_object($data)){
    echo("<script>console.log('PHP: ".json_encode($data)."');</script>");
  } else {
    echo("<script>console.log('PHP: ".$data."');</script>");
  }
}
///////END JAMEY CODE

?>
