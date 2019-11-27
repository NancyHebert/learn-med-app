<?php 

// grant the error reporting only to super admins
if (is_user_logged_in() && is_super_admin()) {
    error_reporting(-1);
}
else {
    error_reporting(0);
}
?>



   
<header class="banner container" role="banner">
  <div class="row">
    <div class="col-lg-12">
      <a class="brand" href="<?php echo home_url('/') ?>"><?php bloginfo('name'); ?></a>
      <nav class="nav-main" role="navigation">
        <?php
          if (has_nav_menu('primary_navigation')) :
            wp_nav_menu(array('theme_location' => 'primary_navigation', 'menu_class' => 'nav nav-pills'));
          endif;
        ?>
       
      </nav>
    </div>


  </div>




</header>

