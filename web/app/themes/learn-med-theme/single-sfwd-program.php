<?php 



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
            $postid = $wp_query->post->ID; ?>

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








<?php while (have_posts()) : the_post(); ?>
  <article <?php post_class(); ?>>
    <header>
    
   
          <div class="thumbnail-image col-md-2" >

          <?php if( get_field('course_thumbnail_image') ):?>

          <img src="<?php the_field('course_thumbnail_image'); ?>"   alt="" /> 

          <?php endif;?>

          </div>


            <div class="instructorCredentials col-md-10" >
            <?php
            global $wp_query;
            $postid = $wp_query->post->ID; ?>

             <p class="name"><?php echo get_post_meta($postid, 'subject_matter_expert_name', true);?></p>
            
             <p><?php  echo get_post_meta($postid, 'creator', true);?></p>
            
            <?php
            wp_reset_query();
            ?>
           </div>

      <br clear = "all">

       <div class="entry-date"><?php get_template_part('templates/entry-meta'); ?></div>

      
      <h1 class="entry-title"><?php the_title(); ?></h1>

      
     

      <!--  <div class="entry-creator"> -->
      <?php
          // global $wp_query;
          // $postid = $wp_query->post->ID;
          // echo get_post_meta($postid, 'creator', true);

          // wp_reset_query();
          ?>

     <!--  </div> -->

    </header>
   
    
   
   
   <div class="tabbable">


     <div class="tab-content">


     


       <div id="pane1" class="tab-pane active">
       

            <div class="entry-content">

               <?php the_content(); ?>

              

             </div>

             <footer>
               <?php wp_link_pages(array('before' => '<nav class="page-nav"><p>' . __('Pages:', 'roots'), 'after' => '</p></nav>')); ?>
             </footer>
             
         
       </div>
       
     </div><!-- /.tab-content -->
   </div><!-- /.tabbable -->
   
    



    
   
  </article>
<?php endwhile; ?>




