<?php

/**
* Implements configuring settings for learn.med platform
*/
class Learn_Med_Command extends WP_CLI_Command {

  /**
  * Sets the French domain in WPML according to the current environment
  *
  * ## EXAMPLES
  *
  *     wp learn-med set-fr-domain
  *
  * @subcommand set-fr-domain
  */
  function set_fr_domain( $args, $assoc_args ) {
    global $sitepress, $envs;

    if (defined('ICL_LANGUAGE_CODE') && defined('WP_ENV') && is_array($envs)) {
      $fr_stages = $envs['fr'];
      $current_stage = WP_ENV;
      if (isset($fr_stages[$current_stage])) {
        $fr_domain = $fr_stages[$current_stage];
        $fr_domain = "https://" . $fr_domain;
      } else {
        return;
      }
    } else {
      return;
    }

    $iclsettings = $sitepress->get_settings();
    $iclsettings['language_domains']['fr'] = $fr_domain;
    $sitepress->save_settings($iclsettings);

    if ($fr_domain == $sitepress->get_settings()['language_domains']['fr']) {
      WP_CLI::success( "Set the French domain to $fr_domain" );
    } else {
      WP_CLI::error( "Error setting the French domain to $fr_domain" );
    }

  }

  /**
  * Sets the LRS settings for the current environment
  *
  * ## EXAMPLES
  *
  *     wp learn-med set-lrs-settings
  *
  * @subcommand set-lrs-settings
  */
  function set_lrs_settings( $args, $assoc_args ) {

    if (!getenv('LRS_ENDPOINT') || !getenv('LRS_USER') || !getenv('LRS_PASSWORD')) {
      WP_CLI::error( "Couldn't set the LRS settings" );
    }

    update_option('grassblade_tincan_endpoint', getenv('LRS_ENDPOINT'));
    update_option('grassblade_tincan_user',     getenv('LRS_USER'));
    update_option('grassblade_tincan_password', getenv('LRS_PASSWORD'));

    WP_CLI::success( "Set the LRS settings" );

  }

  /**
  * Get the password for the bulk import user configured for the Active Directory
  *
  * ## EXAMPLES
  *
  *     wp learn-med get-adi-bulk-import-password
  *
  * @subcommand get-adi-bulk-import-password
  */
  function get_adi_bulk_import_password( $args, $assoc_args ) {
    require_once locate_template('/lib/adi-bulk-import-user.php');

    if (class_exists('ADBulkImportUser')) {
      $ADI = new ADBulkImportUser();
      $credentials = $ADI->get_bulkimport_user_credentials();
      echo $credentials['ad_password'];
    } else {
      WP_CLI::error( "Couldn't get the bulk import password: ADBulkImportUser class not found" );
    }
  }

  /**
  * Update the password for the bulk import user configured for the Active Directory
  *
  * ## EXAMPLES
  *
  *     wp learn-med update-adi-bulk-import-password /path/to/pwd-file
  *
  * @subcommand update-adi-bulk-import-password <pwd-file-path>
  */
  function update_adi_bulk_import_password( $args, $assoc_args ) {
    list( $pwd_file ) = $args;
    require_once locate_template('/lib/adi-bulk-import-user.php');

    if (file_exists($pwd_file)) {
      $pwd = file_get_contents($pwd_file);
    } else {
      WP_CLI::error( "File not found: " . $pwd_file );
    }

    if (class_exists('ADBulkImportUser')) {
      $ADI = new ADBulkImportUser();
      $ADI->update_bulkimport_pwd($pwd);
      WP_CLI::success( "Updated the bulk import password" );
    } else {
      WP_CLI::error( "Couldn't get the bulk import password: ADBulkImportUser class not found" );
    }
  }
}

WP_CLI::add_command( 'learn-med', 'Learn_Med_Command' );
