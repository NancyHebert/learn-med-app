

<section id="header" role="header">

      <div class="container">

        <div class="row">
            
          <div class="branding col-xs-12 col-sm-12 col-md-6 col-lg-5 ">

              <a href="<?php echo home_url('/') ?>" class="brand" role="link" title"<?php bloginfo('name'); ?>">
                <h1  role="heading" aria-level="1"><span class="fa fa-user-md fa-fw"></span><?php _e( 'Learn.Med' , 'learn.med'); ?></h1></a>
          <p class="tagline"><?php bloginfo('name'); ?> </p>
          </div>

          <div class="col-xs-12 col-sm-12 col-md-6 col-lg-7    theNav">

            <ul role="navigation" class="top-navigation">
            
               <!--    <li role="menuitem" tabindex="0" taborder="0" class="welcome">Welcome Nancy</li>
                  <a href="" role="button" class="btn btn-red btn-sm">
                    <li role="menuitem" tabindex="1" taborder="1" > Log out <span class="fa fa-sign-out fa-fw"> </span>
                    </li>
                  </a>
 --> 

                  <?php global $current_user;
                  get_currentuserinfo();

                    if (is_user_logged_in()) {
                        $user = wp_get_current_user();
                        
                      //echo '<li role="menuitem" class="homebtn" tabindex="1" taborder="1"><a href="' . home_url('/') . '" role="button" class="" ><span class="fa fa-home"></span>  Home</a></li>';
                       
                       echo '<li role="menuitem" tabindex="0" taborder="0" class="welcome">' . __("Welcome", 'learn.med') . ' ' . $current_user->user_firstname. '</li>';
// <a href="#" role="button" class="btn btn-red btn-sm">
                       echo '<li role="menuitem" class="signInLogoutBtn" tabindex="1" taborder="1">';
                       //echo _slug('logout');
                       wp_loginout( $_SERVER['REQUEST_URI'] );


                       echo ' <span class="fa fa-sign-out fa-fw"> </span> </li>';
                        echo language_toggle();

                        echo '</ul>';

                    } else {
                        
                        //echo '<li role="menuitem" class="homebtn" tabindex="1" taborder="1"><a href="' . home_url('/') . '" role="button" class="" ><span class="fa fa-home"></span>  Home</a></li>';
                       
                        echo '<li role="list-item" class="signInLoginBtn" tabindex="0" taborder="0" ><a href="';
                        echo icl_get_home_url() . _slug('login', 'page');
                        echo '" role="button" class="btn btn-red btn-lg"><span class="fa fa-users"></span> ';
        
                        //echo site_url(_slug('login', 'page'));
              
                        echo __('Sign in', 'learn.med');



                        echo '</a></li>';

                        echo language_toggle();
                        
                    


                    } ?>

            <!-- <a href="" role="button" class="btn btn-gray btn-sm"><li role="list-item">FR</li></a> -->
              
            </ul>
          </div>

        </div>
      </div>
</section>






  
