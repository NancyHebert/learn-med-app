<?php
/*
Template Name: coursesList
*/
?>



	<?php while ( have_posts() ) : the_post();  ?>
<header>


 <h1 role="heading" title="Learn.Med" aria-level="1" taborder="-1"  >
           <h1 role="heading"><?php the_title(); ?></h1>
 

</header>


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

<h2 role="heading"><?php _e("Course List", 'learn.med'); ?></h2>

  <?php $args = array( 'post_type' => 'sfwd-courses', 'category_name' => 'clerkship', 'posts_per_page' => 10 );
      $loop = new WP_Query( $args );
      while ( $loop->have_posts() ) : $loop->the_post();  ?>

  <article id="page" <?php post_class(); ?>>

    <dl>

      <dt class="course-list-title "><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></dt>
      <dd class="entry-content"><?php the_excerpt(); ?> </dd>
      <dt class="button-enroll"><a href="#" role="link" class="btn btn-primary"><? _e('Take the course!', 'learndash'); ?></a></dt>

	</dl>

  </article>

<?php endwhile; ?>



</section>
