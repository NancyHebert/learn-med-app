<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/wp/wp-blog-header.php'; #TODO: Find home path instead of DOCUMENT_ROOT
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Parser;

require_once locate_template('/lib/lrs.php');

# set defaults

  $filename = 'Completion - Orientation Series - ' . date('F j, Y') . '.xlsx';
  $report_name = "New Faculty Report";

  define("STATUS_COMPLETED", 'completed');

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
  $API_url_module_completion = $lrs_endpoint . '_api/completionreport';

  # prepare connection for querying for statements

  $lrs = new TinCan\RemoteLRS(
    $lrs_endpoint,
    '1.0.1',
    $lrs_username,
    $lrs_password
  );

# get the list of modules to report on

  $config = $yaml->parse(file_get_contents('config.yml'));

  $quizzes = &$config['quizzes'];
  $modules = &$config['modules'];

# test whether to use the LRS (preferable) or learndash's data in Wordpress to determine completion

  foreach($quizzes as $quiz_activity_id => &$quiz_details) {

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

    # store in $quizzes[module_activity_id][students]

      $quiz_details['students'] = array();
      $students = &$quiz_details['students'];

      foreach($response_score_results['results'] as $learner) {

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

        # skip if user is admin
        if (user_can($wp_user_id, 'manage_options' ) ) {
          continue;
        }

        $max_score = $is_passed = $date_first_passed = null;
        $i = 0;

        foreach($learner['scaledScore'] as $score) {
          if ($max_score == null || $score > $max_score) {
            $max_score = $score;
          }
          $i++;
        }

        # call the LRS for the date of the first statement attesting the quiz was passed
        $agent = new TinCan\Agent(
          [ 'mbox' => 'mailto:' . $student_email ]
        );
        $verb = new TinCan\Verb(
          [ 'id' => 'http://adlnet.gov/expapi/verbs/passed' ]
        );
        $activity = new TinCan\Activity(
          [ 'id' =>  $quiz_activity_id ]
        );
        $query_passed_statements = [
          'agent' => $agent,
          'verb'  => $verb,
          'activity' => $activity,
          'ascending' => 'true' // to get the first
        ];

        $response_passed_statements = $lrs->queryStatements($query_passed_statements);

        if ($response_passed_statements->success) {
          $passed_statements = $response_passed_statements->content->getStatements();
          if (count($passed_statements) > 0) {
            $is_passed = true;
            $date_first_passed = $passed_statements[0]->getTimestamp();
          } else {
            $is_passed = false;
          }
        } else {
          dd($response_passed_statements);
        }

        $students[$student_email] = array();
        $student_details = &$students[$student_email];

        $student_details['first_name'] = get_user_meta($wp_user_id, 'first_name', true);
        $student_details['last_name'] = get_user_meta($wp_user_id, 'last_name', true);
        $student_details['department'] = get_user_meta($wp_user_id, 'Department', true);
        $student_details['is_passed'] = $is_passed;
        $student_details['max_score'] = $max_score;
        $student_details['date_first_passed'] = $date_first_passed;
      }

  } // foreach quizzes

  foreach($modules as $module_activity_id => &$module_details) {

    # call the LRS for the activity id

      $response_module_completion = CallAPI("GET", $API_url_module_completion, $lrs_username, $lrs_password, array('activity' => $module_activity_id, 'all' => 'true'));

    # if no response or an error has occurred, throw an error

      if (!$response_module_completion) {
        die("Can't get data on $module_activity_id");
      }

    # parse the returning JSON

      $response_module_completion = json_decode($response_module_completion, true);

    # store in $quizzes[module_activity_id][students]

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

          # skip if user is admin
          // if (user_can($wp_user_id, 'manage_options' ) ) {
          //   continue;
          // }

          $students[$student_email] = array();
          $student_details = &$students[$student_email];

          $student_details['first_name'] = get_user_meta($wp_user_id, 'first_name', true);
          $student_details['last_name'] = get_user_meta($wp_user_id, 'last_name', true);
          $student_details['department'] = get_user_meta($wp_user_id, 'Department', true);
          $student_details['date_completed'] = $learner['completion_date'];
        }
    }

  } // foreach module

# merge the modules and the quizzes into a $learners array

  $learners = array();

  foreach ($quizzes as $quiz_activity_id => &$quiz_details) {
    foreach ($quiz_details['students'] as $student_email => &$student_details) {
      if(!array_key_exists($student_email, $learners)) {
        $learners[$student_email] = array(
          'first_name' => $student_details['first_name'],
          'last_name' => $student_details['last_name'],
          'department' => $student_details['department'],
          'modules' => array(),
          'quizzes' => array(),
        );
      }
      $learners[$student_email]['quizzes'][$quiz_activity_id] = array(
        'is_passed' => $student_details['is_passed'],
        'max_score' => $student_details['max_score'],
        'date_first_passed' => $student_details['date_first_passed']
      );
    }
  }
  foreach ($modules as $module_activity_id => &$module_details) {
    foreach ($module_details['students'] as $student_email => &$student_details) {
      if(!array_key_exists($student_email, $learners)) {
        $learners[$student_email] = array(
          'first_name' => $student_details['first_name'],
          'last_name' => $student_details['last_name'],
          'department' => $student_details['department'],
          'modules' => array(),
          'quizzes' => array(),
        );
      }
      $learners[$student_email]['modules'][$module_activity_id] = array(
        'date_completed' => $student_details['date_completed']
      );
    }
  }

