<?php 

require_once $_SERVER['DOCUMENT_ROOT'] . '/wp/wp-blog-header.php'; #TODO: Find home path instead of DOCUMENT_ROOT
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Parser;

require_once locate_template('/lib/lrs.php');

Dotenv::load(__DIR__);
Dotenv::required(array('AD_ADMIN_USERNAME', 'AD_ADMIN_PASSWORD'));

# load adLDAP

  use \adLDAP;
  try {
      $adldap = new adLDAP(
        array(
          "base_dn" => get_option('AD_Integration_base_dn'),
          "domain_controllers" => explode(';', get_option('AD_Integration_domain_controllers')),
          "ad_port" => get_option('AD_Integration_port'),
          "use_tls" => get_option('AD_Integration_use_tls'),
          "network_timeout" => get_option('AD_Integration_network_timeout'),
          "account_suffix" => get_option('AD_Integration_account_suffix')
        )
      );
  }
  catch (adLDAPException $e) {
      echo $e;
      exit();
  }
  
# authenticate
  
  if ($adldap->authenticate(getenv('AD_ADMIN_USERNAME'), getenv('AD_ADMIN_PASSWORD')) === true) {
    echo "authenticated";
  } 
  
# test retrieving a student's attributes

  d($adldap);

  $studentnumber = $adldap->user_info(strtolower('RSTER029'), array("employeeNumber"))[0]['employeenumber'][0];
  
  d($studentnumber);

?>