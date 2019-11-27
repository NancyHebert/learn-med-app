<?php

class learndash_custom {


  function __construct() {


    add_action('wp_ajax_learndash_custom_topic_and_lesson_mark_complete', array($this, 'mark_complete'));  
    add_action('wp_head', array($this, 'wp_head_config'));


  }

    
  function mark_complete($post) {

    //get the post ID



    $current_user = wp_get_current_user(); 
    $user_id = $current_user->ID;
    $status_code = 200;

    $topic_id = $_POST["topic_id"];
    $topic_post = $_POST["topic_post"];



  
    //find topic based on ID
    $topic = get_post($topic_id);


    //check if post is a topic
    if($topic->post_type == "sfwd-topic") {

      //retrieve associated lesson ID
      //$lesson_id = learndash_get_setting($post, "lesson");
      //$lesson = learndash_get_lesson_list($lesson_id);
      //$lesson = get_post($lesson_id);

      $lesson_id = learndash_get_setting($topic, "lesson");
      $lesson = get_post($lesson_id);
      $topics = learndash_get_topic_list($lesson_id);

      $course_id = learndash_get_course_id($topic_id);

      $lessons = learndash_get_lesson_list($topic_id);
     

      $course_progress = get_user_meta($user_id, '_sfwd-course_progress', true);
      


    
      //mark the topic as complete

      //Grassblade debug code
      //grassblade_debug("this is the debug text");

      $topic_is_complete = learndash_process_mark_complete($user_id, $topic_id, true);

      //Grassblade debug code
      //grassblade_debug("user_id " . $user_id);
      //grassblade_debug("topic_id " . $topic_id);
      //grassblade_debug("topic_is_complete " . $topic_is_complete);

      //update the course progress with the new topic completed
      $the_topic_is_completed = $course_progress[$course_id]['topics'][$lesson_id][$topic_id] = 1;
      //Grassblade debug code
      //grassblade_debug("the_topic_is_completed " . $the_topic_is_completed );



      //mark the associated lesson as complete
      $lesson_is_complete = learndash_lesson_topics_completed($user_id, $lesson_id, true);
      //Grassblade debug code
      //grassblade_debug("lesson_is_complete " . $lesson_is_complete);


      //update the course progress to mark the lesson as complete
      $the_lesson_is_completed = $course_progress[$course_id]['lessons'][$lesson_id] = 1;
      //Grassblade debug code
      //grassblade_debug("the_lesson__is_completed " . $the_lesson_is_completed);

  
      //Update the user meta to save the progress
      update_user_meta( $user_id, '_sfwd-course_progress', $course_progress);


     

      //get topics list 
      //grassblade_debug("topic list "  . $topics);

      //get progress object array
      //$the_progress = learndash_get_course_progress(null, $topics[0]->ID); //returns progress array
      //$the_progress_lesson = learndash_get_course_progress(null, $lessons[0]->ID); //returns progress array
      //$the_progress_lessons = learndash_get_course_progress(null, $lessons[0]->ID);
      
      //grassblade_debug("the_progress_lessons " . $the_progress_lessons);
      //grassblade_debug("course progress " . $the_progress);
      //grassblade_debug('the progress array' . print_r($the_progress, true) );

      //find out if next topic is completed or not completed
      //$next_topic_not_completed = empty($the_progress['next']->completed); //returns 1 or 0 (true or false) topic not completed
      //grassblade_debug("next_not_completed_topic " . $next_topic_not_completed);
      

    

 
          
      // //loop through the topic post  
      // foreach($lessons as $lesson)
      // {
   
      // grassblade_debug("the_progress_lesson new " . print_r($the_progress_lesson, true) );
      // grassblade_debug("lessons " .  print_r($lessons, true));

       
             
      //   //loop through the topic post  
      //   foreach($the_progress['posts'] as $topic) {
            
      //         //get the current topic id

      //         $current_topic_id = $topic->ID;
      //         $prevCompleted = is_previous_complete($topic);
      //         $prevNotCompleted = !is_previous_complete($topic);
      //         $thisCompleted = $topic->completed;

      //         //if the current topic id exist  and the prevtopic is not completed
      //         if ($current_topic_id != null &&  $prevCompleted != 1) {

      //           //Push all not completed topics in and array
               
      //           //loop through not complete array

      //           //Find the first not completed

      //           //get url of the first not completed



                
      //           grassblade_debug("current topic is completed" );
      //           grassblade_debug("NOT COMPLETED ID " . $get_not_completed_id );

      //         }


             
      
      //         grassblade_debug("PROGRESS LESSONS " .  print_r($courseProgressLessons, true) );
      //         grassblade_debug("NEW PROGRESS " . print_r( learndash_get_course_progress(null, 3992), true ) );
      //         grassblade_debug( "MY COURSE PROGRESS " . print_r($course_progress, true) );
      //         grassblade_debug( 'topics ' . print_r($topic, true)); 

      //         grassblade_debug( 'current_topic_id ' . $current_topic_id); 
      //         grassblade_debug("prevCompleted " .  $prevCompleted);
      //         grassblade_debug("prevNotCompleted " .  $prevNotCompleted);
      //         grassblade_debug("thisCompleted " .  $thisCompleted);
      //         grassblade_debug("lessons ID " .  $lessonID);
      //         grassblade_debug("lessons ID other " .  $lesson_id);
              

              

      //         //check if a previous topic is completed 
              
      //         if ( $prevNotCompleted == 1 ) {

      //           //find ID of the first previous not completed topic
      //           $topicId = $topic->ID;

      //           $lessons_list = learndash_get_lesson_list($topicId);
      //           grassblade_debug("lessons_list " . print_r($lessons_list, true));
                
      //           //redirect to first previous not completed topic
      //           //$next = learndash_next_post_link('', true, $topicId);
      //           $link = get_permalink($topicId);

      //           grassblade_debug("not completed topic ID " . $topicId);
      //           grassblade_debug("prev topic is not completed ");
      //           grassblade_debug("link " . $link);



      //         } else {

      //           grassblade_debug("prev topic is completed ");

    
      //           //if there are still not completed topic = not empty

      //             //redirect to next topic not completed return ''


      //         }

     
        //} //loop through the topic posts

      //} //loop through the lesson posts

     
      


      //test to see if both lesson and topic are completed
      if ( $the_topic_is_completed == 1 && $the_lesson_is_completed == 1  ) {

          //both lesson and topic are completed, we return the success = true
          @http_response_code($status_code);
          @header('Content-Type: application/json; charset=' . get_option('blog_charset'), true);
          echo json_encode(array(
            'success' => true
          ));

      }//end if



    }//end if $topic->post_type == "sfwd-topic"


      exit;

      
  }//close mark_complete() function





  function wp_head_config() {
      global $post;

      if (is_null($post)) { return; }

      $config = array(
        'ajax_url' => preg_replace('/http:/', 'https:', admin_url('admin-ajax.php')),
        'topic_id' => $post->ID
        //'topic_post' => $post
      );
      echo "<script>var learndash_custom = " . json_encode($config, JSON_UNESCAPED_SLASHES) . ";</script>";

  }



 

}

$learndash_cust = new learndash_custom();
  


 ?>