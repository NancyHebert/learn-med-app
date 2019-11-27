<?php
/*
Template Name: loginRegister
If you would like to edit this file, copy it to your current theme's directory and edit it there.
Theme My Login will always look in your theme's directory first, before using this default template.
*/
?>

<script>
//Start of document.ready function
$(document).ready(function(){

  //Declaring variables
  var menu = $('#details');
  var lang = $('html').attr('lang');
  var previous_login_choice;
  var login_choice;

  previous_login_choice = "";

      //start of forgot password event handler toggle
    $(".forgotPassword").click(function() {

      $('.passwordRetrieval').toggle();

    });//end of forgot password event handler toggle


    //Start of bootstrap popover code
    // $(function(){
    //   $('rel="popover"').popover({
    //     container: 'body',
    //     html: true
    //   }).click(function(e) {
    //     e.preventDefault();
    //   });
    // });//End of bootstrap popover code


 //Start of bootstrap popover code
    // $(function(){
    //   $('rel="popover"').popover({
    //     container: 'body',
    //     html: true
    //   }).click(function(e) {
    //     e.preventDefault();
    //   });
    // });//End of bootstrap popover code



    

 

    // $("#affiliateLink").popover({
    //   html: true,
    //     content: function() {
    //       return $("#affiliatedPopover1").html();
    //     }
    // });


    // $("#externalLink").popover({
    //   html: true,
    //     content: function() {
    //       return $("#externalPopover1").html();
    //     }
    // });

    // $("#noAccounts").popover({
    //   html: true,
    //     content: function() {
    //       return $("#uottawa-account").html();
    //     }
    // });

    // $("#uottawaAccounts").popover({
    //   html: true,
    //     content: function() {
    //       return $("#uottawa-account").html();
    //     }
    // });






  $('#registerform fieldset input').prop('disabled', true);
  $('#fieldsContainer').addClass('reveal-if-active');
  $('#registerform fieldset input').prop( "" );

  //Start of register form input attest event handler function
  $('#registerform input#attest').on('click', function () {
    $('#registerform fieldset input').prop('disabled', false);
    $('#fieldsContainer').removeClass('reveal-if-active');
    $('#registerform fieldset input').prop( "checked" );
  });//End of register form input attest event handler function

  $("#details").hide();

  // bind a click function to the menu-trigger
  $('.btn-details').click(function(event){
    event.preventDefault();
    event.stopPropagation();
    // if the menu is visible slide it up
    if (menu.is(":visible"))
    {
      menu.slideUp(400);
    }
    // otherwise, slide the menu down
    else
    {
      menu.slideDown(400);
    }
  });

  $('input#affiliatedChoice').attr('checked', false);
  $('input#externalChoice').attr('checked', false);
  $(".external").hide();
  $(".affiliated").hide();
  $(".unableToAccess").hide();
  $(".unableToAccessExt").hide();
  $(".donthaveacount").hide();
  $("a.one45AccountRetrieval").hide();
  $("a.externalAccountRetrieval").hide();
  $(".createacount").hide();
  $(".login-form-borderd").css("margin-top","10px");
  $('#loginForm h3').css('opacity','0.3');
  $('#loginForm').css('opacity','0.3');
  $('#loginForm').attr('aria-hidden','true');
  $('.form-signin').css('opacity','0.3');
  $('.form-signin input').attr('disabled','disabled');


  //Start of input radio login choice event handler
  $('input[type="radio"].login-choice-radio').change(function(){

    login_choice = $(this).attr("value");
    console.log(login_choice);

    if("affiliated" == login_choice) {

      $(".external").hide();
      $(".affiliated").show();
      //$('#loginForm h2').show();


      $('#loginForm').css('opacity','1');
      $('#loginForm').attr('aria-hidden','false');
      $('.form-signin').css('opacity','1');
      $('#loginForm h3').css('opacity','1');
      $('.form-signin input').removeAttr('disabled');
      $(".unableToAccessExt").hide();
      $('input#affiliatedChoice').attr('checked', true);
      $('input#externalChoice').attr('checked', false);

      if (lang == "fr-FR") {
          $("#user_login_label").html('Compte uOttawa <span class="requiredText">(Obligatoire)</span> ');
          $('input#user_login').attr('placeholder', 'votreNomUtilisateur@uottawa.ca');
          $("#user_pass").html('Mot de passe <span class="requiredText">(Obligatoire)</span> ');
          $('input#user_pass').attr('placeholder', 'Mot de passe');
          $('#wp-submit').val('Se connecter');
      } else {

          $("#user_login_label").html('uOttawa account <span class="requiredText">(Required)</span>');
          $('input#user_login').attr('placeholder', 'uOttawaUsername');
          $("#user_pass_label").html('Password <span class="requiredText">(Required)</span>');
          $('input#user_pass').attr('placeholder', 'Password');
          $('#wp-submit').val('Sign in');
      }

      $(".form-signin").show();
      $(".unableToAccess").show();
      $(".donthaveacount").show();
      $(".createacount").hide();
      $("a.one45AccountRetrieval").show();
      $("a.externalAccountRetrieval").hide();
      $(".1").css("color","#731025");
      $(".2").css("color","black");
      $(".login-form-borderd").css("margin-top","0px");
      $("label.one").css("background-color","#f1f1f1");
      $("label.two").css("background-color","transparent");
      //$("form").attr('action', 'indexExternalsLoggedIn.html');

      if (lang == "fr-FR") {

        $(".choiceTitle").html("Affilié à la Faculté de médecine");

      } else {

        $(".choiceTitle").html("Affiliated to the Faculty of Medicine");

      }

    } else if("external" == login_choice) {

        $(".affiliated").hide();
        $(".external").hide();
        $('#loginForm').css('opacity','1');
        $('#loginForm').attr('aria-hidden','false');
        $('.form-signin').css('opacity','1');
        $('#loginForm h3').css('opacity','1');
        $('.form-signin input').removeAttr('disabled');
        $(".unableToAccess").hide();
        $('input#affiliatedChoice').attr('checked', false);
        $('input#externalChoice').attr('checked', true);


        if (lang == "fr-FR") {
            $("#user_login_label").html('Adresse courriel <span class="requiredText">(Obligatoire)</span> ');
            $('input#user_login').attr('placeholder', 'Entrer une adresse courriel valide');
            $("#user_pass").html('Mot de passe <span class="requiredText">(Obligatoire)</span> ');
            $('input#user_pass').attr('placeholder', 'Entrez votre mot de passe');
            $('#wp-submit').val('Se connecter');
        } else {
            $("#user_login_label").html('Email address <span class="requiredText">(Required)</span>');
            $('input#user_login').attr('placeholder', 'Please enter a valid email address');
            $("#user_pass_label").html('Password <span class="requiredText">(Required)</span>');
            $('input#user_pass').attr('placeholder', 'Please enter your password');
            $('#wp-submit').val('Sign in');
        }

        $(".form-signin").show();
        $(".unableToAccessExt").show();
        $(".donthaveacount").show();
        $(".createacount").show();
        $("a.one45AccountRetrieval").hide();
        $("a.externalAccountRetrieval").show();
        $(".2").css("color","#731025");
        $(".1").css("color","black");
        $(".login-form-borderd").css("margin-top","0px");
        $("label.two").css("background-color","#f1f1f1");
        $("label.one").css("background-color","transparent");
        //$("form").attr('action', 'indexResidentsLoggedIn.html');

        if (lang == "fr-FR") {

          $(".choiceTitle").html("Externe à la Faculté de médecine");

        } else {

          $(".choiceTitle").html("External to the Faculty of Medicine");

        }

      }

      if (previous_login_choice != "") {

        $("#user_login").val("");

      }


      <?php if ($_GET["email"] != ""): ?>
      setTimeout(function() {
        $('#user_login').val('<?php echo $_GET["email"]; ?>');
        $('#user_pass').focus();
      },400);
      <?php else: ?>
      setTimeout(function() {
        $('#user_login').focus();
      },100);
      <?php endif; ?>

      previous_login_choice = login_choice;

    });//End of input radio login choice event handler

    <?php if ($_GET["login_as"] == "affiliated" || $_GET["login_as"] == "external"): ?>
      $('input[type="radio"].login-choice-radio[value=<?php echo $_GET["login_as"]; ?>]').click();
    <?php endif; ?>

});//end of document ready function

