<?php

class xapi_wp_pro_quiz {

  private $lrs;
  private $_quiz_id_prefix = 'assessment/wp-pro-quiz/';
  private $_interaction_id_prefix = '/interaction/';

  private $question_mapper;
  private $quiz_mapper;

  # info on the lesson or quiz page the quiz is on
  private $post_id;
  private $post_url;
  private $post_title;

  # info on the quiz itself
  private $quiz_id;
  private $quiz_activity_id;
  private $quiz_title;

  # info on the course the quiz is part of
  private $course_id;
  private $course_url;
  private $course_title;

  # info on the score
  private $points_quiz_total = 0;
  private $points_answers_total = 0;

  private $answer_retries_extension_url = 'https://learn.med.uottawa.ca/xapi/result/extensions/answer-retries';

  # hold statements and send them all at once
  private $statements = array();

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

    add_action('wp_ajax_wp_pro_quiz_completed_quiz', array($this, 'receive_answers'), 1);
    add_action('wp_print_scripts', array($this, 'replace_wpProQuiz_front_javascript'), 1);

  }

  public function replace_wpProQuiz_front_javascript() {
    wp_enqueue_script(
      'wpProQuiz_front_javascript',
      get_template_directory_uri() . '/assets/js/plugins/sfwd-lms/wpProQuiz_front-custom.js',
      array('jquery-ui-sortable'),
      WPPROQUIZ_VERSION,
      true // footer
    );
  }

  function receive_answers() {

    if (!class_exists('WpProQuiz_Model_QuestionMapper') || !class_exists('WpProQuiz_Model_QuizMapper')) {
      return false;
    }

    # disable grassblade's statements about the quiz
    remove_action('learndash_quiz_completed', 'grassblade_learndash_quiz_completed', 1);

    # prepare what we know about the answers received
    $this->parse_results();

    grassblade_debug("wp-pro-quiz results:\n" . print_r($_POST['results'], true));

    # for each question
    foreach($_POST['results'] as $question_id => $learner_results) {
      if ("comp" == $question_id) {
        continue;
      }
      $this->parse_answer($question_id, $learner_results);
    }

    # prepare a statement for the entire quiz
    $this->prepare_completion_statement();

    # send all statements in one shot
    $this->send_statements();

  }

  function parse_results() {

    # interface with the wp-pro-quiz plugin's functions
    $this->question_mapper = new WpProQuiz_Model_QuestionMapper();
    $this->quiz_mapper = new WpProQuiz_Model_QuizMapper();

    # get the lesson or quiz page the quiz is on
    $this->post_id = $_POST["quiz"];
    $this->post_url = preg_replace('/http:/', 'https:', get_permalink($this->post_id));
    $this->post_title = get_post($this->post_id)->post_title;

    # info on the quiz
    $this->quiz_id = $_POST["quizId"];
    $this->quiz_activity_id = $this->post_url . '#' . $this->_quiz_id_prefix . $this->quiz_id;
    $this->quiz_title = $this->quiz_mapper->fetch($this->quiz_id)->getName();

    # info on the course
    $this->course_id = learndash_get_course_id($this->post_id);
    $this->course_url = preg_replace('/http:/', 'https:', get_permalink($this->course_id));
    $this->course_title = get_post($this->course_id)->post_title;

    return true;

  }

  function parse_answer($question_id, $learner_results) {

    $question_info = $this->question_mapper->fetch($question_id);

    $answer_type = $question_info->getAnswerType();

    # skip if not a single-choice question
    switch($answer_type) {
      case 'single':
        $this->parse_single_choice_answer($question_id, $question_info, $learner_results);
        break;
      default:
        continue;
    }

  }

  function parse_single_choice_answer($question_id, $question_info, $learner_results) {

    # get the activity id of the interaction
    $question['activity_id'] = $this->quiz_activity_id . $this->_interaction_id_prefix . $question_id;

    # get the question displayed
    $question['text'] = $question_info->getQuestion();

    # get the details of the answers
    $possible_answers = $question_info->getAnswerData();

    $answer_data = $learner_results['data'];

    $answer = $this->get_single_choice_answer_details($possible_answers, $answer_data);

    if (isset($learner_results['retries']) && is_array($learner_results['retries'])) {
      $retries = $learner_results['retries'];
      foreach($retries as $i => $retry_raw_data) {
        $retries[$i] = $this->get_single_choice_answer_details($possible_answers, $retry_raw_data);
      }

      $answer['retries'] = $retries;
    }

    grassblade_debug("wp-pro-quiz answer details for $question_id with retries:\n" . print_r($answer, true));

    $this->prepare_answered_statement($question, $answer);

  }

  function get_single_choice_answer_details($possible_answers, $answer_raw_response) {

    $learner_answer_id = null;

    # get the answer chosen by the learner
    foreach ($answer_raw_response as $answer_id => $has_chosen) {
      if ($has_chosen) {
        $learner_answer_id = $answer_id;
        break;
      }
    }

    if (is_null($learner_answer_id)) {
      return false;
    }

    # get the answer's text
    $answer['text'] = $possible_answers[$learner_answer_id]->getAnswer();

    # get if answer was correct
    $answer['correct'] = ($possible_answers[$learner_answer_id]->isCorrect())? 1: 0;

    # collect the score
    $points_question = $possible_answers[$learner_answer_id]->getPoints();
    $this->points_quiz_total += $points_question;
    if ($answer['correct'] === 1) {
      $this->points_answers_total += $points_question;
      $answer['points'] = $points_question;
    } else {
      $answer['points'] = 0;
    }

    return $answer;

  }

  function prepare_answered_statement($question, $answer) {

    # prepare an answered statement
    $statement = new TinCan\Statement([
      'actor' => grassblade_getactor(),
      'verb' => [
        'id' => 'http://adlnet.gov/expapi/verbs/answered',
        'display' => [
          'en-US' => 'answered'
        ]
      ]
    ]);

    # set the activity
    $object = new TinCan\Activity();

    $object
    ->setId($question['activity_id'])
    ->setDefinition([]);

    $object->getDefinition()
    ->setType('http://adlnet.gov/expapi/activities/cmi.interaction')
    ->getName()
    ->set('en-US', $question['text']);

    $object->getDefinition()
    ->getDescription()
    ->set('en-US', $question['text']);

    $statement->setObject($object);

    # set the parent

    $parent = new TinCan\Activity();
    $parent
    ->setId($this->quiz_activity_id)
    ->setDefinition([]);

    $parent->getDefinition()
    ->getName()
    ->set('en-US', $this->quiz_title);

    # set the grouping

    $grouping = new TinCan\Activity();
    $grouping
    ->setId($this->course_url)
    ->setDefinition([]);

    $grouping->getDefinition()
    ->getName()
    ->set('en-US', $this->course_title);

    # assemble the context

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

    # set the result
    $result = new TinCan\Result();

    $result
    ->setResponse($answer['text']);

    $result
    ->setSuccess($answer['correct'])
    ->setScore([
        'raw' => intval($answer['points'])
      ]);

    if (isset($answer['retries']) && is_array($answer['retries'])) {
      $retries = &$answer['retries'];

      foreach($retries as $i => $retry_properties) {
        $retry = new TinCan\Result;
        $retry
        ->setResponse($retry_properties['text'])
        ->setSuccess($retry_properties['correct'])
        ->setScore([
          'raw' => $retry_properties['points']
        ]);

        $retries[$i] = $retry->asVersion($xapi_version);
      }

      $result->setExtensions([
        $this->answer_retries_extension_url => $retries
      ]);
    }

    $statement->setResult($result);

    # queue the statement for sending
    array_push($this->statements, $statement);

    # debug
    grassblade_debug($statement);

    return true;
  }

  function prepare_completion_statement() {

    # determine the score

    if (!isset($_POST['results']['comp']) || !isset($_POST['results']['comp']['points']) || !isset($_POST['results']['comp']['result'])) {
      return false;
    }

    $score = array(
      'raw' => intval($_POST['results']['comp']['points']),
      'scaled' => intval($_POST['results']['comp']['result']) / 100
    );

    # prepare a completed statement (no passing grade known to be set)
    $statement = new TinCan\Statement([
      'actor' => grassblade_getactor(),
      'verb' => [
        'id' => 'http://adlnet.gov/expapi/verbs/completed',
        'display' => [
          'en-US' => 'completed'
        ]
      ]
    ]);

    # set the activity
    $object = new TinCan\Activity();

    $object
    ->setId($this->quiz_activity_id)
    ->setDefinition([]);

    $object->getDefinition()
    ->getName()
    ->set('en-US', $this->quiz_title);

    $object->getDefinition()
    ->getDescription()
    ->set('en-US', $this->quiz_title);

    $statement->setObject($object);

    # set the parent

    $parent = new TinCan\Activity();
    $parent
    ->setId($this->post_url)
    ->setDefinition([]);

    $parent->getDefinition()
    ->getName()
    ->set('en-US', $this->post_title);

    # set the grouping

    $grouping = new TinCan\Activity();
    $grouping
    ->setId($this->course_url)
    ->setDefinition([]);

    $grouping->getDefinition()
    ->getName()
    ->set('en-US', $this->course_title);

    # assemble the context

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

    # set the result
    $result = new TinCan\Result();

    $result
    ->setSuccess(true)
    ->setScore($score)
    ->setCompletion(true);

    $statement->setResult($result);

    # queue the statement for sending
    array_push($this->statements, $statement);
  }

  function send_statements() {
    # send all statement
    $response = $this->lrs->saveStatements($this->statements);

    if ($response->success) {
      grassblade_debug("Sent statements for " . $this->quiz_activity_id);
    }
    else {
      grassblade_debug("Error: statements for " . $this->quiz_activity_id . " not sent: \n" . $response->content);
    }
  }

}

$xapi_wpq = new xapi_wp_pro_quiz();
?>
