<?php

class Learn_Med_User_Command extends WP_CLI_Command {

  /**
  * Import users from a CSV file
  *
  * ## OPTIONS
  *
  * <file>
  * : The local CSV file of users to import
  *
  * [--report-action-taken]
  * : Save the action taken as a new field in the original CSV file
  *
  * [--report-password-generated]
  * : Save the generated password as a new field in the original CSV file
  *
  * ## EXAMPLES
  *
  *     wp learn-med user import-csv /path/to/users.csv
  *
  * @subcommand import-csv
  */
  function import_csv( $args, $assoc_args ) {
    $blog_users = get_users();
		$filename = $args[0];

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

    // extract from the list of columns the fields that will be added user_meta
    $user_fields = array(
      'ID',
      'user_pass',
      'user_login',
      'user_nicename',
      'user_url',
      'user_email',
      'display_name',
      'nickname',
      'first_name',
      'last_name',
      'rich_editing',
      'user_registered',
      'role',
      'jabber',
      'aim',
      'yim'
    );
    $user_meta_fields = array();
    foreach ($columns as $column) {
      if (!in_array($column, $user_fields)) {
        array_push($user_meta_fields, $column);
      }
    }

    $update_original_file = false;
    if (isset( $assoc_args['report-password-generated'] )
        || isset( $assoc_args['report-action-taken'] ) ) {
      $update_original_file = true;
      $filename_updated = $filename . '.updated';

      if (isset( $assoc_args['report-action-taken'] )) {
        array_push($columns, 'action_taken');
      }

      if (isset( $assoc_args['report-password-generated'] )) {
        array_push($columns, 'user_pass');
      }

      // create the new updated file and add the original file's the columns
      if (($handle_updated_file = fopen($filename_updated, "w")) !== FALSE) {
        fputcsv($handle_updated_file, $columns);
      }
    }

		foreach ( new \WP_CLI\Iterators\CSV( $filename ) as $i => $new_user ) {
			$defaults = array(
				'role' => get_option('default_role'),
				'user_pass' => wp_generate_password(),
				'user_registered' => strftime( "%F %T", time() ),
				'display_name' => false,
			);
			$new_user = array_merge( $defaults, $new_user );

      $new_user['user_login'] = generate_rand_username(
        $new_user['user_email'],
        $new_user['first_name'],
        $new_user['last_name']
      );

			// User already exists and we just need to add them to the site if they aren't already there
			$existing_user = get_user_by( 'email', $new_user['user_email'] );
			if ( !$existing_user ) {
        $ad_results = $_adldap->user_info_by_email($new_user['user_email']);
        $isADaccount =
          ( $ad_results && isset( $ad_results['count'] )
            && $ad_results['count'] > 0
          )? true: false;
				$existing_user = $isADaccount;
			}

			if ( $existing_user ) {
        // If user already exists, skip creating the user and save action taken
        $message = 'Skipped: user existed already';
        if ($isADaccount) {
          $message .= ' (AD account found)';
        }
        $new_user['action_taken'] = $message;
        unset($new_user['user_pass']);
			} else {
        // Ensure to get a new user ID upon creation
        unset( $new_user['ID'] ); // Unset else it will just return the ID

        // Create the user
        $user_id = wp_insert_user( $new_user );

        if ( is_wp_error( $user_id ) ) {
  				WP_CLI::warning( $new_user['user_email'] . " => " . $user_id );
          $new_user['action_taken'] = 'Error creating user';
  			} else {
          $new_user['action_taken'] = 'Created the user';

          // Add remaining fields to user_meta
          foreach($user_meta_fields as $meta_key) {
            if (isset($new_user[$meta_key]) && '' !== $new_user[$meta_key]) {
              add_user_meta($user_id, $meta_key, $new_user[$meta_key], true);
            }
          }
        }
			}

      if ($update_original_file) {
        // prepare the row to write
        $new_user_output = array();
        foreach($columns as $column) {
          if (isset($new_user[$column])) {
            array_push($new_user_output, $new_user[$column]);
          } else {
            array_push($new_user_output, '');
          }
        }

        // write the row
        fputcsv($handle_updated_file, $new_user_output);
      }
		}

    if ($update_original_file) {
      fclose($handle_updated_file);

      // save a backup of the original file
      rename ($filename, $filename . '.old');

      // rename the new file to the original filename
      rename ($filename_updated, $filename);
    }
  }

  /**
  * Find external users whose email address matches the email address of an AD account
  *
  * ## EXAMPLES
  *
  *     wp learn-med user externals-with-ad-email
  *
  * @subcommand externals-with-ad-email
  */
  function externals_with_ad_email ( $args, $assoc_args ) {

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

    $users = get_users();

    $count_users = count($users);
    $count_external_users = 0;
    $count_external_users_with_ad_email = 0;

    foreach($users as $user) {

      if ("" != get_user_meta($user->ID, 'adi_samaccountname', true)) {
        continue;
      }

      $count_external_users++;

      $ad_results = $_adldap->user_info_by_email($user->user_email);

      $isADaccount =
        ( $ad_results && isset( $ad_results['count'] )
          && $ad_results['count'] > 0
        )? true: false;

      if ($isADaccount) {
        printf("%s %s %s\n", $ad_results[0]['samaccountname'][0], $user->user_email, $user->user_login);
        $i++;
        $count_external_users_with_ad_email++;
      }
    }

    echo "\n";
    printf("%d external users with ad email\n", $count_external_users_with_ad_email);
    printf("%d external users total\n", $count_external_users);
    printf("%d users total\n", $count_users);
  }

}

WP_CLI::add_command( 'learn-med user', 'Learn_Med_User_Command' );
