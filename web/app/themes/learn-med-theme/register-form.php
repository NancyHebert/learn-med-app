<?php
/*
If you would like to edit this file, copy it to your current theme's directory and edit it there.
Theme My Login will always look in your theme's directory first, before using this default template.
*/
?>

<style>

  body.register p.icons { font-size: 4em!important; }



</style>





<section  role="region" id="registerFormInfo" class="container" >

  <?php $template->the_errors(); ?>


	<!-- Start of form container div -->
  <div class="registerForm col-lg-12 col-md-12 col-sm-12 col-xs-12" id="registration">

    <div class="login " id="theme-my-login">




 <div id="whyregister-register">


            <!-- start of quote-container container -->
             <div class="quote-container ">
                <i class="pin"></i>
                <blockquote class="note yellow">
                  <span class="fa fa-info-circle"></span> <?php _e("Get full access to accredited modules & courses", 'learn.med'); ?>.
                  <!-- <cite class="author"> <?php _e("- The Faculty of Medicine", 'learn.med'); ?></cite> -->
                </blockquote>
              </div>  <!--end of quote-container container -->


        </div>

      <h2 role="heading" aria-level="2">
        <?php _e("Create your profile <span class='fullAccess2'> & start your learning experience</span>", 'learn.med'); ?>
        </h2>



      <!-- Start of form -->
      <form name="registerform" id="registerform" action="<?php $template->the_action_url( 'register' ); ?>" method="post">



          <!-- Attest that I am not affiliated with the faculty of medicine-->
          <div id="confirm">



            <!--start of attest content area -->
            <div>



              <p>
          <?php _e("Hey, if you are in any way ", 'learn.med'); ?>
          <a href="#"  id="affiliatedLink1"  data-toggle="popover"  rel="popover" data-popover-content="#affiliated" title="Important Note"  data-trigger="hover" data-placement="bottom" class="importantLink" aria-controls="affiliated" tabindex="1" ><?php _e("affiliated with the Faculty of Medicine, uOttawa ", 'learn.med'); ?> <span class="fa fa-question-circle"></span></a>
           <?php _e(", you probably have an account with us. Using your uOttawa account has ", 'learn.med'); ?>
           <a href="#"  id="advantages" data-toggle="popover" rel="popover" title="Important Note"   data-trigger="hover" data-popover-content="#multipleAdvantages"   data-placement="bottom"  tabindex="3" aria-controls="multipleAdvantages" class="importantLink">
            <?php _e("multiple advantages", 'learn.med'); ?> <span class="fa fa-question-circle"></span></a>.


                </p>



              <p>

                <a class="btn btn-red  btn-md" href="<?php echo site_url(_slug('login', 'page')); ?>"> <?php _e("Sign in", 'learn.med'); ?></a>
                <?php _e(" to your uOttawa account or", 'learn.med'); ?>

              <a class="btn  btn-gray btn-sm  " href="<?php _e('https://app.med.uottawa.ca/PasswordReset/', 'learn.med'); ?>"><?php _e("Reset your password", 'learn.med'); ?></a>
              <?php _e(" if you do not remember it.", 'learn.med'); ?> </p>

              <div id="affiliated" tabindex="2" class="hide" aria-hidden="true" >

                  <p><?php _e(" You are a", 'learn.med'); ?>
                    <span class="bolded"><?php _e(" clinician", 'learn.med'); ?></span>,
                     <span class="bolded"><?php _e(" preceptor", 'learn.med'); ?></span>,
                    <span class="bolded"><?php _e(" professor", 'learn.med'); ?></span>,
                    <span class="bolded"><?php _e(" resident", 'learn.med'); ?></span>,
                    <span class="bolded"><?php _e(" fellow", 'learn.med'); ?> </span>,
                    <span class="bolded"><?php _e(" graduate", 'learn.med'); ?> </span>,
                    <span class="bolded"><?php _e(" student", 'learn.med'); ?></span>
                    <?php _e(" or", 'learn.med'); ?>
                    <span class="bolded"><?php _e(" admin staff", 'learn.med'); ?> </span>.
                  </p>

              </div>


              <div id="multipleAdvantages" class="hide" tabindex="4"  aria-hidden="true">

                <p><span class="bolded"><?php _e("Advantages", 'learn.med'); ?> </span>
                  <?php _e(" to logging in with your", 'learn.med'); ?> <span class="bolded">
                  <?php _e(" uOttawa email account:", 'learn.med'); ?></span> </p>

              <ul role="list">
               <li role="listitem"><span class="fa fa-check"></span>  <?php _e(" Results attached to your uOttawa profile", 'learn.med'); ?></li>
                <li role="listitem"><span class="fa fa-check"></span> <?php _e(" Better tracking and reporting on your progress", 'learn.med'); ?></li>
              </ul>

              </div>


          </div><!--end of attest content area-->


        </div>


        <div id="confirmation">
           <label for="attest"><input type="checkbox" name="attest" id="attest"  value="<?php _e(" I attest that I do not have an affiliation or appointment with the Faculty of Medicine, uOttawa", 'learn.med'); ?>" tabindex="1" required="">
              <span class="requiredFields fa fa-asterisk"></span>
               <?php _e(" I confirm that I do not have an affiliation or appointment with the Faculty of Medicine, uOttawa", 'learn.med'); ?></label>
        </div>


          <!--start of of field container area-->
          <div id="fieldsContainer">


            <fieldset><!-- start of first fieldset -->

              <!--legend-->
              <legend> <?php _e("Personal information", 'learn.med'); ?></legend>

              <p class="requiredFields">
                <em><?php _e("Fields marked with an ", 'learn.med'); ?><span class="fa fa-asterisk"></span> <?php _e(" are required", 'learn.med'); ?>.</em></p>

              <!--start of first column for the form-->
              <div class="form-element-block input required" id="user_email_block">

                <div class="form-label">
                  <span class="requiredFields fa fa-asterisk"></span>
                  <label for="user_email"><?php _e("Email address", 'learn.med'); ?></label>
                </div>

                <!--Start of first form element block-->
                <div class="form-elem">


                  <input type="text" name="user_email" id="user_email" value="<?php $template->the_posted_value( 'user_email' ); ?>" tabindex="2" class="form-control input-lg" required="" />
              <div id="user_email_feedback"></div>

              </div><!--end of first form element block-->




              <!-- start of second form element block-->
              <div class="form-element-block input required">

                <!--start of form label-->
                <div class="form-label">
                  <span class="requiredFields fa fa-asterisk"></span>
                  <label for="first_name"><?php _e("First name", 'theme-my-login'); ?></label>
                </div><!--end of label-->

                <!--start of input field-->
                <div class="form-elem">
                    <input type="text" name="first_name" id="first_name"   value="<?php $template->the_posted_value( 'first_name' ); ?>" tabindex="3"  class="form-control" required="" />
                </div><!--end of input field-->

              </div><!--end of second form element block-->


              <!--start of third element block-->
              <div class="form-element-block input required">

                <!--start of label-->
                <div class="form-label">
                  <span class="requiredFields fa fa-asterisk"> </span><label for="last_name"> <?php _e("Last name", 'theme-my-login'); ?></label>
                </div><!-- end of label-->

                <!--start of input field-->
                <div class="form-elem">
                 <input type="text" name="last_name" id="last_name" value="<?php $template->the_posted_value( 'last_name' ); ?>"   tabindex="4" class="form-control" required="" />

                </div><!--end of input field-->

              </div><!--end of third element block-->


              <!--start of fourth element block-->
              <div class="form-element-block input">

                <!--start of label-->
                <div class="form-label">
                  <label for="address"> <?php _e("Address", 'theme-my-login'); ?></label>
                </div><!--end of label-->

                <!--start of input field-->
                <div class="form-elem">
                  <input type="text" name="address" id="address" value="<?php $template->the_posted_value( 'address' ); ?>"  tabindex="5" class="form-control"  />

                </div><!--end of input field-->

              </div><!--end of fourth element block-->


              <!--start of fifth element block-->
              <div class="form-element-block input ">

                <!--start of label-->
                <div class="form-label">
                  <label for="city"> <?php _e("City", 'theme-my-login'); ?></label>
                </div><!--end of label-->

                <!--start of input field-->
                <div class="form-elem">
                 <input type="text" name="city" id="city"  value="<?php $template->the_posted_value( 'city' ); ?>" tabindex="6" class="form-control" />

                </div><!--end of input field-->

              </div><!--end of fifth element block-->


              <!-- start of seventh element block-->
              <div class="form-element-block input ">

                <!--start of label-->
                <div class="form-label">
                 <!--  <span class="requiredFields fa fa-asterisk"></span>  --><label for="Country"> <?php _e( 'Country', 'theme-my-login' ) ?></label>
                </div><!--end of label-->

                <!-- start of input element-->
                <div class="form-elem">
               <!--    <select name="country" id="country" tabindex="80" class="form-control">
                    <option
                    <?php apply_filters( 'esu_add_extra_form_fields_after','new_add_country_select');?> >

                  </option>

                  </select>-->
                  <!--id="country"-->
                  <select name="Country" id="country-selector" aria-controls="province" autofocus="autofocus" autocorrect="off" autocomplete="off" tabindex="7" placeholder="Type your country" class="form-control turn-to-ac field" >

                  <?php apply_filters( 'esu_add_extra_form_fields_after','new_add_country_select');?> >
                  <option value=""></option>
                  <option value = "Canada"><?php _e("Canada", 'theme-my-login'); ?></option>
                  <option value = "United States"><?php _e("United States", 'theme-my-login'); ?></option>
                  <option value="">--------</option>

                  <?php
                    $country_array = array("Afghanistan", "Aland Islands", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Barbuda", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Trty.", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Caicos Islands", "Cambodia", "Cameroon", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "French Guiana", "French Polynesia", "French Southern Territories", "Futuna Islands", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guernsey", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard", "Herzegovina", "Holy See", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Isle of Man", "Israel", "Italy", "Jamaica", "Jan Mayen Islands", "Japan", "Jersey", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea", "Korea (Democratic)", "Kuwait", "Kyrgyzstan", "Lao", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macao", "Macedonia", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "McDonald Islands", "Mexico", "Micronesia", "Miquelon", "Moldova", "Monaco", "Mongolia", "Montenegro", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "Nevis", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Palestinian Territory, Occupied", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Principe", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Barthelemy", "Saint Helena", "Saint Kitts", "Saint Lucia", "Saint Martin (French part)", "Saint Pierre", "Saint Vincent", "Samoa", "San Marino", "Sao Tome", "Saudi Arabia", "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia", "South Sandwich Islands", "Spain", "Sri Lanka", "Sudan", "Suriname", "Svalbard", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan", "Tajikistan", "Tanzania", "Thailand", "The Grenadines", "Timor-Leste", "Tobago", "Togo", "Tokelau", "Tonga", "Trinidad", "Tunisia", "Turkey", "Turkmenistan", "Turks Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "Uruguay", "US Minor Outlying Islands", "Uzbekistan", "Vanuatu", "Vatican City State", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (US)", "Wallis", "Western Sahara", "Yemen", "Zambia", "Zimbabwe");

                    foreach ($country_array as &$country_value) { ?>

                    <option value="<?php echo $country_value; ?>"><?php _e("$country_value", 'theme-my-login'); ?></option>

                  <?php }?>





                  </select>
                </div><!--end of input element-->

              </div><!--end of seventh element block-->



              <!--start of sixth element block-->
              <div class="form-element-block input theProvinceContainer">

                <!--start of label-->
                <div class="form-label">
                  <!-- <span class="requiredFields fa fa-asterisk"></span>  --><label for="province"> <?php _e( 'Province', 'theme-my-login' ) ?></label>
                </div><!--end of label-->

                <!--start of input field-->
                <div class="form-elem">
                  <select name="province" id="province"  tabindex="8" class="form-control" >
                    <option value=""></option>
                    <option  value = "ON"><?php _e("Ontario", 'theme-my-login'); ?></option>
                    <option value = "QC"><?php _e("Quebec", 'theme-my-login'); ?></option>
                    <option value = "BC"><?php _e("British Columbia", 'theme-my-login'); ?></option>
                    <option value = "AB"><?php _e("Alberta", 'theme-my-login'); ?></option>  
                    <option value = "MB"><?php _e("Manitoba", 'theme-my-login'); ?></option>
                    <option value = "SK"><?php _e("Saskatchewan", 'theme-my-login'); ?></option>
                    <option value = "NS"><?php _e("Nova Scotia", 'theme-my-login'); ?></option>
                    <option value = "NB"><?php _e("New Brunswick", 'theme-my-login'); ?></option>
                    <option value = "NFL"><?php _e("Newfoundland and Labrador", 'theme-my-login'); ?></option>
                    <option value = "PEI"><?php _e("Prince Edward Island", 'theme-my-login'); ?></option>
                    <option value = "NWT"><?php _e("North West territories", 'theme-my-login'); ?></option>
                    <option value = "YT"><?php _e("Yukon", 'theme-my-login'); ?></option>
                    <option value = "NU"><?php _e("Nunavut", 'theme-my-login'); ?></option>
                    
                    
                    
                    
                  </select>
                </div><!--end of input field-->

              </div><!--end of sixth element block-->




              <!--start of eight element block
              <div class="form-element-block input">-->

                <!--start of label
                <div class="form-label">
                  <label for="postal_code"> <?php _e( 'Postal code', 'theme-my-login' ) ?></label>
                </div>-->

                <!--start of input field
                <div class="form-elem">
                 <input type="text" name="postal_code" id="postal_code" value="<?php $template->the_posted_value( 'postal_code' ); ?>"   tabindex="90" class="form-control"  />
