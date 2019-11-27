<?php

use Pheanstalk\Pheanstalk;

$pheanstalk = new Pheanstalk('127.0.0.1');
$statements_tube = 'statements';

try {
  var_dump($pheanstalk->statsTube($statements_tube));
} catch (Exception $e) {
  echo "problem with statements_tube \n" . $e->getMessage();
}

$apis_tube = 'notify-apis';

try {
  var_dump($pheanstalk->statsTube($apis_tube));
} catch (Exception $e) {
  echo "problem with apis_tube: \n" . $e->getMessage();
}
