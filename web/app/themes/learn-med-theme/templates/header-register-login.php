<?php 

// grant the error reporting only to super admins
if (is_user_logged_in() && is_super_admin()) {
    error_reporting(-1);
}
else {
    error_reporting(0);
}
?>


<div class="row">
            
<div class="col-xs-12 col-sm-8 box">
<a role="link"  class="brand" href="<?php echo home_url('/') ?>">
  <h1 role="heading" title="<?php _e( 'Learn.Med' , 'learn.med'); ?>" aria-level="1" taborder="-1"  ><span class="fa fa-user-md"> </span> <?php _e( 'Learn.Med' , 'learn.med'); ?></h1></a>
  <p class="tagline"><?php bloginfo('name'); ?></p>
</div>

          <div class="col-xs-12 col-sm-4  box">

               

           <?php //global $current_user;
                  //get_currentuserinfo();

                    //if (is_user_logged_in()) {
                       // $user = wp_get_current_user();
                       echo '<ul role="menubar" class="top-navigation">';

                       //echo '<li role="menuitem" tabindex="1" class="logging">';
                       //echo _slug('logout');
                       //wp_loginout( $_SERVER['REQUEST_URI'] );



                       //echo ' <span class="fa fa-sign-out fa-fw"> </span> </li>';
                        echo language_toggle();
                        echo '</ul>';

                    //} 
                  ?>

           
            </div>

        </div>

<!-- <header class="banner container" role="banner">
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
 -->
