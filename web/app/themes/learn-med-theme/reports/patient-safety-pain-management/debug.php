<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/wp/wp-blog-header.php'; #TODO: Find home path instead of DOCUMENT_ROOT
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Parser;

require_once locate_template('/lib/lrs.php');
require_once locate_template('/lib/adi-bulk-import-user.php');

# set defaults

  $filename = 'report.xlsx';

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

# prepare connection to AD

  $ad_authenticated = false;

  if (class_exists('ADBulkImportUser')) {
    $ADI = new ADBulkImportUser();
    $credentials = $ADI->get_bulkimport_user_credentials();
  } else {
    WP_CLI::error( "Couldn't get the bulk import password: ADBulkImportUser class not found" );
  }

  use \adLDAP;
  try {
      $adldap = new adLDAP($credentials);
      echo "authenticated<br>";
      $ad_authenticated = true;
  }
  catch (adLDAPException $e) {
      echo $e;
      exit();
  }


# get data known to be missing from the AD

  $missing_data = $yaml->parse(file_get_contents('missing_data.yml'));

# get the list of modules to report on

  $config = $yaml->parse(file_get_contents('config.yml'));

  $modules = &$config['modules'];

  # modules
    # module_activity_id
      # name

# test whether to use the LRS (preferable) or learndash's data in Wordpress to determine completion

  foreach($modules as $module_activity_id => &$module_details) {

    # in the LRS

      # call the LRS for the activity id

        $response = CallAPI("GET", $API_url, $lrs_username, $lrs_password, array('activity' => $module_activity_id, 'all' => 'true' ));

      # if no response or an error has occurred, throw an error

        if (!$response) {
          die("Can't get data on $module_activity_id");
        }

      # parse the returning JSON

        $response = json_decode($response, true);

      # store in $modules[module_activity_id][students_in_lrs]

        $module_details['students_in_lrs'] = array();
        $students_in_lrs = &$module_details['students_in_lrs'];

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

            $students_in_lrs[$student_email] = array();
            $student_details = &$students_in_lrs[$student_email];

            $student_details['first_name'] = get_user_meta($wp_user_id, 'first_name', true);
            $student_details['last_name'] = get_user_meta($wp_user_id, 'last_name', true);
            $student_details['date_completed'] = $learner['completion_date'];
            $student_details['program'] = get_program($wp_user_id);
            $student_details['studentnumber'] = get_student_number($wp_user_id);

          }

        }

    # in Wordpress

        # poll the Wordpress database for the data and store in $modules[module][students_in_wp]

        $courses_url = "https://learn.med.uottawa.ca/courses/";

        $module_details['students_in_wp'] = array();
        $students_in_wp = &$module_details['students_in_wp'];

        $users = get_users( Array( 'meta_key' => '_sfwd-course_progress' ) );

        if ( !empty( $users ) ) {
        	foreach( $users as $u ) {
        		$wp_user_id = $u->ID;

            # skip if user is admin
            if (user_can($wp_user_id, 'manage_options' ) ) {
              continue;
            }

        		$usermeta = get_user_meta( $wp_user_id, '_sfwd-course_progress', true );
        		if(is_string($usermeta))
        		  $usermeta = unserialize($usermeta);
        		if ( !empty( $usermeta ) ) {
        			//$usermeta = explode( ",", $usermeta );
        			foreach( $usermeta as $c => $cv ) {
        				$course = get_post( $c );

        				if ($module_activity_id == $courses_url . $course->post_name . '/') {

        				  $course_completed = ($cv['completed'] >= $cv['total']);

        				  if ($course_completed) {

          				  $students_in_wp[$u->user_email] = array();
          				  $student_details = &$students_in_wp[$u->user_email];

          				  $student_details['first_name'] = get_user_meta($wp_user_id, 'first_name', true);
          				  $student_details['last_name'] = get_user_meta($wp_user_id, 'last_name', true);
          				  $student_details['date_completed'] = $learner['completion_date'];
          				  $student_details['program'] = get_program($wp_user_id);
          				  $student_details['studentnumber'] = get_student_number($wp_user_id);

          				}
        				}
          		}
        	  }
          }
        }

  } // foreach module

  d($modules);

# compare the results between the LRS and WP

  foreach($modules as $module_activity_id => &$module_details) {
    $students_in_lrs = &$module_details['students_in_lrs'];
    $students_in_wp = &$module_details['students_in_wp'];

    foreach($students_in_lrs as $student_in_lrs_email => &$student_in_lrs_details) {
      foreach($students_in_wp as $student_in_wp_email => &$student_in_wp_details) {
        if ($student_in_lrs_email == $student_in_wp_email) {
          $student_in_lrs_details['found_in_wp'] = 'yes';
          $student_in_wp_details['found_in_lrs'] = 'yes';
        }
      }
    }
  }

  d($modules);

# report students with a their program (division) field missing

  $students_with_missing_program = array();

  foreach($modules as $module_activity_id => &$module_details) {
    $students_in_lrs = &$module_details['students_in_lrs'];

    foreach($students_in_lrs as $student_email => $student_detail) {
      if (empty($students_with_missing_program[$student_email])
        && (empty($student_detail['program']) || "" == $student_detail['program'])
        ) {
        $students_with_missing_program[$student_email] = $student_detail;
      }
    }
  }

  d($students_with_missing_program);

# report students with a their studentnumber field missing

  $students_with_missing_student_number = array();

  foreach($modules as $module_activity_id => &$module_details) {
    $students_in_lrs = &$module_details['students_in_lrs'];

    foreach($students_in_lrs as $student_email => $student_detail) {
      if (empty($students_with_missing_student_number[$student_email])
        && (empty($student_detail['studentnumber']) || "" == $student_detail['studentnumber'])
        ) {
        # get the username

        $student_detail['username'] = get_userdata(get_user_by('email', $student_email)->ID)->user_login;

        $students_with_missing_student_number[$student_email] = $student_detail;

      }
    }
  }

  d($students_with_missing_student_number);

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
        echo "not authenticated for $user_email<br>";
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
