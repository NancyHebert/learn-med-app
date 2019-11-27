<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/wp/wp-blog-header.php'; #TODO: Find home path instead of DOCUMENT_ROOT
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Parser;

require_once locate_template('/lib/lrs.php');

if (!class_exists('WpProQuiz_Model_QuestionMapper') || !class_exists('WpProQuiz_Model_QuizMapper')) {
  die("WpProQuiz classes missing");
}

# set defaults

  $report_name_prefix = "Incorrect answers from: ";
  $answer_retries_extension_url = 'https://learn.med.uottawa.ca/xapi/result/extensions/answer-retries';

  define("NO_ANSWER", 'no_answer');
  define("CORRECT_FIRST_TIME", 'correct_first_time');
  define("MULTIPLE_ATTEMPTS", 'multiple_attempts');

  require_once 'xlsx.styles.php';

  $yaml = new Parser();

# prepare connections to the LRS

  $lrs_endpoint = get_option('grassblade_tincan_endpoint');
  $lrs_username = get_option('grassblade_tincan_user');
  $lrs_password = get_option('grassblade_tincan_password');

  # if LRS settings aren't set, return the default grassblade shortcode
  if (!$lrs_endpoint || !$lrs_username || !$lrs_password) {
    die('LRS not configured');
  }

  $API_url_score_results = $lrs_endpoint . '_api/basic_results/v1';
  $API_url_module_completion = $lrs_endpoint . '/_api/completionreport';

  # prepare connection for querying for statements

  $lrs = new TinCan\RemoteLRS(
    $lrs_endpoint,
    '1.0.1',
    $lrs_username,
    $lrs_password
  );