-->
             <!--   </div>end of input field-->

             <!--  </div>end of eight element block-->


              <!--start of ninth element block-->
              <div class="form-element-block input required">

                <!--start of label-->
                <div class="form-label">
                  <label for="phone"> <?php _e("Telephone", 'theme-my-login'); ?></label>
                </div><!--end of label-->

                <!--start of input field-->
                <div class="form-elem">
                 <input type="text" name="phone" id="phone"  value="<?php $template->the_posted_value( 'phone' ); ?>"  tabindex="9"  class="form-control"  />

                </div><!-- end of input field-->

              </div><!--end of ninth element block-->


              <!--start of tenth element block-->
              <div class="form-element-block input ">

                <!--start of label-->
                <div class="form-label">
                  <label for="phone_other"> <?php _e("Telephone other", 'theme-my-login'); ?></label>
                </div>  <!--end of label-->

                <!-- start of input field-->
                <div class="form-elem">
                    <input type="text" name="phone_other" id="phone_other"  value="<?php $template->the_posted_value( 'phone_other' ); ?>"   tabindex="10" class="form-control" />

                </div><!--end of input field-->

              </div><!-- end of tenth element block-->

          </fieldset><!--End of first column for the registration form-->


          <!--start of second column of the form-->
          <fieldset>

            <!--legend-->
            <legend><?php _e("Professional information ", 'learn.med'); ?></legend>

            <!--start of first element block-->
            <div class="form-element-block input ">

              <!--start of label-->
              <div class="form-label">
                <span class="requiredFields fa fa-asterisk"> </span>
                <label for="profession"> <?php _e("Profession", 'theme-my-login'); ?></label>
              </div><!--end of label-->

              <!--start of input field-->
              <div class="form-elem">
                <select name="profession" id="profession" tabindex="11" class="form-control">
                  <option value=""></option>
                  <option value = "Audiologist"><?php _e("Audiologist", 'theme-my-login'); ?></option>
                  <option value = "Dietetist / nutrition"><?php _e("Dietetist/ nutrition", 'theme-my-login'); ?></option>
                  <option value = "Occupational therapist"><?php _e("Occupational therapist", 'theme-my-login'); ?></option>
                  <option value = "Nurse"><?php _e("Nurse", 'theme-my-login'); ?></option>
                  <option value = "Kinesiologist"><?php _e("Kinesiologist", 'theme-my-login'); ?></option>
                  <option value = "Family Medicine/ General practitioner"><?php _e("Family Medicine / General practitioner", 'theme-my-login'); ?></option>
                  <option value = "resident Doctor"><?php _e("Resident Doctor", 'theme-my-login'); ?></option>
                  <option value = "Physician Specialist"><?php _e("Physician Specialist", 'theme-my-login'); ?></option>
                  <option value = "Speech therapist"><?php _e("Speech therapist", 'theme-my-login'); ?></option>
                  <option value = "Physiotherapist"><?php _e("Physiotherapist", 'theme-my-login'); ?></option>
                  <option value = "Emergency"><?php _e("Emergency", 'theme-my-login'); ?></option>
                  <option value = "Social worker "><?php _e("Social worker", 'theme-my-login'); ?></option>
                  <option value = "Psychologist"><?php _e("Psychologist", 'theme-my-login'); ?></option>
                  <option value = "IT"><?php _e("IT", 'theme-my-login'); ?></option>
                  <option value = "Webmaster"><?php _e("Webmaster", 'theme-my-login'); ?></option>
                  <option value = "Administrator"><?php _e("Administrator", 'theme-my-login'); ?></option>
                  <option value = "Other"><?php _e("Other", 'theme-my-login'); ?></option>
                </select>
              </div><!--end of input field-->

            </div><!--end of first element block-->


            <!--start of second element block-->
            <div class="form-element-block input ">

              <!--start of label-->
              <div class="form-label">
                <label for="grad_year"><?php _e( 'Graduation year', 'theme-my-login' ) ?></label>
              </div><!--end of label-->

              <!--start of input field-->
              <div class="form-elem">
                <input type="text" name="grad_year" id="grad_year" value="<?php $template->the_posted_value( 'grad_year' ); ?>"  tabindex="12" class="form-control" />

              </div><!--end of input field-->

            </div><!--end of second element block-->


            <!--start of third element block
            <div class="form-element-block input ">-->

                <!--start of label
                <div class="form-label">
                  <label for="year_experience">Years of experience</label>-->
                <!--</div>end of label-->

                <!--start of input field
                <div class="form-elem">
                  <input type="text" name="year_experience" id="year_experience"  value="" tabindex="140" class="form-control"/>-->
                <!--</div>end of input field-->

            <!--</div>end of third element block-->


            <!--start of fourth element block-->
            <div class="form-element-block input required">

              <!--start of label-->
              <div class="form-label">
                <span class="requiredFields fa fa-asterisk"></span>
                <label for="job_title"><?php _e( 'Job title', 'theme-my-login' ) ?></label>
              </div><!--end of label-->

              <!--start of input field-->
              <div class="form-elem">
               <input type="text" name="job_title" id="job_title" value="<?php $template->the_posted_value( 'job_title' ); ?>"  tabindex="13" class="form-control" required="" />
              </div><!--end of input field-->

            </div><!--end of fourth element block-->


            <!--start of submit input buttons-->
            <div class="form-elem">
                <p id="reg_passmail"><?php echo apply_filters( 'tml_register_passmail_template_message', __( 'A password will be e-mailed to you shortly.' ) ); ?></p>
              <!-- <input type="submit" name="wp-submit" id="wp-submit"  tabindex="190" role="button"  class="btn btn-lg btn-primary btn-block" value="Create my profile" />
              <input type="hidden" name="redirect_to" value="https://learn.med.uottawa.ca/login/?checkemail=registered" />
              <input type="hidden" name="instance" value="" />
              <input type="hidden" name="action" value="register" /> -->

              <input type="submit" name="wp-submit" id="wp-submit"  tabindex="14" role="button"  class="btn btn-lg btn-primary btn-block" value="<?php esc_attr_e( 'Sign up' ); ?>" />
              <input type="hidden" name="redirect_to" value="<?php $template->the_redirect_url( 'register' ); ?>" />
              <input type="hidden" name="return_to" value="<?php $template->the_posted_value( 'return_to' ); ?>" />
              <input type="hidden" name="instance" value="" />
              <input type="hidden" name="action" value="register" />

            </div>

          </fieldset><!--end of second column of the form-->

      </form>

    </div><!-- end of theme my login container -->

  </div><!-- end of registerForm content container -->


  <!-- start of registerInformation container
  <div class="login registerInformation col-lg-4 col-md-4 col-sm-12 col-xs-12" >-->

    <!-- start of instructions container --> <!--
    <div class="instructions">

      <h3 role="heading" aria-level="3"><span class="emphasized"><?php _e("Don't miss out!", 'learn.med'); ?></span>
        <br> <?php _e("Fill in your information to create your profile now", 'learn.med'); ?>.</h3>
      <p class="fullAccess"><?php _e("Get full access to modules & courses", 'learn.med'); ?>
      <p class="message"><span class="fa fa-check"></span> <?php _e("QUICK & EASY registration", 'learn.med'); ?>.</p>
      <p><span class="fa fa-check"></span> <?php _e("FREE & unlimited membership", 'learn.med'); ?>.</p>
      <p><span class="fa fa-check"></span> <?php _e("Leave and come back to where you were", 'learn.med'); ?>.</p>
      <p><span class="fa fa-check"></span> <?php _e("On-the-go learning (anytime, anywhere)", 'learn.med'); ?>.</p>

    </div>end of instructions container -->

    <!-- start of quote-container container -->
   <!-- <div class="quote-container ">
      <i class="pin"></i>
      <blockquote class="note yellow">
        <span class="fa fa-info-circle"></span> <?php _e("We hope you enjoy learning with us and will continue to work to improve your learning experience", 'learn.med'); ?>.
        <cite class="author"> <?php _e("- The Faculty of Medicine", 'learn.med'); ?></cite>
      </blockquote>
    </div> end of quote-container container -->

    <!-- start of moreInformation -->
   <!--  <p class="moreInformation"><span><?php _e("Still not convinced?", 'learn.med'); ?></span><br>
    <span class="moreInformationMessage"><?php _e("Take a look at the benefits of signing up below", 'learn.med'); ?>.</span>
    <br>
    <span class="fa fa-arrow-down"></span>
    </p>end of moreInformation -->

 </section><!--end registerFormInfo section container-->
