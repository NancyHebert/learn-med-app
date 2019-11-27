<?php 

   echo do_shortcode("[surveyPopup]");

?>

<?php while (have_posts()) : the_post(); ?>
  <article <?php post_class(); ?>>


    <h2 role="heading" aria-level="2" class="entry-title"><?php the_title(); ?></h2>


    <div class="entry-content">


      <?php the_content(); ?>
    </div>

    <!--<footer>
      <?php //wp_link_pages(array('before' => '<nav class="page-nav"><p>' . __('Pages:', 'roots'), 'after' => '</p></nav>')); ?>
    </footer>-->

    <?php wp_link_pages(array('before' => '<nav class="page-nav"><p>' . __('Pages:', 'roots'), 'after' => '</p></nav>')); ?>


    <?php if ( comments_open() ) : ?>

     	<?php comments_template('/templates/comments.php'); ?>

     	<?php else : // comments are closed ?>

     	<hr>
      
    <?php endif; ?>

  </article>
<?php endwhile; ?>
