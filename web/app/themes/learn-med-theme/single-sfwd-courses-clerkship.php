<?php
/*
Template Name: coursesListClerkship
*/
?>



	<?php while ( have_posts() ) : the_post();  ?>



<h2 role="heading" aria-level="2"><?php the_title(); ?></h2>




<p class="entry-content"><?php the_content(); ?></p>
<?php endwhile; ?>


<section id="courses-list" class="col-md-12">

<?php
//$args = array( 'post_type' => 'sfwd-courses', 'category_name' => 'cpd', 'posts_per_page' => 10 );
//$loop = new WP_Query( $args );
//while ( $loop->have_posts() ) : $loop->the_post();
//  the_title();
//  echo '<div class="entry-content">';
//  the_content();
//  echo '</div>';
//endwhile;

?>

<h3 role="heading" aria-level="3"><?php _e("Course List", 'learn.med'); ?></h3>

<?php $args = array( 'post_type' => 'sfwd-courses', 'category_name' => 'Clerkship', 'posts_per_page' => 10 );
      $loop = new WP_Query( $args );
      while ( $loop->have_posts() ) : $loop->the_post();  ?>

  <article id="page" <?php post_class(); ?>>

    <dl>

 <?php if( get_field('course_thumbnail_image') ):?>
      <img src="<?php the_field('course_thumbnail_image'); ?>" style="float:left;margin-right: 1em;"  alt="" />
      <?php endif;?>
      <dt class="course-list-title "><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></dt>

      <dd class="entry-content"><?php the_excerpt(); ?> </dd>
      <dt class="button-enroll"><a href="#" role="link" class="btn btn-primary">Suivez le cours!</a></dt>

	</dl>

  </article>

<?php endwhile; ?>



</section>
