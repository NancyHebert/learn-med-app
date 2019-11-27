<?php get_template_part('templates/head'); ?>

<body <?php body_class(); ?>>

<?php get_template_part('templates/browser-update-notice'); ?>

  <div class="wrap container" role="document">
    <div class="content row">

      <main class="main <?php echo roots_main_class(); ?>" role="main">

        <div class="btnBackToCourses"><a href="<?php echo site_url(_slug('undergraduate-medical-education', 'page')); ?>" class="btn btn-primary" ><?php _e("Return to Undergraduate Medical Education ", 'learn.med'); ?></a>

        </div>


<?php
            global $wp_query;
            $postid = $wp_query->post->ID;
 

$transcript = get_post_meta($post->ID, 'transcript', true);
$resources = get_post_meta($post->ID, 'additional_resources', true);

?>
  
  <?php if($transcript != '' &&  $resources != '') { ?>   

       <div class="videotabs">

            <ul class="nav nav-tabs" id="myTab">
              <li class="resources" ><a href="#Resources" data-toggle="tab" ><?php _e("Resources", 'learn.med'); ?></a></li>
              <li class="transcripts" ><a href="#transcripts" data-toggle="tab" ><?php _e("Transcript", 'learn.med'); ?></a></li>
            </ul>


          <div class="tab-content">

            <div class="tab-pane" id="Resources"> 

              <p>

              <?php echo get_post_meta($postid, 'additional_resources', true);  ?>

              </p> 

            </div>

            <div class="tab-pane " id="transcripts"> 

              <p>

              <?php echo get_post_meta($postid, 'transcript', true);  ?>

              </p> 

            </div>
           
          </div>

      </div>

  <?php } else if($transcript != '' &&  $resources == '') { ?>


         <div class="videotabs">

             <ul class="nav nav-tabs" id="myTab">

                <li class="transcripts" ><a href="#transcripts" data-toggle="tab" ><?php _e("Transcript", 'learn.med'); ?></a></li>

             </ul>

            <div class="tab-content">

               <div class="tab-pane " id="transcripts"> <p>

                <?php echo get_post_meta($postid, 'transcript', true);  ?>

                  </p> 

              </div>

            </div>

        </div>



 <?php  }  wp_reset_query(); ?>








<div class="sectionCompleted"><?php _e("Section Completed", 'learn.med'); ?></div>




        <?php include roots_template_path(); ?>




        
      </main><!-- /.main -->
      <?php if (roots_display_sidebar()) : ?>
        <aside class="sidebar <?php echo roots_sidebar_class(); ?>" role="complementary">
        
       

        
          <?php include roots_sidebar_path(); ?>
          
         
        </aside><!-- /.sidebar -->
      <?php endif; ?>
    </div><!-- /.content -->







  </div><!-- /.wrap -->





<?php

$args = array( 'post_type' => 'sfwd-courses');
$loop = new WP_Query( $args );

// $newargs = array( 'post_type' => 'sfwd-lessons', 'numberposts' => '5', 'exclude' => '2' );
// $recent_posts = wp_get_recent_posts( $newargs );
// foreach( $recent_posts as $recent ){
//     //echo '<li><a href="' . get_permalink($recent["ID"]) . '" title="Look '.esc_attr($recent["post_title"]).'" >' .   $recent["post_title"].'</a> </li> ';
// }

?>




<?php //if ( $post->ID = $recent["ID"] ) { 

// echo "this is the latest";
// echo $post->ID;


// } else {

//   echo "this is not the latest";
//   echo $recent["ID"];
// }

?>

<?php wp_footer(); ?>

</body>
</html>