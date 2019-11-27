<style>

.arrow_box {
  position: relative;
  background: #fff;
  border: 4px solid #f2f0ef;
}
.arrow_box:after, .arrow_box:before {
  bottom: 100%;
  left: 50%;
  border: solid transparent;
  content: " ";
  height: 0;
  width: 0;
  position: absolute;
  pointer-events: none;
}

.arrow_box:after {
  border-color: rgba(255, 255, 255, 0);
  border-bottom-color: #fff;
  border-width: 30px;
  margin-left: -30px;
}
.arrow_box:before {
  border-color: rgba(255, 255, 255, 0);
  border-bottom-color: #f2f0ef;
  border-width: 36px;
  margin-left: -36px;
}

</style>






<a name="top"></a>

<section id="moduleCategories">

	<div class="container 
 
		<?php

			if (!is_user_logged_in()) {
			  echo "logged-out";
			} elseif (is_current_user_logged_in_as_external()) {
			  echo "logged-in-as-external";
			} elseif (!is_current_user_logged_in_as_affiliated_and_can_view_post(get_id_by_slug('residency'))) {
			  echo "logged-in-as-affiliated-cant-view-residency";
			}

		?>" >

		<h2 role="heading" aria-level="2" class="hidden-lg hidden-md hidden-sm visible-xs"> 
 			<?php _e( 'Scroll to category choices' , 'learn.med'); ?> 
 			<span class="fa fa-chevron-down"></span>
  		</h2>






		<!-- Start Row div for Two Columns for CPD  -->  
		<div class="row">
			
			<!-- Start of Column One for CPD  -->  
			<a href="<?php echo site_url(_slug('continuing-professional-development', 'page')) . '?cate=cpd_pdt' ?>" class="col-lg-6 col-md-6 col-sm-6 col-xs-12" role="button" id="CPDBox">

				<!-- Start image div for CPD  -->  
				
				
				<div class="image">
      			<img class="img-responsive img-circle center-block" src="<?php echo get_template_directory_uri(); ?>/assets/img/physicianPhotoV4.png" width="562" height="501" alt="<?php _e("Photograph of physician with patients in the background", 'learn.med'); ?>" description="<?php _e("Photograph of physician with patients in the background", 'learn.med'); ?>" />
      		</div>
				
				<!-- End image div for CPD  -->  

				<h3 role="heading" aria-level="3"> <?php echo _e("Continuing Professional Development", 'learn.med'); ?> </h3>

				<!-- Start Content div for CPD  -->
				<div class="contentCpd">

					<p class="mainTextCpd "><?php _e('As a practicing <span class="boldedCpd">physician</span> or <span class="boldedCpd">faculty member</span>, access <em class="free">free</em> modules to support your continuing medical education and faculty development. ', 'learn.med'); ?></p>

					<br clear="all">

					<p class="btnListCpd" role="button" ><?php _e("Browse the list of available modules", 'learn.med'); ?><span class="fa fa-chevron-right fa-fw"> </span></p>

				</div>
				<!-- End Content div for CPD  -->

			</a>
			<!-- End of Column One for CPD  -->  

			<!-- Start of Column two Medicine humanity  -->  
			<a href="<?php echo site_url(_slug('medicine-and-humanity-2', 'page')) . '?cate=medicine_humanity' ?>" class="col-lg-6 col-md-6 col-sm-6 col-xs-12" role="button" id="humanityBox">

      		<!-- Start image div for Medicine humanity  -->  
      		<div class="image">
      			<img class="img-responsive img-circle center-block" src="<?php echo get_template_directory_uri(); ?>/assets/img/humanityV2.png" width="562" height="501" alt="<?php _e("Photograph of thinker/philosopher", 'learn.med'); ?>" description="<?php _e("Photograph of thinker/philosopher", 'learn.med'); ?>" />
      		</div>
      		<!-- End image div for Medicine humanity  -->  

       		<h3 role="heading" aria-level="3"> <?php echo _e("Medicine and Humanity", 'learn.med'); ?> </h3>
     		
     		<!-- Start Content div for Medicine humanity  -->
			<div class="contentCpd">

				<p class="mainTextHumanity "><?php _e('As a <span class="boldedCpd">clinician</span> or a <span class="boldedCpd">faculty</span> or a <span class="boldedCpd">student</span>, access <em class="free">free</em> reflexive modules on Medicine and Humanities. ', 'learn.med'); ?></p>

				<br clear="all">
			

				<p class="btnListHumanity" role="button" ><?php _e("Browse the list of available modules", 'learn.med'); ?><span class="fa fa-chevron-right fa-fw"> </span></p>

			</div>
    		<!-- End Content div for Medicine humanity  -->

    </a>

  </div>
  <!-- End of Row for two Columns for CPD  -->  
    
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  

  
    
      
 <div class="row">
  <?php
      $is_undergrad_one_big_button =  is_current_user_logged_in_as_affiliated_and_can_view_post(get_id_by_slug(_slug('undergraduate-medical-education/', 'page')));
  ?> 
  <?php if ($is_undergrad_one_big_button): ?>
    <a href="<?php echo site_url(_slug('undergraduate-medical-education', 'page'))?>" class="col-lg-6 col-md-6 col-sm-6 col-xs-12" role="button" id="undergradBox">
  <?php else: ?>
  <div id="undergradBox" class="has-secondary-options col-lg-6 col-md-6 col-sm-6 col-xs-12" >
  <?php endif; ?>
    
    <div class="image"><img class="img-responsive img-circle center-block" src="<?php echo get_template_directory_uri(); ?>/assets/img/studentPhotoV2.png" width="562" height="501" alt="<?php _e("Photograph of undergraduate students", 'learn.med'); ?>" description="<?php _e("Photograph of undergraduate students", 'learn.med'); ?>" /></div>

    <h3 role="heading" aria-level="3">
      <?php echo _e("Undergraduate Medical Education", 'learn.med'); ?> 
    </h3>

    <div class="contentCpd">

      <p class="mainTextUndergrad "><?php _e('If you are an <span class="boldedCpd">undergrad student</span>, this is the place to find the modules needed to be completed during your training. ', 'learn.med'); ?></p>

      <br clear="all">

  <?php if ($is_undergrad_one_big_button): ?>

      <p class="btnListUndergrad" role="button" ><?php _e("Browse the list of available modules", 'learn.med'); ?><span class="fa fa-chevron-right fa-fw"> </span></p>
  
  <?php elseif (!is_user_logged_in()): ?>

      <a href="/<?php echo _slug('login'); ?>/?login_as=affiliated&redirect_to=<?php echo urlencode( "/" . _slug('undergraduate-medical-education') . "/"); ?>">
          <p class="btnListUndergrad " role="button">
            <?php _e("Sign in using your <em>uOttawa account</em>", "learn.med"); ?><span class="fa fa-chevron-right fa-fw"> </span>
          </p>
        </a>

        <p class="secondaryTextPostGrad ">
          <span class="fa fa-info-circle fa-fw"> </span>
          <?php _e("Don't have a <a href='#''   tabindex='1'  aria-controls='uottawa' data-trigger='hover' data-placement='top'  title='' rel='popover' data-toggle='popover'  data-popover-content='#uottawa1'><em>uOttawa account</em>?</a>", "learn.med"); ?>
          <a class="requestAccess" href="<?php _e("http://www.med.uottawa.ca/medtech/help/", "learn.med"); ?>">
            <?php _e("Request access to the modules", "learn.med"); ?> <span class="fa fa-external-link "> </span>
          </a>
        </p>
  <?php endif; ?>


    </div>

  <?php if ($is_undergrad_one_big_button): ?>
  </a>
  <?php else: ?>
  </div>
  
  <?php endif; ?>


    <?php
      $is_residency_one_big_button =  is_current_user_logged_in_as_affiliated_and_can_view_post(get_id_by_slug(_slug('residency', 'page')));
    ?>
    <?php if ($is_residency_one_big_button): ?>
    <a href="<?php echo site_url(_slug('residency', 'page')) ?>" class="col-lg-6 col-md-6 col-sm-6 col-xs-12" role="button"
      id="RESBox">
    <?php else: ?>
    <div id="RESBox" class="has-secondary-options col-lg-6col-md-6 col-sm-6 col-xs-12" >
    <?php endif; ?>


    
    
    
    <div class="image"> <img class="img-responsive img-circle center-block" src="<?php echo get_template_directory_uri(); ?>/assets/img/residentsPhotoV9.png" width="562" height="501" alt="<?php _e("Photograph of physician with patients in the background", 'learn.med'); ?>" description="<?php _e("Photograph of physician with patients in the background", 'learn.med'); ?>" /></div>

    <h3 role="heading" aria-level="3" >

      <?php echo _e("Postgraduate Medical Education", 'learn.med'); ?> 

    </h3>

  
    <div class="contentPostGrad">

        <p class="mainTextPostGrad"><?php _e("If you are a <span class='boldedRes'>resident</span> on rotation at a Hospital, this is the place to find the modules needed to be completed during your residency.", 'learn.med'); ?></p>


       <br clear="all">

      <?php if ($is_residency_one_big_button): ?>
         <p class="btnListPostGrad " role="button"><?php _e("Browse the list of available modules", 'learn.med'); ?><span class="fa fa-chevron-right fa-fw"> </span></p>
      <?php elseif (!is_user_logged_in()): ?>
        <a href="/<?php echo _slug('login'); ?>/?login_as=affiliated&redirect_to=<?php echo urlencode( "/" . _slug('residency') . "/"); ?>">
          <p class="btnListPostGrad " role="button">
            <?php _e("Sign in using your <em>uOttawa account</em>", "learn.med"); ?><span class="fa fa-chevron-right fa-fw"> </span>
          </p>
        </a>

        <p class="secondaryTextPostGrad ">
          <span class="fa fa-info-circle fa-fw"> </span>
          <?php _e("Don't have a <a href='#''   tabindex='1'  aria-controls='uottawa' data-trigger='hover' data-placement='top'  title='' rel='popover' data-toggle='popover'  data-popover-content='#uottawa1'><em>uOttawa account</em>?</a>", "learn.med"); ?>
          <a class="requestAccess" href="<?php _e("http://www.med.uottawa.ca/medtech/help/", "learn.med"); ?>">
            <?php _e("Request access to the modules", "learn.med"); ?> <span class="fa fa-external-link "> </span>
          </a>
        </p>
      <?php elseif (is_current_user_logged_in_as_external()): ?>
        <p class="secondaryTextPostGrad ">
          <span class="fa fa-info-circle fa-fw"> </span>
          <?php _e("It looks like you're not logged in using a <a href='#'' tabindex='2'  aria-controls='uottawa' data-trigger='hover' data-placement='top'  title='' rel='popover' data-toggle='popover' data-popover-content='#uottawa2'><em>uOttawa account</em></a>, which is needed to login as a resident.", "learn.med"); ?>
          <a class="btn btn-red btn-md" href="<?php echo wp_logout_url('/' . _slug('login') . '/?login_as=affiliated' ); ?>">
            <?php _e("Logout and log back in with your <em>uOttawa account</em>", "learn.med"); ?> <span class="fa fa-chevron-right fa-fw">
          </a>
        </p>

        <p class="secondaryTextPostGrad ">
          <?php _e("Don't have a <a href='#''   tabindex='3'  aria-controls='uottawa' data-trigger='hover' data-placement='top'  title='' data-toggle='popover' rel='popover' data-popover-content='#uottawa3'><em>uOttawa account</em></a>", "learn.med"); ?>
          <a class="requestAccess btn btn-gray btn-md" href="<?php _e("http://www.med.uottawa.ca/medtech/help/", "learn.med"); ?>">
            <?php _e("Request access to the modules", "learn.med"); ?> <span class="fa fa-external-link"> </span></a>
        </p>
      <?php elseif (!is_current_user_logged_in_as_affiliated_and_can_view_post(get_id_by_slug('residency'))): ?>
        <p class="secondaryTextPostGrad ">
          <span class="fa fa-info-circle fa-fw"> </span>
          <?php _e("Are you a preceptor or a <span class='boldedRes'>resident</span> and you weren't given access to the Postgraduate Medical Education modules? If so,", "learn.med"); ?>
          <a href="<?php _e("http://www.med.uottawa.ca/medtech/help/", "learn.med"); ?>">
            <?php _e("request access to the modules", "learn.med"); ?> <span class="fa fa-external-link "> </span>
          </a>
        </p>
      <?php endif; ?>

      <div id="uottawa1" tabindex="9"  aria-hidden="true" class="hide">
          <?php _e('<p>If you are in any way <span class="bolded">affiliated to the Faculty of Medicine</span>, uOttawa (<span class="bolded">clinician</span>, <span class="bolded">professor</span>, <span class="bolded">resident</span>, <span class="bolded">fellow</span>, <span class="bolded">graduate</span>, <span class="bolded">student</span> or <span class="bolded">admin staff</span>), you <span class="bolded">have an account</span> with us. </p>', "learn.med")?>
      </div>

      
      <div id="uottawa2" tabindex="9"  aria-hidden="true" class="hide">
          <?php _e('<p>If you are in any way <span class="bolded">affiliated to the Faculty of Medicine</span>, uOttawa (<span class="bolded">clinician</span>, <span class="bolded">professor</span>, <span class="bolded">resident</span>, <span class="bolded">fellow</span>, <span class="bolded">graduate</span>, <span class="bolded">student</span> or <span class="bolded">admin staff</span>), you <span class="bolded">have an account</span> with us. </p>', "learn.med")?>   
      </div>

      <div id="uottawa3" tabindex="9"  aria-hidden="true" class="hide">
          <?php _e(' <p>If you are in any way <span class="bolded">affiliated to the Faculty of Medicine</span>, uOttawa (<span class="bolded">clinician</span>, <span class="bolded">professor</span>, <span class="bolded">resident</span>, <span class="bolded">fellow</span>, <span class="bolded">graduate</span>, <span class="bolded">student</span> or <span class="bolded">admin staff</span>), you <span class="bolded">have an account</span> with us. </p>', "learn.med")?>  
      </div>
 
 </div>       
  
  
  
  
  
  

  
  

  
    
   <div class="row">
  <?php
      $is_undergrad_one_big_button =  is_current_user_logged_in_as_affiliated_and_can_view_post(get_id_by_slug(_slug('global-health/', 'page')));
  ?> 
  <?php if ($is_undergrad_one_big_button): ?>
    <a href="<?php echo site_url(_slug('global-health', 'page'))?>" class="col-lg-6 col-md-6 col-sm-6 col-xs-12" role="button" id="undergradBox">
  <?php else: ?>
  <div id="undergradBox" class="has-secondary-options col-lg-12 col-md-12 col-sm-12 col-xs-24" >
  <?php endif; ?>
   </div>
    
    <div class="image"><img class="img-responsive img-circle center-block" src="<?php echo get_template_directory_uri(); ?>/assets/img/global-health.png" width="562" height="501" alt="<?php _e("Photograph of Global Health", 'learn.med'); ?>" description="<?php _e("Photograph of Global Health", 'learn.med'); ?>" /></div>

    <h3 role="heading" aria-level="3">
      <?php echo _e("Global Health", 'learn.med'); ?> 
    </h3>

    <div class="contentCpd">

      <p class="mainTextUndergrad "><?php _e('If you are a <span class="boldedCpd">Global Health Concentration program student</span>, this is the place to find the modules needed to be completed during your training. ', 'learn.med'); ?></p>

      <br clear="all">

  <?php if ($is_undergrad_one_big_button): ?>

      <p class="btnListUndergrad" role="button" ><?php _e("Browse the list of available modules", 'learn.med'); ?><span class="fa fa-chevron-right fa-fw"> </span></p>
  
  <?php elseif (!is_user_logged_in()): ?>

      <a href="/<?php echo _slug('login'); ?>/?login_as=affiliated&redirect_to=<?php echo urlencode( "/" . _slug('global-health') . "/"); ?>">
          <p class="btnListUndergrad " role="button">
            <?php _e("Sign in using your <em>uOttawa account</em>", "learn.med"); ?><span class="fa fa-chevron-right fa-fw"> </span>
          </p>
        </a>

        <p class="secondaryTextPostGrad ">
          <span class="fa fa-info-circle fa-fw"> </span>
          <?php _e("Don't have a <a href='#''   tabindex='1'  aria-controls='uottawa' data-trigger='hover' data-placement='top'  title='' rel='popover' data-toggle='popover'  data-popover-content='#uottawa1'><em>uOttawa account</em>?</a>", "learn.med"); ?>
          <a class="requestAccess" href="<?php _e("http://www.med.uottawa.ca/medtech/help/", "learn.med"); ?>">
            <?php _e("Request access to the modules", "learn.med"); ?> <span class="fa fa-external-link "> </span>
          </a>
        </p>
  <?php endif; ?>


    </div>

  <?php if ($is_undergrad_one_big_button): ?>
  </a>
  <?php else: ?>
  </div>
  
  <?php endif; ?>

    
</div>
 
 
 </div>
    
  

</section>


<!-- <div class="quote-container">
          <i class="pin"></i>
            <blockquote class="note yellow">
              We hope you enjoy learning with us and will continue to work to improve your learning  experience.
            <cite class="author"> - The Medtech team </cite>
          </blockquote> 
        </div> -->










<div id="topPageRes" class="col-xs-12 col-sm-12 col-md-12 col-lg-12 ">
    <p> <a href="#top" class="arrow_box"><!-- <span class="fa fa-chevron-up"></span> -->  <?php _e("Top of the page", 'learn.med'); ?></a></p>
</div>





</div>
</section>

</div>
