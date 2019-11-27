<?php

// set the response content-type
header('content-type: application/json; charset=utf-8');

// require the email query string param
if (isset($_GET['email'])) {
  $email = $_GET['email'];
} else {
  header("HTTP/1.1 400 Bad Request"); exit;
}

// let's load WordPress
require_once $_SERVER['DOCUMENT_ROOT'] . '/wp/wp-blog-header.php'; #TODO

// require a same-domain referer
if (!is_referer_same_domain()) {
  header("HTTP/1.1 403 Forbidden"); exit;
}

// check for rapid multiple requests by the same IP address (prevent email harvesting)
  // handled by the server, see limit_req*

// check if email is valid
if (!is_email($email)) {
  echo json_encode(array(
    'valid' => false,
  ));
  exit;
}

// If the plugin class is not found, die.
if (!class_exists('adLDAP')) {
  header("HTTP/1.1 500 Internal Server Error"); exit;
}

if (!class_exists('ADIntegrationPlugin')) {
  header("HTTP/1.1 500 Internal Server Error"); exit;
}

// load the ADVerify class
require_once locate_template('/lib/ad-verify.php');

if (!class_exists('ADVerify')) {
  header("HTTP/1.1 500 Internal Server Error"); exit;
}

// load the ADBulkImportUser class
require_once locate_template('/lib/adi-bulk-import-user.php');

if (class_exists('ADBulkImportUser')) {
  $ADI = new ADBulkImportUser();
  $credentials = $ADI->get_bulkimport_user_credentials();
} else {
  header("HTTP/1.1 500 Internal Server Error"); exit;
}

try {
  $_adldap = @new ADVerify($credentials);
} catch (Exception $e) {
  //die('adLDAP exception: ' . $e->getMessage());
  header("HTTP/1.1 500 Internal Server Error"); exit;
}

// check in wordpress for a user using the account

// check if email is associated with a user in Wordpress
$user = get_user_by('email', $email);

$isFound = false;
$loginWithEmail = false;

if ($user) {
  $isFound = true;
  // check if the user found is an external account
  if ("" == get_user_meta($user->ID, 'adi_samaccountname', true)) {
    // is an external account
    $loginWithEmail = true;
  }
} else {
  $isFound = $_adldap->is_email_found($_GET['email']);
}

// turn off possible output buffering
ob_end_flush();

echo json_encode(array(
  'valid' => true,
  'found' => $isFound,
  'login_with_email' => $loginWithEmail
));

function is_referer_same_domain() {
  // require a referer
  if (!isset($_SERVER['HTTP_REFERER'])) { return false; }

  // require that the referer start with the same protocol (http/https) and domain
  if (0 ===
      strpos($_SERVER['HTTP_REFERER'],
        "http"
        . ($_SERVER['HTTPS'] == "on"? "s": "")
        . "://"
        . $_SERVER['HTTP_HOST']
      )
    ) {
    return true;
  }
  return false;
}

?>