</div><!--end register-login info-->


<br clear="all">

<!-- start of benefits area #group container -->
<!-- <div id="group">
  <div class="container">


    <div class="row ">

      <h2 role="heading" aria-level="2"> <?php _e("Benefits of being a member", 'learn.med'); ?></h2>

      <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 box">
        <p class="icons"> <span class="fa fa-mobile"></span></p>
        <h3 role="heading" aria-level="3"><?php _e("On-the-go learning (anytime, anywhere)", 'learn.med'); ?></h3>



        <p><a class="btn btn-gray btn-md btn-details" href="#" role="button"><?php _e("View details ", 'learn.med'); ?><span class="fa fa-arrow-down"></span>  </a></p>
      </div>

      <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 box">
       <p class="icons"> <span class="fa fa-certificate"></span></p>
        <h3 role="heading" aria-level="3"><?php _e("Receive a certificate upon completion", 'learn.med'); ?></h3>


        <p><a class="btn btn-gray btn-md btn-details" href="#" role="button"><?php _e("View details ", 'learn.med'); ?><span class="fa fa-arrow-down"></span>  </a></p>
      </div>

      <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 box">
         <p class="icons"> <span class="fa fa-book "></span></p>
         <h3 role="heading" aria-level="3"><?php _e("A variety of accredited modules", 'learn.med'); ?></h3>


        <p><a class="btn btn-gray btn-md btn-details" href="#" role="button"><?php _e("View details ", 'learn.med'); ?><span class="fa fa-arrow-down"></span>  </a></p>
      </div>

      <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 box">
         <p class="icons"> <span class="fa fa-users "></span></p>
         <h3 role="heading" aria-level="3"><?php _e("FREE sign up & unlimited access", 'learn.med'); ?></h3>


        <p><a class="btn btn-gray btn-md btn-details" href="#" role="button"><?php _e("View details ", 'learn.med'); ?><span class="fa fa-arrow-down"></span>  </a></p>
      </div>

    </div>

  </div>

