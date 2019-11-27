<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/wp/wp-blog-header.php'; #TODO: Find home path instead of DOCUMENT_ROOT
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Parser;

require_once locate_template('/lib/lrs.php');

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

  $modules = &$config['modules'];
  $quizzes = &$config['quizzes'];

  # modules
    # module_activity_id
      # name

# test whether to use the LRS (preferable) or learndash's data in Wordpress to determine completion

  foreach($quizzes as $quiz_activity_id => &$quiz_details) {

    # in the LRS

      # call the LRS for the score results

        $response_score_results = CallAPI("GET", $API_url_score_results, $lrs_username, $lrs_password, array('activity' => $quiz_activity_id));

      # if no response or an error has occurred, throw an error

        if (!$response_score_results) {
          die("Can't get data on $quiz_activity_id");
        }

      # parse the returning JSON

        $response_score_results = json_decode($response_score_results, true);

      # store in $modules[module_activity_id][students_in_lrs]

        $quiz_details['students_in_lrs'] = array();
        $students_in_lrs = &$quiz_details['students_in_lrs'];

        dd($response_score_results);

        foreach($response_score_results as $learner) {

          d($learner);

          if (0 && isset($learner['completion']) && true == $learner['completion']) {

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

          }

        }



  } // foreach module

?>
