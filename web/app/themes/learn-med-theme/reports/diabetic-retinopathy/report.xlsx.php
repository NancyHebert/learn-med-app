<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/wp/wp-blog-header.php'; #TODO: Find home path instead of DOCUMENT_ROOT
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Parser;

require_once locate_template('/lib/lrs.php');

# set defaults

  $filename = 'Report - Diabetic Retinopathy - ' . date('F j, Y') . '.xlsx';

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

  $API_url = $lrs_endpoint . '/_api/completionreport';

  $lrs = new TinCan\RemoteLRS(
    $lrs_endpoint,
    '1.0.0',
    $lrs_username,
    $lrs_password
  );

# get the list of modules to report on

  $config = $yaml->parse(file_get_contents('config.yml'));

  $modules = &$config['modules'];

  # modules
    # module_activity_id
      # name

# test whether to use the LRS (preferable) or learndash's data in Wordpress to determine completion

  foreach($modules as $module_activity_id => &$module_details) {

    # call the LRS for the activity id

      $response = CallAPI("GET", $API_url, $lrs_username, $lrs_password, array('activity' => $module_activity_id, 'all' => 'true' ));

    # if no response or an error has occurred, throw an error

      if (!$response) {
        die("Can't get data on $module_activity_id");
      }

    # parse the returning JSON

      $response = json_decode($response, true);

    # store in $modules[module_activity_id][students]

      $module_details['students'] = array();
      $students = &$module_details['students'];

      foreach($response as $learner) {

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

        $students[$student_email] = array();
        $student_details = &$students[$student_email];

        $student_details['first_name'] = get_user_meta($wp_user_id, 'first_name', true);
        $student_details['last_name'] = get_user_meta($wp_user_id, 'last_name', true);

        if (isset($learner['completion']) && true == $learner['completion']) {
          $student_details['date_completed'] = $learner['completion_date'];
        } else {
          $student_details['date_completed'] = null;
          $student_details['furthest_step_completed'] = get_furthest_step_completed($student_email, $module_activity_id);
        }

        // get date of first use

        $student_details['date_first_use'] = get_date_first_use($student_email, $module_activity_id);

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
      $objWorksheet->setTitle(substr($module_details['name'], 0, 30));
    } else {
      $new_worksheet = new PHPExcel_Worksheet($objPHPExcel, substr($module_details['name'], 0, 30));

      $objPHPExcel->addSheet($new_worksheet, $current_worksheet_index);
      $objWorksheet = $objPHPExcel->getSheet($current_worksheet_index);
    }

  # add the title

    $objWorksheet->setCellValue('A1', strtoupper($module_details['name']) . ' - Updated: ' . date("j F Y"));

  # add the column names

    $objWorksheet->setCellValue('A2', 'Name');
    $objWorksheet->setCellValue('B2', 'Email');
    $objWorksheet->setCellValue('C2', 'Date of registration (first use)');
    $objWorksheet->setCellValue('D2', 'Date of completion');

    $n = 1;

  # add the students

    $highestRow = $objWorksheet->getHighestRow();
    $data_first_row_num = $highestRow + 1;

    $row = $data_first_row_num;

    foreach($module_details['students'] as $student_email => &$student_details) {

      # output the general information

        $objWorksheet->setCellValue('A'.$row, $student_details['first_name'] . ' ' . $student_details['last_name']);
        $objWorksheet->setCellValue('B'.$row, strtolower($student_email));

        $timestamp_first_use = strtotime($student_details['date_first_use']);
        $objWorksheet->setCellValue('C'.$row, PHPExcel_Shared_Date::PHPToExcel($timestamp_first_use));
        $objWorksheet->getStyle('C'.$row)
            ->getNumberFormat()
            ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);

        if (!is_null($student_details['date_completed'])) {
          $timestamp_completed = strtotime($student_details['date_completed']);
          $objWorksheet->setCellValue('D'.$row, PHPExcel_Shared_Date::PHPToExcel($timestamp_completed));
          $objWorksheet->getStyle('D'.$row)
              ->getNumberFormat()
              ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
        } else {
          $objWorksheet->setCellValue('D'.$row, "stopped at '"
            . $student_details['furthest_step_completed']->getTarget()->getDefinition()->getName()->asVersion('')['en-US'] . "'"
          );
          $objWorksheet->getStyle('D'.$row)->applyFromArray($styles_no_data);
        }

      # increment to the new row number

        $row++;

    }

  # style the overall worksheet

    $objWorksheet->getDefaultStyle()->getFont()->setSize(14);

  # style the title

    $objWorksheet->getStyle('A1:'.$objWorksheet->getHighestColumn().'1')->applyFromArray($styles_sheet_title);

  # style the column names

    $objWorksheet->getStyle('A2:'.$objWorksheet->getHighestColumn().'2')->applyFromArray($styles_headers);


  # adjust cell widths
    // https://github.com/PHPOffice/PHPExcel/blob/develop/Documentation/markdown/Overview/08-Recipes.md#setting-a-columns-width

    $objWorksheet->getColumnDimension('A')->setWidth(18);
    $objWorksheet->getColumnDimension('B')->setAutoSize(true);
    $objWorksheet->getColumnDimension('C')->setAutoSize(true);
    $objWorksheet->getColumnDimension('D')->setAutoSize(true);

  # Freeze panes (top headers)

    $objWorksheet->freezePane('A3');

  # Set an auto-filter on the set, so the headers can be used to sort the data

    $objWorksheet->setAutoFilter('A2:' . $objWorksheet->getHighestColumn() . $objWorksheet->getHighestRow());

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

function get_date_first_use($email, $activity_id) {

  global $lrs;

  $response = $lrs->queryStatements([
    'agent' => new TinCan\Agent(['mbox' => 'mailto:'.$email]),
    'activity' => new TinCan\Activity(['id' => $activity_id]),
    'ascending' => 'true',
    'by_timestamp' => 'true',
    'include_agent_overrides' => 'true',
    'related_agents' => 'true',
  ]);

  $statements = $response->content->getStatements();

  if (0 == count($statements)) {
    return null;
  } else {
    return $statements[0]->getTimestamp();
  }

}

function get_furthest_step_completed($email, $activity_id) {

  global $lrs;

  // TODO: cycle through the lessons of the module, from the last one to the first one, and check if the person completed them. Return the first one found.

    // get the list of lessons in the module from LearnDash, in descending order

      // get the $postid for the course, given the $activity_id
      // fetch the list of lessons for the course
        // $lessons = learndash_get_lesson_list($postid);

    // call the LRS and assemble all statements made on that activity ID matching the 'completed' verb, following each 'more' link
      // if no statements returned, return null

    // cycle through step of the module, in descending order, and go through the whole list of statements from the LRS

    // return the first step found

  $response = $lrs->queryStatements([
    'agent' => new TinCan\Agent(['mbox' => 'mailto:'.$email]),
    'activity' => new TinCan\Activity(['id' => $activity_id]),
    'by_timestamp' => 'true',
    'related_activities' => 'true',
    'include_agent_overrides' => 'true',
    'related_agents' => 'true',
  ]);

  $statements = $response->content->getStatements();

  $is_furthest_step_found = false;
  $i = 0;

  while ($i < count($statements) && !$is_furthest_step_found) {
    $statement = $statements[$i];
    if ($statement->getContext()
        && $statement->getContext()->getContextActivities()
        && $statement->getContext()->getContextActivities()->getParent()
       ) {
      $is_furthest_step_found = true;
      return $statement;
    }
  }

  return null;

}

?>
