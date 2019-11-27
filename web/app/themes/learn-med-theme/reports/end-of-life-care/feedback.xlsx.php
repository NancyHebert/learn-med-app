<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/wp/wp-blog-header.php'; #TODO: Find home path instead of DOCUMENT_ROOT
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Parser;

require_once locate_template('/lib/lrs.php');

# set defaults

  $report_name_prefix = "Feedback from: ";

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

  $API_url_score_results = $lrs_endpoint . '/_api/analyses/basic_results';
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

  $filename = sprintf($config['reports']['answers']['filename'], date('F j, Y'));

  $modules = &$config['modules'];

  foreach($modules as $module_activity_id => &$module_details) {

    # fetch the question's text
      $questions = array();

      foreach($module_details['questions']['reflection'] as $question_id => $question_details) {
        $questions[$question_id] = array();

        # assume the interaction number in the question_id is a quiz-tool-lite post_id
        preg_match('/interaction\/quiz-tool-lite\/([1-9][0-9]*)$/', $question_id, $matches);
        $question_num = $matches[1];
        $question_info = getQuestionInfo($question_num);
        if (isset($question_details) && isset($question_details['title'])) {
          $questions[$question_id]['question'] = $question_details['title'];
        } else if (isset($question_info)) {
          $questions[$question_id]['question'] = $question_info['question'];
        }
      }

      $module_details['questions']['reflection'] = $questions;

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

          $student_details['answers'] = array();
          $student_answers = &$student_details['answers'];

          # for each question in the module, find the first answer and store it in question_id => answer
          $agent = new TinCan\Agent(
            [ 'mbox' => 'mailto:' . $student_email ]
          );
          $verb = new TinCan\Verb(
            [ 'id' => 'http://adlnet.gov/expapi/verbs/answered' ]
          );

          foreach ($module_details['questions']['reflection'] as $question_id => $question_details) {
            $answers = array();

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
                    array_push($answers, stripslashes($result->getResponse()));
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

            $student_answers[$question_id] = $answers;
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
      $col = 'A';
      foreach ($module_details['questions']['answers'] as $question_id => &$question_details) {
        $question_details['column'] = $col;
        $objWorksheet->setCellValue($col . '2', $question_details['question']);
        $objWorksheet->getStyle($col . '2')
        ->getAlignment()
        ->setWrapText(true);
        $col++;
      }

    # add the feedback

      $highestRow = $objWorksheet->getHighestRow();
      $data_first_row_num = $highestRow + 1;

      $row = $data_first_row_num;

      foreach($module_details['students'] as $learner_email => &$learner_details) {

        # output the feedback

          $student_has_feedback = false;

          foreach ($module_details['questions']['answers'] as $question_id => &$question_details) {
            $col = $question_details['column'];

            if (array_key_exists($question_id, $learner_details['answers'])
                && is_array($learner_details['answers'][$question_id])
                && 1 <= count($learner_details['answers'][$question_id])) {
              $i = 0;
              $count = count($learner_details['answers'][$question_id]);
              $answers_for_question = "";
              $separator = "\n-------\n";

              if (2 <= $count) {
                $answers_for_question .= "(" . $count . " answers recorded)" . $separator;
              }

              foreach($learner_details['answers'][$question_id] as $answer) {
                if ($i >= 1) {
                  $answers_for_question .= $separator;
                }
                $answers_for_question .= $answer; // use the first answer
                $i++;
              }

              if ("" != trim($answers_for_question)) {
                $objWorksheet->setCellValue($col.$row, $answers_for_question);
                $objWorksheet->getStyle($col.$row)
                ->getAlignment()
                ->setWrapText(true);

                $student_has_feedback = true;
              }
            }
          }

        if ($student_has_feedback) {
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

      $col = "A";
      $highestColumn = $objWorksheet->getHighestColumn();
      while ($col <= $highestColumn) {
        $objWorksheet->getColumnDimension($col)->setWidth(52);
        $col++;
      }

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