</div> --><!-- end of benefits area .group container -->


<!-- start of benefits details area #details container -->
<!-- <div id="details"  aria-expanded="false">


  <div  class="container">


    <div class="row ">

      <div class="col-md-1 box">
        <p class="icons"> <span class="fa fa-mobile"></span></p>
      </div>

      <div class="col-md-11 newbox">
        <h3 role="heading" aria-level="3"><?php _e("On-the-go learning (anytime, anywhere) ", 'learn.med'); ?></h3>
        <p><?php _e("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin mollis tellus sit amet consectetur facilisis. Vivamus eu porttitor est, in sagittis diam", 'learn.med'); ?>.</p>
      </div>

      <br clear="all">

      <div class="col-md-1 box">
        <p class="icons"> <span class="fa fa-certificate"></span></p>
      </div>

      <div class="col-md-11 newbox">
        <h3 role="heading" aria-level="3"><?php _e("Receive a certificate upon completion", 'learn.med'); ?></h3>
        <p><?php _e("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin mollis tellus sit amet consectetur facilisis. Vivamus eu porttitor est, in sagittis diam", 'learn.med'); ?>.</p>
      </div>

      <br clear="all">

      <div class="col-md-1 box">
        <p class="icons"> <span class="fa fa-book"></span></p>
      </div>

      <div class="col-md-11 newbox">
        <h3 role="heading" aria-level="3"><?php _e("A variety of accredited modules ", 'learn.med'); ?></h3>
         <p><?php _e("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin mollis tellus sit amet consectetur facilisis. Vivamus eu porttitor est, in sagittis diam", 'learn.med'); ?>.</p>
      </div>

      <br clear="all">

      <div class="col-md-1 box">
        <p class="icons"> <span class="fa fa-users"></span></p>
      </div>

      <div class="col-md-11 newbox">
        <h3 role="heading" aria-level="3"><?php _e("FREE sign up & unlimited access", 'learn.med'); ?></h3>
        <p><?php _e("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin mollis tellus sit amet consectetur facilisis. Vivamus eu porttitor est, in sagittis diam", 'learn.med'); ?>.</p>
      </div>

    </div>

  </div>

