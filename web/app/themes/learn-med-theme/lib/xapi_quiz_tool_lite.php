<?php

  class xapi_quiz_tool_lite {

    private $lrs;
    private $_interaction_id_prefix = 'assessment/interaction/quiz-tool-lite/';

    function __construct() {

      $lrs_endpoint = get_option('grassblade_tincan_endpoint');
      $lrs_username = get_option('grassblade_tincan_user');
      $lrs_password = get_option('grassblade_tincan_password');

      # if LRS settings aren't set, return the default grassblade shortcode
      if (!$lrs_endpoint || !$lrs_username || !$lrs_password) {
        return;
      }

      $this->lrs = new TinCan\RemoteLRS(
        $lrs_endpoint,
        '1.0.1',
        $lrs_username,
        $lrs_password
      );

      add_action('wp_ajax_xapi_quiz_tool_lite_save_answer', array($this, 'receive_answer'));
      add_action('wp_ajax_xapi_quiz_tool_lite_retrieve_answer', array($this, 'retrieve_answer'));
      add_action('wp_head', array($this, 'wp_head_config'));
    }

    function receive_answer() {

      $post_id = $_POST["post_id"];
      $question_id = $_POST["question_id"]; // quiz-tool-lite question id
      $answer = $_POST["answer"];
      $course_id = learndash_get_course_id($post_id);
      $post_url = preg_replace('/http:/', 'https:', get_permalink($post_id));

      $activity_id = $post_url . '#' . $this->_interaction_id_prefix . $question_id;

      $answer = array(
        'post_id' => $post_id,
        'question_id' => $question_id,
        'answer' => $answer,

        'course_id' => $course_id,

        'post_title' => get_post($post_id)->post_title,
        'course_title' => get_post($course_id)->post_title,

        'post_url' => $post_url,
        'course_url' => preg_replace('/http:/', 'https:', get_permalink($course_id)),

        'question' => utils::convertTextFromDB(getQuestionInfo($question_id)['question']), // from quiz-tool-lite
        'activity_id' => $activity_id,
      );

      $this->answered($answer);
      $this->save_state($answer);
    }

    function answered($answer) {

      // prepare the statement
      $statement = new TinCan\Statement(
        [
          'actor' => grassblade_getactor(),
          'verb' => [
            'id' => 'http://adlnet.gov/expapi/verbs/answered',
          ]
        ]
      );

      // set the activity
      $object = new TinCan\Activity();

      $object
      ->setId($answer['activity_id'])
      ->setDefinition([]);

      $object->getDefinition()
      ->setType('http://adlnet.gov/expapi/activities/cmi.interaction')
      ->getName()
      ->set('en-US', $answer['question']);

      $object->getDefinition()
      ->getDescription()
      ->set('en-US', $answer['question']);

      $statement->setObject($object);

      // set the parent

      $parent = new TinCan\Activity();
      $parent
      ->setId($answer['post_url'])
      ->setDefinition([]);

      $parent->getDefinition()
      ->getName()
      ->set('en-US', $answer['post_title']);

      // set the grouping

      $grouping = new TinCan\Activity();
      $grouping
      ->setId($answer['course_url'])
      ->setDefinition([]);

      $grouping->getDefinition()
      ->getName()
      ->set('en-US', $answer['course_title']);

      // assemble the context

      $context = new TinCan\Context();

      $context
      ->setContextActivities([]);

      $context
      ->getContextActivities()
      ->setParent($parent);

      $context
      ->getContextActivities()
      ->setGrouping($grouping);

      $statement->setContext($context);

      // set the result
      $result = new TinCan\Result();

      // determine if the question is reflection or multiple choice
      $question_type = getQuestionInfo($answer['question_id'])['qType'];

      // capture the response and the success based on the type of question
      switch($question_type) {
        case 'reflectionText':
          $result
          ->setResponse($answer["answer"]);
          break;
        case 'radio':
          $response_info = getResponseOptionInfo(intval($answer['question_id'], $answer["answer"]));
          $result
          ->setResponse(utils::convertTextFromDB($response_info->optionValue))
          ->setSuccess($response_info->isCorrect);
          break;
        case 'check':
          $response_text = '';
          $i = 0;

          foreach($answer["answer"] as $response_id) {
            if (0 < $i) {
              $response_text .= ', ';
            }
            $response_info = getResponseOptionInfo(intval($response_id));
            $response_text .= utils::convertTextFromDB($response_info->optionValue);
            $i++;
          }

          $is_correct = self::is_correct_answers_checkboxes($answer['question_id'], $answer['answer']);

          $result
          ->setResponse(utils::convertTextFromDB($response_text))
          ->setSuccess($is_correct);
          break;
      }



      $statement->setResult($result);

      // send the statement
      $response = $this->lrs->saveStatement($statement);
      if ($response->success) {
        grassblade_debug("Sent answered statement for " . $answer['activity_id']);
      }
      else {
        grassblade_debug("Error: answered statement for " . $answer['activity_id'] . " not sent: \n" . $response->content);
      }

      // debug
      grassblade_debug($statement);
    }

    private function save_state($answer) {

      $activity = array(
        'id' => $answer['post_url']
      );

      $question_type = getQuestionInfo($answer['question_id'])['qType'];
      $is_correct = null;

      if ('check' == $question_type  ) {
        $answer['answer'] = implode(',', $answer['answer']);
      }

      $agent = grassblade_getactor();

      $saveResponse = $this->lrs->saveState(
        $activity,
        $agent,
        $this->_interaction_id_prefix . $answer['question_id'],
        $answer['answer']
      );

      if ($saveResponse->success) {
        grassblade_debug("Saved answer for " . $answer['activity_id']);
      }
      else {
        grassblade_debug("Error: answer for " . $answer['activity_id'] . " not saved: \n" . $saveResponse->content);
      }

    }

    function retrieve_answer() {

      $post_id = $_GET["post_id"];
      $question_id = $_GET["question_id"]; // quiz-tool-lite question id
      $post_url = preg_replace('/http:/', 'https:', get_permalink($post_id));

      $activity_id = $post_url . '#' . $this->_interaction_id_prefix . $question_id;

      $question = array(
        'post_id' => $post_id,
        'question_id' => $question_id,
        'post_url' => $post_url,
        'activity_id' => $activity_id,
      );

      $this->retrieve_state($question);
    }

    private function retrieve_state($question) {

      $activity = array(
        'id' => $question['post_url']
      );

      $agent = grassblade_getactor();

      $retrieveResponse = $this->lrs->retrieveState(
        $activity,
        $agent,
        $this->_interaction_id_prefix . $question['question_id']
      );

      if ($retrieveResponse->success) {
        grassblade_debug("Retrieved answer for " . $question['activity_id'] . "\n" . $retrieveResponse->content->getContent());

        if (404 == $retrieveResponse->httpResponse['status']) {
          // no object found, which is normal
          $status_code = 200;
          $answer = null;
        } else {
          $status_code = $retrieveResponse->httpResponse['status'];
          $answer = stripslashes($retrieveResponse->content->getContent());
        }

        $question_type = getQuestionInfo($question['question_id'])['qType'];
        $is_correct = null;

        if ('radio' == $question_type) {
          $response_info = getResponseOptionInfo(intval($answer));
          $is_correct = ($response_info->isCorrect)? true: false;
        } else if ('check' == $question_type) {
          $answer = split(',', $answer);
          // evaluate is_correct on the client-side to show the individual feedback text for each option
        } //else if ('reflectionText' == $question_type ) {
          //$answer = $answer;
        //}

        @http_response_code($status_code);
        @header('Content-Type: application/json; charset=' . get_option('blog_charset'), true);
        echo json_encode(array(
          'question_id' => $question['question_id'],
          'answer' => $answer,
          'is_correct' => $is_correct
        ));
      }
      else {
        grassblade_debug("Error: answer for " . $question['activity_id'] . " not retrieved: \n" . $retrieveResponse->content);
        @header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
      }
      die();
    }

    function wp_head_config() {
      global $post;

      if (is_null($post)) { return; }

      $config = array(
        'ajax_url' => preg_replace('/http:/', 'https:', admin_url('admin-ajax.php')),
        'post_id' => $post->ID
      );
      echo "<script>var xapi_quiz_tool_lite = " . json_encode($config, JSON_UNESCAPED_SLASHES) . ";</script>";
    }

    public static function is_correct_answers_checkboxes($question_id, $answers) {

      $question_type = getQuestionInfo($question_id)['qType'];

      // just check for questions of type checkboxes
      if ($question_type != 'check') {
        return;
      }

      $is_error_found = false;
      $response_options = getResponseOptions($question_id);

      foreach($response_options as $response_option) {
        $answer_found = false;

        foreach($answers as $response_id) {
          if ($response_id == $response_option['optionID']) {
            $answer_found = true;
            break;
          }
        }
        if($response_option[isCorrect]) {
          if (!$answer_found) {
            $is_error_found = true;
            break;
          }
        } else {
          if ($answer_found) {
            $is_error_found = true;
            break;
          }
        }
      }
      return !$is_error_found;
    }


  }

  $xapi_qtl = new xapi_quiz_tool_lite();
?>
