<?php

class Learn_Med_User_Modify_Command extends WP_CLI_Command {

  /**
  * Modify usernames or email addresses from an imported csv
  * Merges users if necessary
  *
  * ## OPTIONS
  *
  * <file>
  * : The local CSV file of current and new usernames and email addresses
  *
  * [--report-action-taken]
  * : Save the action taken as a new field in the original CSV file
  *
  * [--export-lrs-rewrite-jobs=<file>]
  * : Export a file detailing the jobs to import into rewrite-lrs (nodejs utility)
  *
  * ## EXAMPLES
  *
  *     wp learn-med user modify from-csv /path/to/modifications.csv
  *
  *     Sample users.csv file:
	*
	*     current_email,current_login,new_email,new_login
  *
  * @subcommand username-email-from-csv
  */
  function username_email_from_csv( $args, $assoc_args ) {
		$filename = $args[0];

    global $wpdb;

    $defaults = array(
			'export-lrs-rewrite-jobs' => NULL
		);
		wp_parse_args( $assoc_args, $defaults );

		if ( ! file_exists( $filename ) ) {
			WP_CLI::error( sprintf( "Missing file: %s", $filename ) );
      return;
		}

    // connect to the AD
    require_once locate_template('/lib/ad-verify.php');
    require_once locate_template('/lib/adi-bulk-import-user.php');

    $ADI = new ADBulkImportUser();
    $credentials = $ADI->get_bulkimport_user_credentials();

    try {
      $_adldap = @new ADVerify($credentials);
    } catch (Exception $e) {
      WP_CLI::error('adLDAP exception: ' . $e->getMessage());
      return;
    }

    // read the columns from the original file's first line
    if (($handle_original_file = fopen($filename, "r")) !== FALSE) {
      $columns = fgetcsv($handle_original_file);
    }
    fclose($handle_original_file);

    $update_original_file = false;
    if (isset( $assoc_args['report-action-taken'] ) ) {
      $update_original_file = true;
      $filename_updated = $filename . '.updated';
      array_push($columns, 'action_taken');

      // create the new updated file and add the original file's the columns
      if (($handle_updated_file = fopen($filename_updated, "w")) !== FALSE) {
        fputcsv($handle_updated_file, $columns);
      }
    }

    $export_lrs_rewrite_jobs = false;
    if (isset( $assoc_args['export-lrs-rewrite-jobs'] ) && "" != $assoc_args['export-lrs-rewrite-jobs']) {
      $export_lrs_rewrite_jobs = true;

      $lrs_rewrite_jobs_file = $assoc_args['export-lrs-rewrite-jobs'];

      // create the file for exporting the lrs rewrite jobs
      $handle_lrs_rewrite_jobs_file = fopen($lrs_rewrite_jobs_file, "w");

      $lrs_rewrite_jobs = array();
    }

		foreach ( new \WP_CLI\Iterators\CSV( $filename ) as $i => $modification ) {

      $existing_wp_current_user
      = $existing_wp_current_user_by_email
      = $existing_wp_current_user_by_login
      = $existing_wp_new_user
      = $existing_wp_new_user_by_email
      = $existing_wp_new_user_by_login
      = $is_existing_ad_new_user
      = $is_existing_ad_new_user_by_email
      = $is_existing_ad_new_user_by_login
      = $is_existing_ad_current_user
      = $is_existing_ad_current_user_by_email
      = $is_existing_ad_current_user_by_login
      = $is_merge_user
      = $q_update_comments_user_id
      = $q_update_posts_post_author
      = null;

      // make sure the modification parameters are set
      if ("" == $modification['new_email'] && "" == $modification['new_login']) {
        if ($update_original_file) {
          $modification['action_taken'] = 'Skipped: Missing new_email or new_login';
          $this->write_to_file($handle_updated_file, $modification, $columns);
        }
        continue;
      }

      // make sure there's a current user to look for
      if ("" == $modification['current_email'] && "" == $modification['current_login']) {
        if ($update_original_file) {
          $modification['action_taken'] = 'Skipped: Missing current_email or current_login in modification config';
          $this->write_to_file($handle_updated_file, $modification, $columns);
        }
        continue;
      }

      // make sure the emails or logins aren't the same
      if ("" != $modification['current_email'] && "" != $modification['new_email']
          && strcasecmp($modification['current_email'],$modification['new_email']) == 0) {
        if ($update_original_file) {
          $modification['action_taken'] = 'Skipped: Emails in the modification config are the same';
          $this->write_to_file($handle_updated_file, $modification, $columns);
        }
        continue;
      }

      if ("" != $modification['current_login'] && "" != $modification['new_login']
          && strcasecmp($modification['current_login'], $modification['new_login']) == 0) {
        if ($update_original_file) {
          $modification['action_taken'] = 'Skipped: Usernames (current_login and new_login) in the modification config are the same';
          $this->write_to_file($handle_updated_file, $modification, $columns);
        }
        continue;
      }

      // check that the current user is in Wordpress
      if ("" != $modification['current_email']) {
        $existing_wp_current_user = $existing_wp_current_user_by_email = get_user_by( 'email', $modification['current_email'] );
      }
      if ("" != $modification['current_login']) {
        $existing_wp_current_user_by_login = get_user_by( 'login', $modification['current_login'] );

        if (!$existing_wp_current_user_by_email && $existing_wp_current_user_by_login) {
          $existing_wp_current_user = $existing_wp_current_user_by_login;
        } else if ($existing_wp_current_user_by_email && $existing_wp_current_user_by_login && $existing_wp_current_user_by_email->ID != $existing_wp_current_user_by_login->ID) {
          if ($update_original_file) {
            $modification['action_taken'] = 'Skipped: the current_email and current_login refer to different users in the system.';
            $this->write_to_file($handle_updated_file, $modification, $columns);
          }
          continue;
        }
      }

      // check if the new email or the new login are already associated with a user in Wordpress
      if ("" != $modification['new_email']) {
        $existing_wp_new_user = $existing_wp_new_user_by_email = get_user_by( 'email', $modification['new_email'] );
      }
      if ("" != $modification['new_login']) {
        $existing_wp_new_user_by_login = get_user_by( 'login', $modification['new_login'] );
        if (!isset($existing_wp_new_user_by_email) && isset($existing_wp_new_user_by_login)) {
          $existing_wp_new_user = $existing_wp_new_user_by_login;
        }
      }

      if ($existing_wp_new_user_by_email && $existing_wp_new_user_by_login && $existing_wp_new_user_by_email->ID != $existing_wp_new_user_by_login->ID) {
        if ($update_original_file) {
          $modification['action_taken'] = 'Skipped: the new_email and new_login refer to different users in the system.';
          $this->write_to_file($handle_updated_file, $modification, $columns);
        }
        continue;
      }

      // check if the new account should be setup to be tied to AD
      // check if there's an AD account under the same new login

      if ("" != $modification['current_email']) {
        $ad_results = $_adldap->user_info_by_email($modification['current_email']);
        $isADaccount =
          ( $ad_results && isset( $ad_results['count'] )
            && $ad_results['count'] > 0
          )? true: false;
        $is_existing_ad_current_user_by_email = $isADaccount;
      }

      if ("" != $modification['current_login']) {
        $ad_results = $_adldap->user_info($modification['current_login']);
        $isADaccount =
          ( $ad_results && isset( $ad_results['count'] )
            && $ad_results['count'] > 0
          )? true: false;
        $is_existing_ad_current_user_by_login = $isADaccount;
      }

      $is_existing_ad_current_user = ($is_existing_ad_current_user_by_email || $is_existing_ad_current_user_by_login)? true: false;

      if ("" != $modification['new_email']) {
        $ad_results = $_adldap->user_info_by_email($modification['new_email']);
        $isADaccount =
          ( $ad_results && isset( $ad_results['count'] )
            && $ad_results['count'] > 0
          )? true: false;
        $is_existing_ad_new_user_by_email = $isADaccount;
      }

      if ("" != $modification['new_login']) {
        $ad_results = $_adldap->user_info($modification['new_login']);
        $isADaccount =
          ( $ad_results && isset( $ad_results['count'] )
            && $ad_results['count'] > 0
          )? true: false;
        $is_existing_ad_new_user_by_login = $isADaccount;
      }

      $is_existing_ad_new_user = ($is_existing_ad_new_user_by_email || $is_existing_ad_new_user_by_login)? true: false;

      if (!$existing_wp_current_user && $is_existing_ad_current_user && $existing_wp_new_user && !$is_existing_ad_new_user) {
        if ($update_original_file) {
          $modification['action_taken'] = 'Skipped: the new user already exists as external user';
          $this->write_to_file($handle_updated_file, $modification, $columns);
        }
        continue;
      }

      if (!$existing_wp_current_user && $is_existing_ad_current_user && $existing_wp_new_user && $is_existing_ad_new_user) {
        if ($update_original_file) {
          $modification['action_taken'] = "Skipped: there are no users to modify, and there is a user in the system for the new user";
          $this->write_to_file($handle_updated_file, $modification, $columns);
        }
        continue;
      }

      if (!$existing_wp_current_user && $is_existing_ad_current_user && !$existing_wp_new_user) {
        if ($update_original_file) {
          $modification['action_taken'] = 'Skipped: user never logged in through AD. No accounts modified';
          $this->write_to_file($handle_updated_file, $modification, $columns);
        }
        continue;
      }

      if (!$existing_wp_current_user) {
        if ($update_original_file) {
          $modification['action_taken'] = 'Skipped: no existing user to be modified';
          $this->write_to_file($handle_updated_file, $modification, $columns);
        }
        continue;
      }

      $is_merge_user = ($existing_wp_current_user && $existing_wp_new_user)? true: false;

      if ($is_merge_user) {
        // make sure the users found in the database aren't the same
        if ($existing_wp_current_user->ID == $existing_wp_new_user->ID) {
          if ($update_original_file) {
            $modification['action_taken'] = 'Skipped: The two users found in the system are the same';
            $this->write_to_file($handle_updated_file, $modification, $columns);
          }
          continue;
        }
      }

      // prepare the lrs rewrite job
      if ($export_lrs_rewrite_jobs && $existing_wp_current_user) {
        $lrs_rewrite_job = array(
          "old_agent" => array(
            "objectType" => "Agent",
            "mbox" => sprintf("mailto:%s", $existing_wp_current_user->user_email)
          ),
          "new_agent" => array(
            "objectType" => "Agent"
          )
        );

        $lrs_rewrite_job["new_agent"]["name"] = ("" != $modification['new_login'])? $modification['new_login'] : $existing_wp_current_user->user_login;

        $lrs_rewrite_job["new_agent"]["mbox"] = sprintf("mailto:%s",("" != $modification['new_email'])? $modification['new_email'] : $existing_wp_current_user->user_email);

        array_push($lrs_rewrite_jobs, $lrs_rewrite_job);

        // write out the json in case of failure when modifying the user
        rewind($handle_lrs_rewrite_jobs_file);
        fwrite($handle_lrs_rewrite_jobs_file, json_encode($lrs_rewrite_jobs, JSON_PRETTY_PRINT));
        fflush($handle_lrs_rewrite_jobs_file);
        ftruncate($handle_lrs_rewrite_jobs_file, ftell($handle_lrs_rewrite_jobs_file));
      }

      if ($is_merge_user) {

        // merge in the quiz results, progress info from the existing user which matches the new email or new login

        update_user_meta(
          $existing_wp_current_user->ID,
          '_sfwd-quizzes',
          $this->merge_quiz_results(
            get_user_meta($existing_wp_current_user->ID, '_sfwd-quizzes'),
            get_user_meta($existing_wp_new_user->ID, '_sfwd-quizzes')
          )
        );

        update_user_meta(
          $existing_wp_current_user->ID,
          '_sfwd-course_progress',
          array_merge_recursive(
            get_user_meta($existing_wp_current_user->ID, '_sfwd-course_progress'),
            get_user_meta($existing_wp_new_user->ID, '_sfwd-course_progress')
          )[0]
        );

        // update user_id's (from new_user to current_user)
        // wp_comments.user_id
        $q_update_comments_user_id = $wpdb->prepare( "UPDATE $wpdb->comments SET user_id = %s WHERE user_id = %s", $existing_wp_new_user->ID, $existing_wp_current_user->ID );
        $wpdb->query($q_update_comments_user_id);

			  // wp_posts.post_author
        $q_update_posts_post_author = $wpdb->prepare( "UPDATE $wpdb->posts SET post_author = %s WHERE post_author = %s", $existing_wp_new_user->ID, $existing_wp_current_user->ID );
        $wpdb->query($q_update_posts_post_author);

        // delete the existing user which matches the new email or new login
        wp_delete_user($existing_wp_new_user->ID);
      }

      // modify the account
      if ($modification['new_login'] != "") {
        // change the username everywhere
        \WP_CLI::run_command(
          array(
            'learn-med', 'search-replace',
            $existing_wp_current_user->user_login,
            $modification['new_login']
          ),
          array(
            'exact-match' => true,
            'case-insensitive' => true,
            'recurse-objects' => true
          )
        );

        // if there's an AD account under the new username,
        // make sure the user will be able to login using that username against AD
        if ($is_existing_ad_new_user) {
          update_user_meta(
            $existing_wp_current_user->ID,
            'adi_samaccountname',
            $modification['new_login']
          );
        }
        // make sure accountname
      }

      if ($modification['new_email'] != "") {
        // change the email in the account
        wp_update_user(array(
          'ID' => $existing_wp_current_user->ID,
          'user_email' => $modification['new_email']
        ));
        // make sure the grassblade_email is properly set
        update_user_meta(
          $existing_wp_current_user->ID,
          'grassblade_email',
          $modification['new_email']
        );

        // change the email everywhere else
        \WP_CLI::run_command(
          array(
            'learn-med', 'search-replace',
            $existing_wp_current_user->user_email,
            $modification['new_email']
          ),
          array(
            'case-insensitive' => true,
            'recurse-objects' => true
          )
        );
      }

      if ($is_merge_user) {
        $modification['action_taken'] = sprintf("user merged data from existing account ID %d. Existing account deleted", $existing_wp_new_user->ID);
      } else {
        $modification['action_taken'] = "user modified";
      }

      if ($update_original_file) {
        $this->write_to_file($handle_updated_file, $modification, $columns);
      }
		}

    if ($update_original_file) {
      fclose($handle_updated_file);

      // save a backup of the original file
      rename ($filename, $filename . '.old');

      // rename the new file to the original filename
      rename ($filename_updated, $filename);
    }

    if ($export_lrs_rewrite_jobs) {
      rewind($handle_lrs_rewrite_jobs_file);
      fwrite($handle_lrs_rewrite_jobs_file, json_encode($lrs_rewrite_jobs, JSON_PRETTY_PRINT));
      fflush($handle_lrs_rewrite_jobs_file);
      ftruncate($handle_lrs_rewrite_jobs_file, ftell($handle_lrs_rewrite_jobs_file));
      fclose($handle_lrs_rewrite_jobs_file);
    }
  }

