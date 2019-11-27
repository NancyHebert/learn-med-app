<?php 

define('WP_USE_THEMES', false);
//require_once $_SERVER['DOCUMENT_ROOT'] . '/wp/wp-blog-header.php'; #TODO: Find home path instead of DOCUMENT_ROOT
include_once ($_SERVER['DOCUMENT_ROOT'].'/wp/wp-blog-header.php' );
include_once ($_SERVER['DOCUMENT_ROOT'].'/wp/wp-load.php' );


get_template_part('templates/head');


#TODO: ajoputer toute la page . /////  exiger des acces admin //////// 

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Parser;

require_once locate_template('/lib/lrs.php');

# set defaults

 $date = date("d-m-Y");
 $filename = $date.'.xlsx';

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
          
          # skip if user is admin
          if (user_can($wp_user_id, 'manage_options' ) ) {
            continue;
          }

          $students[$student_email] = array();
          $student_details = &$students[$student_email];

          $student_details['first_name'] = get_user_meta($wp_user_id, 'first_name', true);
          $student_details['last_name'] = get_user_meta($wp_user_id, 'last_name', true);
          $student_details['date_completed'] = $learner['completion_date'];
          $student_details['program'] = get_program($wp_user_id);
          $student_details['studentnumber'] = get_student_number($wp_user_id);

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
      $objWorksheet->setTitle(substr($module_details['name'], 0, 30));
    } else {
      $new_worksheet = new PHPExcel_Worksheet($objPHPExcel, substr($module_details['name'], 0, 30));
      
      $objPHPExcel->addSheet($new_worksheet, $current_worksheet_index);
      $objWorksheet = $objPHPExcel->getSheet($current_worksheet_index);
    }

  # add the title
  
    $objWorksheet->setCellValue('A1', strtoupper($module_details['name']) . ' - Updated: ' . date("j F Y"));
  
  # add the column names
  
    $objWorksheet->setCellValue('A2', 'Student Number');
    $objWorksheet->setCellValue('B2', 'Name');
    $objWorksheet->setCellValue('C2', 'Email');
    $objWorksheet->setCellValue('D2', 'Program');
    $objWorksheet->setCellValue('E2', 'Date of completion');
    
    $n = 1;
    
  # add the students
  
    $highestRow = $objWorksheet->getHighestRow();
    $data_first_row_num = $highestRow + 1;
    
    $row = $data_first_row_num;
  
    foreach($module_details['students'] as $student_email => &$student_details) {
    
      # output the general information
      
        $objWorksheet->setCellValue('A'.$row, $student_details['studentnumber']);
        $objWorksheet->setCellValue('B'.$row, $student_details['first_name'] . ' ' . $student_details['last_name']);
        $objWorksheet->setCellValue('C'.$row, strtolower($student_email));
        $objWorksheet->setCellValue('D'.$row, $student_details['program']);
        
        $timestamp_completed = strtotime($student_details['date_completed']);
        $objWorksheet->setCellValue('E'.$row, PHPExcel_Shared_Date::PHPToExcel($timestamp_completed));
        $objWorksheet->getStyle('E'.$row)
            ->getNumberFormat()
            ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);

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
    $objWorksheet->getColumnDimension('E')->setAutoSize(true);
    
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

  /////////////////////////////////////////////   

  echo '<body>' ;
  echo '<div class="wrap" role="document">';
  echo '<div class="content row">';
  echo '<main class="main" role="main">';
  echo '<div class="container">';
  echo '<section id="Login" role="region" class="col-md-12">';
  echo '<article role="article" class="col-md-6 learning">';
  echo '<img alt="" src="<?php echo get_template_directory_uri(); ?>/assets/img/students.jpg">';
  echo '<h1 role="heading">'; 
  echo "</h1>";
  echo "</article>";
  echo '<article role="article" class="col-md-6 register">';

        if (current_user_can( 'manage_options' )) {
  echo '<h1>L’utilisation appropriee des antibiotiques</h1>';
  echo "<table border='1'>
  <tr>
  <th>Prénom </th>
  <th> Nom</th>
  <th>Email</th>
  </tr>";
    
      foreach($module_details['students'] as $student_email => &$student_details) {
  echo "<tr>";
  echo "<td> &nbsp; ".$student_details['first_name']."</td>";
  echo "<td> &nbsp; ".$student_details['last_name']."</td>";
  echo "<td> &nbsp; " . $student_email. "</td>";
  echo "</tr>";
  }
 
  echo "</table>";
  echo "</br>";
  echo '<a href="https://learn.med.uottawa.dev/app/themes/learn-med-theme/reports/baf-report/'.$filename.'"> Telecharger le Raport </a>';
  echo "</article>";
  echo "</section>";
  echo "</main>";
  echo "</div>";
  echo "</div>";
  } 
  else {
      wp_redirect('/login/');
  }
    get_template_part('templates/footer'); 
  echo "</body>";
