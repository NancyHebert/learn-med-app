<?php

use Pheanstalk\Pheanstalk;

$pheanstalk = new Pheanstalk('127.0.0.1');
$tubes = array(
  'statements',
  'notify-apis'
);

foreach ($tubes as $tube) {
  $pheanstalk->watch($tube);
  printf("Deleting jobs from the '%s' tube...\n", $tube);

  $count = 0;
  while($job = $pheanstalk->reserve(5)) {
    echo ".";
    try {
        $pheanstalk->delete($job);
    } catch (Exception $e) {
        // Bury a job
        $pheanstalk->bury($job);
        echo $e->getMessage();
    }
    $count++;
  }
  printf("%d jobs deleted from %s\n\n", $count, $tube);
}