  static function write_to_file($handle, $record, $valid_columns) {
    // prepare the row to write
    $output = array();
    foreach($valid_columns as $column) {
      if (isset($record[$column])) {
        array_push($output, $record[$column]);
      } else {
        array_push($output, '');
      }
    }

    // write the row
    fputcsv($handle, $output);
  }

  static function merge_quiz_results($a, $b) {
    if (!is_array($a) || !is_array($b)) {
      return false;
    }

    $merged = array_merge($a, $b);

    if (is_array($merged) and isset($merged[0])) {
      usort($merged[0], array('Learn_Med_User_Modify_Command', 'cmp_quizzes'));
    }

    return $merged;
  }

  static function cmp_quizzes($a, $b) {
    if (!isset($a["time"]) || !isset($b["time"])) {
      return 0;
    }
    if ($a["time"] == $b["time"]) {
        return 0;
    }
    return ($a["time"] < $b["time"]) ? -1 : 1;
  }

  /**
  * Import passwords from a CSV file
  *
  * ## OPTIONS
  *
  * <file>
  * : The local CSV file of passwords to import
  *
  * ## EXAMPLES
  *
  *     wp learn-med user modify import-password-csv /path/to/passwords.csv
  *
  * @subcommand import-password-csv
  */
  function import_password_csv( $args, $assoc_args ) {
    $filename = $args[0];

		if ( ! file_exists( $filename ) ) {
			WP_CLI::error( sprintf( "Missing file: %s", $filename ) );
      return;
		}

    foreach ( new \WP_CLI\Iterators\CSV( $filename ) as $i => $user_password ) {
      $existing_user = get_user_by( 'email', $user_password['user_email'] );
      if ( !$existing_user ) {
        WP_CLI::warning( sprintf( "Missing user: %s", $user_password['user_email'] ) );
        continue;
      }

      wp_set_password($user_password['user_pass'], $existing_user->ID);
    }
  }
}

WP_CLI::add_command( 'learn-med user modify', 'Learn_Med_User_Modify_Command' );
