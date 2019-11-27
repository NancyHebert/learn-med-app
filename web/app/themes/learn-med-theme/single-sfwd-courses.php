<?php

echo do_shortcode("[surveyPopup]");

// To modify the code for this page, copy the contents of the templates/content-single-sfwd.php file and paste it in this file, overwriting all its contents
?>

<!-- <div id="instructions" class="modal hide fade">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h2>Instructions</h2>
  </div>
  <div class="modal-body">
    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.
    Donec placerat sem ipsum, ut faucibus nulla. Nullam mattis volutpat
    dolor, eu porta magna facilisis ut. In ultricies, purus sed pellentesque
    mollis, nibh tortor semper elit, eget rutrum purus nulla id quam.</p>
  </div>
</div>  -->




 <!-- Modal -->
  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h1 class="modal-title"><?php _e('Instructions', 'learndash'); ?></h1>
        </div>
        <div class="modal-body">
          <p>

            <?php
            global $wp_query;
            $postid = $wp_query->post->ID;
            $thumbnail = get_post_meta($post->ID, 'course_thumbnail_image', true);
            $creator = get_post_meta($post->ID, 'creator', true); ?>
            <p><?php echo get_post_meta($postid, 'specific_module_instructions', true);?></p>
            <?php
            wp_reset_query();
            ?>
    </p>




        </div>


        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal"><?php _e('Close', 'learndash'); ?></button>
         <!--  <button type="button" class="btn btn-primary">Save changes</button> -->
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->





<p class="homebtn"><a href="<?php echo home_url('/') ?>" role="button"><span class="fa fa-home"></span> <span class="homebtn-label"><?php _e( 'Home' , 'learn.med'); ?></span></a></p>

<?php while (have_posts()) : the_post(); ?>
  <article <?php post_class(); ?>>
    <header>





 <!--  <?php

  // get an image field (return type = object)


$image = get_field('partner_logos');

if( get_field('partner_logos') ) { ?>

    <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />



  <?php } ?>
 -->



      <br clear = "all">

      <h1 class="entry-title"><?php the_title(); ?></h1>

<?php
// global $post;
// $categories = get_the_category($post->ID);
// // var_dump('<pre>');
// // var_dump($categories);
// // var_dump('</pre>');


// foreach($categories as $category) {
//   $args = $category->category_nicename;
//   var_dump('<pre>');
//   var_dump( $args);
//   var_dump('</pre>');
// };


// get the category name
//$post_category_name = $args;

// var_dump('<pre>');
// var_dump($post_category_name);
// var_dump('</pre>');

// var_dump('<pre>');
// var_dump( $post_category_name->category_nicename );
// var_dump('</pre>');

?>



      <div class="entry-creator">
      <?php
          // global $wp_query;
          // $postid = $wp_query->post->ID;
          // echo get_post_meta($postid, 'creator', true);

          // wp_reset_query();
          ?>

   </div>

    </header>




   <div class="tabbable">


     <div class="tab-content">





       <div id="pane1" class="tab-pane active">


            <div class="entry-content">

               <?php the_content(); ?>



             </div>

             <!-- <footer>
               <?php //wp_link_pages(array('before' => '<nav class="page-nav"><p>' . __('Pages:', 'roots'), 'after' => '</p></nav>')); ?>
             </footer> -->


       </div>

     </div><!-- /.tab-content -->
   </div><!-- /.tabbable -->







  </article>
<?php endwhile; ?>