# determine if the learner completed all requirements

  $num_required_quizzes = 1;
  $num_required_modules = count($modules);

  # also separately track a list of learners who completed all requirements
  $completion_history = array();

  foreach($learners as $learner_email => &$learner_details) {

    $latest_completion_date = null;

    $count_passed_quizzes = $count_passed_modules = 0;
    foreach($learner_details['quizzes'] as $quiz_activity_id => &$quiz_details) {
      if ($quiz_details['is_passed']) {
        $count_passed_quizzes++;
      }
      if ($quiz_details['date_first_passed'] != null) {
        if (is_null($latest_completion_date) || cmp_iso8601_dates($quiz_details['date_first_passed'], $latest_completion_date)) {
          $latest_completion_date = $quiz_details['date_first_passed'];
        }
      }
    }
    foreach($learner_details['modules'] as $module_activity_id => &$module_details) {
      if ($module_details['date_completed'] != null) {
        $count_passed_modules++;

        if (is_null($latest_completion_date) || cmp_iso8601_dates($module_details['date_completed'], $latest_completion_date)) {
          $latest_completion_date = $module_details['date_completed'];
        }
      }
    }

    $learner_details['completed_all_requirements'] = false;
    if ($count_passed_quizzes >= $num_required_quizzes && $count_passed_modules >= $num_required_modules) {
      $learner_details['completed_all_requirements'] = true;
      $learner_details['completion_date'] = $latest_completion_date;
      $completion_history[$learner_email] = $learner_details;
    }
  }

# sort the learners by last name

  $is_sorted = uasort($learners, 'cmp_learners_by_last_name');

  if (!$is_sorted) {
    die("error trying to sort learner array");
  }

# assemble a history of when learners completed all requirements

  # sort completion_history by the latest of the dates of all the modules and quizzes

  $is_sorted = uasort($completion_history, 'cmp_learners_by_completion_dates');

  if (!$is_sorted) {
    die("error trying to sort completion_history array");
  }