</script>

<div class="container">

<?php $template->the_errors(); ?>
<?php //$template->the_action_template_message( 'login' ); ?>

<section id="loginChoices" role="region" class="row">


 <h2 role="heading" aria-level="2"><?php _e("Sign in <span class='fullAccess2'>& start your learning experience</span>", 'learn.med'); ?></h2>

  <div class="form col-lg-8 col-md-8 col-sm-12 col-xs-12">


      <fieldset>
      <legend><span class="fa fa-hand-o-right"></span> <span class="bolded"> <?php _e("Step 1:", 'learn.med'); ?></span> <?php _e("Tell us who you are", 'learn.med'); ?></legend>


        <p>

        <label for="affiliatedChoice" class="login-choice one col-lg-12 col-md-12 col-sm-12 col-xs-12 " style="background-color: transparent;">
          <input type="radio" name="Radio" id="affiliatedChoice" value="affiliated" aria-controls="theme-my-login1" class="login-choice-radio">
           <?php _e("I am affiliated with the Faculty of Medicine", 'learn.med'); ?>  <a href="#" id="affiliateLink" class="importantLink " aria-controls="affiliatedPopover1" data-trigger="hover" rel="popover" data-placement="bottom" title="" data-toggle="popover" data-popover-content="#affiliatedPopover1" data-original-title="IMPORTANT NOTE">
          <span class="fa fa-question-circle" aria-hidden="false"></span></a>
        </label>

        </p>


      

          <div id="affiliatedPopover1" aria-hidden="true"  class="hide">

            <p> <?php _e("You are a ", 'learn.med'); ?> <span class="bolded"> <?php _e("clinician", 'learn.med'); ?></span>, 
              <span class="bolded"><?php _e("professor", 'learn.med'); ?></span>, 
              <span class="bolded"><?php _e("resident", 'learn.med'); ?></span>, 
              <span class="bolded"><?php _e("fellow", 'learn.med'); ?></span>, 
              <span class="bolded"><?php _e("graduate", 'learn.med'); ?></span>, 
              <span class="bolded"><?php _e("student", 'learn.med'); ?></span> or 
              <span class="bolded"><?php _e("admin staff", 'learn.med'); ?></span>.
            </p>

          </div>

       


   
        <p>
          <label for="externalChoice" class="login-choice  two col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
          <input type="radio" name="Radio" id="externalChoice" value="external" class="login-choice-radio" aria-controls="theme-my-login1" >
          <?php _e("I am an external to the Faculty of Medicine  ", 'learn.med'); ?>
          
          <a href="#" class="importantLink " id="externalLink" data-trigger="hover" data-placement="bottom"  title="IMPORTANT NOTE" rel="popover" data-toggle="popover" aria-controls="externalPopover1" data-popover-content="#externalPopover1">
            <span class="fa fa-question-circle"></span>
          </a>
          </label>

      
         </p> 

          <div id="externalPopover1" aria-hidden="true"  class="hide">
            <p> <span class="bolded"><?php _e("You DO NOT currently have any affiliation or appointment ", 'learn.med'); ?></span> <?php _e("with the Faculty of Medicine, uOttawa", 'learn.med'); ?>.</p>
          </div>

       
     

        

      </fieldset>


      <br clear="all">

      <section id="loginForm">

        <div class="login login-form-wrapper" id="theme-my-login<?php $template->the_instance(); ?>"  aria-hidden="true" >

          <h3 role="heading" aria-level="3"><span class="fa fa-hand-o-right"></span> <span class="bolded"><?php _e("Step 2: ", 'learn.med'); ?></span> <?php _e("Enter your sign in information", 'learn.med'); ?></h3>

          <h4 role="heading" aria-level="4" class="choiceTitle"></h4>


          <form class="form-signin  col-lg-6 col-md-6 col-sm-12 col-xs-12" role="form" name="loginform" id="loginform<?php $template->the_instance(); ?>" action="<?php $template->the_action_url( 'login' ); ?>" method="post">

            <fieldset>

              <div class="form-element-block input required">

                <div class="form-label">

                  <div class="userLogin">
                    <label for="user_login" id="user_login_label">
                    <?php _e( 'uOttawa account ' , 'learn.med'); ?><span class="requiredText"><?php _e( '(required)' , 'learn.med'); ?></span></label>
                  </div>

                </div>

                <div class="form-elem">
                    <input type="text" name="log" id="user_login" taborder="3" aria-required placeholder="<?php _e("uOttawaUsername", 'learn.med'); ?>" title="<?php _e("Login", 'learn.med'); ?>"  class="form-control" required="" value="<?php $template->the_posted_value( 'log' ); ?>"/>
                </div>


                <div class="form-element-block input required">

                  <div class="form-label">
                   <label for="user_pass" id="user_pass_label"><?php _e( 'Password ' , 'learn.med'); ?></label>
                  </div>

                  <div class="form-elem">
                   <input type="password" name="pwd" id="user_pass" class="form-control"  aria-required taborder="4"  placeholder="<?php _e("Enter your password", 'learn.med'); ?>" required=""value=""/>
                  </div>

                </div>

              </div>
              <?php do_action( 'login_form' ); ?>
              <!-- <input type="hidden" name="_wp_original_http_referer" value="https://apprendre.med.uottawa.ca/" />
 -->
              <div class="form-element-block rememberMe required ">
                <div class="form-elem">
                  <label class="checkbox" for="rememberme" id="remember"><input id="rememberme" name="rememberme" type="checkbox"  taborder="5" value="remember-me" /><?php _e( 'Remember me on this computer' , 'learn.med'); ?></label>
                </div>
              </div>


            <div class="form-element-block submit">

              <div class="form-elem">

                <input type="submit" name="wp-submit" class="btn btn-red btn-lg " role="button"  id="wp-submit"  taborder="6"  value="<?php esc_attr_e( 'Sign in' , 'learn.med'); ?>" />
                <input type="hidden" name="redirect_to" value="<?php $template->the_redirect_url( _slug('login','page') ); ?>" />
                <input type="hidden" name="instance" value="<?php $template->the_instance(); ?>"  />
                <input type="hidden" name="action" value="<?php _e( 'login' , 'learn.med'); ?>" />

              </div>
            </div>


             <section id="noPassword" role="region" >

          <p class="unableToAccess">
            <span class="bolded"><?php _e( 'Forgot your Password? ' , 'learn.med'); ?></span>
