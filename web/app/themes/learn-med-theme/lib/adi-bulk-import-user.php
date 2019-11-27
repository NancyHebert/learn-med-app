<?php

class ADBulkImportUser extends ADIntegrationPlugin {
  public function get_bulkimport_user_credentials() {

    $credentials = array(
      "base_dn" => get_option('AD_Integration_base_dn'),
      "domain_controllers" => explode(';', get_option('AD_Integration_domain_controllers')),
      "ad_username" => get_option('AD_Integration_bulkimport_user'), 		// User
      "ad_password" => trim($this->_decrypt(get_option('AD_Integration_bulkimport_pwd'))), // password, trimmed because of bug in _encrypt -> _decrypt
      "ad_port" => get_option('AD_Integration_port'),            // AD port
      "use_tls" => (!get_option('AD_Integration_use_tls'))? false: true,         // secure?
      "network_timeout" => get_option('AD_Integration_network_timeout')	// network timeout
    );
    return $credentials;
  }

  public function update_bulkimport_pwd($pwd) {
    update_option('AD_Integration_bulkimport_pwd', $this->sanitize_bulkimport_user_pwd($pwd));
  }
}
