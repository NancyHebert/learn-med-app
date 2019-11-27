<?php
/*
Template Name: No Status Flags Template
*/
?>


<header>
<h1 role="heading"><?php the_title(); ?></h1>
</header>

<p class="entry-content"><?php the_content(); ?></p>



<section id="courses-list" class="col-md-12">



<?php while (have_posts()) : the_post(); ?>
  <article <?php post_class(); ?>>




  </article>
<?php endwhile; ?>







</section>