<!-- forgotPassword -->
            <a role="link" href="<?php _e('https://app.med.uottawa.ca/PasswordReset/', 'learn.med'); ?>" target="_blank" rel="external"  class="one45AccountRetrieval btn btn-gray btn-lg"><?php _e('Reset it', 'learn.med'); ?></a>


          </p>



          <p class="unableToAccessExt">
          <!-- forgotPassword -->
            <span class="bolded"><?php _e('Forgot your password', 'learn.med'); ?>?</span> <a class="externalAccountRetrieval  btn btn-default btn-lg btn-gray" role="button" rel="external" href="<?php echo site_url(_slug('lostpassword', 'page')); ?>" ><?php _e('Reinitialize it', 'learn.med'); ?></a></p>

          </p>

      </section>




          </fieldset>

        </form>


        <section id="noAccount" class="col-lg-6 col-md-6 col-sm-12 col-xs-12" role="region" >


          <p class="unableToAccess">

          <span class="bolded"><?php _e("Don't have a ", "learn.med"); ?>
            <a href="#"   aria-controls="uottawa-account" id="uottawaAccounts" class="importantLink" data-trigger="hover" data-placement="bottom"  title="Important Note" rel="popover" data-toggle="popover"  data-popover-content="#uottawa-account">
              <?php _e( 'uOttawa account ' , 'learn.med'); ?></a> 
              <?php _e( "or don't remember your account details" , "learn.med"); ?>?  </span>
            
            <a class="one45AccountRetrieval  btn btn-gray btn-md" id="noAccounts" role="button" rel="external" href="<?php _e("http://www.med.uottawa.ca/medtech/help/", 'learn.med'); ?>" target="_blank">
              <?php _e('Contact the experts at medtech ', 'learn.med'); ?></a><?php _e(' to recover your account information', 'learn.med'); ?>.

          </p>


          <div id="uottawa-account" aria-hidden="true" class="hide">
           <p><?php _e('If you are, in any way ', 'learn.med'); ?><span class="bolded">
            <?php _e('affiliated to the Faculty of Medicine', 'learn.med'); ?>, </span><?php _e('uOttawa ', 'learn.med'); ?>
            <span class="bolded"><?php _e('(clinician', 'learn.med'); ?></span>, <span class="bolded"><?php _e('professor', 'learn.med'); ?></span>, <span class="bolded"><?php _e('resident', 'learn.med'); ?></span>, <span class="bolded"><?php _e('fellow', 'learn.med'); ?></span>, <span class="bolded"><?php _e('graduate', 'learn.med'); ?></span>, <span class="bolded"><?php _e('student ', 'learn.med'); ?></span><?php _e('or ', 'learn.med'); ?><span class="bolded"><?php _e('admin staff)', 'learn.med'); ?></span>, <span class="bolded"><?php _e('you have an account ', 'learn.med'); ?></span><?php _e('with us', 'learn.med'); ?>. </p>
           </div>

            <p class="unableToAccessExt">
            <span class="bolded"><?php _e("Don't have an account", "learn.med"); ?>? </span>
            <a class="externalAccountRetrieval btn btn-gray btn-md" role="button" rel="external" href="<?php
  $register_url = site_url(_slug('register', 'page'));
  $register_url = add_query_arg( 'return_to', urlencode( wp_get_referer() ), $register_url );
  echo $register_url;
