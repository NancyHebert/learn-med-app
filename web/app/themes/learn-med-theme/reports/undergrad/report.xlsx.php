<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/wp/wp-blog-header.php'; #TODO: Find home path instead of DOCUMENT_ROOT
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Parser;

require_once locate_template('/lib/lrs.php');


# set defaults

  define("STATUS_COMPLETED", 'completed');
  define("STATUS_STARTED", 'started');

  require_once 'xlsx.styles.php';

# prepare connections to the LRS

  $lrs_endpoint = get_option('grassblade_tincan_endpoint');
  $lrs_username = get_option('grassblade_tincan_user');
  $lrs_password = get_option('grassblade_tincan_password');

  # if LRS settings aren't set, return the default grassblade shortcode
  if (!$lrs_endpoint || !$lrs_username || !$lrs_password) {
    die('LRS not configured');
  }

  $API_url = $lrs_endpoint . '/_api/completionreport';

# load the config

  $yaml = new Parser();

  $config = $yaml->parse(file_get_contents('config.yml'));

  $filename = $config["report_name"] . ' Professionalism Series Report - ' . date('F j, Y') . '.xlsx';

  $students = $config["students"];

  # PGY1
    # email address
      # name:
      # level:
      # username:
      # program:

# check which resident never logged in

  foreach($students as $email => &$details) {
    //foreach($students_in_year as $email => &$details) {

    # check if ever logged-in

      if (!get_user_by('login', $details['username'])
          && !get_user_by('email', $email) ) {
        $details['never_logged_in'] = true;
      }
    //}
  }


# get the data on modules

  # get the list of modules in order with their names and activity IDs and record who started or completed each module

    $args = array( 'post_type' => 'gb_xapi_content', 'category_name' => 'UndergradStoryline', 'posts_per_page' => 20, 'order' => 'ASC'  );
    $loop = new WP_Query( $args );

    $modules = array();

    while ( $loop->have_posts() ) {
      $loop->the_post();

      $module_title = get_the_title();
      $module_activity_id = get_post_meta(get_the_ID(), 'xapi_activity_id', true);

      # record the module into the array
      $modules[$module_activity_id] = $module_title;
      $modules[$module_activity_id] = $date;

      # retrieve the list of users from the LRS who have data on that activity id

        # call the LRS

          $response = CallAPI("GET", $API_url, $lrs_username, $lrs_password, array('activity' => $module_activity_id, 'all' => 'true'  ));

        # if no response or an error has occurred, default to the grassblade shortcode

          if (!$response) {
            die("Can't get data on $module_activity_id");
          }

        # parse the returning JSON

          $response = json_decode($response, true);

          d($response);

      # save the data on each learner found, if part of the list of residents

      foreach($response as $learner) {

        # for each resident

          foreach($students as $email => &$details) {

            //foreach($students_in_year as $email => &$details) {

              # create modules list if not already created

                if (!isset($details['modules'])) {
                  $details['modules'] = array();
                }

              # check if the learner is found in the residents list

                if (isset($learner['agent']['mbox']) && "mailto:".strtolower($email) == strtolower($learner['agent']['mbox'])) {

                  echo "found $email\n<br>";

                  # if the user is in the array and has an entry in the completion property or in the success property, mark as completed

                    if (isset($learner['completion']) && true == $learner['completion']) {
                      $details['modules'][$module_activity_id] = STATUS_COMPLETED;
                    }

                  # if the user is in the array and has no entries in the completion property, mark as started

                    else {
                      $details['modules'][$module_activity_id] = STATUS_STARTED;
                    }

                  break; // learner found in list for the current year

                }
            }
          }

      }


  print "<pre>" . Yaml::dump($students, 6);

# assemble the xls

//  $residents = $residents_example;

