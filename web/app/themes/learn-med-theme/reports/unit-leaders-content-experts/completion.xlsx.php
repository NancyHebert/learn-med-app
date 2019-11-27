<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/wp/wp-blog-header.php'; #TODO: Find home path instead of DOCUMENT_ROOT
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Parser;

require_once locate_template('/lib/lrs.php');

# set defaults

  $filename = 'Quizzes - Unit Leaders and Content Experts - ' . date('F j, Y') . '.xlsx';
  $report_name_prefix = "Quiz for ";

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

        $is_passed = $date_first_passed = null;

        # call the LRS for the date of the first statement attesting the quiz was passed
        $agent = new TinCan\Agent(
          [ 'mbox' => 'mailto:' . $student_email ]
        );
        $verb_passed = new TinCan\Verb(
          [ 'id' => 'http://adlnet.gov/expapi/verbs/passed' ]
        );
        $activity = new TinCan\Activity(
          [ 'id' =>  $quiz_activity_id ]
        );
        $query_passed_statements = [
          'agent' => $agent,
          'verb'  => $verb_passed,
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

        $is_failed = false;
        $date_last_failed = null;

        if (!$is_passed) {
          $verb_failed = new TinCan\Verb(
            [ 'id' => 'http://adlnet.gov/expapi/verbs/failed' ]
          );
          $query_failed_statements = [
            'agent' => $agent,
            'verb'  => $verb_failed,
            'activity' => $activity,
            'ascending' => 'false' // to get the last 'failed' statement
          ];

          $response_failed_statements = $lrs->queryStatements($query_failed_statements);

          if ($response_failed_statements->success) {
            $failed_statements = $response_failed_statements->content->getStatements();
            if (count($failed_statements) > 0) {
              $is_failed = true;
              $date_last_failed = $failed_statements[0]->getTimestamp();
            } else {
              $is_failed = false;
            }
          } else {
            dd($response_failed_statements);
          }
        }

        $date_first_attempted = null;
        $num_attempts = 0;

        $verb_attempted = new TinCan\Verb(
          [ 'id' => 'http://adlnet.gov/expapi/verbs/attempted' ]
        );

        $query_attempted_statements = [
          'agent' => $agent,
          'verb'  => $verb_attempted,
          'activity' => $activity,
          'ascending' => 'true' // to get the first
        ];

        $response_attempted_statements = $lrs->queryStatements($query_attempted_statements);

        while ($response_attempted_statements->success) {
          $attempted_statements = $response_attempted_statements->content->getStatements();
          $num_attempted_statements = count($attempted_statements);
          $num_attempts += $num_attempted_statements;
          if ($num_attempted_statements > 0) {
            if (is_null($date_first_attempted)) {
              $date_first_attempted = $attempted_statements[0]->getTimestamp();
            }
          }
          if (is_null($response_attempted_statements->content->getMore())) {
            break;
          } else {
            // fetch more
            $response_attempted_statements = $lrs->moreStatements($response_attempted_statements->content->getMore());
          }
        }

        $students[$student_email] = array();
        $student_details = &$students[$student_email];

        $student_details['first_name'] = get_user_meta($wp_user_id, 'first_name', true);
        $student_details['last_name'] = get_user_meta($wp_user_id, 'last_name', true);
        $student_details['is_passed'] = $is_passed;
        $student_details['date_first_passed'] = $date_first_passed;
        $student_details['is_failed'] = $is_failed;
        $student_details['date_last_failed'] = $date_last_failed;
        $student_details['date_first_attempted'] = $date_first_attempted;
        $student_details['num_attempts'] = $num_attempts;
      }

  } // foreach quizzes

