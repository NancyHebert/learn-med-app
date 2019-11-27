<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/wp/wp-blog-header.php'; #TODO: Find home path instead of DOCUMENT_ROOT
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Parser;

require_once locate_template('/lib/lrs.php');


# set defaults

  $filename_suffix = ' - Report - ' . date('F j, Y') . '.xlsx';
  $filename = $filename_suffix;

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

# get the list of residents for each rotation

  $yaml = new Parser();

  $residents = $yaml->parse(file_get_contents('residents.yml'));

  # block 1
    # email address
      # name:
      # level:
      # username:
      # program:

# update the filename of the report with the latest block number

  end($residents); // go to the last entry in the array
  $block_num = ucfirst(key($residents));
  $filename = $block_num . $filename;

# check which resident never logged in

  foreach($residents as $block => &$residents_in_block) {
    foreach($residents_in_block as $email => &$details) {

    # check if ever logged-in

      if (!get_user_by('login', $details['username'])
          && !get_user_by('email', $email) ) {
        $details['never_logged_in'] = true;
      }

    # check if email and username on file don't match

      $email_from_username = get_user_by('login', $details['username'])->user_email;

      if (empty($details['never_logged_in'])
          && strtolower($email) != strtolower($email_from_username)) {
        $details['mismatch_email'] = true;
        printf("<strong>Mismatch</strong>: the email from the username %s is %s and it doesn't match the email %s found in the config<br>\n", $details['username'], $email_from_username, $email);
      }

    }
  }


# get the data on modules

  # get the list of modules in order with their names and activity IDs and record who started or completed each module

    $args = array( 'post_type' => 'gb_xapi_content', 'category_name' => 'pediatrics', 'posts_per_page' => 23, 'order' => 'ASC'  );
    $loop = new WP_Query( $args );

    $modules = array();

    while ( $loop->have_posts() ) {
      $loop->the_post();

      $module_title = get_the_title();
      $module_activity_id = get_post_meta(get_the_ID(), 'xapi_activity_id', true);

      # record the module into the array
      $modules[$module_activity_id] = $module_title;

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

          foreach($residents as $block => &$residents_in_block) {

            foreach($residents_in_block as $email => &$details) {

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

                  break; // learner found in list for the current block

                }
            }
          }

      }
  }

  print "<pre>" . Yaml::dump($residents, 6);

# assemble the xls

//  $residents = $residents_example;

//  d($residents);

  $objPHPExcel = new PHPExcel();

  # set the cell caching method

    $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_sqlite3;

    PHPExcel_Settings::setCacheStorageMethod($cacheMethod);

  # for each block, add a worksheet

    $current_worksheet_index = 0;

  foreach($residents as $blockname => &$residents_in_block) {

    if ($current_worksheet_index == 0) {
      $objWorksheet = $objPHPExcel->getActiveSheet();
      $objWorksheet->setTitle($blockname);
    } else {
      $new_worksheet = new PHPExcel_Worksheet($objPHPExcel, $blockname);

      $objPHPExcel->addSheet($new_worksheet, $current_worksheet_index);
      $objWorksheet = $objPHPExcel->getSheet($current_worksheet_index);
    }

  # add the title

    $objWorksheet->setCellValue('A1', 'iLEARN-PEDS COMPLETION REPORT (' . strtoupper($blockname) . ') - Updated: ' . date("j F Y"));

  # add the column names

    $objWorksheet->setCellValue('A2', 'Name');
    $objWorksheet->setCellValue('C2', 'Level');
    $objWorksheet->setCellValue('D2', 'Program');

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

    foreach($residents_in_block as $email => &$details) {

      # output the general information

        $objWorksheet->setCellValue('A'.$row, $details['name']);
        $objWorksheet->setCellValue('B'.$row, strtolower($email));
        $objWorksheet->setCellValue('C'.$row, $details['level']);

        if (!is_array($details['program'])) {
          $objWorksheet->setCellValue('D'.$row, $details['program']);
        } else {
          $objWorksheet->setCellValue('D'.$row, implode(", ",$details['program']));
        }

      # for each module

        $col = "E";

        foreach ($modules as $activity_id => $module_name) {

          if (isset($details['mismatch_email']) && $details['mismatch_email'] == true) {

            $objWorksheet->setCellValue($col.$row, "email is probably wrong");
            $objWorksheet->getStyle($col.$row)->applyFromArray($styles_no_data);

          } else if (isset($details['never_logged_in']) && $details['never_logged_in'] == true) {

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

    }

# style the overall worksheet

  $objWorksheet->getDefaultStyle()->getFont()->setSize(14);

  # style the title

    $objWorksheet->getStyle('A1:'.$objWorksheet->getHighestColumn().'1')->applyFromArray($styles_sheet_title);

  # style the column names

    $objWorksheet->getStyle('A2:'.$objWorksheet->getHighestColumn().'2')->applyFromArray($styles_headers);

    $objWorksheet->getStyle('A3:'.$objWorksheet->getHighestColumn().'3')->applyFromArray($styles_module_name);

  # adjust cell widths
    // https://github.com/PHPOffice/PHPExcel/blob/develop/Documentation/markdown/Overview/08-Recipes.md#setting-a-columns-width

    $objWorksheet->getColumnDimension('A')->setWidth(30);
    $objWorksheet->getColumnDimension('B')->setAutoSize(true);
    $objWorksheet->getColumnDimension('C')->setAutoSize(true);
    $objWorksheet->getColumnDimension('D')->setAutoSize(true);

    for ($col = $modules_first_col; $col <= $objWorksheet->getHighestColumn(); $col++) {
      $objWorksheet->getColumnDimension($col)->setWidth(14);
    }

  # Freeze panes (top headers and left headers)

    $objWorksheet->freezePane('B4');

  # Increment the worksheet number

    $current_worksheet_index++;

  } // foreach block of residents

# write the xls to disk

  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
  $objWriter->save($filename);

# clear the memory

  $objPHPExcel->disconnectWorksheets();

  unset($objPHPExcel);

echo "Saved to $filename\n";

?>
