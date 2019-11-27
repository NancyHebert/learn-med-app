<?php get_template_part('templates/head'); ?>
<body <?php body_class(); ?> data-course-id="<?php echo $course_id ?>" >

<?php get_template_part('templates/browser-update-notice'); ?>

<p><a href="#content" class="sr-only"><?php _e("Skip to main content", 'learn.med'); ?></a></p>
<?php
    do_action('get_header');
    // Use Bootstrap's navbar if enabled in config.php
    if (current_theme_supports('bootstrap-top-navbar')) {
      get_template_part('templates/header-top-navbar');
    } else {
      get_template_part('templates/header');
    }
  ?>

  <div class="wrap container" role="document">
    <div class="content row">

      <main class="main <?php echo roots_main_class(); ?>" role="main">

      <div class="alert alert-warning">
        <?php _e('Sorry, but the course or page you were trying to view has been removed.', 'roots'); ?>
      </div>

      </main><!-- /.main -->
    </div><!-- /.content -->

  </div><!-- /.wrap -->


<?php wp_footer(); ?>

</body>
</html>