</div> --><!-- end of benefits details area .details container -->


<script>

(function($) {
$(document).ready(function () {

  // jQuery(function(){
  //  jQuery('select.turn-to-ac').selectToAutocomplete();
  // });

  $('.turn-to-ac').selectToAutocomplete({
    'copy-attributes-to-text-field': false
  });

  $('.theProvinceContainer').hide();
  $('.theProvinceContainer').attr("aria-hidden", "true");


   $('select#country-selector').change(function(e){

  //   $('.theProvinceContainer').toggleClass('fieldiHidden', $(this).val() == 'Canada');

    console.log('change');

    if ($(this).val() == "Canada") {

      console.log('selected canada');
      $('.theProvinceContainer').show();
      $('.theProvinceContainer').attr("aria-hidden", "false");

    } else {

      $('.theProvinceContainer').hide();
      $('.theProvinceContainer').attr("aria-hidden", "true");

    }

   });


      


      // $("#affiliatedLink1").popover({
      // html: true,
      //   content: function() {
      //     return $("#affiliated").html();
      //   }
      // });

      // $("#advantages").popover({
      // html: true,
      //   content: function() {
      //     return $("#multipleAdvantages").html();
      //   }
      // });







      $("#details").hide();


      var menu = $('#details');

      // bind a click function to the menu-trigger
      $('.btn-details').click(function(event){
          event.preventDefault();
          event.stopPropagation();
          // if the menu is visible slide it up
          if (menu.is(":visible")) {
            menu.slideUp(400);
          }else { // otherwise, slide the menu down
            menu.slideDown(400);
          }
      });//end bind a click function to the menu-trigger



      $('#registerform fieldset input').prop('disabled', true);
      $('#fieldsContainer').addClass('reveal-if-active');
      $('#registerform fieldset input').prop( "" );

      $('#registerform input#attest').on('click', function () {

        $('#registerform fieldset input').prop('disabled', false);
        $('#fieldsContainer').removeClass('reveal-if-active');
        $('#registerform fieldset input').prop( "checked" );

      });//end $('#registerform input#attest') function





  		$("#user_email").focus();

  		$("#user_email").blur(function(){
  			var email = $(this).val();
  			if ("" != email) {
  				check_email(email);
  			} else {
  				remove_feedback();
  				enable_disabled_fields();
  			}
  		});//end user_email blur function


  		function check_email(email) {
  			$.ajax({
  				url: '<?php echo get_template_directory_uri(); ?>/lib/check-email.php?email=' + encodeURIComponent(email),
  				success: function(data) {
  					treat_email_check(data, email);
  				}
  			});
  		}//end check_email(email)


  		function treat_email_check(data, email) {
  			response = JSON.parse(data);

  			if (!response.valid) {
  				$('#user_email_block').removeClass('has-error').addClass('has-error has-feedback');
  				display_feedback('#email_invalid', { "email": email });
  				enable_disabled_fields();
  				return false;
  			}

  			if (response.found) {
  				$('#user_email_block').removeClass('has-error').addClass('has-warning has-feedback');
  				if (response.login_with_email) {
  					display_feedback('#login_with_email', { "email": email });
  				} else {
  					display_feedback('#login_with_account', { "email": email });
  				}
  				disable_fields_except_email();
  				return false;
  			}

  			// else
  			$('#user_email_block').removeClass('has-warning has-error has-feedback');
  			remove_feedback();
  			enable_disabled_fields();
  			return true;
  		}//end treat_email_check(data, email)


  		function enable_check_again_link() {

  			original = $("#user_email").val();
  			$("#user_email").keyup(function(e) {
  				if($(this).val() != original) {
  					$("#user_email_feedback .check-email.hidden").removeClass("hidden");
  					$(this).unbind("keyup");
  				}
  			});

  			$("#user_email_feedback .check-email").click(function(event) {
  				event.preventDefault();
  				var email = $("#user_email").val();
  				if ("" != email) {
  					check_email(email);
  				}
  			});
  		}//end enable_check_again_link()


  		function render_message(template, data) {

  			if ($(template).length == 0) { return ""; }

  			var template = $(template).html();
  			Mustache.parse(template);

  			return Mustache.render(template, data);

  		}//end render_message(template, data)


  		function remove_feedback() {
  			$("#user_email_feedback").html("");
  			return true;
  		}//end remove_feedback()


  		function display_feedback(template, data) {

        var message = render_message(template, {
  				"email": data.email,
  				"email_uri_escaped": encodeURIComponent(data.email)
  			});

  			$("#user_email_feedback").html(message);

  			if ("" == message) { return false; }
    			// focus on the message
    			$("#user_email").siblings(".help-block").eq(0).focus();
    			enable_check_again_link();
    			return true;

      }//end display_feedback()

  		function disable_fields_except_email() {
  			$("#user_email_block").find(".form-element-block").each(function() {
  				$(this).addClass("disabled");
  				$(this).find(".form-control, input[type=submit], .ui-autocomplete-input").prop('disabled', true);
  			});
  			$("#user_email_block").parent("fieldset").siblings("fieldset").each(function() {
  				$(this).addClass("disabled");
  				$(this).find(".form-control, input[type=submit]").prop('disabled', true);
  			});
  		}//end disable_fields_except_email()

  		function enable_disabled_fields() {
  			$("#user_email_block").siblings(".form-element-block").each(function() {
  				$(this).removeClass("disabled");
  				$(this).find(".form-control, input[type=submit]").removeProp('disabled');
  			});
  			$("#user_email_block").parent("fieldset").siblings("fieldset").each(function() {
  				$(this).removeClass("disabled");
  				$(this).find(".form-control, input[type=submit]").removeProp('disabled');
  			});
  		}//end enable_disabled_fields()

});

})(jQuery);
</script>

<script id="email_invalid" type="x-tmpl-mustache">

<?php require_once locate_template('templates/mustache/email_invalid.mst.php'); ?>

</script>

<script id="login_with_email" type="x-tmpl-mustache">

<?php require_once locate_template('templates/mustache/login_with_email.mst.php'); ?>

</script>

<script id="login_with_account" type="x-tmpl-mustache">

<?php require_once locate_template('templates/mustache/login_with_account.mst.php'); ?>

</script>