# assemble the xls

  $objPHPExcel = new PHPExcel();

  # set the cell caching method

    $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_sqlite3;

    PHPExcel_Settings::setCacheStorageMethod($cacheMethod);

  # for each module, add a worksheet

    $current_worksheet_index = 0;

    foreach($quizzes as $quiz_activity_id => &$quiz_details) {

      if ($current_worksheet_index == 0) {
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $objWorksheet->setTitle(substr($quiz_details['name'], 0, 30));
      } else {
        $new_worksheet = new PHPExcel_Worksheet($objPHPExcel, substr($quiz_details['name'], 0, 30));

        $objPHPExcel->addSheet($new_worksheet, $current_worksheet_index);
        $objWorksheet = $objPHPExcel->getSheet($current_worksheet_index);
      }

    # add the title

      $objWorksheet->setCellValue('A1', strtoupper($quiz_details['name']) . ' - Updated: ' . date("j F Y"));

    # add the column names

      $objWorksheet->setCellValue('A2', 'Name');
      $objWorksheet->setCellValue('B2', 'Email');


      $col = $objWorksheet->getHighestColumn();
      $col++;
      $quiz_details['column'] = $col;

      $objWorksheet->setCellValue($col . '2', 'Pass/Fail/Started');
      $col++;
      $objWorksheet->setCellValue($col . '2', 'Date first passed, latest failed');
      $col++;
      $objWorksheet->setCellValue($col . '2', 'Date of first attempt');
      $col++;
      $objWorksheet->setCellValue($col . '2', 'Number of attempts');

    # add the students

      $highestRow = $objWorksheet->getHighestRow();
      $data_first_row_num = $highestRow + 1;

      $row = $data_first_row_num;

      foreach($quiz_details['students'] as $student_email => &$student_details) {

        # output the general information

          $objWorksheet->setCellValue('A'.$row, $student_details['first_name'] . ' ' . $student_details['last_name']);
          $objWorksheet->setCellValue('B'.$row, strtolower($student_email));

          $col = 'C';

        # Pass/Fail/Started

          if ($student_details['is_passed'] == "TRUE") {
            $objWorksheet->setCellValue($col.$row, "Passed");
            $objWorksheet->getStyle($col.$row)->applyFromArray($styles_status_passed);
          } else if ($student_details['is_failed'] == "TRUE") {
            $objWorksheet->setCellValue($col.$row, "Failed");
            $objWorksheet->getStyle($col.$row)->applyFromArray($styles_status_failed);
          } else if ($student_details['num_attempts'] > 0){
            $objWorksheet->setCellValue($col.$row, "Started");
            $objWorksheet->getStyle($col.$row)->applyFromArray($styles_status_started);
          } else {
            $objWorksheet->setCellValue($col.$row, "Not Started");
            $objWorksheet->getStyle($col.$row)->applyFromArray($styles_status_not_started);
          }

          $col++;

        # Date first passed, latest failed

          $date_passed_failed = null;

          if ($student_details['date_first_passed'] != null) {
            $date_passed_failed = strtotime($student_details['date_first_passed']);
          } else if ($student_details['date_last_failed'] != null) {
            $date_passed_failed = strtotime($student_details['date_last_failed']);
          }

          if ($date_passed_failed != null) {
            $objWorksheet->setCellValue($col.$row, PHPExcel_Shared_Date::PHPToExcel($date_passed_failed));
            $objWorksheet->getStyle($col.$row)
            ->getNumberFormat()
            ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
            $objWorksheet->getStyle($col.$row)
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
          }

          $col++;

        # Date of first attempt

          if ($student_details['date_first_attempted'] != null) {
            $date_first_attempted = strtotime($student_details['date_first_attempted']);
            $objWorksheet->setCellValue($col.$row, PHPExcel_Shared_Date::PHPToExcel($date_first_attempted));
            $objWorksheet->getStyle($col.$row)
            ->getNumberFormat()
            ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
            $objWorksheet->getStyle($col.$row)
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
          }

          $col++;

        # Number of attempts

          $objWorksheet->setCellValue($col.$row, $student_details['num_attempts']);

        # increment to the new row number

          $row++;

      }

    # style the overall worksheet

      $objWorksheet->getDefaultStyle()->getFont()->setSize(14);

    # style the title

      $objWorksheet->getStyle('A1:'.$objWorksheet->getHighestColumn().'1')->applyFromArray($styles_sheet_title);

    # style the column names

      $objWorksheet->getStyle('A2:'.$objWorksheet->getHighestColumn().'2')->applyFromArray($styles_headers);

      # Freeze panes (top headers)

      $objWorksheet->freezePane('C3');

      # Set an auto-filter on the set, so the headers can be used to sort the data

      $objWorksheet->setAutoFilter('A2:' . $objWorksheet->getHighestColumn() . $objWorksheet->getHighestRow());

          # adjust cell widths
      // https://github.com/PHPOffice/PHPExcel/blob/develop/Documentation/markdown/Overview/08-Recipes.md#setting-a-columns-width

      $objWorksheet->getColumnDimension('A')->setWidth(24);
      $objWorksheet->getColumnDimension('B')->setAutoSize(true);
      $col = "C";
      $highestColumn = $objWorksheet->getHighestColumn();
      while ($col <= $highestColumn) {
        $objWorksheet->getColumnDimension($col)->setAutoSize(true);
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
