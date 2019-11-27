<?php

// require the email query string param
if (isset($_POST['action'])) {
  $email = $_POST['action'];
} else {
  header("HTTP/1.1 400 Bad Request"); exit;
}

// let's load WordPress
require_once $_SERVER['DOCUMENT_ROOT'] . '/wp/wp-blog-header.php'; #TODO

//action(s) below found here -> learn-med-app/web/app/themes/learn-med-theme/lib/parse_xapi_statements.php
do_action( 'xapi_statements_' . $_POST['action'] );
