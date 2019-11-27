<?php get_template_part('templates/head'); ?>

<body <?php body_class(); ?>>



<?php get_template_part('templates/browser-update-notice'); ?>

<div class="wrap" role="document">
    <div class="content row">

      <main class="main" role="main">



<div class="container">




<section id="Login" role="region" class="col-md-12">



<article role="article" class="col-md-6 learning">


<img alt="" src="<?php echo get_template_directory_uri(); ?>/assets/img/students.jpg">

 <h1 role="heading" aria-level="1"><?php bloginfo('name'); ?></h1>


 

</article>


<article role="article" class="col-md-6 register">

<?php include roots_template_path(); ?>

</article>





</section>

</main>
</div>
</div>

  <?php get_template_part('templates/footer'); ?>

</body>
</html>
