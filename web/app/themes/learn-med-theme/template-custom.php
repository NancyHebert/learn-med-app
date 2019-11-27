<?php
/*
Template Name: Custom Template
*/
?>


<header>
<h1 role="heading"><?php the_title(); ?></h1>
</header>

<p class="entry-content"><?php the_content(); ?></p>



<section id="courses-list" class="col-md-12">



<?php while (have_posts()) : the_post(); ?>
  <article <?php post_class(); ?>>




    <dl>
      <dt class="course-list-title "><?php the_title(); ?></dt>
      </dt>
      <dd class="entry-content col-md-8"><?php the_excerpt(); ?> </dd>
      <dt class="button-enroll col-md-3"><a href="#" role="link" class="btn btn-primary">Enroll Now!</a></dt>
    </dl>




  </article>
<?php endwhile; ?>







</section>
