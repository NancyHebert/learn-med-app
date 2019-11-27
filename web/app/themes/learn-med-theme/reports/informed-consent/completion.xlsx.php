<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/wp/wp-blog-header.php'; #TODO: Find home path instead of DOCUMENT_ROOT
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Parser;

require_once locate_template('/lib/lrs.php');

# set defaults

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

# get the list of modules to report on

  $config = $yaml->parse(file_get_contents('config.yml'));

  $filename = sprintf($config['reports']['completion']['filename'], date('F j, Y'));

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
          $student_details['date_completed'] = $learner['completion_date'];
          // $student_details['hospital'] = get_user_meta($wp_user_id, 'Hospital', true);
          // if (!$student_details['other_company']) { $student_details['other_company'] = ""; }
        }

      }

  } // foreach module

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

    $objWorksheet->setCellValue('A1', $module_details['name'] . ' - Updated: ' . date("j F Y"));

  # add the column names

    $objWorksheet->setCellValue('A2', 'First Name');
    $objWorksheet->setCellValue('B2', 'Last Name');
    $objWorksheet->setCellValue('C2', 'Email');
    $objWorksheet->setCellValue('D2', 'Date of completion');
    // $objWorksheet->setCellValue('E2', 'Company/Hospital');
    // $objWorksheet->setCellValue('F2', '> Other');

    $n = 1;

  # add the students

    $highestRow = $objWorksheet->getHighestRow();
    $data_first_row_num = $highestRow + 1;

    $row = $data_first_row_num;

    foreach($module_details['students'] as $student_email => &$student_details) {

      # output the general information

        $objWorksheet->setCellValue('A'.$row, $student_details['first_name']);
        $objWorksheet->setCellValue('B'.$row, $student_details['last_name']);

        $objWorksheet->setCellValue('C'.$row, strtolower($student_email));

        $timestamp_completed = strtotime($student_details['date_completed']);
        $objWorksheet->setCellValue('D'.$row, PHPExcel_Shared_Date::PHPToExcel($timestamp_completed));
        $objWorksheet->getStyle('D'.$row)
            ->getNumberFormat()
            ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
        // $objWorksheet->setCellValue('E'.$row, $student_details['hospital']);
        // $objWorksheet->setCellValue('F'.$row, $student_details['other_company']);


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

    $objWorksheet->getColumnDimension('A')->setWidth(24);
    $objWorksheet->getColumnDimension('B')->setWidth(24);
    $objWorksheet->getColumnDimension('C')->setAutoSize(true);
    $objWorksheet->getColumnDimension('D')->setAutoSize(true);
    // $objWorksheet->getColumnDimension('E')->setAutoSize(true);
    // $objWorksheet->getColumnDimension('F')->setAutoSize(true);

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

$all_meta_for_user = get_user_meta(2314);
print_r( $all_meta_for_user );


?>
