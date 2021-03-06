<?php get_template_part('templates/head'); ?>
<body <?php body_class();  ?> >

<?php get_template_part('templates/browser-update-notice'); ?>

  <a href="#content" class="sr-only"><?php _e("Skip to main content", 'learn.med'); ?></a>


  <?php
    do_action('get_header');
    // Use Bootstrap's navbar if enabled in config.php
    if (current_theme_supports('bootstrap-top-navbar')) {
      get_template_part('templates/header-top-navbar-new');
    } else {
      get_template_part('templates/header');
    }
  ?>

  <div class="wrap container" role="document">

    <div class="content row" id="content">
      <main class="main <?php echo roots_main_class(); ?>" role="main"> 
        <?php include roots_template_path(); ?>

      </main> <!--/.main -->
      <?php if (roots_display_sidebar()) : ?>
        <aside class="sidebar <?php echo roots_sidebar_class(); ?>" role="complementary">
        
       
        
          <?php include roots_sidebar_path(); ?>
          
         
        </aside><!-- /.sidebar -->
      <?php endif; ?>
    </div><!-- /.content -->
  </div><!-- /.wrap -->

  <?php get_template_part('templates/footer'); ?>

</body>
</html>