//  d($residents);

  $objPHPExcel = new PHPExcel();

  # set the cell caching method

    $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_sqlite3;

    PHPExcel_Settings::setCacheStorageMethod($cacheMethod);

    $objWorksheet = $objPHPExcel->getActiveSheet();
    $objWorksheet->setTitle($config["report_name"]);

  # add the title

    $objWorksheet->setCellValue('A1', 'PROFESSIONALISM SERIES COMPLETION REPORT - Updated: ' . date("j F Y"));

  # add the column names

    $objWorksheet->setCellValue('A2', 'Year');
    $objWorksheet->setCellValue('B2', 'Name');
    $objWorksheet->setCellValue('C2', 'Email');

    $modules_first_col = $objWorksheet->getHighestColumn();
    $modules_first_col++;

    $col = $modules_first_col;
    $n = 1;

  # add the module names

    foreach ($modules as $activity_id => $module_name) {
      $objWorksheet->setCellValue($col.'2', 'Module '.$n);
      $objWorksheet->setCellValue($col.'3', $module_name);

      $col++;
      $n++;
    }

    $highestRow = $objWorksheet->getHighestRow();
    $data_first_row_num = $highestRow + 1;

    $row = $data_first_row_num;

  # for each resident
    foreach($students as $email => &$details) {

      //preg_match('/([1-9][0-9]*)$/', $yearname, $matches);
      //$year_num = (isset($matches[1]))? $matches[1] : "n/a";

      //foreach($students as $email => &$details) {

        # output the general information

          $objWorksheet->setCellValue('A'.$row, $year_num);
          $objWorksheet->getStyle('A'.$row)->getNumberFormat()->applyFromArray(array(
            'code' => '"PGY"#'
          ));
          $objWorksheet->getStyle('A'.$row)->applyFromArray(array(
            'alignment' => array(
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
            )
          ));

          $objWorksheet->setCellValue('B'.$row, $details['name']);
          $objWorksheet->setCellValue('C'.$row, strtolower($email));


        # for each module

          $col = "D";

          foreach ($modules as $activity_id => $module_name) {

            if (isset($details['never_logged_in']) && $details['never_logged_in'] == true) {

              $objWorksheet->setCellValue($col.$row, "never logged in");
              $objWorksheet->getStyle($col.$row)->applyFromArray($styles_no_data);

            } else if (isset($details['modules']) && isset($details['modules'][$activity_id])) {

              $status = $details['modules'][$activity_id];

              # output whether it was completed or started or not

                if ($status == STATUS_COMPLETED) {

                  $objWorksheet->setCellValue($col.$row, "COMPLETED");
                  $objWorksheet->getStyle($col.$row)->applyFromArray($styles_status_completed);

                } else if ($status == STATUS_STARTED) {

                  $objWorksheet->setCellValue($col.$row, "STARTED");
                  $objWorksheet->getStyle($col.$row)->applyFromArray($styles_status_started);

                }
            } else {

              $objWorksheet->setCellValue($col.$row, "not started");
              $objWorksheet->getStyle($col.$row)->applyFromArray($styles_status_unknown);

            }

            # increment to the new col number

              $col++;

          }

        # increment to the new row number

          $row++;

      //} // foreach residents in year

    } // foreach year

  # style the overall worksheet

    $objWorksheet->getDefaultStyle()->getFont()->setSize(14);

  # style the title

    $objWorksheet->getStyle('A1:'.$objWorksheet->getHighestColumn().'1')->applyFromArray($styles_sheet_title);

  # style the column names

    $objWorksheet->getStyle('A2:'.$objWorksheet->getHighestColumn().'2')->applyFromArray($styles_headers);

    $objWorksheet->getStyle('A3:'.$objWorksheet->getHighestColumn().'3')->applyFromArray($styles_module_name);

  # adjust cell widths
    // https://github.com/PHPOffice/PHPExcel/blob/develop/Documentation/markdown/Overview/08-Recipes.md#setting-a-columns-width

    $objWorksheet->getColumnDimension('A')->setWidth(6);
    $objWorksheet->getColumnDimension('B')->setAutoSize(true);
    $objWorksheet->getColumnDimension('C')->setAutoSize(true);

    for ($col = $modules_first_col; $col <= $objWorksheet->getHighestColumn(); $col++) {
      $objWorksheet->getColumnDimension($col)->setWidth(14);
    }

  # Freeze panes (top headers and left headers)

    $objWorksheet->freezePane('D4');

# write the xls to disk

  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
  $objWriter->save($filename);

# clear the memory

  $objPHPExcel->disconnectWorksheets();

  unset($objPHPExcel);

echo "Saved to $filename\n";

?>