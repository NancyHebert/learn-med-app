<?php

function learndash_trigger_completion() {

    global $post;
    if (!$post) {
      return;
    }

  # prepare the statement's activity

    $course = get_post(learndash_get_course_id($post->ID));

    grassblade_learndash_course_completed(array(
      'course' => $course,
      'progress' => null
    ));

    //file_put_contents("test.txt", $course, FILE_APPEND);

}

add_shortcode("trigger_completion", "learndash_trigger_completion");

?>
