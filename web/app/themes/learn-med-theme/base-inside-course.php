
<?php

$course_id = learndash_get_course_id($post->ID);
$course = get_post($course_id);

?>

<script>
function goBack() {
   event.preventDefault();
    window.history.back();
}
</script>

<?php get_template_part('templates/head'); ?>

<body <?php body_class( get_course_category_nicenames($course_id) );  ?> data-course-id="<?php echo $course_id ?>" >

<?php  //do_shortcode('[surveyPopup]'); ?>
<?php //add_survey_popup_shortcode(); ?>
<?php //do_shortcode('surveyPopup'); ?>

<?php get_template_part('templates/browser-update-notice'); ?>

<a href="#content" class="sr-only"><?php _e("Skip to main content", 'learn.med'); ?></a>
<?php
    do_action('get_header');
    // Use Bootstrap's navbar if enabled in config.php
    if (current_theme_supports('bootstrap-top-navbar')) {
      get_template_part('templates/header-top-navbar');
    } else {
      get_template_part('templates/header');
    }
  ?>


  <div class="wrap container" role="document">
    <div class="content row">

      <main class="main  col-xs-12 col-md-8" role="main">

<p class="homebtn"><a href="<?php echo get_permalink($course_id); ?>" class="btnBackToCourses" ><span class="fa fa-chevron-left"></span> <span class="homebtn-label"><?php _e('Return to course description', 'learndash'); ?></span></a>  | <a href="<?php echo home_url('/') ?>" role="button"><span class="fa fa-home"></span> <span class="homebtn-label"><?php _e( 'Home' , 'learn.med'); ?></span></a></p>
       <!--  <p class="homebtn"> <span class="fa fa-chevron-left"></span>  <a href="#" onclick="goBack()" ><?php _e('Previous', 'learndash'); ?></a>  | <span class="fa fa-home"></span> <a href="<?php echo home_url('/') ?>" role="button">Home</a></p>
 -->
<!-- <div class="btnBackToCourses"><a href="<?php echo get_permalink($course_id); ?>" class="btn btn-primary" ><?php _e('Return to course description', 'learndash'); ?></a> </div>
 -->

<?php global $wp_query;
      $postid = $wp_query->post->ID;

$objectives = get_post_meta($post->ID, 'learning_objectives', true);
$resources = get_post_meta($post->ID, 'additional_resources', true);
$transcript = get_post_meta($post->ID, 'transcript', true);

$objectives2 = get_post_meta($post->ID, 'wpcf-learning_objectives2', true);
$resources2 = get_post_meta($post->ID, 'wpcf-advanced_resources2', true);
$transcript2 = get_post_meta($post->ID, 'wpcf-transcript2', true);

?>




    <div class="videotabs">

            <ul class="nav nav-tabs" id="myTab">

            <?php if($objectives2 != '') { ?> <li class="objectives active" ><a href="#objectives" data-toggle="tab" ><?php _e("Objectives", 'learn.med'); ?></a></li> <?php }?>
            <?php if($resources2 != '') { ?>   <li class="resources" ><a href="#resources" data-toggle="tab" ><?php _e("Resources", 'learn.med'); ?></a></li> <?php }?>
            <?php if($transcript2 != '') { ?>   <li class="transcripts" ><a href="#transcripts" data-toggle="tab" ><?php _e("Transcript", 'learn.med'); ?></a></li> <?php }?>

            </ul>


          <div class="tab-content">

          <?php if($objectives2 != '') { ?>

          <div class="tab-pane active" id="objectives">

              <p>

              <?php echo get_post_meta($postid, 'wpcf-learning_objectives2', true);  ?>

              </p>

            </div>

           <?php }?>

           <?php if($resources2 != '') { ?>

            <div class="tab-pane" id="resources">

              <p>

              <?php echo get_post_meta($postid, 'wpcf-advanced_resources2', true);  ?>

              </p>

            </div>

          <?php }?>


          <?php if($transcript2 != '') { ?>

            <div class="tab-pane " id="transcripts">

              <p>

              <?php echo get_post_meta($postid, 'wpcf-transcript2', true);  ?>

              </p>

            </div>

          <?php }?>

          </div>



      </div>



 <?php   wp_reset_query(); ?>








<div class="sectionCompleted"><?php _e("Section Completed", 'learn.med'); ?></div>




        <?php include roots_template_path(); ?>





      </main><!-- /.main -->
      <?php if (roots_display_sidebar()) : ?>
        <aside class="sidebar  col-xs-12 col-md-4" role="complementary">




          <?php include roots_sidebar_path(); ?>


        </aside><!-- /.sidebar -->
      <?php endif; ?>
    </div><!-- /.content -->







  </div><!-- /.wrap -->





<?php

$args = array( 'post_type' => 'sfwd-courses');
$loop = new WP_Query( $args );



?>


<?php wp_footer(); ?>

  <?php get_template_part('templates/footer'); ?>




</body>
</html>
