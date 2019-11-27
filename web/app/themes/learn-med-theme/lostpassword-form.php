<?php
/*
If you would like to edit this file, copy it to your current theme's directory and edit it there.
Theme My Login will always look in your theme's directory first, before using this default template.
*/
?>
<div class="container">

<div class="row">
	<!-- <div class="image col-lg-3 col-md-3 col-sm-6 col-xs-12" ><img class="img-responsive center-block" src="<?php echo get_template_directory_uri(); ?>/assets/img/lostPastMascot.svg" width="200" alt="<?php _e("Cartoon of a lock pointing to the instructions", 'learn.med'); ?>" description="<?php _e("Cartoon of a lock pointing to the instructions", 'learn.med'); ?>" /></div>
 -->
<!-- 	<section id="forgotPasswordContent" class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
		

		<h2 role="heading" aria-level"2"><?php _e('Forgot your password?', 'learn.med'); ?></h2>
		<p ><span class="bolded"><?php _e("Don't worry, simply reset it", "learn.med"); ?></span>, <?php _e("by following these simple directions.", "learn.med"); ?> <span class="fa fa-arrow-right"></span></p>
		
	</section>
 -->


		<div class="login col-lg-12 col-md-12 col-sm-12 col-xs-12" id="theme-my-login<?php $template->the_instance(); ?>">
			<div class="lostPasswordContent">
				<h2 role="heading" aria-level"2"><?php _e('Forgot your password?', 'learn.med'); ?></h2>
				<p ><span class="boldedOther"><?php _e("Don't worry, you can easily reset it", "learn.med"); ?></span>.</p>
			
			<!-- 	<p class="message"></span> <?php _e("Please enter the email address that you used to create your profile.", 'learn.med'); ?>
					<br><?php _e("We will send you a link to create a new password via email.", 'learn.med'); ?></p>
 -->
				 <div id="whyregister-lostPassword">


		            <!-- start of quote-container container -->
		             <div class="quote-container ">
		                <i class="pin"></i>
		                <blockquote class="note yellow">
		                   <span class="fa fa-info-circle"></span> 
		                  <?php _e("Please enter the email address that you used to create your profile.", 'learn.med'); ?>
		                  <br>
		                  <?php _e("We will send you a link to create a new password via email.", 'learn.med'); ?>
		             <!--      <cite class="author"> <?php _e("- The Faculty of Medicine", 'learn.med'); ?></cite>  -->
		                </blockquote> 
		              </div>  <!--end of quote-container container -->


		      </div>	

				<?php $template->the_errors(); ?>
				<form name="lostpasswordform" id="lostpasswordform<?php $template->the_instance(); ?>" action="<?php $template->the_action_url( 'lostpassword' ); ?>" method="post">
					<p>
						<label for="user_login<?php $template->the_instance(); ?>"><?php _e( 'Email:' , 'learn.med'); ?></label>
						<input type="text" name="user_login" id="user_login<?php $template->the_instance(); ?>" class="input" value="<?php echo ($_GET["email"])? $_GET["email"]: $template->get_posted_value( 'user_login' ); ?>" size="20" />
					</p>

					<?php do_action( 'lostpassword_form' ); ?>

					<p class="submit">
						<input type="submit" name="wp-submit" id="wp-submit<?php $template->the_instance(); ?>" value="<?php esc_attr_e( 'Give me a new password', 'learn.med' ); ?>" />
						<input type="hidden" name="redirect_to" value="<?php $template->the_redirect_url( 'lostpassword' ); ?>" />
						<input type="hidden" name="instance" value="<?php $template->the_instance(); ?>" />
						<input type="hidden" name="action" value="lostpassword" />
					</p>
				</form>

				<p class="lostPassReturnLink"><a href="<?php echo icl_get_home_url() . _slug('login', 'page'); ?>">
					<?php _e("Got here by mistake or remember your password. Just sign in", "learn.med"); ?></a></p>
				<!-- <div class="Steps">
					<p class="messageOther"><span class="bolded"><?php _e("Step 2: ", 'learn.med'); ?></span><?php _e("Check your email, we sent you a password reset link.", 'learn.med'); ?></p>
					<p class="messageOther"><span class="bolded"><?php _e("Step 3: ", 'learn.med'); ?></span><?php _e("Reset your password using reset link in your email.", 'learn.med'); ?></p>
				</div> -->
			</div>
		</div>
	</div>
</div>