?>" ><?php _e('Create your profile ', 'learn.med'); ?></a>
            <br> <br>
            <span class="bolded"><?php _e('Not sure if you have an account', 'learn.med'); ?>?</span><a class="externalAccountRetrieval  btn btn-gray btn-md" role="button" rel="external" href="<?php _e("http://www.med.uottawa.ca/medtech/help/", 'learn.med'); ?>"><?php _e('Contact the experts at Medtech ', 'learn.med'); ?></a> <?php _e('for assistance', 'learn.med'); ?>.

          </p>

      </section>

        </div>

      </section>

      <br clear="all">

    </div>
<!-- registerInformation -->
    <div class="login  col-lg-4 col-md-4 col-sm-12 col-xs-12" >


       <div id="whyregister">


            <!-- start of quote-container container -->
             <div class="quote-container ">
                <i class="pin"></i>
                <blockquote class="note yellow">
                 <!--  <span class="fa fa-info-circle"></span>  --> <?php _e('We hope you enjoy your learning experience with us', 'learn.med'); ?>.
                  <cite class="author"> <?php _e("- The Faculty of Medicine", 'learn.med'); ?></cite>
                </blockquote>
              </div>  <!--end of quote-container container -->

      </div>

  </div>



  </section> <!-- end of register form section -->

</div><!--End of container div-->


</div><!--end register-login info-->






 </section><!--end register form container-->