# assemble the xls

  $objPHPExcel = new PHPExcel();

  # set the cell caching method

    $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_sqlite3;

    PHPExcel_Settings::setCacheStorageMethod($cacheMethod);

    $objWorksheet = $objPHPExcel->getActiveSheet();
    $objWorksheet->setTitle(substr($report_name, 0, 30));

  # add the title

    $objWorksheet->setCellValue('A1', strtoupper($report_name) . ' - Updated: ' . date("j F Y"));

  # add the column names

    $objWorksheet->setCellValue('A3', 'Name');
    $objWorksheet->setCellValue('B3', 'Email');
    $objWorksheet->setCellValue('C3', 'Department');
    $objWorksheet->setCellValue('D3', 'Completed all requirements?');
    $objWorksheet->setCellValue('E3', 'Date all completed');

    foreach ($quizzes as $quiz_activity_id => &$quiz_details) {
      $col = $objWorksheet->getHighestColumn();
      $col++;
      $quiz_details['column'] = $col;
      $objWorksheet->setCellValue($col . '2', $quiz_details['name']);

      $objWorksheet->setCellValue($col . '3', 'Passed?');
      $col++;
      $objWorksheet->setCellValue($col . '3', 'Date first passed');
      $col++;
      $objWorksheet->setCellValue($col . '3', 'Max score');
    }

    foreach ($modules as $module_activity_id => &$module_details) {
      $col = $objWorksheet->getHighestColumn();
      $col++;
      $module_details['column'] = $col;
      $objWorksheet->setCellValue($col . '2', $module_details['name']);
      $objWorksheet->setCellValue($col . '3', 'Date completed');
    }

  # add the students

    $highestRow = $objWorksheet->getHighestRow();
    $data_first_row_num = $highestRow + 1;

    $row = $data_first_row_num;

    foreach($learners as $learner_email => &$learner_details) {

      # output the general information

        $objWorksheet->setCellValue('A'.$row, $learner_details['first_name'] . ' ' . $learner_details['last_name']);
        $objWorksheet->setCellValue('B'.$row, strtolower($learner_email));
        $objWorksheet->setCellValue('C'.$row, $learner_details['department']);
        if($learner_details['completed_all_requirements']) {
          $objWorksheet->setCellValue('D'.$row, "Completed");
          $objWorksheet->getStyle('D'.$row)->applyFromArray($styles_status_completed);
        }
        if($learner_details['completion_date'] != null) {
          $date_all_completed = strtotime($learner_details['completion_date']);
          $objWorksheet->setCellValue('E'.$row, PHPExcel_Shared_Date::PHPToExcel($date_all_completed));
          $objWorksheet->getStyle('E'.$row)
          ->getNumberFormat()
          ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
          $objWorksheet->getStyle('E'.$row)
          ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
          $objWorksheet->getStyle('E'.$row)->applyFromArray($styles_status_completed);
        }

        foreach ($quizzes as $quiz_activity_id => &$quiz_details) {
          $col = $quiz_details['column'];

          $quiz_of_learner = null;

          if (array_key_exists($quiz_activity_id, $learner_details['quizzes'])) {
            $quiz_of_learner = $learner_details['quizzes'][$quiz_activity_id];

            $objWorksheet->setCellValue($col.$row, ($quiz_of_learner['is_passed'] == "TRUE")? "Passed": "Failed");

            $col++;
            if ($quiz_of_learner['date_first_passed'] != null) {
              $date_first_passed_timestamp = strtotime($quiz_of_learner['date_first_passed']);
              $objWorksheet->setCellValue($col.$row, PHPExcel_Shared_Date::PHPToExcel($date_first_passed_timestamp));
              $objWorksheet->getStyle($col.$row)
              ->getNumberFormat()
              ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
              $objWorksheet->getStyle($col.$row)
              ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            }

            $col++;
            $objWorksheet->setCellValue($col.$row, $quiz_of_learner['max_score']);
            $objWorksheet->getStyle($col.$row, $quiz_of_learner['max_score'])
            ->getNumberFormat()
            ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE);

          }

          foreach ($modules as $module_activity_id => &$module_details) {
            $col = $module_details['column'];

            if (array_key_exists($module_activity_id, $learner_details['modules'])) {
              $module_of_learner = $learner_details['modules'][$module_activity_id];

              $date_completed_timestamp = strtotime($module_of_learner['date_completed']);
              $objWorksheet->setCellValue($col.$row, PHPExcel_Shared_Date::PHPToExcel($date_completed_timestamp));
              $objWorksheet->getStyle($col.$row)
              ->getNumberFormat()
              ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
              $objWorksheet->getStyle($col.$row)
              ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            }
          }

        }

      # increment to the new row number

        $row++;

    }

  # style the overall worksheet

    $objWorksheet->getDefaultStyle()->getFont()->setSize(14);

  # style the title

    $objWorksheet->getStyle('A1:'.$objWorksheet->getHighestColumn().'1')->applyFromArray($styles_sheet_title);

  # style the column names

    $objWorksheet->getStyle('A2:'.$objWorksheet->getHighestColumn().'2')->applyFromArray($styles_module_quiz_names);
    $objWorksheet->getStyle('A3:'.$objWorksheet->getHighestColumn().'3')->applyFromArray($styles_headers);

    # Freeze panes (top headers)

    $objWorksheet->freezePane('D4');

    # Set an auto-filter on the set, so the headers can be used to sort the data

    $objWorksheet->setAutoFilter('A3:' . $objWorksheet->getHighestColumn() . $objWorksheet->getHighestRow());

        # adjust cell widths
    // https://github.com/PHPOffice/PHPExcel/blob/develop/Documentation/markdown/Overview/08-Recipes.md#setting-a-columns-width

    $objWorksheet->getColumnDimension('A')->setWidth(24);
    $objWorksheet->getColumnDimension('B')->setAutoSize(true);
    $objWorksheet->getColumnDimension('C')->setAutoSize(true);
    $objWorksheet->getColumnDimension('D')->setAutoSize(true);
    $objWorksheet->getColumnDimension('E')->setAutoSize(true);
    $col = "F";
    $highestColumn = $objWorksheet->getHighestColumn();
    while ($col <= $highestColumn) {
      $objWorksheet->getColumnDimension($col)->setAutoSize(true);
      $col++;
    }

  # Increment the worksheet number

    $current_worksheet_index++;


  # write the xls to disk

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
    $objWriter->save($filename);

  # clear the memory

    $objPHPExcel->disconnectWorksheets();

    unset($objPHPExcel);

echo "Saved to $filename\n";

function cmp_learners_by_last_name($a, $b) {
  if ($a['last_name'] == $b['last_name']) {
    return 0;
  }
  return ($a['last_name'] < $b['last_name']) ? -1 : 1;
}

function cmp_learners_by_completion_dates($a, $b) {

  if ($a['completion_date'] == $b['completion_date']) {
    return 0;
  }
  return ($a['completion_date'] < $b['completion_date']) ? -1 : 1;
}

function cmp_iso8601_dates($a, $b) {
  if (strtotime(date($a)) == strtotime(date($b))) {
    return 0;
  }
  return (strtotime(date($a)) < strtotime(date($b))) ? -1 : 1;
}

?>
