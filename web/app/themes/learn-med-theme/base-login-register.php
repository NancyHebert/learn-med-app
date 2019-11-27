<?php get_template_part('templates/head'); ?>

<body <?php body_class(); ?> >

  <a href="#article" class="sr-only">Skip to main content</a>


<?php get_template_part('templates/browser-update-notice'); ?>

<!-- <div class="wrap" role="document">
    <div class="content row">

<main class="main" role="main">

<div class="container">

<div class="col-xs-12 col-md-12 col-lg-12">
	<h1 role="heading" aria-level="1" taborder="-1"  >
		   <span class="fa fa-user-md"> </span>  <?php bloginfo('name'); ?>
		</h1>
</div>
 -->


<section id="headerOther2" role="banner">

  <div class="container">

     <?php get_template_part('templates/header-register-login'); ?>


    </div>

</section>

<!-- id="Login" -->
<div id="register-login">
    	 <?php include roots_template_path(); ?>

  <?php get_template_part('templates/footer'); ?>

</body>
</html>