# get student number from user meta, if present, or from the AD (and save to user meta)

  function get_student_number($userid) {
    global $adldap;
    global $ad_authenticated;
    global $missing_data;
    
    $user_login = get_user_by('id',$userid)->user_login;
    $user_email = get_user_by('id',$userid)->user_email;
    $user_meta_student_number = get_user_meta($userid, 'adi_employeeid', true);
    
    if (isset($user_meta_student_number) && "" != $user_meta_student_number) {
      update_user_meta($userid, 'adi_employeeid', strval($user_meta_student_number));
      return $user_meta_student_number;
    } else if (isset($missing_data[$user_email]) && isset($missing_data[$user_email]['studentnumber']) ) {
    
      $studentnumber = $missing_data[$user_email]['studentnumber'];
      # record the student number
      update_user_meta($userid, 'adi_employeeid', $studentnumber);
      
      return $studentnumber;
      
    } else {
      if (!$ad_authenticated) {
        //echo "not authenticated for $user_email<br>";
        return NULL;
      } else {
        $ad_user_info = $adldap->user_info(
          strtolower($user_login),
          array('employeenumber')
        );
        if (
          is_array($ad_user_info)
          && isset($ad_user_info[0])
          && isset($ad_user_info[0]['employeenumber'])
          && is_array($ad_user_info[0]['employeenumber'])
          && isset($ad_user_info[0]['employeenumber'][0])
          ) {
          
          $studentnumber = $ad_user_info[0]['employeenumber'][0];
          
          # record the student number
          update_user_meta($userid, 'adi_employeeid', $studentnumber);
          
          return $studentnumber;
          
        } else {
          return NULL;
        }
      }
    }
  }
  
# get the program (division) from user meta, if present, or from the AD (and save to user meta)

  function get_program($userid) {
    global $adldap;
    global $ad_authenticated;
    global $missing_data;

    $user_login = get_user_by('id',$userid)->user_login;
    $user_email = get_user_by('id',$userid)->user_email;
    $user_meta_division = get_user_meta($userid, 'adi_division', true);
    
    if (isset($user_meta_division) && "" != $user_meta_division) {
      return $user_meta_division;
    } else if (isset($missing_data[$user_email]) && isset($missing_data[$user_email]['program']) ) {
    
      $program = $missing_data[$user_email]['program'];
      # record the student number
      update_user_meta($userid, 'adi_division', $program);
      
      return $program;
      
    } else {
      if (!$ad_authenticated) {
        return NULL;
      } else {
        $ad_user_info = $adldap->user_info(
          strtolower($user_login),
          array('division')
        );
        if (
          is_array($ad_user_info)
          && isset($ad_user_info[0])
          && isset($ad_user_info[0]['division'])
          && is_array($ad_user_info[0]['division'])
          && isset($ad_user_info[0]['division'][0])
          ) {
          
          $program = $ad_user_info[0]['division'][0];
          
          # record the student number
          update_user_meta($userid, 'adi_division', $program);
          
          return $program;
          
        } else {
          return NULL;
        }
      }
    }
    
  }

?>

</body>
</html>