# get the list of modules to report on

  $config = $yaml->parse(file_get_contents('config.yml'));

  $filename = sprintf($config['reports']['incorrect_answers']['filename'], date('F j, Y'));

  $modules = &$config['modules'];

  foreach($modules as $module_activity_id => &$module_details) {

      if (!isset($module_details['questions']['scored']) || !isset($module_details['first_scores'])) {
        continue;
      }

    # fetch the question's text
      $questions = array();

      foreach($module_details['questions']['scored'] as $question_activity_id) {
        $questions[$question_activity_id] = array();

        # require that the question be from a wp-pro-quiz, else skip this question
        if (false === strpos($question_activity_id, 'assessment/wp-pro-quiz/')) {
          continue;
        }

        # fetch the question's post_id
        preg_match('/interaction\/([1-9][0-9]*)$/', $question_activity_id, $matches);
        $question_id = $matches[1];

        # fetch the question's details
        $question_mapper = new WpProQuiz_Model_QuestionMapper();
        $question_details = $question_mapper->fetch($question_id);

        # fetch the question's text
        $question = $question_details->getQuestion();

        $questions[$question_activity_id]['question'] = $question;

        # fetch the question's choices
        $choices = array();

        $choices_details = $question_details->getAnswerData();
        foreach ($choices_details as $choice) {
          array_push($choices, [
            'correct' => $choice->isCorrect(),
            'answer' => $choice->getAnswer()
          ]);
        }

        $questions[$question_activity_id]['choices'] = $choices;
      }

      $module_details['questions']['scored'] = $questions;

    # call the LRS for the activity id

      $response_module_completion = CallAPI("GET", $API_url_module_completion, $lrs_username, $lrs_password, array('activity' => $module_activity_id, 'all' => 'true'));

    # if no response or an error has occurred, throw an error

      if (!$response_module_completion) {
        die("Can't get data on $module_activity_id");
      }

    # parse the returning JSON

      $response_module_completion = json_decode($response_module_completion, true);

    # store in $modules[module_activity_id][students]

      $module_details['students'] = array();
      $students = &$module_details['students'];

      foreach($response_module_completion as $learner) {

        if (isset($learner['completion']) && true == $learner['completion']) {

          $student_email = substr($learner['agent']['mbox'], strlen('mailto:'));

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

          # skip if user is admin, or a tester
          if (user_can($wp_user_id, 'manage_options' ) || (isset($config['testers']) && in_array($student_email, $config['testers']))) {
            continue;
          }

          $students[$student_email] = array();
          $student_details = &$students[$student_email];

          $student_details['first_name'] = get_user_meta($wp_user_id, 'first_name', true);
          $student_details['last_name'] = get_user_meta($wp_user_id, 'last_name', true);
          $student_details['department'] = get_user_meta($wp_user_id, 'Department', true);
          $student_details['date_completed'] = $learner['completion_date'];

          $student_details['answers'] = array();
          $student_answers = &$student_details['answers'];

          # for each question in the module, find the first answer and store it in question_id => answer
          $agent = new TinCan\Agent(
            [ 'mbox' => 'mailto:' . $student_email ]
          );
          $verb = new TinCan\Verb(
            [ 'id' => 'http://adlnet.gov/expapi/verbs/answered' ]
          );
          foreach ($module_details['questions']['scored'] as $question_id => $question_details) {
            $attempts = array();

            if ($config['inspect']) {
              $tests = $config['inspect'];
              $skip_result = true;
              foreach($tests as $test) {
                if ($student_email == $test['agent_email'] && $question_id == $test['activity_id']) {
                  $skip_result = false;
                  break;
                }
              }
              if ($skip_result) {
                continue;
              }
            }

            $activity = new TinCan\Activity(
              [ 'id' =>  $question_id ]
            );
            $query_answered_statements = [
              'agent' => $agent,
              'verb'  => $verb,
              'activity' => $activity,
              'ascending' => 'true' // to get the first
            ];

            $response_answered_statements = $lrs->queryStatements($query_answered_statements);

            while ($response_answered_statements->success) {
              $answered_statements = $response_answered_statements->content->getStatements();
              if (count($answered_statements) > 0) {
                foreach($answered_statements as $answered_statement) {
                  $result = $answered_statement->getResult();
                  if ($result) {
                    $answers = array();
                    array_push($answers, [
                      'correct' => $result->getSuccess(),
                      'response' => stripslashes($result->getResponse())
                    ]);

                    $extensions = $result->getExtensions();

                    if ($extensions) {
                      $extensions = $extensions->asVersion('1.0.1');

                      if (is_array($extensions) && isset($extensions[$answer_retries_extension_url]) && is_array($extensions[$answer_retries_extension_url])) {
                        foreach($extensions[$answer_retries_extension_url] as $i => $retry_properties) {
                          $retry = new TinCan\Result($retry_properties);
                          array_push($answers, [
                            'correct' => $retry->getSuccess(),
                            'response' => stripslashes($retry->getResponse())
                          ]);
                        }
                      }
                    }

                    array_push($attempts, $answers);
                  }
                }
              }
              if (is_null($response_answered_statements->content->getMore())) {
                break;
              } else {
                // fetch more
                $response_answered_statements = $lrs->moreStatements($response_answered_statements->content->getMore());
              }
            }

            $student_answers[$question_id]['attempts'] = $attempts;

            if ($config['inspect']) {
              grassblade_debug(sprintf("Inspect incorrect answer attempts:\nTest name: %s\nactivity_id: %s\nagent_email: %s\n%s",
                $test['name'],
                $test['activity_id'],
                $test['agent_email'],
                print_r($attempts, true)
              ));
            }
          }

        }
      }

  # fetch the first recorded scores

    $students = &$module_details['students'];

    if (isset($module_details['first_scores'])) {
      foreach ($module_details['first_scores'] as $quiz_activity_id => &$score_details) {
        # call the LRS for the activity id

          $response_score_results = CallAPI("GET", $API_url_score_results, $lrs_username, $lrs_password, array('activity' => $quiz_activity_id));

        # if no response or an error has occurred, throw an error

          if (!$response_score_results) {
            echo "Can't get data on $quiz_activity_id";
            var_dump($response_score_results);
            exit;
          }

        # parse the returning JSON

          $response_score_results = json_decode($response_score_results, true);

        # store in $student_details[first_scores][quiz_activity_id]

          foreach($response_score_results['results'] as $learner) {

            $student_email = substr($learner['agent']['mbox'], strlen('mailto:'));

            if (!isset($students[$student_email])) {
                continue; // only report scores on those who completed the module
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

            # skip if user is admin
            if (user_can($wp_user_id, 'manage_options' ) ) {
              // continue;
            }

            $first_score = null;

            foreach($learner['scaledScore'] as $score) {
              if (isset($score)) {
                $first_score = $score;
                break;
              }
            }

            # require a score
            if (is_null($first_score)) {
              continue;
            }
            $student_details = &$students[$student_email];

            if (!isset($student_details['first_name'])) {
              $student_details['first_name'] = get_user_meta($wp_user_id, 'first_name', true);
              $student_details['last_name'] = get_user_meta($wp_user_id, 'last_name', true);
              $student_details['department'] = get_user_meta($wp_user_id, 'Department', true);
            }

            if (!isset($student_details['first_scores'])) {
              $student_details['first_scores'] = array();
            }

            $student_details['first_scores'][$quiz_activity_id] = $first_score;
          }
      }

    }


  } // foreach module

  d($modules);

# assemble the xls

  $objPHPExcel = new PHPExcel();

  # set the cell caching method

    $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_sqlite3;

    PHPExcel_Settings::setCacheStorageMethod($cacheMethod);

  # for each module, add a worksheet

    $current_worksheet_index = 0;

    foreach($modules as $module_activity_id => &$module_details) {

      if (!isset($module_details['questions']['scored'])) {
        continue;
      }

      if ($current_worksheet_index == 0) {
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $objWorksheet->setTitle(substr($module_details['name_abbr'], 0, 30));
      } else {
        $new_worksheet = new PHPExcel_Worksheet($objPHPExcel, substr($module_details['name_abbr'], 0, 30));

        $objPHPExcel->addSheet($new_worksheet, $current_worksheet_index);
        $objWorksheet = $objPHPExcel->getSheet($current_worksheet_index);
      }

    # add the title

      $objWorksheet->setCellValue('A1', strtoupper($report_name_prefix) . ' ' . $module_details['name'] . ' - Updated: ' . date("j F Y"));

    # add the column names

      $objWorksheet->setCellValue('A2', 'Name');
      $objWorksheet->setCellValue('B2', 'Email');
      $objWorksheet->setCellValue('C2', 'Department');

      if (isset($module_details['first_scores'])) {

        foreach ($module_details['first_scores'] as $quiz_activity_id => &$score_details) {
          $col = $objWorksheet->getHighestColumn();
          $col++;

          if (!is_array($score_details)) {
            continue;
          }
          $score_details['column'] = $col;

          $objWorksheet->setCellValue($col . '2', $score_details['name']);
          $objWorksheet->getStyle($col . '2')
          ->getAlignment()
          ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
          ->setWrapText(true);
        }

      }

      foreach ($module_details['questions']['scored'] as $question_id => &$question_details) {
        $col = $objWorksheet->getHighestColumn();
        $col++;

        if (!is_array($question_details)) { // no data found on that question in the LRS
          continue;
        }

        $question_details['column'] = $col;

        $objWorksheet->setCellValue($col . '2', $question_details['question']);
        $objWorksheet->getStyle($col . '2')
        ->getAlignment()
        ->setWrapText(true);

        $choices = "";
        foreach($question_details['choices'] as $choice_num => $choice) {
          $choices .= '[';
          $choices .= ($choice['correct'])? '*' : ' ';
          $choices .= '] ';
          $choices .= $choice['answer'] . "\n";
        }

        $objWorksheet->setCellValue($col . '3', $choices);
        $objWorksheet->getStyle($col . '3')
        ->getAlignment()
        ->setWrapText(true)
        ->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);

      }

    # add the students

      $highestRow = $objWorksheet->getHighestRow();
      $data_first_row_num = $highestRow + 1;

      $row = $data_first_row_num;

      if (isset($module_details['students'])) {
        foreach($module_details['students'] as $learner_email => &$learner_details) {

            if ($config['inspect'] && empty($learner_details['answers'])) {
              continue;
            }

          # output the general information

            $objWorksheet->setCellValue('A'.$row, $learner_details['first_name'] . ' ' . $learner_details['last_name']);
            $objWorksheet->setCellValue('B'.$row, strtolower($learner_email));
            $objWorksheet->setCellValue('C'.$row, $learner_details['department']);

          # output the first scores

            if (isset($learner_details['first_scores'])) {
              foreach ($module_details['first_scores'] as $quiz_activity_id => &$score_details) {
                $col = $score_details['column'];

                if (array_key_exists($quiz_activity_id, $learner_details['first_scores'])) {
                  $objWorksheet->setCellValue($col.$row, sprintf("%d%%", round($learner_details['first_scores'][$quiz_activity_id] * 100), 0));
                  $objWorksheet->getStyle($col.$row)
                  ->getAlignment()
                  ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                }
              }
            }

          # output the answers

            if (isset($learner_details['answers'])) {
              foreach ($module_details['questions']['scored'] as $question_id => &$question_details) {
                $col = $question_details['column'];

                $status = null;

                if (array_key_exists($question_id, $learner_details['answers'])
                    && is_array($learner_details['answers'][$question_id])) {
                  if (!isset($learner_details['answers'][$question_id]['attempts'])) {
                    $output .= "(no answer recorded)";
                    $status = NO_ANSWER;
                  } else {
                    $attempts = &$learner_details['answers'][$question_id]['attempts'];
                    $count = count($attempts);
                    $output = "";
                    $separator = "\n-------\n";

                    if (0 == count($attempts)) {
                      $output .= "(no answer recorded)";
                      $status = NO_ANSWER;
                    }
                    foreach ($attempts as $attempt_index => $answers) {
                      if ($attempt_index >= 1) {
                        $output .= $separator;
                        $status = MULTIPLE_ATTEMPTS;
                      }
                      if (is_array($answers)) {
                        $count_incorrect_answers = 0;
                        foreach($answers as $answer) {
                          if ($answer['correct']) {
                            if (0 == $count_incorrect_answers) {
                              if (0 == $attempt_index) {
                                $output .= "(answered correctly the first time)";
                                $status = CORRECT_FIRST_TIME;
                              } else {
                                $output .= "(answered correctly the first time on this attempt)";
                              }
                            }
                            break;
                          }
                          $output .= sprintf("- %s\n", $answer['response']);
                          $count_incorrect_answers++;
                        }
                        if (1 <= $count_incorrect_answers) {
                          $output .= sprintf(_n(
                            "(%d incorrect answer on this attempt)\n",
                            "(%d incorrect answers on this attempt)\n",
                            $count_incorrect_answers), $count_incorrect_answers);
                        }
                      }
                    }
                  }

                  $objWorksheet->setCellValue($col.$row, $output);
                  $objWorksheet->getStyle($col.$row)
                  ->getAlignment()
                  ->setWrapText(true);
                  switch($status) {
                    case NO_ANSWER:
                      $objWorksheet->getStyle($col.$row)->applyFromArray($styles_no_answer);
                      break;
                    case CORRECT_FIRST_TIME:
                      $objWorksheet->getStyle($col.$row)->applyFromArray($styles_correct_first_time);
                      break;
                    case MULTIPLE_ATTEMPTS:
                      break;
                  };
                }
              }
            }

          # set top alignment for the row

            $objWorksheet->getStyle('A' . $row . ':' . $objWorksheet->getHighestColumn() . $row)
            ->getAlignment()
            ->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);

          # increment to the new row number

            $row++;

        }
      }

    # style the overall worksheet

      $objWorksheet->getDefaultStyle()->getFont()->setSize(14);

    # style the title

      $objWorksheet->getStyle('A1:'.$objWorksheet->getHighestColumn().'1')->applyFromArray($styles_sheet_title);

    # style the column names

      $objWorksheet->getStyle('A2:'.$objWorksheet->getHighestColumn().'2')->applyFromArray($styles_headers);

    # set the alignment and the wrapping

      $objWorksheet->getStyle($col . '2')
      ->getAlignment()
      ->setWrapText(true);

    # adjust cell widths
      // https://github.com/PHPOffice/PHPExcel/blob/develop/Documentation/markdown/Overview/08-Recipes.md#setting-a-columns-width

      $objWorksheet->getColumnDimension('A')->setWidth(24);
      $objWorksheet->getColumnDimension('B')->setAutoSize(true);
      $objWorksheet->getColumnDimension('C')->setAutoSize(true);
      if (isset($module_details['first_scores'])) {
        foreach ($module_details['first_scores'] as $quiz_activity_id => &$score_details) {
          if (isset($score_details['column'])) {
            $col = $score_details['column'];
            $objWorksheet->getColumnDimension($col)->setWidth(12);
          } else {
            continue;
          }
        }
      }
      $col++;

      $highestColumn = $objWorksheet->getHighestColumn();
      while ($col <= $highestColumn) {
        $objWorksheet->getColumnDimension($col)->setWidth(52);
        $col++;
      }

    # adjust row height for the top headers

      $objWorksheet->getRowDimension('2')->setRowHeight(120);

    # Freeze panes (top headers)

      $objWorksheet->freezePane('D3');

    # Increment the worksheet number

      $current_worksheet_index++;

  } // foreach worksheet

  # write the xls to disk

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
    $objWriter->save($filename);

  # clear the memory

    $objPHPExcel->disconnectWorksheets();

    unset($objPHPExcel);

echo "Saved to $filename\n";

?>
