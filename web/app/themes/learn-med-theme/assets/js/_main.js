/* ========================================================================
 * DOM-based Routing
 * Based on http://goo.gl/EUTi53 by Paul Irish
 *
 * Only fires on body classes that match. If a body class contains a dash,
 * replace the dash with an underscore when adding it to the object below.
 *
 * .noConflict()
 * The routing is enclosed within an anonymous function so that you can
 * always reference jQuery with $, even when in .noConflict() mode.
 *
 * Google CDN, Latest jQuery
 * To use the default WordPress version of jQuery, go to lib/config.php and
 * remove or comment out: add_theme_support('jquery-cdn');
 * ======================================================================== */

//show progress for completed lessons and topic




var theLanguage = $('html').attr('lang');

$('div.gform_wrapper').css('display','');

function textAreaAdjust(o) {
  o.style.height = "1px";
  o.style.height = (25+o.scrollHeight)+"px";
}

function redirectPage() {
  window.location = linkLocation;
}



setTimeout(function(){






  if ( $('article').hasClass('category-proquiz_en') || $('article').hasClass('category-proquiz_fr') ) {

    if (theLanguage === "fr-FR") {

      $('.wpProQuiz_response h4').html('<strong>Rétroaction</strong>');
      $('.wpProQuiz_results h4').css('display', 'none');
      $('.wpProQuiz_results h4').attr('aria-hidden', 'true');
      $("<p class='proquizText'>Avez-vous bien réussi? Y a-t-il des sujets abordés dans ce module que vous devez réviser? Vous pouvez accéder directement à chacune des pages spécifiques en utilisant le bouton à la droite de votre écran.</p> <p>Afin d'obtenir un crédit pour ce module, cliquez sur Terminer maintenant.</p>").insertAfter('.wpProQuiz_results h4');


    } else {

      $('.wpProQuiz_response h4').html('<strong>Feedback</strong>');
      $('.wpProQuiz_results h4').css('display', 'none');
      $('.wpProQuiz_results h4').attr('aria-hidden', 'true');
      $('<p class="proquizText">How did you do? Are there topics addressed in this module that you need to review? You can navigate directly to specific screens using the button on the right of your screen.</p><p>In order to get credit for this module, click Finish now.</p>').insertAfter('.wpProQuiz_results h4');


    }


  }




}, 30);

if ( $('article').hasClass('category-advquiz-single-question-en') || $('article').hasClass('category-advquiz-single-question-fr') ) {
    $('.wpProQuiz_content').on('changeQuestion', function() {
        window.questionSolved = false;
        $('.wpProQuiz_question_page').remove();
        $('.wpProQuiz_results').remove();
    });
    $('.wpProQuiz_content').on('questionSolved', function() {
        $('.wpProQuiz_listItem input[name=next]').remove();
        window.questionSolved = true;
    });

    $('.wpProQuiz_content input[name=check]').click(function() {
        if (!window.questionSolved) { return; }
        if (1 <= $('form#sfwd-mark-complete').length) {
            $('form#sfwd-mark-complete').show();
        } else {
            $('p#learndash_next_prev_link div.continue').show();
        }
    });

}

$('#questionDiv div.opened').attr('style', ' ');
$('#questionDiv div.closed').attr('style', ' ');



// var openedID = $('#questionDiv div.opened').attr('id');
// var closedID = $('#questionDiv div.closed').attr('id');

// var newOpenedID = openedID.match(/([A-Z].*[a-z]|[a-z].*[A-Z].*[a-z]|[a-z])/g);
// var newClosedID = closedID.match(/([A-Z].*[a-z]|[a-z].*[A-Z].*[a-z]|[a-z])/g);

// $(newOpenedID).attr('style', ' ');
// $(newClosedID).attr('style', ' ');


if($(window).width() <= 390) {

  $('a.btn-cpd').removeClass('btn-lg');

} else {

  $('a.btn-cpd').addClass('btn-lg');
}





$('.signInLogoutBtn').find('span').appendTo('.signInLogoutBtn a');
//var myRegExp = /^[ \ta-zA-Z<>]*/;
//$('dd.entry-content p').text().replace(/^[ \ta-zA-Z<>]*/, " ");
$('div.wpProQuiz_question').attr('style', '');


$('span.fa').attr("aria-hidden", "false");


$('body.register ul.top-navigation, body.login ul.top-navigation, body.lostpassword ul.top-navigation').find('a').removeClass('btn btn-primary').addClass('btn btn-gray btn-lg');
$('.page-header').hide();

$('ul.top-navigation li.signInLogoutBtn').find('a').addClass('btn btn-red btn-lg');


$('ul.top-navigation li:last-child').find('a').removeClass('btn btn-primary').addClass('btn btn-gray btn-lg');


// The routing fires all common scripts, followed by the page specific scripts.
// Add additional events for more control over timing e.g. a finalize event
var UTIL = {
  fire: function(func, funcname, args) {
    var namespace = Roots;
    funcname = (funcname === undefined) ? 'init' : funcname;
    if (func !== '' && namespace[func] && typeof namespace[func][funcname] === 'function') {
      namespace[func][funcname](args);
    }
  },
  loadEvents: function() {
    UTIL.fire('common');

    $.each(document.body.className.replace(/-/g, '_').split(/\s+/),function(i,classnm) {
      UTIL.fire(classnm);
    });
  }
};

 var Roots = {
    // All pages
    common: {
      init: function() {
        // JavaScript to be fired on all pages
      }
    },
    // Home page
    home: {
      init: function() {
        // JavaScript to be fired on the home page
      }
    },
    // About us page, note the change from about-us to about_us.
    about_us: {
      init: function() {
        // JavaScript to be fired on the about us page
      }
    }

  };


(function ($) {
    $.fn.getCursorPosition = function () {
        var input = this.get(0);
        if (!input) return; // No (input) element found
        if ('selectionStart' in input) {
            // Standard-compliant browsers
            return input.selectionStart;
        } else if (document.selection) {
            // IE
            input.focus();
            var sel = document.selection.createRange();
            var selLen = document.selection.createRange().text.length;
            sel.moveStart('character', -input.value.length);
            return sel.text.length - selLen;
        }
    }
})(jQuery);



(function($) {




  //initiation-a-lethique-clinique/

    if($('div#surveyPopupModal').length > 0 && !Modernizr.touch) {

      var course_info_config_courseLink = window.course_info.courseLink;
      console.log('course_info_config_courseLink ' + course_info_config_courseLink);

      var course_link_part = course_info_config_courseLink.replace(/https:\/\/apprendre.med.uottawa.dev\/cours\//i, "");
      console.log('course_link_part ' + course_link_part);

      var course_link_part_new = course_link_part.replace(/\//i, "");
      console.log('course_link_part_new ' + course_link_part_new);

      // get the config parameters, exit if not found
      if (typeof(window.course_info) == undefined) {
        return false;
      }



      var course_info_config = window.course_info;
      var course_info_config_CourseID = course_info_config.courseID;
      var course_info_config_userLogin = course_info_config.userLogin;
      var course_info_config_userEmail = course_info_config.userEmail;
      var course_info_config_courseStatus = course_info_config.courseStatus;




      console.log('course_info_config_object ' + course_info_config);
      console.log('course_info_config ' + course_info_config_CourseID);
      console.log('course_info_config_userLogin ' + course_info_config_userLogin);
      console.log('course_info_config_userEmail ' + course_info_config_userEmail);
      console.log('course_info_config_courseStatus ' + course_info_config_courseStatus);



      $( "#btn-notInterested" ).on('click', function(e) {

          console.log('clicked');

        //   if ( $.cookie('surveyPopup') === null ) {
             $.cookie('surveyPopup'+ course_info_config_CourseID, 'NotInterested', { expires: 365, secure: true });

        //   }
        console.log($.cookie('surveyPopup'+ course_info_config_CourseID, 'NotInterested', { expires: 365, secure: true }));
        $('#surveyPopupModal').modal('hide');
      });


      $( "#btn-survey" ).on('click', function(e) {


        var name = window.course_info.userLogin;
        var email = window.course_info.userEmail;
        console.log(name);

        //window.location.href = 'https://fr.surveymonkey.com/r/ApprendreMed?n='&name;
        //https://fr.surveymonkey.com/r/ApprendreMed
        //userName='+name+'&email='+email

        if (theLanguage === "fr-FR") {

          var url = 'http://uottawa.fluidsurveys.com/surveys/bafmed/apprendremed/?courseTitle='+course_link_part_new;

        } else {

          var url = 'http://uottawa.fluidsurveys.com/s/LearnMed2015/?courseTitle='+course_link_part_new;


        }
        console.log('url ' + url);
        if (typeof ga == 'function') {
            ga('send', 'pageview', '/_external/survey-fall-2015');
        }

        setTimeout(function(){
            window.location.href=url;
        }, 500);


      });


      $(document).mousemove(function(e) {

          $('#surveyPopupModal').css('left', (window.innerWidth/2 - $('#surveyPopupModal').width()/2));
          $('#surveyPopupModal').css('top', (window.innerHeight/2 - $('#surveyPopupModal').height()/2));

          if(e.pageY <= 5 && window.course_info.courseStatus !== "Completed" && window.course_info.courseStatus !== "Complété" && undefined === $.cookie('surveyPopup'+ course_info_config_CourseID)) {

              // Show the exit popup
              $('#exitpopup_bg').fadeIn();
              $('#surveyPopupModal').fadeIn();
              $('#surveyPopupModal').modal('show');

          }

      });

      $('#exitpopup_bg').click(function(){
          $('#exitpopup_bg').fadeOut();
          $('#surveyPopupModal').slideUp();
      });
    }


  $('#rootwizard').bootstrapWizard({'tabClass': 'nav nav-tabs'});

  $('div#learndash_course_content').attr('aria-hidden','true');
  $('div#learndash_course_content').addClass('arrow_box_top col-xs-12 col-sm-12 col-md-12 col-lg-12');


  $('h1').attr('role','button');
  $('h2').attr('role','button');
  $('h3').attr('role','button');
  $('h4').attr('role','button');
  $('h5').attr('role','button');
  $('h6').attr('role','button');

  $('h1').attr('aria-level','1');
  $('h2').attr('aria-level','2');
  $('h3').attr('aria-level','3');
  $('h4').attr('aria-level','4');
  $('h5').attr('aria-level','5');
  $('h6').attr('aria-level','6');


  if ( $('body').hasClass('page') && $('div.deanMessage').length > 0 || $('body').hasClass("page-template-single-sfwd-courses-cpd-php") || $('body').hasClass("page-template-single-sfwd-courses-undergraduate-php")  ) {

    $('main').removeClass('col-sm-8').addClass('col-sm-12');
    $('aside.sidebar').hide();

  }



  //$('body.page-id-3408, body.page-id-3910').find('main').removeClass('col-sm-8').addClass('col-sm-12');
  //$('body.page-id-3408, body.page-id-3910').find('aside.sidebar').hide();

//set a variable for the navbar item with active class
//set a variable with the next item after active class

// $('body.page-id-4129 div.tab-content div.tab-pane').each(function(e) {

//   var currentActiveTab = $('div.rootwizard ul.nav-tabs').find('li.active');
//   var nextActiveTab = $('div.rootwizard ul.nav-tabs li.active').next('li').find('a').text();


//   console.log('current active tab ' + currentActiveTab);
//   console.log('next tab after active ' + nextActiveTab);

//   console.log('looping through each div.tab-pane ' + $(this).hasClass('active') );


//    if ( $(this).hasClass('active') ) {

//     console.log('tab-pane has class of active');


//       if ( $('div.active span.xapi-activity-status-completed').length > 0 && $('div.active span.xapi-activity-status-completed').text() == 'Completed' ) {

//            console.log('span.xapi-activity-status xapi-activity-status-completed exists and its text is Completed');

//           //add class active to next tab after the active one
//           $(nextActiveTab).addClass('active');
//           $(currentActiveTab).removeClass('active');
//           //remove active class from current active tab

//       } else {

//         return false;

//       }//if the text of span.xapi-activity-status xapi-activity-status-completed exist and the text is Completed


//    }//Find the tab-pane with class active






// });//loop through tab-content to all the div.tab-pane











// if ( $('body').hasClass('body.page-id-4129') ||  $('body').hasClass('body.page-id-4229')  )  {

//   if ( $('span').hasClass('xapi-activity-status xapi-activity-status-started') ) {



//   }

// }


//move the comments above the continue button
// if( $('form#sfwd-mark-complete').length )// use this if you are using id to check
// {
//   // it exists
//   $('section#comments').insertBefore('form#sfwd-mark-complete');
//   $('section#respond').insertAfter('section#comments');

// } else {

//   $('section#comments').insertBefore('p#learndash_next_prev_link');
//   $('section#respond').insertAfter('section#comments');

// }


if ( !$('section#comments').length > 0 ) {

  console.log('there are no comments added yet to the discussion');

  if ($('form#sfwd-mark-complete').length > 0 ) {

    console.log('mark complete exists');

    $('form#sfwd-mark-complete').show();
    $('p#learndash_next_prev_link div.continue').hide();
    $('section#respond').insertBefore('form#sfwd-mark-complete');

  } else {

    console.log('continue button on the page');
    $('form#sfwd-mark-complete').hide();
    $('p#learndash_next_prev_link div.continue').show();
    $('section#respond').insertBefore('p#learndash_next_prev_link');


  }






} else {

  console.log('comments were added to the discussion');

  if ($('form#sfwd-mark-complete').length > 0 ) {

    console.log('mark complete exists');

    $('form#sfwd-mark-complete').show();
    $('p#learndash_next_prev_link div.continue').hide();

    $('section#respond').insertBefore('form#sfwd-mark-complete');
    $('section#comments').insertAfter('section#respond');

  } else {

    console.log('continue button on the page');

    $('form#sfwd-mark-complete').hide();
    $('p#learndash_next_prev_link div.continue').show();

    $('section#respond').insertBefore('p#learndash_next_prev_link');
    $('section#comments').insertAfter('section#respond');

  }



}






$("textarea").keyup(function(e) {
    while($(this).outerHeight() < this.scrollHeight + parseFloat($(this).css("borderTopWidth")) + parseFloat($(this).css("borderBottomWidth"))) {
        $(this).height($(this).height()+1);
    };
});

  //Function to toggle the notepap element
  toggleNotes();


  //$('div#learndash_quizzes').hide();
  $('div#learndash_quizzes div#quiz_heading').hide();
  $('div#learndash_quizzes div.list-count').hide();






  //Function to do something when the page is a lesson and the article has a class of category-certificate
  pageIsLessonAndArticleHasCertificateCategory();

  //Function to hide the message section completed on quiz, lessons or topic pages.
  hideCompletedMessageForQuizLessonsTopicsPages();

  //Hide the credits when the body tag has a class of page
  hideCreditsWhenBodyHasClassOfPage();

  //Change the text of the next button on lesson page that have quizzes
  changeTextOfnextButtonforQuizOnLessonPages();

  //Change the confirmation message text for gravity form
  changeConfirmationMessgeTextForGravityForm();

  //Change the text of various elements for pages with a category of certificat or certificate
  changeTextForPageWithCategoryCertificatOrCertificate();

  //Function to display and hide elements on quiz pages
  hideShowElementsOnQuizPages();

  //Code for the ilearnPeds page
  iLearnPedsChangeElements();

  //Function with code for the front(home) page
  changingElementsOnTheHomePage();

  //Function with code for the login or register page
  loginRegisterPageCode();

  //Function to on hide paragraph with class of message on register page
  registerPageCode();



  //Declaring variables



  var myElement = $('div.learndash_nevigation_lesson_topics_list div');
  var myElementWithCompletedClass = $('div.learndash_nevigation_lesson_topics_list div.lesson_completed');
  var firstElement = $('div#learndash_lessons div#lessons_list h4 a').first();
  var lastElement = $('div#learndash_lessons div#lessons_list h4 a').last();
  var firstElementText = $('div#learndash_lessons div#lessons_list h4 a').first().text();
  var firstElementLink = $('div#learndash_lessons div#lessons_list h4 a').first().attr('href');
  var lastElementCompleted = $('div#learndash_lessons div#lessons_list h4').find('a.completed').last();
  var nextNotCompletedElementText = $('div#learndash_lessons div#lessons_list h4').find('a.completed').last().closest('div.is_not_sample').next().find('a.notcompleted').text();
  var nextNotCompletedElementLink = $('div#learndash_lessons div#lessons_list h4').find('a.completed').last().closest('div.is_not_sample').next().find('a.notcompleted').attr('href');
  var LastCompletedClass = $('div.learndash_nevigation_lesson_topics_list div.lesson_completed').last();
  var myElementNotCompletedClass = $('div.learndash_nevigation_lesson_topics_list div.lesson_incomplete');
  var LessonListMyElement = $('div.is_not_sample a');
  var LessonListMyElementWithCompletedClass = $('div.is_not_sample a.completed');
  var LessonListLastCompletedClass = $('div.is_not_sample a.completed').last();
  var LessonListMyElementNotCompletedClass = $('div.is_not_sample a.notcompleted');
  var TopicListMyElement = $('div#learndash_lesson_topics_list ul li span a');
  var TopicListMyElementWithCompletedClass = $('div#learndash_lesson_topics_list ul li a.topic-completed');
  var TopicListLastCompletedClass = $('div#learndash_lesson_topics_list ul li a.topic-completed').last();
  var TopicListMyElementNotCompletedClass = $('div#learndash_lesson_topics_list ul li a.topic-notcompleted');
  var myNextButton = $('p#learndash_next_prev_link div:first-child');
  var myNextButtonLink = $('p#learndash_next_prev_link div:first-child a');
  var myNextSpanLink = $('p#learndash_next_prev_link div:first-child span a');
  var LessonNotCompletedCount = 0;
  var TopicsNotCompletedCount = 0;
  var nextLessonInactiveIncomplete = $('div.lesson').closest('div.learndash_nevigation_lesson_topics_list div.active').next().find('div.addIncomplete');

 var backToTheCourseHref = $('.btnBackToCourses').attr('href');
 var backToTheCourseText = $('.btnBackToCourses').text();

  //log whether the last element has a class completed
  console.log($(lastElement).hasClass('completed'));

  //Code to change the arrow to a square for the elements in the widget sidebar menu when they do not have topics
  changeArrowToSquare();

  //Adds the class addCompleted to the div element lesson_completed and adding the green checkmark
  addCompletedClassToLesson();

  //Adds the class addIncomplete to the div element lesson_incomplete and adding the faded greycheckmark
  addIncompleteClassToLesson();


  $('div.learndash_topic_dots').css('display', 'block');
  $('div.wpProQuiz_results p').css('display','none');
  $('div.wpProQuiz_question_page').wrapInner('<h3></h3>');
  $('h5.wpProQuiz_header').css('display', 'none');
  $("div.wpProQuiz_incorrect, div.wpProQuiz_correct" ).find('span').replaceWith( "<h4>Explication</h4>" );


  if (theLanguage === "fr-FR") {

    $('div.wpProQuiz_content').find('h2').text('Tester vos connaissances');
    $('div.wpProQuiz_incorrect h4').text('Explication');
    $('div.wpProQuiz_correct h4').text('Rétroaction');

  } else {

    $('div.wpProQuiz_content').find('h2').text('Test your knowledge');
    $('div.wpProQuiz_incorrect h4').text('Explanation');
    $('div.wpProQuiz_correct h4').text('Feedback');

  }

  $('div#learndash_lessons div#lessons_list h4 > a').wrapInner('<span></span>');
  $('table#learndash_lessons tr.is_not_sample td a').wrapInner('<span></span>');
  //$('table#learndash_lessons tr.is_not_sample td > a').addClass('ellipsis');
  $('div.sectionCompleted').hide();//Hides the div.sectionCompleted
  $('div#lessons_list a.completed').css('text-decoration','none!important');
  $('<span></span>').appendTo('form#sfwd-mark-complete');//Appends a span tag within the form#sfwd-mark-complete as the last element
  $('div.widget_course_return').hide();//Hides the div.widget_course_return from the sidebar
  $('form#sfwd-mark-complete input[type=submit]').val('Save and continue →').addClass('save');//Adds class of save to the learndash mark complete button
  $('input[type=submit]#next-question').val('Save and next question').addClass('next');
  $('input[type=submit]#action-button').val('Submit and review answers');
  $('div#watu_quiz hr').remove();//Removes hr from watu quiz
  $('span#learndash_complete_prev_lesson').addClass('previousNotComplete');
  $('div#learndash_back_to_lesson').hide();
  $('div.expand_collapse a:last-child').hide();
  $('div.expand_collapse ').hide();
  $('p#learndash_next_prev_link').find('br').hide();
  $('span.meta-nav').hide();
  $('section.widget_ldcourseprogress').hide();
  $('div.learndash_nevigation_lesson_topics_list').hide();
  $('form#sfwd-mark-complete').append('<button name="sfwd_mark_complete" class="save"></button>');
  $('form#sfwd-mark-complete input[type=submit]').hide();
  $('<br>').insertAfter('div.learndash_topic_dots strong');
  $('input.wpProQuiz_button.wpProQuiz_QuestionButton').css('float','none');




  //Functions to check if lessons and topics are not comleted

  $(myElementNotCompletedClass).each(function() {

    if (LastCompletedClass){

      $(myElementNotCompletedClass).next().parent(':first').bind('click', true);
      $(myElementNotCompletedClass).next().parent().not(':first').bind('click', false);
      $(myElementNotCompletedClass).next().parent().not(':first').find('a').css({cursor:"default"});

    }

    LessonNotCompletedCount = LessonNotCompletedCount + 1;

  });



  $(LessonListMyElementNotCompletedClass).each(function() {

    //LessonListMyElementNotCompletedClass.append(' my element not completed');
    if (LessonListLastCompletedClass){

      //LessonListLastCompletedClass.append(' my element last completed');
      $(LessonListMyElementNotCompletedClass).parent(':first').bind('click', true);
      $(LessonListMyElementNotCompletedClass).parent().not(':first').bind('click', false);
      $(LessonListMyElementNotCompletedClass).parent().not(':first').css({cursor:"default"});
      $(LessonListMyElementNotCompletedClass).parent().not(':first').find('h4').css('background-color', 'white!important');
    }

  });



  $(TopicListMyElementNotCompletedClass).each(function() {

    // TopicListMyElementNotCompletedClass.append(' my element not completed');
    if (TopicListLastCompletedClass){

      //TopicListLastCompletedClass.append(' my element last completed');
      $(TopicListMyElementNotCompletedClass).parent(':first').bind('click', true);
      $(TopicListMyElementNotCompletedClass).parent().not(':first').bind('click', false);
      $(TopicListMyElementNotCompletedClass).parent().not(':first').find('a').css({cursor:"pointer"});

    }

    TopicsNotCompletedCount = TopicsNotCompletedCount +1;

  });


  //Function to change the text on course description page
  changeTextCourseDescriptionPage() ;

  //Function to check if topics list is expanded
  checkTopicExpandedORCollapsed();



  // if (theLanguage = "fr-FR") {
    //$('div.btnBackToCourses a').text('Retourner à la description du cours');
    //$('aside.sidebar strong').text('Progrès :');
    //$('aside.sidebar div h4').text('Reste à completer');
    //$('aside.sidebar div h5').text('Déjà complété');
  //} else {
    //$('div.btnBackToCourses a').text('Return to course description');
    //$('aside.sidebar strong').text('Progress:');
    //$('aside.sidebar div h4').text('What is left to complete?');
    //$('aside.sidebar div h5').text('All Previously completed');
  //}


  $('div#learndash_course_content').hide();




  //On a Course Page
  if ($('body').hasClass('single-sfwd-courses') ) {

    setTimeout(function(){


         if ( $("span#learndash_course_status").text().trim(" ") == "État d’avancement : Non Commencé" ) {

            $('span#learndash_course_status').text("État d’avancement : non commencé");
        }

    }, 30);

    //Check if course status is completed. Changed the learndash_course_status span background color accordingly
    var courseStatusTextTrimmed = $('span#learndash_course_status').text().trim(' ');
    console.log('courseStatusTextTrimmed ' + courseStatusTextTrimmed);
    // ||  courseStatusTextTrimmed == "Course Status: In Progress"  || courseStatusTextTrimmed == "Status de cours: En Cours" || courseStatusTextTrimmed == "Course Status: Not Started"  ||  courseStatusTextTrimmed == "Status de cours: Non Commencé"


    //var findNonCommence = courseStatusTextTrimmed.match(/^[Non Commencé]*$/);




    $('a.btnLaunchLink').unbind('click');

    if ( courseStatusTextTrimmed == "Course Status: Completed"  || courseStatusTextTrimmed == "État d’avancement : Complété"   ) {

      $('span#learndash_course_status').css('background-color', '#5cb85c');

      //$( ".btnLaunchLink" ).appendTo( "#learndash_course_status" );
      $( ".theLaunch" ).prependTo( ".homebtn" );
      $( "<span> | </span>").insertAfter( ".theLaunch" );




    } else {

      $('span#learndash_course_status').css('background-color', '#e5e5e5');
      $('span#learndash_course_status').css('color', '#000');

      //$('a.btnLaunchLink').hide();
      $('.theLaunch').hide();

    }





    //Hide the widget_ldcourseprogress
    $('section.widget_ldcourseprogress').hide();
    //$('div#learndash_course_content').appendTo('aside.sidebar');




    //Function used if user is logged in
    if ( $('body').hasClass('logged-in') ) {

      $('<div class="callToAction" ><a href="" class="btnStartTheCourse col-xs-12 col-md-5" role="button" >Start the course</a><span class="theText col-xs-12 col-md-6"></span><a href="#" class="btnViewTheCourseContent col-xs-12 col-sm-12 col-md-12 col-lg-8" role="button">View your course progress and navigate through the module <span class="fa fa-th-list fa-fw"> </span> </a></div>').insertBefore('div#learndash_course_content');

    }//user logged in





    //Change the text on the button btnViewTheCourseContent according to language
    if (theLanguage === "fr-FR") {

      $('a.btnViewTheCourseContent').html('Voir votre progrès et naviguer à travers le module <span class="fa fa-th-list fa-fw"> </span> ');
      $('span.or').text('ou');

    } else {

      $('a.btnViewTheCourseContent').html('View your course progress and navigate through the module <span class="fa fa-th-list fa-fw"> </span> ');
      $('span.or').text('or');
    }






    //Function to check if there is a join button or not
    console.log('firstElementLink ' +  firstElementLink);
    console.log('if the learndash join button is visible');

    if ( $('div.learndash_join_button').length ) {

      if (theLanguage === "fr-FR") {

        $('div.learndash_join_button').find('input[type=submit]').val('S\'inscrire et commencer le cours');


      } else {

        $('div.learndash_join_button').find('input[type=submit]').val('Enroll and start the course');

      }


      $('div.learndash_join_button').find('form').attr('action', firstElementLink);
      $('div.learndash_join_button').find('input[type=submit]').addClass('col-xs-12 col-sm-6 col-md-6 col-lg-6');
      $('div.learndash_join_button input#btn-join').css('margin', '2em 0 2em 0');
      $('div.callToAction').hide();

    } else  {

      console.log('if the learndash join button is not visible');
      $('div.callToAction').show();

    }





    //Function to verify if the first element is completed or not
    if ( $(firstElement).hasClass('completed') ) {

      if (theLanguage === "fr-FR") {

        $('a.btnStartTheCourse').text('Continuer ');
        $('a.btnStartTheCourse').attr('href', nextNotCompletedElementLink);
        $('span.theText').text(' [ à ' + nextNotCompletedElementText + ' ] ou ');

      } else {

        $('a.btnStartTheCourse').text('Continue ');
        $('a.btnStartTheCourse').attr('href', nextNotCompletedElementLink );
        $('span.theText').text(' [ to ' + nextNotCompletedElementText + ' ] or ');

      }

    }  else  {

      if (theLanguage === "fr-FR") {

        $('a.btnStartTheCourse').text('Débuter le cours');
        $('a.btnStartTheCourse').attr('href', firstElementLink);
        //$('span.theText').text(' [ ' + firstElementText + ' ]');

      } else {

        $('a.btnStartTheCourse').text('Start the course');
        $('a.btnStartTheCourse').attr('href', firstElementLink);
                //$('span.theText').text(' [ ' + firstElementText + ' ]');

      }

    }




    //Function to verify if the last element is completed or not
    if ( $(lastElement).hasClass('completed') )  {

      if (theLanguage === "fr-FR") {

        $('a.btnStartTheCourse').text('Revue à partir du début');
        $('a.btnStartTheCourse').attr('href', firstElementLink);
        $('span.theText').text('');
        //$('span.theText').text(' [ à ' + firstElementText + ' ]');

      } else {

        $('a.btnStartTheCourse').text('Review from the beginning');
        $('a.btnStartTheCourse').attr('href', firstElementLink);
        $('span.theText').text('');
        //$('span.theText').text(' [ to ' + firstElementText + ' ]');

      }


    } else if ( $(firstElement).hasClass('completed') )  {


      if (theLanguage === "fr-FR") {

        $('a.btnStartTheCourse').text('Continuer ');
        $('a.btnStartTheCourse').attr('href', nextNotCompletedElementLink);
        $('span.theText').text(' [ à ' + nextNotCompletedElementText + ' ] ou ');

      } else {

        $('a.btnStartTheCourse').text('Continue ');
        $('a.btnStartTheCourse').attr('href', nextNotCompletedElementLink );
        $('span.theText').text(' [ to ' + nextNotCompletedElementText + ' ] or ');

      }

    } else {

      if (theLanguage === "fr-FR") {

        $('a.btnStartTheCourse').text('Débuter le cours');
        $('a.btnStartTheCourse').attr('href', firstElementLink);
        //$('span.theText').text(' [ ' + firstElementText + ' ]');

      } else {

        $('a.btnStartTheCourse').text('Start the course');
        $('a.btnStartTheCourse').attr('href', firstElementLink);
        //$('span.theText').text(' [ ' + firstElementText + ' ]');

      }

    }





    //Add event to toggle the btnViewTheCourseContent button
    $("a.btnViewTheCourseContent").click(function (e) {

      e.preventDefault();

      $('div#learndash_course_content').toggle( "slow", function() {
        // Animation complete.
      });

    });



  }



  //Add notes element to the sidebar
  $("aside.sidebar a.notes").wrap('<div class="notepad"></div>');
  $('div.notepad a.notes').append(' <span class="fa fa-pencil-square-o fa-fw"> </span> ');
  $('div.notepad a.notes').append(' <span class="fa fa-pencil-square-o fa-fw"> </span> ');


  //Change text on button to toggle notes
  if (theLanguage === "fr-FR") {

    $("div.notepad a.notes").text('Voir et écrire vos notes');

  } else {

    $("div.notepad a.notes").text('View and write your notes');

  }


  //Prepend list of topics to the sidebar navigation
  $('article.sfwd-topic div.entry-content div.learndash_topic_dots').prependTo('aside.sidebar');

  //Hide div.learndash_topic_dots
  $('aside.sidebar div.learndash_topic_dots').hide();

  //push all navigation element text to an array
  var pagesText = $('div.learndash_nevigation_lesson_topics_list div.active div.learndash_topic_widget_list ul li a span');
  var my_arr = [];

  //Add / Remove class topicActive
  $(pagesText).each(function() {


   var textTrimmed = $(this).text().trim();
   var currentPageTitle = $('article.sfwd-topic h2').first().text().trim();
   console.log('item: ' + textTrimmed );
   console.log('currentPageTitle new: ' + currentPageTitle);

   //var trimmedElement = (this).text().trim();


    //my_arr.push($(this).text().trim());
    //Prints out the text contained in this DIV

    if ( $(this).text().trim() == currentPageTitle ) {

      console.log('item: ' + $(this).text().trim() );

      $(this).closest('a').addClass('topicActive');



    } else {

      $(this).closest('a').removeClass('topicActive');

    }

  });


  var activeLesson = $('div.learndash_nevigation_lesson_topics_list').find('div.active');//find active lesson
  var lastCompletedLesson = $('div.learndash_nevigation_lesson_topics_list').find('div.lesson_completed').last();//find last completed lesson
  var lastCompletedLessonTopic = $('div.learndash_nevigation_lesson_topics_list div.learndash_topic_widget_list').find('ul li span.topic_item a.topic-completed').last();//find last completed lesson topic
  var LessonCompleted = $('div.list_lessons').hasClass('addCompleted');//Lesson completed
  var nextCompletedTopicLink = $('div.active div.lesson ul li span.topic_item a.topic-completed').attr('href');//get next not completed lesson topic text and link
  var nextCompletedTopicText = $('div.active div.lesson ul li span.topic_item a.topic-completed').text();
  var LastItemInList = $('div#learndash_lesson_topics_list ul li').last();
  var firstItemInList = $('div#learndash_lesson_topics_list ul li').first();
  var LastCompletedItemInList = $('div#learndash_lesson_topics_list ul li span.topic_item a.topic-completed').last().text();

  console.log(LastCompletedItemInList);

  var firstCompletedItemInList = $('div#learndash_lesson_topics_list ul li span.topic_item a.topic-completed').first().text();

  console.log(firstCompletedItemInList);

  var firstLastCompletedItemInList = $('div#learndash_lesson_topics_list ul li span.topic_item a.topic-completed').last().first();
  var NextItemAfterLastCompletedText = $(LastCompletedItemInList).parent().parent().next().find('a').text();
  var currentActiveLessonLink = $('div.learndash_nevigation_lesson_topics_list div.active').find('div.lesson a').attr('href');
  var currentActiveLessonText = $('div.learndash_nevigation_lesson_topics_list div.active').find('div.lesson').text();
  var NextInactiveLessonLink = $('div.lesson a').closest('div.learndash_nevigation_lesson_topics_list div.active').next().find('div.lesson a').attr('href');
  var NextInactiveLessonText = $('div.lesson a').closest('div.learndash_nevigation_lesson_topics_list div.active').next().find('div.lesson a').text();
  var PrevInactiveLessonLink = $('div.lesson a').closest('div.learndash_nevigation_lesson_topics_list div.active').prev().find('div.lesson a').attr('href');
  var PrevInactiveLessonText = $('div.lesson a').closest('div.learndash_nevigation_lesson_topics_list div.active').prev().find('div.lesson a').text();
  var TopicsAllCompleted = $('div.learndash_nevigation_lesson_topics_list > div.active > div.list_lessons').hasClass('addCompleted');//get info of lesson all completed
  var AllSpecificTopicsAllCompleted = $('div#learndash_lesson_topics_list ul li span.topic_item a').each(function(e) {
    $(this).hasClass('topic-completed');
  });

  console.log("AllSpecificTopicsAllCompleted " + AllSpecificTopicsAllCompleted);

  var TopicsAllCompletedTopics = $('div.learndash_nevigation_lesson_topics_list div.active div.learndash_topic_widget_list span.topic_item a').find("*").hasClass('topic-completed');
  var LessonHasTopics = $('div.learndash_nevigation_lesson_topics_list div.active div.learndash_topic_widget_list ').find("span.topic_item");//all topics completed
  var AllTopicsCompleted = $('div.learndash_nevigation_lesson_topics_list div.active div.learndash_topic_widget_list span.topic_item a').find("*").hasClass('topic-completed');//all topics completed
  var firstLessonActive = $('div.learndash_nevigation_lesson_topics_list div:first-child').hasClass('active');
  var lastLessonActive = $('div.learndash_nevigation_lesson_topics_list div:last-child').hasClass('active');

  console.log(firstLessonActive);
  console.log(lastLessonActive);

  var firstTopicActive = $('div.active div.learndash_topic_widget_list span.topic_item a');
  var nextLessonLink = $('div.lesson').closest('div.learndash_nevigation_lesson_topics_list div.active').next('div.inactive').find('div.lesson a').attr('href');
  var nextLessonText = $('div.lesson').closest('div.learndash_nevigation_lesson_topics_list div.active').next('div.inactive').find('div.lesson a').text();
  nextLessonText = $.trim(nextLessonText);

  var nextTopicLink = $('div#learndash_lesson_topics_list ul li span.topic_item a.topic-notcompleted').first().attr('href');
  var nextTopicText = $('div#learndash_lesson_topics_list ul li span.topic_item a.topic-notcompleted').first().text();
  nextTopicText = $.trim(nextTopicText);

  console.log(nextTopicText);

  var firstTopicInList = $('div.learndash_nevigation_lesson_topics_list div.active ul li span.topic_item a').first().text();
  var LastTopicCompletedInList = $('div.learndash_nevigation_lesson_topics_list div.active ul li span.topic_item a.topic-completed').last().text();
  var PrevLessonLink = $('div.lesson').closest('div.learndash_nevigation_lesson_topics_list div.active').prev('div.inactive').find('div.lesson a').attr('href');
  var PrevLessonText = $('div.lesson').closest('div.learndash_nevigation_lesson_topics_list div.active').prev('div.inactive').find('div.lesson a').text();
  var nextTopicItemLink = $('div#learndash_lesson_topics_list ul li span.topic_item a.topic-completed').closest('li').next().find('span.topic_item a.topic-notcompleted').attr('href');
  var nextTopicItemText = $('div#learndash_lesson_topics_list ul li span.topic_item a.topic-completed').closest('li').next().find('span.topic_item a.topic-notcompleted').text();
  var prevTopicItemLink = $('div#learndash_lesson_topics_list ul li span.topic_item a.topic-completed').closest('li').prev().find('span.topic_item a.topic-completed').attr('href');
  var prevTopicItemText = $('div#learndash_lesson_topics_list ul li span.topic_item a.topic-completed').closest('li').prev().find('span.topic_item a.topic-completed').text();
  var TopicItemLessonText = $('div#learndash_lesson_topics_list ul li span.topic_item a.topicActive').closest('div.learndash_topic_widget_list').siblings('div.lesson').find('a').text();
  var currentLessonText = $('div.lesson').closest('div.learndash_nevigation_lesson_topics_list div.active').find('div.lesson a').text();
  var currentLessonHref = $('div.lesson').closest('div.learndash_nevigation_lesson_topics_list div.active').find('div.lesson a').attr('href');

  console.log(TopicItemLessonText);

  //var prevTopicItemText = $('div.learndash_nevigation_lesson_topics_list div.active ul li span.topic_item a.topic-completed').closest('li').prev().find('span.topic_item a.topic-completed').last().text();
  //var prevTopicItemLink = $('div.learndash_nevigation_lesson_topics_list div.active ul li span.topic_item a.topic-completed').closest('li').prev().find('span.topic_item a.topic-completed').last().attr('href');
  var FirstLessonActive = $('div.learndash_nevigation_lesson_topics_list div').first().hasClass('active');
  var BacktoCourseText = $('div.btnBackToCourses a').text();

  console.log(BacktoCourseText);

  //$('<button>Voir votre progres</button>').appendTo('p#learndash_next_prev_link div.text p');
  $('<div class="myProgress"></div>').insertAfter('p#learndash_next_prev_link');
  //div.addCompleted
  $('div.learndash_nevigation_lesson_topics_list ').find('div.list_lessons').clone().appendTo( 'div.myProgress' );
  $('div.myProgress a.topic-notcompleted').show();
  $('div.myProgress a.topic-firstLessonActivecompleted').show();
  $('div.user_has_access div.myProgress').hide();
  //aside.sidebar div.myProgress div.list_lessons div.lesson a.addIncomplete

  var LastCompletedLesson = $('aside.sidebar div.myProgress div.addCompleted').last();
  var LastCompletedLessonText = $(LastCompletedLesson).find('a').text();
  var FirstIncompletedLesson = $('aside.sidebar div.myProgress div.addIncomplete').first();
  var FirstIncompletedLessonText = $(FirstIncompletedLesson).find('div.lesson a').text();

  console.log('LastCompletedLessonText ' + LastCompletedLessonText );
  console.log('FirstIncompletedLesson ' + FirstIncompletedLesson);
  console.log('FirstIncompletedLessonText ' + FirstIncompletedLessonText);




  //Underline lesson  when completed to mark as done else remove the underline
  $('aside.sidebar div.myProgress div.list_lessons').each(function(e) {

    if ( $(this).hasClass('addIncomplete') ) {

      $(this).find('div.lesson a').click(function() { return false; });
      $(this).find('div.lesson a').css('text-decoration', 'none');

    } else {

      $(this).find('div.lesson a').click(function() { return true; });
      $(this).find('div.lesson a').css('text-decoration', 'underline');

    }

  });



  //Add fontAwesome icon to the back to course button
  $('div.btnBackToCourses a.btn').prepend('<span class="fa fa-hand-o-left fa-fw"> </span>');




  var FirstTopicInList = $('div.learndash_nevigation_lesson_topics_list div.active div.learndash_topic_widget_list ul li').first();
  var theFirstTopicItemInList = $('div.learndash_nevigation_lesson_topics_list div.active div.learndash_topic_widget_list ul').find('li').first().find('a').text();
  console.log( "theFirstTopicItemInList " + $.trim(theFirstTopicItemInList));

  var FirstTopicItemInListText = $.trim(theFirstTopicItemInList);
  console.log(FirstTopicItemInListText);

  var FirstTopicItemInListLink = $('div.learndash_nevigation_lesson_topics_list div.active div.learndash_topic_widget_list ul').find('li').first().find('a').attr('href');
  console.log(FirstTopicItemInListLink);

  var theLastTopicItemInList = $('div.learndash_nevigation_lesson_topics_list div.active div.learndash_topic_widget_list ul').find('li').last();
  var theLastTopicItemInListText = $('div.learndash_nevigation_lesson_topics_list div.active div.learndash_topic_widget_list ul').find('li').last().find('a').text();
  console.log(theLastTopicItemInListText);

  var theLastCompletedTopicItemInList = $('div.learndash_nevigation_lesson_topics_list div.active div.learndash_topic_widget_list ul li').find('a.topic-completed').last().text();
  var theLastCompletedTopicItem = $('div.learndash_nevigation_lesson_topics_list div.active div.learndash_topic_widget_list ul li').find('a.topic-completed').last();
  var NextTopicAfterLastCompletedText = $(theLastCompletedTopicItem).closest('li').next().find('a.topic-notcompleted').text();
  NextTopicAfterLastCompletedTextTrimmed = $.trim(NextTopicAfterLastCompletedText);
  var NextTopicAfterLastCompleted = $(theLastCompletedTopicItem).closest('li').next().find('a.topic-notcompleted').first();
  var nextTopicItemInListText = $('div.learndash_nevigation_lesson_topics_list div.active div.learndash_topic_widget_list ul li a.topic-completed').closest('li').next('li').find('a.topic-notcompleted').first().text();
  var prevTopicItemInListText = $('div.learndash_nevigation_lesson_topics_list div.active div.learndash_topic_widget_list ul li a.topic-completed').closest('li').prev('li').find('a.topic-notcompleted').first().text();
  var nextTopicItemInListLink = $('div.learndash_nevigation_lesson_topics_list div.active div.learndash_topic_widget_list ul li a.topic-completed').closest('li').next('li').find('a.topic-notcompleted').first().attr('href');
  var prevTopicItemInListLink = $('div.learndash_nevigation_lesson_topics_list div.active div.learndash_topic_widget_list ul li a.topic-completed').closest('li').prev('li').find('a.topic-notcompleted').first().attr('href');
  var currentPageTitle = $('article.sfwd-topic h2').text().trim();
  console.log("currentPageTitle " + currentPageTitle);

  var activeLessonIsComplete = $('div.learndash_nevigation_lesson_topics_list').find('div.active').find('div').hasClass('lesson_completed');


  //get body class of current page
  var pageClassStr = $('body').attr('class'),
  currentPageClassStr = pageClassStr.substr( pageClassStr.lastIndexOf(' ') + 1);
  console.log('current page Class ' + currentPageClassStr);

  var currentClassNoHyphens = currentPageClassStr;
  currentClassNoHyphens = currentClassNoHyphens.replace(/-/g, ' ');
  currentClassNoHyphensFirstLetterCaps = currentClassNoHyphens.charAt(0).toUpperCase() + currentClassNoHyphens.slice(1).toLowerCase();

  console.log('currentClassNoHyphensFirstLetterCaps ' + currentClassNoHyphensFirstLetterCaps);
  console.log('NextTopicAfterLastCompletedText ' + NextTopicAfterLastCompletedText);
  console.log( $('div.learndash_nevigation_lesson_topics_list div.active div.learndash_topic_widget_list ul li').find('a').text() );
  console.log("NextTopicAfterLastCompletedTextTrimmed " + NextTopicAfterLastCompletedTextTrimmed);





  var activeTopic = $('div.learndash_nevigation_lesson_topics_list div.active div.learndash_topic_widget_list ul li').find('a.topicActive');
  var firstActiveTopic = $('div.learndash_nevigation_lesson_topics_list div.active div.learndash_topic_widget_list ul li').find('a.topicActive').first();
  var lastActiveTopic = $('div.learndash_nevigation_lesson_topics_list div.active div.learndash_topic_widget_list ul li').find('a.topicActive').last();
  var activeTopicText = $('div.learndash_nevigation_lesson_topics_list div.active div.learndash_topic_widget_list ul li').find('a.topicActive').text();
  var numberTopicItems = $('div.learndash_nevigation_lesson_topics_list div.active div.learndash_topic_widget_list ul').children().length;
  console.log(numberTopicItems);



  if ( numberTopicItems <= 1 ) {

    console.log('smaller');

  } else {

    console.log('greater');

  }



  //var numberOfTopics = $('div.learndash_nevigation_lesson_topics_list div.active div.list_lessons div.learndash_topic_widget_list ul').find('li').length();
  //console.log(numberOfTopics);
  console.log("the topic active " + $.trim(activeTopicText));


  var NextTopicAfterActiveTopicText = $('div.learndash_nevigation_lesson_topics_list div.active div.learndash_topic_widget_list ul li a.topicActive').closest('li').next('li').find('a').first().text();
  var NextTopicAfterActiveTopicHref = $('div.learndash_nevigation_lesson_topics_list div.active div.learndash_topic_widget_list ul li a.topicActive').closest('li').next('li').find('a').first().attr('href');
  var PrevTopicAfterActiveTopicText = $('div.learndash_nevigation_lesson_topics_list div.active div.learndash_topic_widget_list ul li a.topicActive').closest('li').prev('li').find('a').first().text();
  var PrevTopicAfterActiveTopicHref = $('div.learndash_nevigation_lesson_topics_list div.active div.learndash_topic_widget_list ul li a.topicActive').closest('li').prev('li').find('a').first().attr('href');
  var PrevTopicFindLessonText = $('div.learndash_nevigation_lesson_topics_list div.active div.learndash_topic_widget_list ul li a.topicActive').closest('div.list_lessons').find('div.lesson a').text();
  var PrevTopicFindLessonLink = $('div.learndash_nevigation_lesson_topics_list div.active div.learndash_topic_widget_list ul li a.topicActive').closest('div.list_lessons').find('div.lesson a').attr('href');

  console.log(PrevTopicFindLessonText);
  console.log(PrevTopicFindLessonLink);
  console.log("NextTopicAfterActiveTopicText " + NextTopicAfterActiveTopicText);
  console.log("NextTopicAfterActiveTopicHref " + NextTopicAfterActiveTopicHref);
  console.log("PrevTopicAfterActiveTopicText " + PrevTopicAfterActiveTopicText);
  console.log("PrevTopicAfterActiveTopicHref " + PrevTopicAfterActiveTopicHref);
  console.log("theFirstTopicItemInList " + theFirstTopicItemInList);
  console.log("theLastTopicItemInList " + theLastTopicItemInList);
  console.log("theLastCompletedTopicItemInList " + theLastCompletedTopicItemInList);
  console.log(theLastCompletedTopicItemInList == theFirstTopicItemInList);
  console.log("nextTopicItemInListText " + nextTopicItemInListText);
  console.log("prevTopicItemInListText " + prevTopicItemInListText);
  console.log("nextTopicItemInListLink " + nextTopicItemInListLink);
  console.log("prevTopicItemInListLink " + prevTopicItemInListLink);

  //find last completed lesson topic
  var lastCompletedTopic = $('div.learndash_nevigation_lesson_topics_list div.learndash_topic_widget_list').find('ul li span.topic_item a.topic-completed').last();
  var ActiveTopic = $('div.learndash_nevigation_lesson_topics_list div.active div.learndash_topic_widget_list ul li a').hasClass('topicActive');
  console.log("Active topic text " + $.trim(activeTopicText));
  console.log("Last topic item in list text " + $.trim(theLastTopicItemInListText));
  console.log("Is active topic text = to last topic item in list text  " + ( $.trim(activeTopicText).toString() == $.trim(theLastTopicItemInListText).toString() )  );




  //On a Certificate Page. Hide the credits. Hide the title H1
  if ($('body').hasClass('single-sfwd-certificates')){

    $('section#credits').hide();
    $('h1.entry-title').hide();

  }



  //to make sure only the modules users have access to appear in the list for residency.
  if ( $('body').hasClass('page') ) {

    $('article#page').find('p.restricted').closest('dl').hide();
    // $('main.main').find('p.restricted').show();
    // $('main.main').find('p.restricted').sibling().show();

  }







  //************************** START LESSON PAGE SECTION ****************************

  if ( $('body').hasClass('single-sfwd-lessons') ){ //On a lesson page


    $('p#learndash_next_prev_link').find('a').wrap('<div></div>');
    $('p#learndash_next_prev_link div').find('a').addClass('button');
    $('p#learndash_next_prev_link div').find('a').addClass('col-xs-12 col-sm-12 col-md-3 col-lg-3');
    $('div.videotabs').insertAfter('div.video-wrapper');

    //Adds a div element with class of myProgress to the sidebar
    $('aside.sidebar').append('<div class="myProgress" aria-hidden="true"></div>');

    //Appends the content of the div element with class myProgress from the body of the page to the sidebar div element with class myProgress
    $("div.myProgress").contents().appendTo('aside.sidebar div.myProgress');

    //Insert div.myProgress after sidebar a.btnProgress
    $("div.myProgress").insertAfter('aside.sidebar a.btnProgress');



    //Check if page is french or english and appends the button with class btnProgress to the Sidebar div element with class myProgress
    if (theLanguage === "fr-FR") {

      $('<a class="btnProgress">Voir votre progrès et naviguer à travers le module </a>').insertBefore('aside.sidebar div.myProgress');
      $('<a class="btn btnNotes">Voir et écrire vos notes</a> ').insertAfter('aside.sidebar div.myProgress');
      $('<div class="Notes" aria-hidden="true"><textarea id="MyNotes" name="MyNotes" placeholder="Nous vous encourageons à prendre des notes lorsque vous parcourez le module." rows="40"  onkeyup="textAreaAdjust(this)" style="overflow:hidden"></textarea></div>').insertAfter('a.btnNotes');

    } else {

      $('<a class="btnProgress">View your course progress and navigate through the module </a>').insertBefore('aside.sidebar div.myProgress');
      $('<a class="btnNotes">View or write some notes</a> ').insertAfter('aside.sidebar div.myProgress');
      $('<div class="Notes" aria-hidden="true"><textarea id="MyNotes" name="MyNotes" placeholder="We encourage you to write some notes as you progress through the module" rows="40"  onkeyup="textAreaAdjust(this)" style="overflow:hidden"></textarea></div>').insertAfter('a.btnNotes');

    }




  var course_id = $("body").eq(0).data("course-id");



  $(document).ready(function() {
    $('div.Notes').sisyphus({
      //locationBased: true

      locationBased: false,
      customKeySuffix: 'course_' + course_id,

      onSave: function() {
        console.log('Saved');
      }

    }); //save the reflection answers through localStorage
  });




  //Appends a span element with the glyphicon glyphicon-list-alt class to display icon within the view progress button
  $('aside.sidebar a.btnProgress').append('<span class="fa fa-th-list fa-fw"> </span> ');
  $('aside.sidebar a.btnNotes').append('<span class="fa fa-pencil-square-o fa-fw"> </span> ');


  //Hides div.myProgress and div.Notes
  $('aside.sidebar div.myProgress').hide();
  $('aside.sidebar div.Notes').hide();


  //Toggles the div.myProgress on click event on the view progress button (a.btnProgress)
  $("aside.sidebar a.btnProgress").click(function (event) {

    event.preventDefault();

    $('aside.sidebar div.myProgress').slideToggle( "slow", function(event) {
        // Animation complete.
    });

  });


  //Toggles the textarea#MyNotes on click event on to add notes
  $("aside.sidebar a.btnNotes").click(function (c) {

    c.preventDefault();

    $('aside.sidebar div.Notes').slideToggle( "slow", function() {
        // Animation complete.
    });
  });


  //Add footer links to lesson pages
  //$('div.container').append('<hr><div class="footerResources"><ul><li>Resources</li><li>Glossary</li><li>Notes</li><li>Help</li></div>');

  $('article.sfwd-lessons hr').hide();//Hide extra <hr>
  $('#learndash_next_prev_link div').addClass('myMain');//Add class myMain to the #learndash_next_prev_link div
  $('#learndash_next_prev_link div.myMain').append('<div><p></p></div>');//Append a diva nd p to #learndash_next_prev_link div
  $('#learndash_next_prev_link div.myMain > a').addClass('col-xs-12 col-sm-6 col-md-8 col-lg-8');//Add mobile class to the anchor element
  $('#learndash_next_prev_link div.myMain > div').addClass('text col-xs-12 col-sm-6 col-md-8 col-lg-8');//Add mobile class to div.myMain > div
  $('input.save').val(nextLessonText + ' ⟶');//Add next lesson text and a right arrow to the input.save
  $('div#learndash_lesson_topics_list').hide();//Hides div#learndash_lesson_topics_list



  if (firstLessonActive) {  //firstLessonActive

     console.log('THE FIRST lesson is active');

    if (($('div.active > div.list_lessons > div.learndash_topic_widget_list').children().length > 0 )) { //if lesson has topic

        console.log('THE lesson has topics');

           $('#learndash_next_prev_link div.myMain').find('a.button').attr('rel', 'next');
            //$('#learndash_next_prev_link div.myMain').last().find('a.button').attr('rel', 'prev');
            $('#learndash_next_prev_link a').removeClass('prev').addClass('next');
            //$('#learndash_next_prev_link a').last().removeClass('next').addClass('prev');
            $('#learndash_next_prev_link div.myMain').find('a.button').attr('href', FirstTopicItemInListLink);

            $('#learndash_next_prev_link div.myMain').addClass('continue');
            $('#learndash_next_prev_link div.myMain').find('a.button').append('<span class="fa fa-chevron-right fa-fw"> </span>');//adding glyphicon right arrow
            $('#learndash_next_prev_link div.myMain').find('div p').html(' [ ' + FirstTopicItemInListText + ' ] ');



            //Mark complete form button
            $('form#sfwd-mark-complete button').addClass('next');
            $('form#sfwd-mark-complete button').append('<span class="fa fa-chevron-right fa-fw"> </span>');
            $('form#sfwd-mark-complete').append('<span class="theText"></span>');
            $('form#sfwd-mark-complete span.theText').html(' [ ' + nextLessonText + ' ] '); //FirstTopicItemInListText



            //prev button links to the previous lesson
           // $('#learndash_next_prev_link div.myMain').last().find('a.button').attr('href', PrevLessonLink);
            //$('#learndash_next_prev_link div.myMain').last().addClass('return');
            //adding glyphicon left arrow
           // $('#learndash_next_prev_link div.myMain').last().find('a.button').prepend('<span class="fa fa-chevron-left fa-fw"> </span>');
            //$('#learndash_next_prev_link div.myMain').last().find('div p').html(' [ ' + PrevLessonText + ' ] ');



        if ( $('form#sfwd-mark-complete').length > 0 && $('#learndash_next_prev_link div.continue').length ) {

            console.log('mark complete exist');
            console.log('the learndash continue exist');


            $('#learndash_next_prev_link div.continue').hide();

            if (theLanguage === "fr-FR") { //test if language is french

              //next and previous button text

              $('#learndash_next_prev_link div.myMain').find('a.button').html('Continuer ');
              //$('#learndash_next_prev_link div.myMain').last().find('a.button').html(' Retourner  ');
              $('form#sfwd-mark-complete button').text('Continuer ');
              //$('#learndash_next_prev_link div.myMain').first().find('a.button').html('Continuer <br> <span class="moreInforTxt"> ' + FirstTopicItemInListText + ' </span> ');
              //$('#learndash_next_prev_link div.myMain').last().find('a.button').html(' Retourner <br> <span class="moreInfoTxt"> ' + PrevLessonText + ' </span> ');
              //$('form#sfwd-mark-complete button').text('Continuer ');

            } else { //language is english

              //next button and previous buttons text
              $('#learndash_next_prev_link div.myMain').find('a.button').html('Continue  ');
              $('form#sfwd-mark-complete button').text('Continue ');
              //$('#learndash_next_prev_link div.myMain').find('a.button').html(' Go back ');
              //$('#learndash_next_prev_link div.myMain').first().find('a.button').html('Continue <br> <span class="moreInforTxt"> ' + FirstTopicItemInListText + ' </span> ');
              //$('form#sfwd-mark-complete button').text('Continue ');
              //$('#learndash_next_prev_link div.myMain').last().find('a.button').html(' Go back <br> <span class="moreInfoTxt"> ' + PrevLessonText + ' </span> ');
            }



        } else {

              console.log('mark complete DOES NOT exist');
              console.log('the learndash continue DOES NOT exist');



              $('#learndash_next_prev_link div.continue').show();
              $('#learndash_next_prev_link div.continue').show();

            if (theLanguage === "fr-FR") { //test if language is french

              //next and previous button text

              $('#learndash_next_prev_link div.myMain').find('a.button').html('Continuer ');
              //$('#learndash_next_prev_link div.myMain').last().find('a.button').html(' Retourner  ');
              $('form#sfwd-mark-complete button').text('Continuer ');
              //$('#learndash_next_prev_link div.myMain').first().find('a.button').html('Continuer <br> <span class="moreInforTxt"> ' + FirstTopicItemInListText + ' </span> ');
              //$('#learndash_next_prev_link div.myMain').last().find('a.button').html(' Retourner <br> <span class="moreInfoTxt"> ' + PrevLessonText + ' </span> ');
              //$('form#sfwd-mark-complete button').text('Continuer ');

            } else { //language is english

              //next button and previous buttons text
              $('#learndash_next_prev_link div.myMain').find('a.button').html('Continue  ');
              //$('#learndash_next_prev_link div.myMain').last().find('a.button').html(' Retourner  ');
              $('form#sfwd-mark-complete button').text('Continue ');
              //$('#learndash_next_prev_link div.myMain').find('a.button').html(' Go back ');
              //$('#learndash_next_prev_link div.myMain').first().find('a.button').html('Continue <br> <span class="moreInforTxt"> ' + FirstTopicItemInListText + ' </span> ');
              //$('form#sfwd-mark-complete button').text('Continue ');
              //$('#learndash_next_prev_link div.myMain').last().find('a.button').html(' Go back <br> <span class="moreInfoTxt"> ' + PrevLessonText + ' </span> ');
            }







        }



    } else {  //lesson has no topics


        if (theLanguage === "fr-FR") { //test if language is french

                  //next button text
                  $('#learndash_next_prev_link div.myMain').first().find('a.button').html('Continuer  ');
                  //$('#learndash_next_prev_link div.myMain').first().find('a.button').html('Continuer <br> <span class="moreInfoTxt"> [ ' + nextLessonText + ' ] </span> ');
                  //markcomplete button text
                  $('form#sfwd-mark-complete button').text('Continuer ');


        } else { //language is english

                  //next button text
                  $('#learndash_next_prev_link div.myMain').first().find('a.button').html('Continue ');
                  //$('#learndash_next_prev_link div.myMain').first().find('a.button').html('Continue <br> <span class="moreInfoTxt"> [ ' + nextLessonText + ' ] </span> ');
                  //markcomplete button text
                  $('form#sfwd-mark-complete button').text('Continue ');

        }


        $('#learndash_next_prev_link div.myMain').first().find('a.button').attr('rel', 'next');
        $('#learndash_next_prev_link a').first().removeClass('prev').addClass('next');

        //next button links to the next lesson
        $('#learndash_next_prev_link div.myMain').first().find('a.button').attr('href', nextLessonLink);
        $('#learndash_next_prev_link div.myMain').first().addClass('continue');
        //adding glyphicon right arrow
        $('#learndash_next_prev_link div.myMain').first().find('a.button').append('<span class="fa fa-chevron-right fa-fw"> </span>');

        $('#learndash_next_prev_link div.myMain').first().find('div p').html(' [ ' + nextLessonText + ' ] ');


        //Mark complete form button

        $('form#sfwd-mark-complete button').addClass('next');
        $('form#sfwd-mark-complete button').append('<span class="fa fa-chevron-right fa-fw"> </span>');
        $('form#sfwd-mark-complete').append('<span class="theText"></span>');
        $('form#sfwd-mark-complete span.theText').html(' [ ' + nextLessonText + ' ] ');


    }


  } else if (lastLessonActive) { //lastLessonActive

      console.log('Last lesson active');

      if  ( $('div.active > div.list_lessons > div.learndash_topic_widget_list').children().length > 0  ) { //Last lesson has topic

         console.log('Lesson has topics');

         $("<div class='myMain' ><a href='' class='button col-xs-12 col-sm-12 col-md-3 col-lg-3 col-sm-6 col-md-8 col-lg-8 prev' ></a><div class='text col-xs-12 col-sm-6 col-md-8 col-lg-8'><p></p></div></div>").appendTo('#learndash_next_prev_link');

          if (theLanguage === "fr-FR") { //test if language is french

            //next button links to the first topic in the list
            $('#learndash_next_prev_link div.myMain').first().find('a.button').html('Continuer ');
            $('form#sfwd-mark-complete button').text('Continuer ');
            //$('form#sfwd-mark-complete button').text('Terminer le cours ');
            $('#learndash_next_prev_link div.myMain').last().find('a.button').html(' Retourner ');

            //$('#learndash_next_prev_link div.myMain').first().find('a.button').html('Continuer <br> <span class="moreInfoTxt"> [ ' + FirstTopicItemInListText + ' ] </span> ');
            //$('form#sfwd-mark-complete button').text('Continuer ');
            //$('#learndash_next_prev_link div.myMain').last().find('a.button').html(' Retourner <br> <span class="moreInfoTxt"> [ ' + PrevLessonText + ' ] </span> ');

          } else { //language is english

            //next button links to the first topic in the list
            $('#learndash_next_prev_link div.myMain').first().find('a.button').html('Continue ');
            $('form#sfwd-mark-complete button').text('Continue ');
            //$('form#sfwd-mark-complete button').text('Finish the course ');
            $('#learndash_next_prev_link div.myMain').last().find('a.button').html(' Go back ' );
            //$('#learndash_next_prev_link div.myMain').first().find('a.button').html('Continue <br> <span class="moreInfoTxt"> [ ' + FirstTopicItemInListText + ' ] </span> ');
            //$('form#sfwd-mark-complete button').text('Continue ');
            //$('#learndash_next_prev_link div.myMain').last().find('a.button').html(' Go back <br> <span class="moreInfoTxt"> [ ' + PrevLessonText + ' ] </span> ' );

          }





          $('#learndash_next_prev_link div.myMain').first().find('a.button').attr('rel', 'next');
          $('#learndash_next_prev_link div.myMain').last().find('a.button').attr('rel', 'prev');
          $('#learndash_next_prev_link a').first().removeClass('prev').addClass('next');
          $('#learndash_next_prev_link a').last().removeClass('next').addClass('prev');
          $('#learndash_next_prev_link div.myMain').first().find('a.button').attr('href', FirstTopicItemInListLink);
          $('#learndash_next_prev_link div.myMain').first().addClass('continue');
          $('#learndash_next_prev_link div.myMain').first().find('a.button').append('<span class="fa fa-chevron-right fa-fw"> </span>'); //adding glyphicon right arrow
          $('#learndash_next_prev_link div.myMain').first().find('div p').html(' [ ' + FirstTopicItemInListText + ' ] ');

          //Mark complete form button
          $('form#sfwd-mark-complete button').addClass('next');
          //$('form#sfwd-mark-complete button').append('<span class="fa fa-chevron-right fa-fw"> </span>');
          $('form#sfwd-mark-complete').append('<span class="theText"></span>');
          //$('form#sfwd-mark-complete span.theText').html(' [ ' + FirstTopicItemInListText + ' ] ');

          $('form#sfwd-mark-complete span.theText').html(' [ ' + backToTheCourseText + ' ] ');


          console.log('LAST LESSON ACTIVE');

          //$('form#sfwd-mark-complete span.theText').html(' [ ' + backToTheCourseText + ' ] ');










          //prev button links to the previous lesson
          $('#learndash_next_prev_link div.myMain').last().find('a.button').attr('href', PrevLessonLink);
          $('#learndash_next_prev_link div.myMain').last().addClass('return');
          $('#learndash_next_prev_link div.myMain').last().find('div p').html(' [ ' + PrevLessonText + ' ] ');


          //adding glyphicon left arrow
          $('#learndash_next_prev_link div.myMain').last().find('a.button').prepend('<span class="fa fa-chevron-left fa-fw"> </span>');
          $('#learndash_next_prev_link div.myMain').last().find('div p').html(' [ ' + PrevLessonText + ' ] ');


      } else {  //(Last lesson has no topics)


          console.log('lesson has NO topics ');

          console.log('backToTheCourseText ' + backToTheCourseText);

          if (theLanguage === "fr-FR") { //test if language is french

                  console.log('No topics and in the french ');

                  //$('form#sfwd-mark-complete button').text('Continuer ');

                  //$('form#sfwd-mark-complete button').html('Terminer ');

                  $('form#sfwd-mark-complete button').html('Terminer');


                  $('#learndash_next_prev_link div.myMain').last().find('a.button').html(' Retourner '  );
                  //$('form#sfwd-mark-complete button').text('Continuer ');
                  //$('#learndash_next_prev_link div.myMain').last().find('a.button').html(' Retourner <br> <span class="moreInfoTxt"> [ ' + PrevLessonText + ' ] </span> '  );

          } else { //test if language is english

                  console.log('No topics and in the english ');
                   //$('form#sfwd-mark-complete button').text('Continue ');

                  //$('form#sfwd-mark-complete button').html('Finish ');
                  $('form#sfwd-mark-complete button').html('Finish');


                   $('#learndash_next_prev_link div.myMain').last().find('a.button').html(' Go back  '  );
                   //$('form#sfwd-mark-complete button').text('Continue ');
                   //$('#learndash_next_prev_link div.myMain').last().find('a.button').html(' Go back <br> <span class="moreInfoTxt"> [ ' + PrevLessonText + ' ] </span> '  );
          }


          //Previous button navigation
          $('#learndash_next_prev_link div.myMain').find('a.button').attr('rel', 'prev');
          $('#learndash_next_prev_link a').last().removeClass('next').addClass('prev');

          //Mark complete navigation
          $('form#sfwd-mark-complete button').addClass('next');
          $('form#sfwd-mark-complete button').append('<span class="fa fa-chevron-right fa-fw"> </span>');
          $('form#sfwd-mark-complete').append('<span class="theText"></span>');
          //$('form#sfwd-mark-complete span.theText').html(' [ ' +  nextLessonText + ' ] ');

          $('form#sfwd-mark-complete span.theText').html(' [ ' + backToTheCourseText + ' ] ');



          //prev button links to the prev lesson
          $('#learndash_next_prev_link div.myMain').last().find('a.button').attr('href', PrevLessonLink);
          $('#learndash_next_prev_link div.myMain').last().addClass('return');
          //adding glyphicon left arrow
          $('#learndash_next_prev_link div.myMain').last().find('a.button').prepend('<span class="fa fa-chevron-left fa-fw"> </span>');
          $('#learndash_next_prev_link div.myMain').find('div p').html(' [ ' + PrevLessonText + ' ] ');

      }


    } else { //any other lesson active


        if  (($('div.active > div.list_lessons > div.learndash_topic_widget_list').children().length > 0 )) {  //lesson has topics

          console.log('lesson has topics');
          if (theLanguage === "fr-FR") { //test if language is french

              //next button links to the first topic in the list
              $('#learndash_next_prev_link div.myMain').first().find('a.button').html('Continuer ' );
              $('form#sfwd-mark-complete button').text('Continuer ');
              $('#learndash_next_prev_link div.myMain').last().find('a.button').html(' Retourner '  );
              //$('#learndash_next_prev_link div.myMain').first().find('a.button').html('Continuer <br> <span class="moreInfoTxt"> [ ' + FirstTopicItemInListText + ' ] </span> ' );
              //$('form#sfwd-mark-complete button').text('Continuer ');
              //$('#learndash_next_prev_link div.myMain').last().find('a.button').html(' Retourner <br> <span class="moreInfoTxt"> [ ' + PrevLessonText + ' ] </span> '  );

          } else { //language is english

            //next button links to the first topic in the list
            $('#learndash_next_prev_link div.myMain').first().find('a.button').html('Continue ' );
            $('form#sfwd-mark-complete button').text('Continue ');
            $('#learndash_next_prev_link div.myMain').last().find('a.button').html(' Go back '  );
            //$('#learndash_next_prev_link div.myMain').first().find('a.button').html('Continue <br> <span class="moreInfoTxt"> [ ' + FirstTopicItemInListText + ' ] </span> ' );
            //$('form#sfwd-mark-complete button').text('Continue ');
            //$('#learndash_next_prev_link div.myMain').last().find('a.button').html(' Go back <br> <span class="moreInfoTxt"> [ ' + PrevLessonText + ' ] </span> '  );

          }

            $('#learndash_next_prev_link div.myMain').first().find('a.button').attr('rel', 'next');
            $('#learndash_next_prev_link div.myMain').last().find('a.button').attr('rel', 'prev');
            $('#learndash_next_prev_link a').first().removeClass('prev').addClass('next');
            $('#learndash_next_prev_link a').last().removeClass('next').addClass('prev');
            $('#learndash_next_prev_link div.myMain').first().find('a.button').attr('href', FirstTopicItemInListLink);
            $('#learndash_next_prev_link div.myMain').first().addClass('continue');
            $('#learndash_next_prev_link div.myMain').first().find('a.button').append('<span class="fa fa-chevron-right fa-fw"> </span>');//adding glyphicon right arrow
            $('#learndash_next_prev_link div.myMain').first().find('div p').html(' [ ' + FirstTopicItemInListText + ' ] ');


            //Mark complete form button
            $('form#sfwd-mark-complete button').addClass('next');
            $('form#sfwd-mark-complete button').append('<span class="fa fa-chevron-right fa-fw"> </span>');
            $('form#sfwd-mark-complete').append('<span class="theText"></span>');
            $('form#sfwd-mark-complete span.theText').html(' [ ' + FirstTopicItemInListText + ' ] ');

            //prev button links to the previous lesson
            $('#learndash_next_prev_link div.myMain').last().find('a.button').html(' Retourner' );
            $('#learndash_next_prev_link div.myMain').last().find('a.button').attr('href', PrevLessonLink);
            $('#learndash_next_prev_link div.myMain').last().addClass('return');

            //adding glyphicon left arrow
            $('#learndash_next_prev_link div.myMain').last().find('a.button').prepend('<span class="fa fa-chevron-left fa-fw"> </span>');
            $('#learndash_next_prev_link div.myMain').last().find('div p').html(' [ ' + PrevLessonText + ' ] ');


        } else {  //lesson has no topics


           if (lastLessonActive) {


              if (theLanguage === "fr-FR") { //test if language is french

                      console.log('lesson has not topics and in the french');
                      //next button links to next lesson
                      $('#learndash_next_prev_link div.myMain').first().find('a.button').html('Continuer ');
                      //$('form#sfwd-mark-complete button').text('Continuer ');

                      $('form#sfwd-mark-complete button').html('Terminer <span class="fa fa-chevron-right fa-fw"> </span>');

                      $('#learndash_next_prev_link div.myMain').last().find('a.button').html(' Retourner');
                      //$('#learndash_next_prev_link div.myMain').first().find('a.button').html('Continuer <br> <span class="moreInfoTxt"> [ ' + nextLessonText + ' ] </span> ');
                      //$('form#sfwd-mark-complete button').text('Continuer ');
                      //$('#learndash_next_prev_link div.myMain').last().find('a.button').html(' Retourner <br> <span class="moreInfoTxt"> [ ' + PrevLessonText + ' ] </span> ');

              } else { //language is english

                      console.log('lesson has not topics and in the english');

                      //next button links to next lesson
                      $('#learndash_next_prev_link div.myMain').first().find('a.button').html('Continue ');
                      //$('form#sfwd-mark-complete button').text('Continue ');

                      $('form#sfwd-mark-complete button').html('Finish <span class="fa fa-chevron-right fa-fw"> </span>');

                      $('#learndash_next_prev_link div.myMain').last().find('a.button').html(' Go back ' );
                      //$('#learndash_next_prev_link div.myMain').first().find('a.button').html('Continue <br> <span class="moreInfoTxt"> [ ' + nextLessonText + ' ] </span> ');
                      //$('form#sfwd-mark-complete button').text('Continue ');
                      //$('#learndash_next_prev_link div.myMain').last().find('a.button').html(' Go back <br> <span class="moreInfoTxt"> [ ' + PrevLessonText + ' ] </span> ' );
              }


            } else {

               if (theLanguage === "fr-FR") { //test if language is french

                      console.log('lesson has not topics and in the french');
                      //next button links to next lesson
                      $('#learndash_next_prev_link div.myMain').first().find('a.button').html('Continuer ');
                      $('form#sfwd-mark-complete button').text('Continuer ');

                      //$('form#sfwd-mark-complete button').html('Terminer <span class="fa fa-chevron-right fa-fw"> </span>');

                      $('#learndash_next_prev_link div.myMain').last().find('a.button').html(' Retourner');
                      //$('#learndash_next_prev_link div.myMain').first().find('a.button').html('Continuer <br> <span class="moreInfoTxt"> [ ' + nextLessonText + ' ] </span> ');
                      //$('form#sfwd-mark-complete button').text('Continuer ');
                      //$('#learndash_next_prev_link div.myMain').last().find('a.button').html(' Retourner <br> <span class="moreInfoTxt"> [ ' + PrevLessonText + ' ] </span> ');

              } else { //language is english

                      console.log('lesson has not topics and in the english');

                      //next button links to next lesson
                      $('#learndash_next_prev_link div.myMain').first().find('a.button').html('Continue ');
                      $('form#sfwd-mark-complete button').text('Continue ');

                      //$('form#sfwd-mark-complete button').html('Finish <span class="fa fa-chevron-right fa-fw"> </span>');

                      $('#learndash_next_prev_link div.myMain').last().find('a.button').html(' Go back ' );
                      //$('#learndash_next_prev_link div.myMain').first().find('a.button').html('Continue <br> <span class="moreInfoTxt"> [ ' + nextLessonText + ' ] </span> ');
                      //$('form#sfwd-mark-complete button').text('Continue ');
                      //$('#learndash_next_prev_link div.myMain').last().find('a.button').html(' Go back <br> <span class="moreInfoTxt"> [ ' + PrevLessonText + ' ] </span> ' );
              }

            }





            $('#learndash_next_prev_link div.myMain').first().find('a.button').attr('rel', 'next');
            $('#learndash_next_prev_link div.myMain').last().find('a.button').attr('rel', 'prev');
            $('#learndash_next_prev_link a').first().removeClass('prev').addClass('next');
            $('#learndash_next_prev_link a').last().removeClass('next').addClass('prev');
            $('#learndash_next_prev_link div.myMain').first().find('a.button').attr('href', nextLessonLink);
            $('#learndash_next_prev_link div.myMain').first().addClass('continue');
            $('#learndash_next_prev_link div.myMain').first().find('a.button').append('<span class="fa fa-chevron-right fa-fw"> </span>');//adding glyphicon right arrow
            $('#learndash_next_prev_link div.myMain').first().find('div p').html(' [ ' + nextLessonText + ' ] ');

            //Mark complete form button
            $('form#sfwd-mark-complete button').addClass('next');
            $('form#sfwd-mark-complete button').append('<span class="fa fa-chevron-right fa-fw"> </span>');
            $('form#sfwd-mark-complete').append('<span class="theText"></span>');
            $('form#sfwd-mark-complete span.theText').html(' [ ' + nextLessonText + ' ] ');

            //prev button links to the prev lesson
            $('#learndash_next_prev_link div.myMain').last().find('a.button').attr('href', PrevLessonLink);
            $('#learndash_next_prev_link div.myMain').last().addClass('return');
            $('#learndash_next_prev_link div.myMain').last().find('a.button').prepend('<span class="fa fa-chevron-left fa-fw"> </span>');//adding glyphicon left arrow
            $('#learndash_next_prev_link div.myMain').last().find('div p').html(' [ ' + PrevLessonText + ' ] ');


        } //end of lesson has no topics

    }//end of any other lesson active

  }//end of on a lesson page

  //************************** END LESSON PAGE SECTION ****************************







    //************************** START TOPIC PAGE SECTION ****************************

    //var NextTopicAfterActiveTopicText = $('div.learndash_nevigation_lesson_topics_list div.active div.learndash_topic_widget_list ul li a.topicActive').closest('li').next('li').find('a').first().text();
    //var NextTopicAfterActiveTopicHref = $('div.learndash_nevigation_lesson_topics_list div.active div.learndash_topic_widget_list ul li a.topicActive').closest('li').next('li').find('a').first().attr('href');

  if ( $('body').hasClass('single-sfwd-topic') ){ //On a topic page


      if ( ( $.trim(activeTopicText).toString() == $.trim(theFirstTopicItemInList).toString() ) || ( $.trim(activeTopicText).toString() == $.trim(theLastTopicItemInListText).toString() ) ) {

          $('<a href="" rel="prev" class="prev" > Retourner </a>').appendTo('p#learndash_next_prev_link');

      }


      //Lesson ONLY has one topic
      if ( ($.trim(activeTopicText) == $.trim(theFirstTopicItemInList)) &&  ( $.trim(activeTopicText) == $.trim(theLastTopicItemInListText).toString() )  ) {

        console.log('topic is first and last element');
        $('<a href="" rel="prev" class="prev" > Retourner </a>').appendTo('p#learndash_next_prev_link');

      }



    $('div.videotabs').insertAfter('div.video-wrapper');
    //$('div.videotabs').insertBefore('p#learndash_next_prev_link');
    $('p#learndash_next_prev_link').find('a').wrap('<div></div>');
    $('p#learndash_next_prev_link div').find('a').addClass('button');
    $('p#learndash_next_prev_link div').find('a').addClass('col-xs-12 col-sm-12 col-md-3 col-lg-3');
    $('#learndash_next_prev_link div').addClass('myMain');
    $('#learndash_next_prev_link div.myMain').append('<div><p></p></div>');
    $('#learndash_next_prev_link div.myMain > a').addClass('col-xs-12 col-sm-6 col-md-8 col-lg-8');
    $('#learndash_next_prev_link div.myMain > div').addClass('text col-xs-12 col-sm-6 col-md-8 col-lg-8');
    $('#learndash_next_prev_link div.myMain').first().addClass('continue');
    $('#learndash_next_prev_link div.myMain').last().addClass('return');
    $('#myTab a:first').tab('show');
    $('div.videotabs').insertAfter('div.learndash embed');
    //$('div.container').append('<hr><div class="footerResources"><ul><li>Resources</li><li>Glossary</li><li>Notes</li><li>Help</li></div>');
    $('article.sfwd-topic hr').hide();
    $('.meta-nav').hide();

    console.log('on a topic page');

    //$('div#learndash_back_to_lesson').find('a').text('⟵ ' + PrevLessonText );
    $('section.widget_ldcourseprogress').hide();

    console.log("nextTopicItemText " + nextTopicItemText);

    $('aside.sidebar').append('<div class="myProgress"></div>');//Adds a div element with class of myProgress to the sidebar
    $("div.myProgress").contents().appendTo('aside.sidebar div.myProgress');//Appends the content of the div element with class myProgress from the body of the page to the sidebar div element with class myProgress


    //Check if page is french or english and appends the button with class btnProgress to the Sidebar div element with class myProgress
    if (theLanguage === "fr-FR") {

      $('<a class="btnProgress">Voir votre progrès et naviguer à travers le module </a>').insertBefore('aside.sidebar div.myProgress');
                                $('<a class="btn btnNotes">Voir et écrire vos notes</a> ').insertAfter('aside.sidebar a.btnProgress');
                                $('<div class="Notes" aria-hidden="true"><textarea id="MyNotes" name="MyNotes" placeholder="Nous vous encourageons à prendre des notes lorsque vous parcourez le module." rows="40"  onkeyup="textAreaAdjust(this)" style="overflow:hidden"></textarea></div>').insertAfter('a.btnNotes');

                              } else {

                                $('<a class="btnProgress">View your course progress and navigate through the module </a>').insertBefore('aside.sidebar div.myProgress');
                                $('<a class="btnNotes">View or write some notes</a> ').insertAfter('aside.sidebar div.myProgress');
                                $('<div class="Notes" aria-hidden="true"><textarea id="MyNotes" name="MyNotes" placeholder="We encourage you to write some notes as you progress through the module" rows="40"  onkeyup="textAreaAdjust(this)" style="overflow:hidden"></textarea></div>').insertAfter('a.btnNotes');

                              }



      var course_id = $("body").eq(0).data("course-id");

      $(document).ready(function() {

        $('div.Notes').sisyphus({
            //locationBased: true

            locationBased: false,
            customKeySuffix: 'course_' + course_id,


            onSave: function() {


              console.log('Saved');

            }

        }); // save the reflection answers through localStorage

      });




      $('aside.sidebar a.btnProgress').append('<span class="fa fa-th-list fa-fw"> </span>');//Appends a span element with the glyphicon glyphicon-list-alt class to display icon within the view progress button
      $('aside.sidebar a.btnNotes').append(' <span class="fa fa-th-list fa-fw"> </span>');//Appends a span element with the glyphicon glyphicon-list-alt class to display icon within the view Notes button
      $('aside.sidebar div.myProgress').hide();//Hides the sidebar div element  with class myProgress
      $('aside.sidebar div.Notes').hide();//Hides the sidebar div element  with class Notes


      $("aside.sidebar a.btnProgress").click(function (e) {
        e.preventDefault();
          $('aside.sidebar div.myProgress').toggle( "slow", function() {
            // Animation complete.
          });
       });//Event handler to toggle navigation myProgress


      $("aside.sidebar a.btnNotes").click(function (c) {
        c.preventDefault();
        $('aside.sidebar div.Notes').toggle( "slow", function() {
          //insert code here
        });
      });//Event Handler to toggle notes



      if ( numberTopicItems <= 1 ) { //ONLY ONE topic


          console.log('Only 1 topic');

          //if language is french
          if (theLanguage === "fr-FR") {

            console.log(theLanguage);
            console.log('french');

            //Mark complete button code
            $('form#sfwd-mark-complete button').text('Continuer ');
            $('form#sfwd-mark-complete button').addClass('next');
            $('form#sfwd-mark-complete button').append('<span class="fa fa-chevron-right fa-fw"> </span>')
            $('form#sfwd-mark-complete').append('<span class="theText"></span>');
            $('form#sfwd-mark-complete span.theText').html(' [ ' + nextLessonText + ' ] ');

            $('#learndash_next_prev_link div.myMain').first().find('a.button').text('Continuer ');
            //$('#learndash_next_prev_link div.myMain').first().find('a.button').attr('href', nextLessonLink);
            $('#learndash_next_prev_link div.myMain').first().find('div p').html(' [ ' + nextLessonText + ' ] ');
            $('#learndash_next_prev_link div.myMain').first().find('a.button').attr('href', nextLessonLink);
            $('#learndash_next_prev_link div.myMain').first().addClass('continue');
            $('#learndash_next_prev_link div.myMain').first().find('a.button').removeClass('prev').addClass('next');
            //adding glyphicon right arrow
            $('#learndash_next_prev_link div.myMain').first().find('a.button').append('<span class="fa fa-chevron-right fa-fw"> </span>');
            $('#learndash_next_prev_link div.myMain').last().find('a.button').text(' Retourner');
            $('#learndash_next_prev_link div.myMain').last().find('a.button').attr('href', currentLessonHref);
            $('#learndash_next_prev_link div.myMain').last().find('div p').html(' [ ' + currentLessonText + ' ] ');
            $('#learndash_next_prev_link div.myMain').last().addClass('return');
            $('#learndash_next_prev_link div.myMain').last().find('a.button').removeClass('next').addClass('prev');
            //adding glyphicon left arrow
            $('#learndash_next_prev_link div.myMain').last().find('a.button').prepend('<span class="fa fa-chevron-left fa-fw"> </span>');


          } else { //if language is english

            console.log('english');

            //Mark complete button code
            $('form#sfwd-mark-complete button').text('Continue ');
            $('form#sfwd-mark-complete button').addClass('next');
            $('form#sfwd-mark-complete button').append('<span class="fa fa-chevron-right fa-fw"> </span>')
            $('form#sfwd-mark-complete').append('<span class="theText"></span>');
            $('form#sfwd-mark-complete span.theText').html(' [ ' + nextLessonText + ' ] ');

            $('#learndash_next_prev_link div.myMain').first().find('a.button').text('Continue ');
            $('#learndash_next_prev_link div.myMain').first().find('a.button').attr('href', nextLessonLink);
            $('#learndash_next_prev_link div.myMain').first().find('div p').html(' [ ' + nextLessonText + ' ] ');
            $('#learndash_next_prev_link div.myMain').first().addClass('continue');
            $('#learndash_next_prev_link div.myMain').first().find('a.button').removeClass('prev').addClass('next');
            $('#learndash_next_prev_link div.myMain').first().find('a.button').append('<span class="fa fa-chevron-right fa-fw"> </span>'); //adding glyphicon right arrow
            $('#learndash_next_prev_link div.myMain').last().find('a.button').text(' Go back');
            $('#learndash_next_prev_link div.myMain').last().find('a.button').attr('href', currentLessonHref);
            console.log('PrevLessonText ' + PrevLessonText);
            $('#learndash_next_prev_link div.myMain').last().find('div p').html(' [ ' + currentLessonText + ' ] ');
            $('#learndash_next_prev_link div.myMain').last().addClass('return');
            $('#learndash_next_prev_link div.myMain').last().find('a.button').removeClass('next').addClass('prev');
            $('#learndash_next_prev_link div.myMain').last().find('a.button').prepend('<span class="fa fa-chevron-left fa-fw"> </span>');//adding glyphicon left arrow

          }

      } else {//MORE than ONE topic

            console.log('more than 1 topic');

            if ( $.trim(activeTopicText) == $.trim(theFirstTopicItemInList) )  {//Active topic is first in the list

              console.log('topic active is first in list');

              //if language is french
              if (theLanguage === "fr-FR") {

                console.log('french');

                //$('#learndash_next_prev_link div.myMain:first-child').find('a.button').text('Continue ');
                $('#learndash_next_prev_link div.myMain').first().find('a.button').text('Continuer ');
                //$('#learndash_next_prev_link div.myMain:first-child').find('a.button').attr('href', NextTopicAfterActiveTopicHref);
                $('#learndash_next_prev_link div.myMain').first().find('a.button').attr('href', NextTopicAfterActiveTopicHref);
                //$('#learndash_next_prev_link div.myMain:first-child a.button').append('<span class="fa fa-chevron-right fa-fw"> </span>');
                $('#learndash_next_prev_link div.myMain').first().find('a.button').append('<span class="fa fa-chevron-right fa-fw"> </span>');//adding glyphicon right arrow
                //$('#learndash_next_prev_link div.myMain:first-child').find('div p').html(' [ ' + NextTopicAfterActiveTopicText + ' ] ');
                $('#learndash_next_prev_link div.myMain').first().find('div p').html(' [ ' + NextTopicAfterActiveTopicText + ' ] ');
                $('#learndash_next_prev_link div.myMain').first().find('a').addClass('next');

                console.log('NextTopicAfterActiveTopicText' + NextTopicAfterActiveTopicText);
                $('form#sfwd-mark-complete button').text('Continuer ');
                $('form#sfwd-mark-complete').append('<span class="theText"></span>');
                $('form#sfwd-mark-complete button').addClass('next');
                $('form#sfwd-mark-complete button').append('<span class="fa fa-chevron-right fa-fw"> </span>')
                $('form#sfwd-mark-complete span.theText').html(' [ ' + NextTopicAfterActiveTopicText + ' ] ');

                $('#learndash_next_prev_link div.myMain:first-child a').addClass('next');
                $('#learndash_next_prev_link div.myMain:last-child').find('a.button').text(' Retourner');
                $('#learndash_next_prev_link div.myMain:last-child').find('a.button').attr('href', currentLessonHref);
                $('#learndash_next_prev_link div.myMain:last-child a.button').prepend('<span class="fa fa-chevron-left fa-fw"> </span>'); //adding glyphicon left arrow
                $('#learndash_next_prev_link div.myMain:last-child').find('div p').html(' [ ' + currentLessonText + ' ] ');
                //$('#learndash_next_prev_link div.myMain:last-child').find('div p').html('à la leçon ' + PrevTopicAfterActiveTopicText + ' ou <button class="btnProgress" >voir votre Progrès</button>.');
                // $('#learndash_next_prev_link div.myMain:last-child').find('button.btnProgress').append('<span class="glyphicon glyphicon glyphicon-list-alt"></span>');
                $('#learndash_next_prev_link div.myMain:last-child a').addClass('prev');


              } else { //if language is english

                console.log('new ' + NextTopicAfterActiveTopicText);
                console.log('new ' + NextTopicAfterActiveTopicHref);
                console.log('english');

                //$('#learndash_next_prev_link div.myMain:first-child').find('a.button').text('Continue ');
                $('#learndash_next_prev_link div.myMain').first().find('a.button').text('Continue ');
                //$('#learndash_next_prev_link div.myMain:first-child').find('a.button').attr('href', NextTopicAfterActiveTopicHref);
                $('#learndash_next_prev_link div.myMain').first().find('a.button').attr('href', NextTopicAfterActiveTopicHref);
                //$('#learndash_next_prev_link div.myMain:first-child a.button').append('<span class="fa fa-chevron-right fa-fw"> </span>');
                $('#learndash_next_prev_link div.myMain').first().find('a.button').append('<span class="fa fa-chevron-right fa-fw"> </span>');//adding glyphicon right arrow
                //$('#learndash_next_prev_link div.myMain:first-child').find('div p').html(' [ ' + NextTopicAfterActiveTopicText + ' ] ');
                $('#learndash_next_prev_link div.myMain').first().find('div p').html(' [ ' + NextTopicAfterActiveTopicText + ' ] ');
                $('#learndash_next_prev_link div.myMain').first().find('a').addClass('next');

                console.log('NextTopicAfterActiveTopicText' + NextTopicAfterActiveTopicText);
                $('form#sfwd-mark-complete button').text('Continue ');
                $('form#sfwd-mark-complete').append('<span class="theText"></span>');
                $('form#sfwd-mark-complete button').addClass('next');
                $('form#sfwd-mark-complete button').append('<span class="fa fa-chevron-right fa-fw"> </span>')
                $('form#sfwd-mark-complete span.theText').html(' [ ' + NextTopicAfterActiveTopicText + ' ] ');

                $('#learndash_next_prev_link div.myMain:last-child').find('a.button').text(' go back');
                $('#learndash_next_prev_link div.myMain:last-child').find('a.button').attr('href', currentLessonHref);
                $('#learndash_next_prev_link div.myMain:last-child a.button').prepend('<span class="fa fa-chevron-left fa-fw"> </span>');//adding glyphicon left arrow
                console.log('PrevLessonText ' + PrevLessonText);
                $('#learndash_next_prev_link div.myMain:last-child').find('div p').html(' [ ' + currentLessonText + ' ] ');
                //$('#learndash_next_prev_link div.myMain:last-child').find('div p').html('to the ' + PrevTopicAfterActiveTopicText + 'topic or <button class="btnProgress" >view your progress</button>.');
                //$('#learndash_next_prev_link div.myMain:last-child').find('button.btnProgress').append('<span class="glyphicon glyphicon glyphicon-list-alt"></span>');
                $('#learndash_next_prev_link div.myMain:last-child a').addClass('prev');

              }


            } else if ( $.trim(activeTopicText).toString()  ==  $.trim(theLastTopicItemInListText).toString()  ) {//active topic is last in the list

                console.log('topic active is last in list');


                // if ( $('form#sfwd-mark-complete').length > 0 )  {


                // }







                //if language is french
                if (theLanguage === "fr-FR") {

                  console.log('french');
                  console.log(nextLessonText);

                  $('#learndash_next_prev_link div.myMain').first().find('a.button').text('Continuer ');
                  $('#learndash_next_prev_link div.myMain').first().find('a.button').attr('href', nextLessonLink);
                  $('#learndash_next_prev_link div.myMain').first().find('div p').html(' [ ' + nextLessonText + ' ] ');
                  $('#learndash_next_prev_link div.myMain').first().addClass('continue');
                  $('#learndash_next_prev_link div.myMain').first().find('a.button').addClass('next');
                  $('#learndash_next_prev_link div.myMain').first().find('a.button').append('<span class="fa fa-chevron-right fa-fw"> </span>');//adding glyphicon right arrow

                  //Mark complete button code
                  console.log("this is nextLessonLink " + nextLessonLink);
                  console.log("this is backToTheCourse link " + backToTheCourseHref);
                  console.log("this is backToTheCourse text " + backToTheCourseText);


                  //$('form#sfwd-mark-complete button').text('Continuer ');
                 // $('form#sfwd-mark-complete button').text('Terminer le cours ');
                  $('form#sfwd-mark-complete button').addClass('next');
                  $('form#sfwd-mark-complete button').append('<span class="fa fa-chevron-right fa-fw"> </span>')
                  $('form#sfwd-mark-complete').append('<span class="theText"></span>');
                  //$('form#sfwd-mark-complete span.theText').html(' [ ' + backToTheCourseText + ' ] ');


                  $('#learndash_next_prev_link div.myMain').last().find('a.button').text(' Retourner');
                  $('#learndash_next_prev_link div.myMain').last().find('a.button').attr('href', PrevTopicAfterActiveTopicHref);
                  $('#learndash_next_prev_link div.myMain').last().find('div p').html(' [ ' + PrevTopicAfterActiveTopicText + ' ] ');
                  $('#learndash_next_prev_link div.myMain').last().addClass('return');
                  $('#learndash_next_prev_link div.myMain').last().find('a.button').addClass('prev');
                  $('#learndash_next_prev_link div.myMain').last().find('a.button').prepend('<span class="fa fa-chevron-left fa-fw"> </span>');//adding glyphicon left arrow



                    if (lastLessonActive) {

                        console.log('LAST LESSON ACTIVE');



                        $('form#sfwd-mark-complete span.theText').html(' [ ' + backToTheCourseText + ' ] ');
                        $('div.continue div.text').find('p').html(' [ ' + backToTheCourseText + ' ] ');
                        $('#learndash_next_prev_link div.myMain').first().find('a.button').attr('href', backToTheCourseHref);

                        //$('form#sfwd-mark-complete button').text('Finish the course ');
                          //if (theLanguage === "fr-FR") {

                            $('form#sfwd-mark-complete button').html('Terminer <span class="fa fa-chevron-right fa-fw"> </span>');

                          //} else {

                            //$('form#sfwd-mark-complete button').html('Finish <span class="fa fa-chevron-right fa-fw"> </span>');
                          //}

                  } else {

                        $('form#sfwd-mark-complete span.theText').html(' [ ' + nextLessonText + ' ] ');

                          //if (theLanguage === "fr-FR") {

                           $('form#sfwd-mark-complete button').html('Continuer <span class="fa fa-chevron-right fa-fw"> </span>');

                          //} else {

                              //$('form#sfwd-mark-complete button').html('Continue <span class="fa fa-chevron-right fa-fw"> </span>');

                          //s}
                        //$('form#sfwd-mark-complete button').text('Continue ');
                  }




                } else { //if language is english

                  console.log('topic is the last');
                  console.log('english');


                  var parentLesson = $('div.learndash_nevigation_lesson_topics_list div.active div.learndash_topic_widget_list ul li').find('a.topicActive').closest('div.list_lessons');

                  console.log('the activeTopic ' +  activeTopic);
                  console.log('the parentLesson ' +  parentLesson);



                  $('#learndash_next_prev_link div.myMain').first().find('a.button').text('Continue ');
                  $('#learndash_next_prev_link div.myMain').first().find('a.button').attr('href', nextLessonLink);
                  $('#learndash_next_prev_link div.myMain').first().find('div p').html(' [ ' + nextLessonText + ' ] ');
                  $('#learndash_next_prev_link div.myMain').first().addClass('continue');
                  $('#learndash_next_prev_link div.myMain').first().find('a.button').addClass('next');
                  $('#learndash_next_prev_link div.myMain').first().find('a.button').append('<span class="fa fa-chevron-right fa-fw"> </span>');//adding glyphicon right arrow


                  //Mark complete button code
                  console.log("this is nextLessonLink " + nextLessonLink);
                  console.log("this is backToTheCourse link " + backToTheCourseHref);
                  console.log("this is backToTheCourse text " + backToTheCourseText);
                  console.log('nextLessonInactiveIncomplete ' + nextLessonInactiveIncomplete);


                  $('form#sfwd-mark-complete button').addClass('next');
                  //$('form#sfwd-mark-complete button').append('<span class="fa fa-chevron-right fa-fw"> </span>')
                  $('form#sfwd-mark-complete').append('<span class="theText"></span>');

                  //$('form#sfwd-mark-complete').attr('action', nextLessonLink);

                  $('#learndash_next_prev_link div.myMain').last().find('a.button').text(' Go back');
                  $('#learndash_next_prev_link div.myMain').last().find('a.button').attr('href', PrevTopicAfterActiveTopicHref);
                  $('#learndash_next_prev_link div.myMain').last().find('div p').html(' [ ' + PrevTopicAfterActiveTopicText + ' ] ');
                  $('#learndash_next_prev_link div.myMain').last().addClass('return');
                  $('#learndash_next_prev_link div.myMain').last().find('a.button').addClass('prev');
                  $('#learndash_next_prev_link div.myMain').last().find('a.button').prepend('<span class="fa fa-chevron-left fa-fw"> </span>');//adding glyphicon left arrow



                  if (lastLessonActive) {

                        console.log('LAST LESSON ACTIVE');



                        $('form#sfwd-mark-complete span.theText').html(' [ ' + backToTheCourseText + ' ] ');
                        $('div.continue div.text').find('p').html(' [ ' + backToTheCourseText + ' ] ');
                        $('#learndash_next_prev_link div.myMain').first().find('a.button').attr('href', backToTheCourseHref);

                        //$('form#sfwd-mark-complete button').text('Finish the course ');
                          //if (theLanguage === "fr-FR") {

                            //$('form#sfwd-mark-complete button').html('Terminer <span class="fa fa-chevron-right fa-fw"> </span>');

                         // } else {

                            $('form#sfwd-mark-complete button').html('Finish <span class="fa fa-chevron-right fa-fw"> </span>');
                          //}

                  } else {

                        $('form#sfwd-mark-complete span.theText').html(' [ ' + nextLessonText + ' ] ');

                          //if (theLanguage === "fr-FR") {

                           //$('form#sfwd-mark-complete button').html('Continuer <span class="fa fa-chevron-right fa-fw"> </span>');

                          //} else {

                              $('form#sfwd-mark-complete button').html('Continue <span class="fa fa-chevron-right fa-fw"> </span>');

                          //}
                        //$('form#sfwd-mark-complete button').text('Continue ');
                  }


                }

            } else { //active topic is any other but the first or last

                console.log('topic active is anything but first or last in list');

                if ( $('div#questionDiv').length > 0 ) {

                    console.log('there is a qtl question on the topic page');
                    $("form#sfwd-mark-complete").hide();

                }

                //if language is french
                if (theLanguage === "fr-FR") {

                  console.log('french');
                  console.log(nextLessonText);



                  $('#learndash_next_prev_link div.myMain').first().find('a.button').text('Continuer ');
                  $('#learndash_next_prev_link div.myMain').first().find('a.button').attr('href', NextTopicAfterActiveTopicHref);
                  $('#learndash_next_prev_link div.myMain').first().find('div p').html(' [ ' + NextTopicAfterActiveTopicText + ' ] ');
                  $('#learndash_next_prev_link div.myMain').first().addClass('continue');
                  $('#learndash_next_prev_link div.myMain').first().find('a.button').addClass('next');
                  $('#learndash_next_prev_link div.myMain').first().find('a.button').append('<span class="fa fa-chevron-right fa-fw"> </span>');//adding glyphicon right arrow

                  //Mark complete button code
                  $('form#sfwd-mark-complete button').text('Continuer ');
                  $('form#sfwd-mark-complete button').addClass('next');
                  $('form#sfwd-mark-complete button').append('<span class="fa fa-chevron-right fa-fw"> </span>')
                  $('form#sfwd-mark-complete').append('<span class="theText"></span>');
                  $('form#sfwd-mark-complete span.theText').html(' [ ' + NextTopicAfterActiveTopicText + ' ] ');

                  $('#learndash_next_prev_link div.myMain').last().find('a.button').text(' Retourner');
                  $('#learndash_next_prev_link div.myMain').last().find('a.button').attr('href', PrevTopicAfterActiveTopicHref);
                  $('#learndash_next_prev_link div.myMain').last().find('div p').html(' [ ' + PrevTopicAfterActiveTopicText + ' ] ');
                  $('#learndash_next_prev_link div.myMain').last().addClass('return');
                  $('#learndash_next_prev_link div.myMain').last().find('a.button').addClass('prev');
                  $('#learndash_next_prev_link div.myMain').last().find('a.button').prepend('<span class="fa fa-chevron-left fa-fw"> </span>');//adding glyphicon left arrow

                } else { //if language is english


                  console.log('english');
                  console.log(nextLessonText);





                  $('#learndash_next_prev_link div.myMain').first().find('a.button').text('Continue ');
                  $('#learndash_next_prev_link div.myMain').first().find('a.button').attr('href', NextTopicAfterActiveTopicHref);
                  $('#learndash_next_prev_link div.myMain').first().find('div p').html(' [ ' + NextTopicAfterActiveTopicText + ' ] ');
                  $('#learndash_next_prev_link div.myMain').first().addClass('continue');
                  $('#learndash_next_prev_link div.myMain').first().find('a.button').addClass('next');
                  $('#learndash_next_prev_link div.myMain').first().find('a.button').append('<span class="fa fa-chevron-right fa-fw"> </span>');//adding glyphicon right arrow

                  //Mark complete button code
                  $('form#sfwd-mark-complete button').text('Continue ');
                  $('form#sfwd-mark-complete button').addClass('next');
                  $('form#sfwd-mark-complete button').append('<span class="fa fa-chevron-right fa-fw"> </span>')
                  $('form#sfwd-mark-complete').append('<span class="theText"></span>');
                  $('form#sfwd-mark-complete span.theText').html(' [ ' + NextTopicAfterActiveTopicText + ' ] ');


                  $('#learndash_next_prev_link div.myMain').last().find('a.button').text(' Go back');
                  $('#learndash_next_prev_link div.myMain').last().find('a.button').attr('href', PrevTopicAfterActiveTopicHref);
                  $('#learndash_next_prev_link div.myMain').last().find('div p').html(' [ ' + PrevTopicAfterActiveTopicText + ' ] ');
                  $('#learndash_next_prev_link div.myMain').last().addClass('return');
                  $('#learndash_next_prev_link div.myMain').last().find('a.button').addClass('prev');
                  $('#learndash_next_prev_link div.myMain').last().find('a.button').prepend('<span class="fa fa-chevron-left fa-fw"> </span>');//adding glyphicon left arrow



            }





        }// End of active topic is any other but the first or last

    }//End of MORE than ONE topic

  }//End of on a topic page





//show and hide the continue button
function showHideContinueQuestionAnswered () {

  if(document.querySelectorAll(".save").length || document.querySelectorAll(".previousNotComplete").length){

    $("p#learndash_next_prev_link div.continue").hide();
    $('section.widget_sfwd-lessons-widget li a').data("disabled", "disabled");

  } else {

    $("p#learndash_next_prev_link div.continue").show();

  }

}


// $('form#sfwd-mark-complete button').text('Continuer ');
//if ( $('div.learndash_nevigation_lesson_topics_list div:first-child').find'div.list_lessons').hasClass('addIncomplete') == true ) {
//$("section.widget_sfwd-lessons-widget li a").attr("disabled","disabled");


var oldVal = "";
var currentVal;
var divTags = $( "div#questionDiv" );

if ( divTags.parent().is( "div#theExam" ) ) {

  divTags.unwrap();

}



$('div#reflectionFeedbackDiv p:first-child').nextAll('h3, *').wrapAll('<div class="additionalInformation"></div>');
$('div#questionDiv').unwrap();

//displays incorrect when users has incorrect answer also display when correct, need to fix.
//$('span.incorrect').insertBefore('div#reflectionFeedbackDiv');
//$('span.incorrect').css('display','block');


$('span.correct').closest('div').css('border','0.1em solid #000');
$('span.incorrect').closest('div').css('border','0.1em solid #000');

$('span.correct').closest('div').css('padding','1em 1.5em 2em 1.5em');
$('span.incorrect').closest('div').css('padding','1em 1.5em 2em 1.5em');

$('span.correct').closest('div').css('margin-top','2em');
$('span.incorrect').closest('div').css('margin-top','2em');


// $('div.correctFeedbackDiv').css('border','none');
// $('div.incorrectFeedbackDiv').css('border','none');
// $('div.correctFeedbackDiv').css('padding','0.3em 1em 2em 2em');
// $('div.incorrectFeedbackDiv').css('padding','0.3em 1em 2em 2em');

if (theLanguage === "fr-FR") {

  $("div#questionDiv textarea").attr('placeholder', 'Écrire votre réponse ici.');

} else {

  $("div#questionDiv textarea").attr('placeholder', 'Write your answer here');

}

$('input.feedbackQuestion').css('display', 'none');


//disable checkanswer button for the quiz tool lite open ended questions
//$('input.checkAnswer').css('display', 'none');
$('input.checkAnswer').attr('disabled', 'disabled');
$('input.checkAnswer').removeClass(':hover');
$('input.checkAnswer').css('opacity', '.1');


qtl_has_feedback = true;
if (1 <= $('div#questionDiv textarea').size()) {
    qtl_question_num = $('div#questionDiv textarea').first().attr('id').match(/[1-9][0-9]*/)[0];
    qtl_feedback = $('div#exampleQuestionAnswerCorrect' + qtl_question_num);

    if ("" == $(qtl_feedback).text().trim()) {
        qtl_has_feedback = false;

    }
}


if (theLanguage === "fr-FR") {

    if (qtl_has_feedback) {
        $('input.checkAnswer').val('Sauvegarder et vérifier votre réponse');
    } else {
        $('input.checkAnswer').val('Sauvegarder votre réponse');
    }
    $("input[name='next']").val('Question suivante');
    $("input[name='check']").val('Vérifier la réponse');

} else {

    if (qtl_has_feedback) {
        $('input.checkAnswer').val('Save and verify your answer');
    } else {
        $('input.checkAnswer').val('Save your answer');
    }
    $("input[name='next']").val('Next question');
    $("input[name='check']").val('Check answer');

}

var textAreaNoValue = $.trim( $('div#questionDiv textarea').val() );



// //article class is qtl_questionen
// if ($('article').hasClass('category-qtl_questionen') || $('article').hasClass('category-qtl_questionfr') ) {
//     console.log('article has category category-qtl_questionen or category-qtl_questionfr');
//   //if (textAreaNoValue === "") {

//       //hide the continue button

//       $("article.category-qtl_questionen div.continue, article.category-qtl_questionfr div.continue").hide();
//       //hide the mark complete button
//       $('article.category-qtl_questionen form#sfwd-mark-complete, article.category-qtl_questionfr form#sfwd-mark-complete').hide();
//       $('article.category-qtl_questionen form#sfwd-mark-complete button, article.category-qtl_questionfr form#sfwd-mark-complete button').hide();
//       $('article.category-qtl_questionen form#sfwd-mark-complete button span.theText, article.category-qtl_questionfr form#sfwd-mark-complete button span.theText').hide();


//   //}

// }

var originalCurrentVal;

if ($('article').hasClass('category-qtl_questionen') || $('article').hasClass('category-qtl_questionfr') ) {


       originalCurrentVal = $.trim($('div#questionDiv textarea').val());
       console.log('originalCurrentVal ' + originalCurrentVal);
       //originally hide the continue and mark complete
       console.log('originally hide mark complete and continue');
       //show the mark complete button
       $('form#sfwd-mark-complete').hide();
       $('form#sfwd-mark-complete button').hide();
       $('form#sfwd-mark-complete button span.theText').hide();
       //hide the continue
       $('div.continue').hide();

       if ( !$('div#questionDiv textarea').val() ) {
        console.log('textarea is NOT empty');
        //if the textarea is NOT empty

          //check if mark complete exist
          if ( $('form#sfwd-mark-complete').length > 0 ) {

            console.log('mark complete exist)')

            //show the mark complete button
            $('form#sfwd-mark-complete').show();
            $('form#sfwd-mark-complete button').show();
            $('form#sfwd-mark-complete button span.theText').show();
            //hide the continue
            $('div.continue').hide();


          } else {

            console.log('mark complete DOES NOT exist)')

            //show the continue button
            $('div.continue').show();

            //hide the mark complete button
            $('form#sfwd-mark-complete').hide();
            $('form#sfwd-mark-complete button').hide();
            $('form#sfwd-mark-complete button span.theText').hide();

          }


       } else { //if textarea is empty

             console.log('textarea IS empty');

            //hide the continue button
            $('div.continue').hide();

            //hide the mark complete button
            $('form#sfwd-mark-complete').hide();
            $('form#sfwd-mark-complete button').hide();
            $('form#sfwd-mark-complete button span.theText').hide();


       }


}



//$('div#questionDiv').append('<span class="checkanswerText"></span>');
$("div#questionDiv textarea").on('change keyup', function(e) {


  //console.log("changed");
  currentVal = $.trim($(this).val());
  //console.log("my current value" + currentVal);

  //oldVal = currentVal;

 if ($('article').hasClass('category-objectivesen') || $('article').hasClass('category-objectivesfr') ) {

    if (currentVal === ''   )  {

      console.log('has class objectiveen or objectivesfr and textarea has nothing in it');

      //then disable the save answer
      $('input.checkAnswer').attr('disabled', 'disabled');
      //change opacity of save answer to .1
      $('input.checkAnswer').css('opacity', '.1');


        if ( $('form#sfwd-mark-complete').length > 0 ) {

            $('form#sfwd-mark-complete').show();
            $('div.continue').hide();

        } else  {

          $('form#sfwd-mark-complete').hide();
          $('div.continue').show();
        }


    } else {//else if there is a value

      console.log('there is a value in textarea');

      if ( currentVal == oldVal ) { //if the value is the same as before

        console.log('currentvalue is equal to previous entered value');



        //do nothing return


      } else { //else if the value is not the same as before



        console.log('currentvalue is NOT equal to previous entered value. it has changed');

          $('input.checkAnswer').show();
          //then remove disabled attr of save answer
          $('input.checkAnswer').removeAttr('disabled', 'disabled');

          //change opacity of save answer ot 1
          $('input.checkAnswer').css('opacity', '1');
          //display the feedback
          $('input.feedbackQuestion').css('display', 'block');


      }
    }

}  else {





 //hide the continue button
    $('div.continue').hide();
    //hide the mark complete button
    $('form#sfwd-mark-complete').hide();
    $('form#sfwd-mark-complete button').hide();
    $('form#sfwd-mark-complete button span.theText').hide();

//&& !$('article').hasClass('category-objectivesen') ||  !$('article').hasClass('category-objectivesfr')
  //if I have no value in textarea
  if (currentVal === ''   ) {

    console.log('no value in textarea');

    //then disable the save answer
    $('input.checkAnswer').attr('disabled', 'disabled');
    //change opacity of save answer to .1
    $('input.checkAnswer').css('opacity', '.1');

    $('div.continue').hide();




   } else { //else if there is a value

      console.log('there is a value in textarea');



      if ( currentVal == oldVal ) { //if the value is the same as before

        console.log('currentvalue is equal to previous entered value');



        //do nothing return


      } else { //else if the value is not the same as before



        console.log('currentvalue is NOT equal to previous entered value. it has changed');

          $('input.checkAnswer').show();
          //then remove disabled attr of save answer
          $('input.checkAnswer').removeAttr('disabled', 'disabled');

          //change opacity of save answer ot 1
          $('input.checkAnswer').css('opacity', '1');
          //display the feedback
          $('input.feedbackQuestion').css('display', 'block');


          // if ( $('form#sfwd-mark-complete').length > 0 ) {

          //   console.log('mark complete exist)')

          //   //show the mark complete button
          //   $('form#sfwd-mark-complete').show();
          //   $('form#sfwd-mark-complete button').show();
          //   $('form#sfwd-mark-complete span.theText').show();
          //   //hide the continue
          //   $('div.continue').hide();


          // } else {

          //   console.log('mark complete DOES NOT exist here');

          //   //show the continue button
          //   $('div.continue').show();

          //   //hide the mark complete button
          //   $('form#sfwd-mark-complete').hide();
          //   $('form#sfwd-mark-complete button').hide();
          //   $('form#sfwd-mark-complete span.theText').hide();

          // }



      }

   }
}

});



//on save answer clicked
    $("div#questionDiv input.checkAnswer").on('click', function(e) {

         if ( $('form#sfwd-mark-complete').length > 0 ) {

            console.log('mark complete exist)')

            //show the mark complete button
            $('form#sfwd-mark-complete').show();
            $('form#sfwd-mark-complete button').show();
            $('form#sfwd-mark-complete span.theText').show();
            //hide the continue
            $('div.continue').hide();


          } else {

            console.log('mark complete DOES NOT exist now here')

            //show the continue button
            $('div.continue').show();

            //hide the mark complete button
            $('form#sfwd-mark-complete').hide();
            $('form#sfwd-mark-complete button').hide();
            $('form#sfwd-mark-complete span.theText').hide();

          }



    });



$("div#questionDiv input[type=radio]").on('click', function(e) {

  console.log('radio clicked');

  //console.log("changed");
  currentVal = $.trim($(this).val());
  console.log('currentVal ' + currentVal);
  //console.log("my current value" + currentVal);

  if (!currentVal) {

    oldVal = currentVal;
    console.log('current val has not changed ');
    console.log('oldVal ' + oldVal);
    // textarea is empty or contains only white-space
    //$('input.checkAnswer').css('display', 'none');
    $('input.checkAnswer').attr('disabled', 'disabled');
    $('input.checkAnswer').css('opacity', '.5');
    $('form#sfwd-mark-complete').hide();
    $('div.continue').hide();
    $('div.continue').hide();


    //console.log("Please enter something before checking your answer");
    return; //check to prevent multiple simultaneous triggers

  } else {

    oldVal = currentVal;
    console.log('current val changed ');
    console.log('oldVal ' + oldVal);
    //action to be performed on textarea changed
    //$('input.checkAnswer').css('display', 'block');
    $('input.checkAnswer').removeAttr('disabled', 'disabled');
    $('input.checkAnswer').css('opacity', '1');
    $('input.checkAnswer').css('display', 'block');
    $('form#sfwd-mark-complete').hide();
    $('div.continue').hide();
    $('div.continue').hide();

    //if (theLanguage === "fr-FR") {
      //$('span.checkanswerText').html('et sauvergarder votre réponse');
    //} else {
      //$('span.checkanswerText').html('and save your answer');
    //}
  }

});



$("div#questionDiv input[type=checkbox]").on('click', function(e) {



  //console.log("changed");
  currentVal = $.trim($(this).val());
  //console.log("my current value" + currentVal);

  if (!currentVal) {

    oldVal = currentVal;
    // textarea is empty or contains only white-space
    //$('input.checkAnswer').css('display', 'none');
    $('input.checkAnswer').attr('disabled', 'disabled');
    $('input.checkAnswer').css('opacity', '.5');
    $('form#sfwd-mark-complete').hide();
    $('div.continue').hide();
    $('div.continue').hide();
    //console.log("Please enter something before checking your answer");
    return; //check to prevent multiple simultaneous triggers

  } else {

    oldVal = currentVal;
    //action to be performed on textarea changed
    //$('input.checkAnswer').css('display', 'block');
    $('input.checkAnswer').removeAttr('disabled', 'disabled');
    $('input.checkAnswer').css('opacity', '1');
    $('input.checkAnswer').css('display', 'block');
    $('form#sfwd-mark-complete').hide();
    $('div.continue').show();
    $('div.continue').show();

    //if (theLanguage === "fr-FR") {
      //$('span.checkanswerText').html('et sauvergarder votre réponse');
    //} else {
      //$('span.checkanswerText').html('and save your answer');
    //}
  }

});









$('article.category-certificat div.continue').hide();

$('article.category-certificat div.wpProQuiz_question_text p').hide();

if (theLanguage === "fr-FR") {

  console.log('in the french');
  $('input[name=checkSingle]').val('Continuer');

} else {

  console.log('in the english');
  $('input[name=checkSingle]').val('Continue');

}


// $('body.be-in-the-know-form div.continue').hide();
// $('form#gform_8 div.gform_footer').prepend('<button name="gform_footer"  id="gform_submit_button_8"  class="save" tabindex="6" onclick="if(window[&quot;gf_submitting_8&quot;]){return false;}  window[&quot;gf_submitting_8&quot;]=true; "></button>');
// $('form#gform_8 div.gform_footer input[type=submit]').hide();
// //<input type="submit" id="gform_submit_button_8" class="button gform_button" value="Submit" tabindex="6" onclick="if(window[&quot;gf_submitting_8&quot;]){return false;}  window[&quot;gf_submitting_8&quot;]=true; ">
// $('form#gform_8 div.gform_footer button.save').text('Continue ');
// $('form#gform_8 div.gform_footer button.save').addClass('button col-xs-12 col-sm-12 col-md-3 col-lg-3 col-sm-6 col-md-8 col-lg-8 next');
// $('form#gform_8 div.gform_footer button.save').append('<span class="fa fa-chevron-right fa-fw"> </span>')
// $('form#gform_8 div.gform_footer').append('<span class="theText col-xs-12 col-sm-6 col-md-8 col-lg-8"></span>');
// $('form#gform_8 span.theText').html(' [ ' + nextLessonText + ' ] ');
// $('body.formulaire-soyez-au-courant div.continue').hide();
// $('form#gform_9 div.gform_footer').prepend('<button name="gform_footer"  id="gform_submit_button_9"  class="save" tabindex="6" onclick="if(window[&quot;gf_submitting_9&quot;]){return false;}  window[&quot;gf_submitting_9&quot;]=true; "></button>');
// $('form#gform_9 div.gform_footer input[type=submit]').hide();
// $('form#gform_9 div.gform_footer button.save').text('Continuer ');
// $('form#gform_9 div.gform_footer button.save').addClass('button col-xs-12 col-sm-12 col-md-3 col-lg-3 col-sm-6 col-md-8 col-lg-8 next');
// $('form#gform_9 div.gform_footer button.save').append('<span class="fa fa-chevron-right fa-fw"> </span>')
// $('form#gform_9 div.gform_footer').append('<span class="theText col-xs-12 col-sm-6 col-md-8 col-lg-8"></span>');
// $('form#gform_9 span.theText').html(' [ ' + nextLessonText + ' ] ');



$('article.category-certificat div.continue').hide();

$('article.category-certificat div.wpProQuiz_question_text p').hide();

$('article.category-certificate form#sfwd-mark-complete button').hide();
$('article.category-certificate div.continue').hide();
$('article.category-certificate-fr div.continue').hide();
$('article.category-certificate-fr form#sfwd-mark-complete button').hide();

$('article.category-certificate div.wpProQuiz_question_text p').hide();


//grabs the last digit for the input submit gf id
if ($('article').hasClass('category-gf') || $('article').hasClass('category-gf-5') ) {

  gravity_form_id =  $("div.gform_footer input[type=submit]").attr('id').match(/(\d+)[^\d]*$/)[0];
  console.log('gravity_form_id ' + gravity_form_id);

}


//question_id =  $(this).parent("#questionDiv").find("input[type=radio]:checked").first().attr('name').match(/(\d+)[^\d]*$/)[0]`


//(\d+)[^\d]*$



$('body.be-in-the-know-form div.continue').hide();
$('body.formulaire-soyez-au-courant div.continue').hide();

if ($('article').hasClass('category-gf') || $('article').hasClass('category-gf-5') ) {

  //gravity from change input for button
  $('form div.gform_footer').prepend('<button name="gform_footer" id="gform_submit_button_' + gravity_form_id +  '" class="save" tabindex="6" onclick="if(window[&quot;gf_submitting_' + gravity_form_id + '&quot;]){return false;}  window[&quot;gf_submitting_' + gravity_form_id + '&quot;]=true; "></button>');
  $('form div.gform_footer input[type=submit]').hide();
  $('form div.gform_footer button').text('Sauvegarder ');
  $('form div.gform_footer button').addClass('button saveGf');
  //$('form#gform_9 div.gform_footer button.save').addClass('button col-xs-12 col-sm-12 col-md-3 col-lg-3 col-sm-6 col-md-8 col-lg-8 next');
  $('form div.gform_footer button').append('<span class="icon-save"> </span>')
  $('form div.gform_footer button.save').removeClass('save');

}


// $('form#gform_8 div.gform_footer').prepend('<button name="gform_footer"  type="submit" id="gform_submit_button_8"  class="save" tabindex="6" onclick="if(window[&quot;gf_submitting_8&quot;]){return false;}  window[&quot;gf_submitting_8&quot;]=true; "></button>');
// $('form#gform_8 div.gform_footer input[type=submit]').hide();
// //<input type="submit" id="gform_submit_button_8" class="button gform_button" value="Submit" tabindex="6" onclick="if(window[&quot;gf_submitting_8&quot;]){return false;}  window[&quot;gf_submitting_8&quot;]=true; ">
// $('form#gform_8 div.gform_footer button').text('Save ');
// $('form#gform_8 div.gform_footer button').addClass('button saveGf');
// //$('form#gform_8 div.gform_footer button.save').addClass('button col-xs-12 col-sm-12 col-md-3 col-lg-3 col-sm-6 col-md-8 col-lg-8 next');
// $('form#gform_8 div.gform_footer button').append('<span class="icon-save"> </span>')
// $('form#gform_8 div.gform_footer button.save').removeClass('save');


//$('form#gform_8 div.gform_footer').append('<span class="theText col-xs-12 col-sm-6 col-md-8 col-lg-8"></span>');
//$('form#gform_8 span.theText').html(' [ ' + nextLessonText + ' ] ');





//$('form#gform_9').attr('action', '/lecons/generer-votre-certificat-bitk/');
// $('form#gform_9 div.gform_footer').prepend('<button name="gform_footer"   id="gform_submit_button_9"  class="save" tabindex="6" onclick="if(window[&quot;gf_submitting_9&quot;]){return false;}  window[&quot;gf_submitting_9&quot;]=true; "></button>');
// $('form#gform_9 div.gform_footer input[type=submit]').hide();
// $('form#gform_9 div.gform_footer button').text('Sauvegarder ');
// $('form#gform_9 div.gform_footer button').addClass('button saveGf');
// //$('form#gform_9 div.gform_footer button.save').addClass('button col-xs-12 col-sm-12 col-md-3 col-lg-3 col-sm-6 col-md-8 col-lg-8 next');
// $('form#gform_9 div.gform_footer button').append('<span class="icon-save"> </span>')
// $('form#gform_9 div.gform_footer button.save').removeClass('save');
//$('form#gform_9 div.gform_footer').append('<span class="theText col-xs-12 col-sm-6 col-md-8 col-lg-8"></span>');
//$('form#gform_9 span.theText').html(' [ ' + nextLessonText + ' ] ');






  //$('body.be-in-the-know-form div.continue').hide();
  //$("div#questionDiv input.gform_button").on('click', function(e) {
    //$('div#gforms_confirmation_message').hide();
    //setTimeout(function() {
      //window.location.reload();
      //$('body.be-in-the-know-form article').addclass('posted');
      //$('body.be-in-the-know-form article.posted div.continue').show();
    //}, 0);
  //});

  //if ( $('article').hasClass('category-quizq') && document.querySelectorAll(".save").length ) {
    //$("p#learndash_next_prev_link div.continue").hide();
    //$('form#sfwd-mark-complete ').hide();
  // }


if ($('article').hasClass('category-quizq') || $('article').hasClass('category-quizq-fr') ) {

  console.log('page cat quiz');

  if ($('div.learndash_nevigation_lesson_topics_list div.active').find('div').hasClass('lesson_completed') == false  ) {

    console.log('active lesson is not completed');
    $('article.category-quizq-fr div.continue').hide();
    $('article.category-quizq-fr form#sfwd-mark-complete ').hide();

  } else {

    console.log('active lesson is completed');
    $('article.category-quizq div.continue').show();
    $('article.category-quizq form#sfwd-mark-complete ').hide();

  }
}


//start of Gravity form code

//$('input#gf_submitting_8').hide();

  var course_id = $("body").eq(0).data("course-id");

    $(document).ready(function() {
      $('div.gform_wrapper form').sisyphus({
      //locationBased: true

      locationBased: true,
      customKeySuffix: 'course_' + course_id,

      onSave: function() {
        console.log('Saved');
      }

    }); // save the reflection answers through localStorage

});




// $('div.gform_wrapper form').sisyphus({

// locationBased: true

// }); // save the reflection answers through localStorage

if ($('article').hasClass('category-gf') || $('article').hasClass('category-gf-5') ) {

   //article.category-gf-5 div#gform_confirmation_message_' + gravity_form_id
   // if ( $("div#gform_confirmation_message").length > 0 ) {

   //   gform_confirmation_message_id = $("div#gform_confirmation_message").attr('id').match(/(\d+)[^\d]*$/)[0];
   //   console.log('gform_confirmation_message_id ' + gform_confirmation_message_id);

   // }

    console.log('lesson has category category-gf or category-gf-5');

        //if we are on a page with a gravity form
        //hide the mark complete button
        $('form#sfwd-mark-complete').hide();
        //hide the learndash continue button
        $('article.category-gf p#learndash_next_prev_link div.continue').show();
        $('article.category-gf-5 p#learndash_next_prev_link div.continue').show();
        //display the form
        $('div.gform_wrapper form').show();

        //hide the gravity form save information button
        $('div.gform_footer button[name=gform_footer]').hide();
        //hide the text with the next page info next to the continue button
        $('div.gform_footer span.theText').hide();
        $('div.gform_wrapper div.gform_footer span.theText').hide();


        //change the text on the gravity form save button
        if (theLanguage === "fr-FR") {

          console.log('in the french change input text Soumettre vos information');

            $('button.saveGf').val('Soumettre vos informations');
            $('button.saveGf').html('Soumettre vos informations');

        } else {

           console.log('in the english change input text Submit your information');

            $('button.saveGf').val('Submit your information');
            $('button.saveGf').html('Submit your information');

        }




        if ($('div.learndash_nevigation_lesson_topics_list div.active').find('div').hasClass('lesson_completed') === false ) {

          //if the lesson is not completed
          console.log('lesson NOT completed');




          //hide the gform footer button
          $('div.gform_footer button[name=gform_footer]').show();
          //onclick of save button
          $('button.saveGf').click(function(){

          console.log('save button clicked');


                // $('div.gform_wrapper form').sisyphus({

                //   //locationBased: true

                //   locationBased: true,
                //   customKeySuffix: 'course_' + course_id,

                //   onSave: function() {
                //     console.log('data Saved');
                //   },
                //   onRestore: function() {
                //        console.log('data restored');

                //   }

                // }); // save the reflection answers through localStorage


                //show mark complete
                $('form#sfwd-mark-complete').show();

                //hide save button
                $('button.saveGf').hide();

                  //change text of the thank you page
                   if (theLanguage === "fr-FR") {

                  //$('article.category-gf-5 div.learndash p').first().html('Merci. Votre information nous a été transmise avec succès.');

                  //$('article.category-gf-5 div#gform_confirmation_message').first().html('Merci. Votre information nous a été transmise avec succès.');

                  $('article.category-gf-5 div#gforms_confirmation_message').html('Merci. Votre information nous a été transmise avec succès.');




                 // $('div.gform_confirmation_wrapper div').html('Merci. Votre information nous a été transmise avec succès.');

                } else {

                  //$('article.category-gf div.learndash p:first-child').first().html('Thank you. Your information was saved successfully.');
                  $('article.category-gf div#gforms_confirmation_message').html('Thank you. Your information was saved successfully.');





                 // $('div.gform_confirmation_wrapper div').html('Thank you. Your information was saved successfully.');

                }



            });


             // $('div.gform_footer button[name=gform_footer]').hide();
            //$('div.gform_footer span.theText').first().hide();

             //hide learndash continue button
             $('article.category-gf p#learndash_next_prev_link div.continue').hide();
            $('article.category-gf-5 p#learndash_next_prev_link div.continue').hide();

             //$('div.gform_footer button[name=gform_footer]').last().show();
              //$('div.gform_footer span.theText').last().show();



       } else {

         console.log('lesson completed');


              //on lesson completed
              //hide gravity form save button
              $('button.saveGf').hide();

              //show learndash continue button
              $('article.category-gf p#learndash_next_prev_link div.continue').show();
            $('article.category-gf-5 p#learndash_next_prev_link div.continue').show();
             $('div.gform_wrapper form').show();



              $("select, input").focus(function() {


                $('button.saveGf').show();

                  if (theLanguage === "fr-FR") {

                     $('button.saveGf').val('Éditer et sauvegarder votre information');
                    $('button.saveGf').html('Éditer et sauvegarder votre information');

                   } else {

                    $('button.saveGf').val('Edit and save your information');
                    $('button.saveGf').html('Edit and save your information');

                  }

                $('article.category-gf p#learndash_next_prev_link div.continue').hide();
                $('article.category-gf-5 p#learndash_next_prev_link div.continue').hide();

              });






                $('button.saveGf').click(function(){


                      console.log('save button clicked');

                      $('button.saveGf').hide();

                       $('article.category-gf p#learndash_next_prev_link div.continue').show();
                       $('article.category-gf-5 p#learndash_next_prev_link div.continue').show();
                      $('div.gform_wrapper form').show();



                         if (theLanguage === "fr-FR") {

                  //$('article.category-gf-5 div.learndash p').first().html('Merci. Votre information nous a été transmise avec succès.');

                  $('article.category-gf-5 div#gforms_confirmation_message').html('Merci. Votre information nous a été transmise avec succès.');



                 // $('div.gform_confirmation_wrapper div').html('Merci. Votre information nous a été transmise avec succès.');

                } else {

                  //$('article.category-gf div.learndash p').first().html('Thank you. Your information was saved successfully.');

                  $('article.category-gf div#gforms_confirmation_message').html('Thank you. Your information was saved successfully.');


                      }

                 });


       }




}








//lesson has topics

var howManyTopicsInLesson = $('div.learndash_nevigation_lesson_topics_list').find('div.active').find('div.learndash_topic_widget_list').find('a').length;
var howManyTopicsCompletedInLesson = $('div.learndash_nevigation_lesson_topics_list').find('div.active').find('div.learndash_topic_widget_list').find('a.topic-completed').length;
var activeLessonComplete = $('div.learndash_nevigation_lesson_topics_list').find('div.active').find('div').hasClass('lesson_completed');


    if (  $('body').hasClass('single-sfwd-lessons') && $('article.sfwd-lessons') && !$('article').hasClass('category-qtl_questionen')  && !$('article').hasClass('category-qtl_questionfr') && !$('article').hasClass('category-quizq')  && !$('article').hasClass('category-quizq-fr') && !$('article').hasClass('category-gf') && !$('article').hasClass('category-gf-5')   ){

        if ( !$('div.learndash_nevigation_lesson_topics_list').find('div.active').find('div').hasClass('lesson_completed')) {

                      $('form#sfwd-mark-complete').show();
                      $('div.continue').hide();

        } else {
              console.log('lesson complete');
                      $('form#sfwd-mark-complete').hide();
                      $('div.continue').show();

        }

    }





//if lesson has topics
if ( $('div.active > div.list_lessons > div.learndash_topic_widget_list').children().length > 0  ) {

      console.log('has TOPICS');
        //check if all topics completed abd lesson completed
        if ( (howManyTopicsInLesson == howManyTopicsCompletedInLesson)  &&  activeLessonComplete  == true ) {

             console.log('number of topics equals number topics completed and lesson is complete');

             $('form#sfwd-mark-complete').hide();
             $('div.continue').show();

        } else if ( (howManyTopicsInLesson == howManyTopicsCompletedInLesson)  &&  activeLessonComplete == false ) {

            console.log('number of topics equal number topics completed and lesson is not complete');

            $('form#sfwd-mark-complete').show();
            $('div.continue').hide();

        } else if ( (howManyTopicsInLesson != howManyTopicsCompletedInLesson)  &&  activeLessonComplete == false )  {

           console.log('number of topics does not equal number topics completed and lesson is not complete');

          $('div.continue').show();

        } else {

        }


}




if ( $('body').hasClass('single-sfwd-topic') && $('article.sfwd-topic')  ){


    if ( !$('div.learndash_nevigation_lesson_topics_list').find('div.active').find('div.learndash_topic_widget_list').find('a.topicActive').hasClass('topic-completed')) {
                  console.log('topic not completed ')
                  $('form#sfwd-mark-complete').show();
                  $('div.continue').hide();

    } else {

                  $('form#sfwd-mark-complete').hide();
                  $('div.continue').show();


    }

}







$('div.wpProQuiz_content').css('overflow', 'hidden');

//showing the continue/mark complete button only when the result page is visible for quizzes with advanced quiz and more than one answer.

//$('article').hasClass('category-advquiz') || $('article').hasClass('category-advquizFr') '
//$('div.learndash user_has_access').find('div').hasClass('wpProQuiz_content')

   //QuizPRO
  if ( $('div.wpProQuiz_content').length > 0 ) {

    if ($('form#sfwd-mark-complete').length > 0 ){

      if (lastLessonActive) {


        if (theLanguage === "fr-FR") {

          $('form#sfwd-mark-complete button').html('Terminer <span class="fa fa-chevron-right fa-fw"> </span>');


        } else {

          $('form#sfwd-mark-complete button').html('Finish <span class="fa fa-chevron-right fa-fw"> </span>');


        }



      } else {

        if (theLanguage === "fr-FR") {

          $('form#sfwd-mark-complete button').html('Continuer <span class="fa fa-chevron-right fa-fw"> </span>');


        } else {

          $('form#sfwd-mark-complete button').html('Continue <span class="fa fa-chevron-right fa-fw"> </span>');


        }


      }


    }

  }


if  ( 0 < $('.wpProQuiz_listItem').size() ) {

  console.log('There is a wp-proQuiz on the page');


  $('form#sfwd-mark-complete').hide();
  $('div.continue').hide();





  //if ( $( "div.wpProQuiz_results").attr('style') === '' ) {
  //if ( $('div.wpProQuiz_results').is(':visible')  ) {

  $('li.wpProQuiz_listItem').last().find('input[name=next]').bind('click', function() {

    console.log('finish button clicked');

        if ( !$('div.learndash_nevigation_lesson_topics_list').find('div.active').find('div').hasClass('lesson_completed')) {

          console.log('the lesson is  NOT completed');
          $('form#sfwd-mark-complete').show();
          $('form#sfwd-mark-complete span.theText').show();
          $('div.continue').hide();

        } else {

          console.log('the lesson is completed');
          $('form#sfwd-mark-complete').hide();
          $('div.continue').show();

        }


    //} else {



    //}


  });



}











//end of gravity form code




var URLPath = window.location.protocol + window.location.host + "/" + window.location.pathname;
var URLPath2 = window.location.pathname;

console.log('URLPath2 ' + URLPath2);

// if ( $('body').hasClass('single-sfwd-topic') && $('div#questionDiv').length > 0 ) {

//   console.log('On a topic page and we have a QTL question');

//   if ($("div#questionDiv textarea") == '') {

//     console.log('textarea empty');

//      $('form#sfwd-mark-complete').hide();

//   } else {

//      $('form#sfwd-mark-complete').show();

//   }




// if ( $('body.single-sfwd-topic').length > 0 && $('div#questionDiv').length > 0   )   {

//   console.log('on a topic and there is a qtl question');
//   $('form#sfwd-mark-complete').hide();


// }



        $("div#questionDiv input.checkAnswer").on('click', function(e) {

          //if ( document.querySelectorAll(".save").length ) {
            //$('form#sfwd-mark-complete ').show();
          // } else {
            //$('article.category-quizq div.continue').show();
            //$('form#sfwd-mark-complete ').hide();
          // }

//CHANGED //$.cookie('QuestionSaved', 'Clicked', {path : URLPath2});

          //$.cookies.set('QuestionSaved', 'Clicked', {path : URLPath2});
          // if ( $.cookie('QuestionSaved') && $('article').hasClass('category-quizq') ) {

          //console.log('cookie saved, check answer clicked');

            //$('div.learndash_nevigation_lesson_topics_list').find('div.active').find('div.learndash_topic_widget_list').find('a').length > 0
            if ( $('form#sfwd-mark-complete').length > 0 ) {

                console.log('mark complete exists');
                $('div.continue').hide();
                $('form#sfwd-mark-complete').show();



            } else {

              console.log('mark complete does not exist');
              $('div.continue').show();
              $('form#sfwd-mark-complete').hide();

            }


  // if ( $('div.learndash_nevigation_lesson_topics_list div.active').find('div.learndash_topic_widget_listdiv .learndash_topic_widget_list').find('a.topicActive').hasClass('topic-completed') == false  ) {


  //   console.log('active topic is not completed');
  //   $('article.category-quizq div.continue').hide();

  //   $('form#sfwd-mark-complete').show();

  // } else {


  //   console.log('active topic is completed');
  //   $('article.category-quizq div.continue').show();

  //   $('form#sfwd-mark-complete').hide();

  // }

  //    } else {
  //      console.log('cookie not saved, check answer not clicked')
  //     $('article.category-quizq div.continue').hide();
  //     $('form#sfwd-mark-complete').hide();
  // }

});



//hiding the code to hide learndash quiz for certificate generation
if ( $('article').hasClass('category-certificat') ) {
  //$('div#learndash_quizzes').hide();
  //$('form#sfwd-mark-complete').find('span.theText').hide();
}

if ( $.cookie('QuestionSaved') ) {

  //$('article.category-quizq div.continue').show();

} else {

  //$('article.category-quizq div.continue').hide();

}


              //start of fix for the double continue buttons on pages with questions.
              // if ( $.cookie('QuestionSaved') && $('article').hasClass('category-quizq') ) {
              // console.log('cookie saved, check answer clicked')
              // if ($('div.learndash_nevigation_lesson_topics_list div.active').find('div').hasClass('lesson_completed') == false  ) {
              //           console.log('active lesson is not completed');
              //           $('article.category-quizq div.continue').hide();
              //           $('form#sfwd-mark-complete').show();
              //       } else {
              //           console.log('active lesson is completed');
              //           $('article.category-quizq div.continue').show();
              //           $('form#sfwd-mark-complete').hide();
              //       }
              //    } else {
              //      console.log('cookie not saved, check answer not clicked')
              //     $('article.category-quizq div.continue').hide();
              //     $('form#sfwd-mark-complete').hide();
              // }
              // if (!$('article').hasClass('category-quizq') ) {
              //   if ($('div.learndash_nevigation_lesson_topics_list div.active').find('div').hasClass('lesson_completed') == false  ) {
              //           console.log('active lesson is not completed');
              //           $('article.category-quizq div.continue').hide();
              //           $('form#sfwd-mark-complete ').show();
              //       } else {
              //           console.log('active lesson is completed');
              //           $('article.category-quizq div.continue').show();
              //           $('form#sfwd-mark-complete ').hide();
              //       }
              // }
              //end of fix for the double continue buttons on pages with questions.



if ($('div.inactive').prev().hasClass('active')) {

  $('div.inactive').css('opacity', '.5');

};



// $("input.checkAnswer").on("click", function() {

//   $('input.feedbackQuestion').css('display', 'block');
//   $('input.checkAnswer').css('display', 'none');

// });



$('section.widget_sfwd-lessons-widget').affix({

  offset: {

    top: 100
    , bottom: function () {
    return (this.bottom = $('#credits').outerHeight(true))
    }
  }
})




$("a#instructions").click(function(){

  $('#myModal').modal('show');

});


              // $("section.widget_sfwd-lessons-widget ul li a").click(function(event){
              //     event.preventDefault();
              //     linkLocation = this.href;
              //     $("body").fadeOut(1000, redirectPage);
              //   });



$("section.widget_sfwd-lessons-widget ul > li > h4").click(function(e) {

  getUrl = $(this).children("a").attr("href");
  //console.log(getUrl);
  window.location.href = getUrl;
  $("body").fadeOut(1000);

})
  .find("a") // now we need to make sure that links don't get clicked twice.
  .click(function(e) {
  e.stopPropagation();

});




$("article.sfwd-lessons div.btnBackToCourses a").click(function(event){

  event.preventDefault();
  linkLocation = this.href;
  $("body").fadeOut(1000, redirectPage);

});


$("article.sfwd-lessons div.btnBackToCourses a").click(function(event){

  event.preventDefault();
  linkLocation = this.href;
  $("body").fadeOut(1000, redirectPage);

});

// $(document).ready(function() {
// });
// $(window).load(function () {
// $('#modal').modal('show');
// });


    $('li.separator').addClass('fa fa-angle-right');
    $('ul#breadcrumbs li').first().find('a').addClass('fa fa-home');
    $('ul#breadcrumbs li').first().find('a').html('');

    $('body.single-sfwd-courses').find('aside.sidebar').hide();
    $('body.page-template-single-sfwd-courses-cpd-php').find('aside.sidebar').hide();

    $('body.single-sfwd-courses').find('main.main').removeClass('col-sm-8');
    $('body.single-sfwd-courses').find('main.main').addClass('col-xs-12 col-sm-12 col-md-12 col-lg-12');
    $('body.single-sfwd-lessons div.entry-content').find('img').addClass('img-responsive');
    $('body.single-sfwd-courses section#credits').find('img').addClass('img-responsive');

              // if($(window).width() < 1200) {
              //   $('body.single-sfwd-lessons').find('main.main').removeClass('col-sm-8');
              //   $('body.single-sfwd-lessons').find('main.main').addClass('col-xs-12 col-sm-12 col-md-12 col-lg-12');
              //   $('body.single-sfwd-lessons').find('aside.sidebar').removeClass('col-sm-4');
              //   $('body.single-sfwd-lessons').find('aside.sidebar').addClass('col-xs-12 col-sm-12 col-md-12 col-lg-12');
              // } else {
              // $('body.single-sfwd-lessons').find('main.main').removeClass('col-sm-8');
              // $('body.single-sfwd-lessons').find('main.main').addClass('col-xs-12 col-sm-12 col-md-8 col-lg-8');
              // $('body.single-sfwd-lessons').find('aside.sidebar').removeClass('col-sm-4');
              // $('body.single-sfwd-lessons').find('aside.sidebar').addClass('col-xs-12 col-sm-12 col-md-4 col-lg-4');
              // }
              // if($(window).width() <= 768) {
              //  console.log('smaller than 768');
              //  $( "h2.course-group-choices-heading" ).show();
              //  //$( "a.course-group-choice-cpd > div.myImage" ).hide();
              // $( "a.course-group-choice-cpd > p.mainText" ).hide();
              // $( "a.course-group-choice-cpd > p.btnList" ).hide();
              // $( "a.course-group-choice-cpd > p.mainText" ).attr('role', 'region');
              // $( "a.course-group-choice-cpd > p.mainText" ).attr('aria-label', 'Description');
              // $( "a.course-group-choice-cpd > p.mainText" ).attr('aria-hidden', 'true');
              // $( "a.course-group-choice-cpd > p.btnList" ).attr('role', 'button');
              // $( "a.course-group-choice-cpd > p.btnList" ).attr('aria-label', 'View the list of modules');
              // $( "a.course-group-choice-cpd > p.btnList" ).attr('aria-hidden', 'true');
              //  $( "a.course-group-choice-residency > p.mainText" ).hide();
              //  $( "a.course-group-choice-residency > p.btnList2" ).hide();
              //  $( "a.course-group-choice-residency > p.mainText" ).attr('role', 'region');
              // $( "a.course-group-choice-residency > p.mainText" ).attr('aria-label', 'Description');
              // $( "a.course-group-choice-residency > p.mainText" ).attr('aria-hidden', 'true');
              // $( "a.course-group-choice-residency > p.btnList" ).attr('role', 'button');
              // $( "a.course-group-choice-residency > p.btnList" ).attr('aria-label', 'View the list of modules');
              // $( "a.course-group-choice-residency > p.btnList" ).attr('aria-hidden', 'true');
              //  $('a.course-group-choice-cpd').css('background-color', '#240757');
              //  $('a.course-group-choice-cpd  h3.course-group-choice-button').css('color', '#fff');
              //  $('a.course-group-choice-residency').css('background-color', '#471a88');
              //  $('a.course-group-choice-residency  h3.course-group-choice-button').css('color', '#fff');
              //     $( "a.course-group-choice-cpd" ).mouseover(function() {
              //          //$( "a.course-group-choice-cpd > div.myImage" ).show();
              //          $( "a.course-group-choice-cpd > p.mainText" ).show();
              //          $( "a.course-group-choice-cpd > p.btnList" ).show();
              //          //$('a.course-group-choice-cpd > div.myImage').show();
              //        $( "a.course-group-choice-cpd > p.mainText" ).attr('aria-hidden', 'false');
              //        $( "a.course-group-choice-cpd > p.btnList" ).attr('aria-hidden', 'false');
              //        //$( "a.course-group-choice-cpd > div.myImage" ).attr('aria-hidden', 'false');
              //          $('a.course-group-choice-cpd').css('background-color', '#fff');
              //          $('a.course-group-choice-cpd > h3.course-group-choice-button').css('color', '#240757');
              //      }).mouseout(function() {
              //           // $( "a.course-group-choice-cpd > div.myImage" ).hide();
              //             $( "a.course-group-choice-cpd > p.mainText" ).hide();
              //             $( "a.course-group-choice-cpd > p.btnList" ).hide();
              //             //$('a.course-group-choice-cpd > div.myImage').hide();
              //             $( "a.course-group-choice-cpd > p.mainText" ).attr('aria-hidden', 'true');
              //             $( "a.course-group-choice-cpd > p.btnList" ).attr('aria-hidden', 'true');
              //            // $( "a.course-group-choice-cpd > div.myImage" ).attr('aria-hidden', 'true');
              //              $('a.course-group-choice-cpd').css('background-color', '#240757');
              //             $('a.course-group-choice-cpd  h3.course-group-choice-button').css('color', '#fff');
              //     });
              //    $( "a.course-group-choice-residency" ).mouseover(function() {
              // //$( "a.course-group-choice-residency > div.myImage" ).show();
              //  $( "a.course-group-choice-residency > p.mainText" ).show();
              //  $( "a.course-group-choice-residency > p.btnList2" ).show();
              //  //$('a.course-group-choice-residency > div.myImage').show();
              //  $( "a.course-group-choice-residency > p.mainText" ).attr('aria-hidden', 'false');
              //  $( "a.course-group-choice-residency > p.btnList" ).attr('aria-hidden', 'false');
              //  //$( "a.course-group-choice-residency > div.myImage" ).attr('aria-hidden', 'false');
              //   $('a.course-group-choice-residency').css('background-color', '#fff');
              //   $('a.course-group-choice-residency  h3.course-group-choice-button').css('color', '#471a88');
              //      }).mouseout(function() {
              //            //$( "a.course-group-choice-residency > div.myImage" ).hide();
              //  $( "a.course-group-choice-residency > p.mainText" ).hide();
              //   $( "a.course-group-choice-residency > p.btnList2" ).hide();
              //  //$('a.course-group-choice-residency > div.myImage').hide();
              //  $( "a.course-group-choice-residency > p.mainText" ).attr('aria-hidden', 'true');
              //  $( "a.course-group-choice-residency > p.btnList" ).attr('aria-hidden', 'true');
              //  //$( "a.course-group-choice-residency >  div.myImage" ).attr('aria-hidden', 'false');
              //  $('a.course-group-choice-residency').css('background-color', '#471a88');
              //  $('a.course-group-choice-residency  h3.course-group-choice-button').css('color', '#fff');
              //     });
              // } else  {
              // $('div.myImage').show();
              // $( "a.course-group-choice-cpd > div.myImage" ).show();
              // $( "a.course-group-choice-cpd > p.mainText" ).show();
              // $( "a.course-group-choice-residency > div.myImage" ).show();
              // $( "a.course-group-choice-residency > p.mainText" ).show();
              // //$( "h2.course-group-choices-heading" ).hide();
              // }


    $('article.category-certificate div.continue').hide();



    if (theLanguage === "fr-FR") {

      console.log('in the french');
      $('input[name=checkSingle]').val('Continuer');

    } else {

      console.log('in the english');
      $('input[name=checkSingle]').val('Continue');

    }



    var block = localStorage.getItem('display');
    var elementId = localStorage.getItem('TheId');
    var theState = localStorage.getItem('state');
    //var theStateParsed = JSON.parse(theState);
    //console.log("theState " + theState);
    //console.log("theStateParsed " + theStateParsed);
    //var elementId = localStorage.getItem('TheId');

    var pageUrl =  window.location.pathname; //unique id of the page url
    var items = [];

    //code to retain which div was visible
    $('div#questionDiv span.correct').closest('div').addClass('closed');
    $('div#questionDiv span.incorrect').closest('div').addClass('closed');




    $("input.checkAnswer").click(function() {

        $('input.checkAnswer').css('display', 'none');

        if ( $("div#questionDiv span.correct").closest('div').css("display")=="block" ) {

          $('div#questionDiv span.correct').closest('div').removeClass('closed');
          $('div#questionDiv span.correct').closest('div').addClass('opened');

        } else {

          $('div#questionDiv span.correct').closest('div').removeClass('opened');
          $('div#questionDiv span.correct').closest('div').addClass('closed');

        }

        if ( $("div#questionDiv span.incorrect").closest('div').css("display")=="block" ) {

          $('div#questionDiv span.incorrect').closest('div').removeClass('closed');
          $('div#questionDiv span.incorrect').closest('div').addClass('opened');

        } else {

          $('div#questionDiv span.incorrect').closest('div').removeClass('opened');
          $('div#questionDiv span.incorrect').closest('div').addClass('closed');
        }

});//hide checkanswer button when clicked


$('span.correct').hide();
$('span.incorrect').hide();

// var proQuizItem = [];

// $('div.wpProQuiz_response').children('div').addClass('closed');

// $("input[name=check]").bind('click', function() {

//    console.log('input check is clicked')

//    $('div.wpProQuiz_response').children('div').each(function () {

//       console.log($(this));
//       //$(this).is(':visible')
//      if ( $(this).css('display') ==='none' )   {

//         console.log('proquiz response div  is  NOT visible');

//         $(this).removeClass('closed');
//         $(this).addClass('closed');

//      } else {


//        console.log('proquiz response div is visible');

//         $(this).removeClass('closed');
//         $(this).addClass('opened');

//         proQuizItem.push($('.opened').attr('class'));
//         localStorage.setItem(pageUrl, JSON.stringify(proQuizItem));
//         proQuizItem = JSON.parse(localStorage.getItem(pageUrl));
//         console.log('item key 1 ' + proQuizItem.length); //4
//         console.log('items 2 ' + proQuizItem); //4




//      }


//    })

// });





    // if (block == 'true') {

    //   $('div#questionDiv div.closed').each(function () {




    //     if ( $(this).attr('id') == elementId ) {

    //       $(this).removeClass('closed');
    //       $(this).addClass('opened');

    //     }

    //   });


    //   $('.opened').show();
    //   $('.closed').hide();

    // }
    //end of gravity form code to retain div that is visible







// var theStateNew = localStorage.getItem('theState');
// console.log('theStateNew ' + theStateNew);

// var getIdOfDivWithClassOpened = $("div#questionDiv span").closest('div.opened').attr('id');
// console.log('getIdOfDivWithClassOpened ' + getIdOfDivWithClassOpened);

// $.each(theStateNew, function () {

//   if (getIdOfDivWithClassOpened) {

//     localStorage.removeItem(getIdOfDivWithClassOpened);


//   } else {

//     localStorage.addItem(getIdOfDivWithClassOpened);

//   }

// });



  // if ($("div#questionDiv textarea").length > 0) {

  //     if ( $('form#sfwd-mark-complete').length > 0  ) {

  //       $('form#sfwd-mark-complete').hide();
  //       $('div.continue').hide();

  //     } else {

  //       $('div.continue').show();

  //     }


  //     $("div#questionDiv textarea, div#questionDiv input[type=radio]").on('change keyup', function(e) {

  //       if ( "" != $("div#questionDiv textarea").eq(0).val().trim().toString() ) {

  //           $('input.checkAnswer').removeAttr('disabled');





  //       } else {

  //         $('input.checkAnswer').attr('disabled', 'disabled');
  //         $('div.continue').show();
  //         $('form#sfwd-mark-complete').hide();



  //       }

  //     });

  // }





  $("input.checkAnswer").on("click", function() {

    $('input.checkAnswer').css('display', 'none');

    if ( $('form#sfwd-mark-complete').length > 0  ) {

        $('form#sfwd-mark-complete').show();
        $('div.continue').hide();

    }


  });//hide checkanswer button when clicked


if ($('article').hasClass('category-objectivesen') || $('article').hasClass('category-objectivesfr') ) {

  if ( $.trim($("div#questionDiv textarea").val() ) === "" ) {

    console.log('has class objectiveen or objectivesfr and textarea has nothing in it');

      if ( $('form#sfwd-mark-complete').length > 0 ) {

          $('form#sfwd-mark-complete').show();
          $('div.continue').hide();

      } else  {

        $('form#sfwd-mark-complete').hide();
        $('div.continue').show();
      }


  }

}


//article hasClass category-preventprogressionen or category-preventprogressionfr
if ($('article').hasClass('category-preventprogressionen') || $('article').hasClass('category-preventprogressionfr') ) {

  console.log('has class of preventprogressionen or preventprogressionfr');

  $('form#sfwd-mark-complete').hide();
  $('div.continue').hide();


}//check if the lesson has a class of category-preventprogressionen or category-preventprogressionfr






// //if page is topic and mark complete visible NEW CODE QTL
// if ( $('article').hasClass('sfwd-topic')  && $('form#sfwd-mark-complete button').length > 0 ){

//   console.log('topic and marck complete exist');
//  // $('form#sfwd-mark-complete button').show(); //show mark complete
//   //$('form#sfwd-mark-complete button span.theText').show();
//   //$('div.continue').hide(); //hide continue


//   //check if there is a qtl question
//   if ($("div#questionDiv").length > 0) {

//      console.log('there is a qtl question on the page');

//     setTimeout(function() {
//       // Do something after 2 seconds

//       //check if qtl  input has value!$('div#questionDiv div').hasClass('opened')
//       console.log('qtl_has_feedback ' + qtl_has_feedback);

//       if ( $('div#questionDiv div#reflectionFeedbackDiv').hasClass('opened') || $('div#questionDiv div').hasClass('opened') ) {
//       //if (!$('div#questionDiv div').hasClass('opened')) {
//           console.log('qtl question input has value');
//         //hide mark complete
//         $('form#sfwd-mark-complete').show();
//         $('form#sfwd-mark-complete button').show(); //show mark complete
//         $('form#sfwd-mark-complete span.theText').show();
//         $('div.continue').hide();//show continue


//       } else { //if input no value

//          console.log('qtl question input has no value');
//         //show mark complete
//         $('form#sfwd-mark-complete').hide();
//         $('form#sfwd-mark-complete button').hide(); //show mark complete
//         $('form#sfwd-mark-complete span.theText').hide();

//       }

//     }, 30);

//   }//end


// //else if topic but no mark complete
// } else  {
//    console.log('topic and mark complete DOES NOT exist');
//   $('div.continue').show();//show continue
//   //$('form#sfwd-mark-complete button').hide(); //show mark complete
//   //$('form#sfwd-mark-complete span.theText').hide();

// }//end






  if ( $("div#questionDiv textarea").length > 0 ) {

      //if ( $('body').hasClass('single-sfwd-quiz') &&  $('article').hasClass('category-certificate') )  {
      //$('p#learndash_next_prev_link').show();
      // }

      if ($('div.learndash_nevigation_lesson_topics_list > div').last().find('div').hasClass('lesson_completed') && $('form#sfwd-mark-complete').css('display') == 'block' ) {

          console.log('last lesson');

          if (theLanguage === "fr-FR") {

            $('<p>Ne pas oubliez pas de cliquer sur le bouton continuer afin de compléter le module.</p>').prependTo('article.category-certificat div.entry-content');
            $('article.category-certificat form#sfwd-mark-complete button.save').html('Terminer le module <span class="fa fa-chevron-right fa-fw"> </span>');
            $('article.category-certificat form#sfwd-mark-complete span.theText').text('[ Description du cours ]');

          } else {

            $('<p>Do not forget to click the continue button in order to fully complete the module.</p>').prependTo('article.category-certificat div.entry-content');
            $('article.category-certificat form#sfwd-mark-complete button.save').html('Finish the module <span class="fa fa-chevron-right fa-fw"> </span>');
            $('article.category-certificat form#sfwd-mark-complete span.theText').text('[ Course description ]');

          }

          var lastLessonUrl = $('div.learndash_nevigation_lesson_topics_list > div').last().find('a').attr('href');

          // $('<p id="learndash_next_prev_link"><div class="myMain return"></div>').appendTo('body.single-sfwd-quiz article.category-certificate div.learndash');
          // $('<p><a rel="prev" class="button col-xs-12 col-sm-12 col-md-3 col-lg-3 col-sm-6 col-md-8 col-lg-8 prev" href=""><span class="fa fa-chevron-left fa-fw"> </span> Go back  </a><div class="text col-xs-12 col-sm-6 col-md-8 col-lg-8"><p>  </p></div></p></p>').appendTo('div.return');
          // $('div.myMain a.button').attr('lastLessonUrl');
          // $('p#learndash_next_prev_link').show();
          //$('article.category-certificate div.quiz_continue_link').show();
          //$('article.category-certificate div.quiz_continue_link a').html('<span class="fa fa-chevron-left fa-fw"> </span>go back');

          var backtocourseText = $('div.btnBackToCourses').find('a').text();

          //$('form#sfwd-mark-complete span.theText').css('display','none');
          // if (theLanguage === "fr-FR") {
          //   $('form#sfwd-mark-complete span.theText').text('[ Description du cours ]');
          // } else {
          //     $('form#sfwd-mark-complete span.theText').text('[ Course description ]');
          // }

      }

      setTimeout(function() {

          //$("<script id='forQuiz'></script>").appendTo("body.single-sfwd-quiz");
          //$("<script id='forQuiz'>setTimeout(function() { var lastLessonUrl = $('div.learndash_nevigation_lesson_topics_list > div').last().find('a').attr('href');  if (theLanguage === 'fr-FR') { $('article.sfwd-quiz a#quiz_continue_link').html('Continuer le module');} else { $('article.sfwd-quiz a#quiz_continue_link').html('Continue to the module'); } $('article.sfwd-quiz a#quiz_continue_link').attr('href', lastLessonUrl);}, 9000);</script>").appendTo('body.single-sfwd-quiz');
          $('<script>window.continue_after_quiz_done = true;</script>').appendTo('body.single-sfwd-quiz');

          if (typeof window.continue_after_quiz_done !== "undefined" && window.continue_after_quiz_done === true) {

            var lastLessonUrl = $('div.learndash_nevigation_lesson_topics_list > div').last().find('a').attr('href');

              if (theLanguage === "fr-FR") {

                $('article.sfwd-quiz a#quiz_continue_link').html('Terminer');
                // $('article.sfwd-quiz form#sfwd-mark-complete button.save').text('Terminer le module');
                //$('article.sfwd-quiz form#sfwd-mark-complete span.theText').text('Description du cours');

              } else {

                $('article.sfwd-quiz a#quiz_continue_link').html('Finish');
                //$('article.sfwd-quiz form#sfwd-mark-complete button.save').text('Finish the module');
                //$('article.sfwd-quiz form#sfwd-mark-complete span.theText').text('Course description');

              }

            //$('article.sfwd-quiz a#quiz_continue_link').attr('href', lastLessonUrl);
            $('<span class="fa fa-chevron-right fa-fw"> </span>').appendTo('article.sfwd-quiz a#quiz_continue_link');
            $('article.sfwd-quiz a#quiz_continue_link').addClass('button col-xs-12 col-sm-12 col-md-3 col-lg-3 col-sm-6 col-md-8 col-lg-8 prev');
            $('article.sfwd-quiz div.quiz_continue_link').addClass('return');


            if ($('article.sfwd-quiz p.printCertificate').css('display') == "block") {

              if (theLanguage === "fr-FR") {

                 $('<p>Ne pas oublier de revenir sur cette page et cliquer le bouton Terminer le module après avoir généré votre certificat.</p>').prependTo('article.sfwd-quiz div.entry-content');

              } else {

                $('<p>Do not forget to come back to this page and click the finish the module button after you have generated your certificate.</p>').prependTo('article.sfwd-quiz div.entry-content');

              }


            }

        }


      }, 3000);


      //$('<script>var lastLessonUrl = $("div.learndash_nevigation_lesson_topics_list > div").last().find("a").attr("href");
      // $("article.sfwd-quiz a#quiz_continue_link").html("Continue to the module");
      // $("article").attr("href", lastLessonUrl);</script>').appendTo('body.single-sfwd-quiz');





      $("div#questionDiv textarea, div#questionDiv input[type=radio]").on('change keyup', function(e) {

        if ( "" != $("div#questionDiv textarea").eq(0).val().trim().toString() ) {

          $('input.checkAnswer').removeAttr('disabled');

        } else {

          $('input.checkAnswer').attr('disabled', 'disabled');

        }

      });


  }

$('article.category-quizq input[name=check]').attr('disabled', 'disabled');
//$('article.category-quizq input[name=check]').css('background-color', '#240757');
$('article.category-quizq input[name=check]').removeClass(':hover');
$('article.category-quizq input[name=check]').css('opacity', '.1');

//Code to have the checkanswer button disabled  for the advanced quiz single choice question

$("input[type=radio]").on('change keyup', function(e) {

  if ( $('input[type=radio]').length > 0) {

      $('input[name=check]').removeAttr('disabled', 'disabled');
      $('input[name=check]').css('opacity', '1');

  } else {

      $('input[name=check]').attr('disabled', 'disabled');
     // $('article.category-quizq input[name=check]').css('background-color', '#240757');
      $('article.category-quizq input[name=check]').removeClass(':hover');
      $('input[name=check]').css('opacity', '.1');

  }

});











    //resetting title and nav when on inside pages
    resettingTitlesinsidePages();



    setTimeout(function() {

        if (theLanguage === "fr-FR") {

          console.log('in the french');

          $('input[name=checkSingle]').val('Continuer');
          //$('article.sfwd-quiz div.learndash input.wpProQuiz_questionInput').html('Je certifie avoir complété tous les élémentsde ce module.');
          //$('article.category-certificate-fr li.wpProQuiz_questionListItem label').html('<input class="wpProQuiz_questionInput" type="radio" name="question_20_46" value="1"> Je certifie avoir complété tous les éléments de ce module.');

        } else {

          console.log('in the english');
          $('input[name=checkSingle]').val('Continue');
          //$('article.sfwd-quiz div.learndash input.wpProQuiz_questionInput').html('<input class="wpProQuiz_questionInput" type="radio" name="question_20_46" value="1"> I certify that I have completed all elements of this module.');
          //$('article.category-certificate li.wpProQuiz_questionListItem label').html('I certify that I have completed all elements of this module.');
           //$('article.category-certificate li.wpProQuiz_questionListItem label').html('<input class="wpProQuiz_questionInput" type="radio" name="question_20_46" value="1"> I certify that I have completed all elements of this module.');

        }

        $('article.sfwd-quiz div.learndash p').hide();
        //$('article.category-certificate div.quiz_continue_link').hide();




    }, 1000);



  $(document).ready(UTIL.loadEvents);



  if ( $('body').hasClass('single-sfwd-courses')  && $('article').hasClass('category-haslaunchen') || $('article').hasClass('category-haslaunchfr') ) {

      console.log('article has class of haslaunchen or haslaunchfr');
      //https://learn.med.uottawa.dev/new-faculty-orientation-program-3/
      if ( courseStatusTextTrimmed == "Course Status: Completed"  || courseStatusTextTrimmed == "État d’avancement : Complété "   ) {

        console.log('course completed');


        if (theLanguage === "fr-FR") {

          $('<span class="launchLink"><span class="fa fa-list"></span> <a href="https://apprendre.med.uottawa.ca/programme-dorientation-des-nouveaux-membres-du-corps-professoral/
" target=_"blank">Back to orientation program requirements</a></span>').appendTo('h1.entry-title');


        } else {

          $('<span class="launchLink"><span class="fa fa-list"></span> <a href="https://learn.med.uottawa.ca/orientation-new-faculty/" target=_"blank">Back to orientation program requirements</a></span>').appendTo('h1.entry-title');


        }


      }
  }


// setTimeout(function() {
//   console.log('textAreaNoValue ' + textAreaNoValue);
// //detect if textarea has no value and if mark complete is mark complete is there
//   if( $('article').hasClass('category-quizq') || $('article').hasClass('category-quizq-fr') && textAreaNoValue === "" ) { //!$('div#questionDiv textarea').val() == ''

//     console.log('textarea IS empty ');

//     if ($('article').hasClass('category-quizq') || $('article').hasClass('category-quizq-fr') && $('article.category-quizq form#sfwd-mark-complete, article.category-quizq-fr form#sfwd-mark-complete').length > 0 ) {

//       console.log('Mark complete exist');

//       $('article.category-quizq div.continue').hide();
//       $('article.category-quizq form#sfwd-mark-complete').show();
//       $('article.category-quizq form#sfwd-mark-complete button').hide();
//       $('article.category-quizq form#sfwd-mark-complete span.theText').hide();

//     } else {

//       console.log('Mark complete DOES NOT exist');

//       $('article.category-quizq div.continue').hide();
//       $('article.category-quizq form#sfwd-mark-complete').hide();
//       $('article.category-quizq form#sfwd-mark-complete button').hide();
//       $('article.category-quizq form#sfwd-mark-complete span.theText').hide();



//     }


//   } else {


//     console.log('textarea not empty');


//     if ($('article').hasClass('category-quizq') || $('article').hasClass('category-quizq-fr') && $('article.category-quizq form#sfwd-mark-complete, article.category-quizq-fr form#sfwd-mark-complete').length > 0 ) {

//       console.log('Mark complete exist');
//       $('article.category-quizq div.continue').hide();
//       $('article.category-quizq form#sfwd-mark-complete').show();
//       $('article.category-quizq form#sfwd-mark-complete button').show();
//       $('article.category-quizq form#sfwd-mark-complete span.theText').show();


//     } else {

//       console.log('Mark complete DOES NOT exist');

//       $('article.category-quizq div.continue').show();
//       $('article.category-quizq form#sfwd-mark-complete').hide();
//       $('article.category-quizq form#sfwd-mark-complete button').hide();
//       $('article.category-quizq form#sfwd-mark-complete span.theText').hide();

//     }



//   }

// }, 1000);

  //Do only if the quiz category is noCorrectAnswerEn or noCorrectAnswerFr


  if ( $('article').hasClass('category-nocorrectansweren') || $('article').hasClass('category-nocorrectanswerfr') ) {


    console.log('article has class category-nocorrectansweren or category-nocorrectanswerf');


    $('form#sfwd-mark-complete').hide();
    $('form#sfwd-mark-complete span.theText').hide();
    $('div.continue').hide();

    if (theLanguage === "fr-FR") {

      //$('div.wpProQuiz_results h4').text("Merci d'avoir complété le post-test");//hides the result information
      $('div.wpProQuiz_results h4').text("");
      $("<p>Vous avez maintenant terminé le prétest.</p>").insertAfter("div.wpProQuiz_results h4");//hides the result information


    } else {

      $('div.wpProQuiz_results h4').text("");
      $("<p>You have now completed the pre-test</p>").insertAfter("div.wpProQuiz_results h4");//hides the result information

    }




    $('article.category-nocorrectansweren .wpProQuiz_content, article.category-nocorrectanswerfr .wpProQuiz_content').on('changeQuestion', function() {

      console.log('onchange question when category is noCorrectAnswerEn ');





        setTimeout(function() {
          //detect checkanswer click
          $('input[name="check"]').click(function (e) {

              if (e.target) {

                console.log(e.target.id + ' clicked');



                //loop through all listitem type radio
                $('input[type=radio]').each(function() {


                  if ( $(this).is(':checked') ) {

                    console.log('checked');



                  } else {

                    console.log('unchecked');

                    //for all unchecked radio buttons remove the classes incorrect or correct on its parent li
                    $(this).closest('li').removeClass('wpProQuiz_answerCorrect');
                    $(this).closest('li').removeClass('wpProQuiz_answerIncorrect');

                    return;

                  }

                });

              }

          });

        }, 100);


    });



  }





  var currentQuestionInitialState;


  //Do only if the quiz has the category retryquizen or retryquizfr
  if ( $('article').hasClass('category-retryquizen') || $('article').hasClass('category-retryquizfr') ) {


      if (theLanguage === "fr-FR") {

        //$('div.wpProQuiz_results h4').text("Merci d'avoir complété le post-test");//hides the result information
        $('div.wpProQuiz_results h4').text("");
        $("<p>Avez-vous bien réussi? Y a-t-il des sujets abordés dans ce module que vous devez réviser? Vous pouvez accéder directement à chacune des pages spécifiques en utilisant le bouton à la droite de votre écran.</p> <p>Afin d'obtenir un crédit pour ce module, vous devez continuer à la page des références et cliquer sur Terminer. Veuillez aussi prendre quelques minutes pour nous fournir des commentaires concernant ce module. Vos commentaires seront utilisés pour améliorer ce module ainsi que plusieurs autres modules d'apprentissage à la Faculté.</p><p>Cliquez sur Continuer maintenant.</p>").insertAfter("div.wpProQuiz_results h4");//hides the result information



      } else {

        $('div.wpProQuiz_results h4').text("");
        $("<p>How did you do? Are there topics addressed in this module that you need to review? You can navigate directly to specific screens using the button on the right of your screen.</p><p>In order to get credit for this module, you must continue to the references and click Finish. Please also take some time to provide us with feedback on the module. Your feedback will be used to improve this and other learning modules at the Faculty.</p><p>Click Continue now.</p>").insertAfter("div.wpProQuiz_results h4");//hides the result information

      }//end language test


      $('article.category-retryquizen .wpProQuiz_content, article.category-retryquizfr .wpProQuiz_content').on('changeQuestion', function() {

        console.log('changed question');
        //show checkanswer
        $('input[name=check]').show();
        //change opacity and disable next button
        //$('input[type="button"].wpProQuiz_button:hover').css('background-color', '#240757');
        $('input[name=check]').css('opacity', '.1');
        $('input[name=check]').attr('disabled', 'disabled');
        //remove hover class on checkanswer button
        $('input[name=check]').removeClass( "hover" );
        //show next button
        $('input[name=next]').show();
        //change opacity and disable next button
        $('input[name=next]').css('opacity', '.1');
        $('input[name=next]').attr('disabled', 'disabled');
        //remove hover class on next button
        $('input[name=next]').removeClass( "hover" );
        //hide feedback
        $('div.wpProQuiz_response').hide();


        // remove all the other try again buttons
        $('input[name="tryAgain"]').each(function() {
            $(this).remove();
        });

        var $currentQuestion = $duplicatedQuestion = null;
        $('.wpProQuiz_listItem').each(function () {
            if (
                typeof this.style == "undefined" ||
                typeof this.style.display == "undefined" ||
                this.style.display != 'none'
            ) {
                $currentQuestion = $(this);
                return false; // break the each loop
            }
        });

        if ($currentQuestion == null) {
            return false;
        }

        $currentAnswerButton = $currentQuestion.find("input[name=check]");

        //check if there is a try again button created already
        if ( !$('input[name="tryAgain"]').length ) {

          //configure try again
          if (theLanguage === "fr-FR") {

            $('<input type="button" class="wpProQuiz_button tryAgain" name="tryAgain" style="margin-right: 10px!important;" value="Essayer de nouveau">').insertBefore($currentAnswerButton);

          } else {

            $('<input type="button" class="wpProQuiz_button tryAgain" name="tryAgain" style="margin-right: 10px!important;" value="Try Again">').insertBefore($currentAnswerButton);

          }

        }


        //hide try again originally
        $('input[name=tryAgain]').hide();

        //bind event to the try again button
        $("input[name=tryAgain]").on('click', function() {

            //uncheck all the checked elements
            //remove attr disabled on radio buttons
            // $('input[type=radio]').removeAttr('disabled');
            // //remove classes incorrect and correct
            // $('ul.wpProQuiz_questionList li').removeClass('wpProQuiz_answerCorrect');
            // $('ul.wpProQuiz_questionList li').removeClass('wpProQuiz_answerIncorrect');
            // //uncheck radio buttons
            // $('input[type=radio]').prop('checked', false);

            // find the current question
            var $currentQuestion = $currentQuestionList = $duplicatedQuestionList = null;
            $('.wpProQuiz_listItem').each(function () {
                if (
                    typeof this.style == "undefined" ||
                    typeof this.style.display == "undefined" ||
                    this.style.display != 'none'
                ) {
                    $currentQuestion = $(this);
                    return false; // break the each loop
                }
            });

            if ($currentQuestion == null) {
                return false;
            }

            // duplicate the current question and store it in a temp area
            $currentQuestionList = $currentQuestion.find('.wpProQuiz_questionList').not('.storedAttempt');
            $duplicatedQuestionList = $currentQuestionList.clone(true);
            $duplicatedQuestionList.addClass('storedAttempt');
            $duplicatedQuestionList.insertBefore($currentQuestionList);

            // make sure the inputs are uniquely named (grouped)
            var countStoredAttempts = $currentQuestion.find('.wpProQuiz_questionList.storedAttempt').length;

            $duplicatedQuestionList.find('.wpProQuiz_questionInput').each(function() {
                i = $(this).index();
                groupName = $currentQuestionList.find('.wpProQuiz_questionInput').eq(i).attr('name') + '_' + countStoredAttempts;
                $(this).attr('name', groupName);
            });

            $duplicatedQuestionList.hide();

            // reset the new copy of the current question
            $currentQuestionList.find('.wpProQuiz_questionInput').removeAttr('disabled').removeAttr('checked').css('background-color', '');
            $currentQuestionList.find('.wpProQuiz_answerCorrect, .wpProQuiz_answerIncorrect').removeClass('wpProQuiz_answerCorrect wpProQuiz_answerIncorrect');
            $currentQuestion.find('.wpProQuiz_response').hide().children().hide();
            //set focus on the first radio button
            $currentQuestionList.find('.wpProQuiz_questionInput').first().focus();

            // show the new copy of the current question
            $currentQuestion.data('check', false);

            //show next button
            $('input[name=check]').show();
            //change opacity of the next button and disable it
            $('input[name=next]').css('opacity', '.1');
            $('input[name=next]').attr('disabled', 'disabled');
            //show checkanswer button
            $('input[name=check]').show();
            //change opacity of checkanswer and disable it
            $('input[name=check]').css('opacity', '.1');
            $('input[name=check]').attr('disabled', 'disabled');
            //hide try again button
            $('input[name=tryAgain]').hide();

      });// end on change question for retry quiz


      setTimeout(function(){
          //detect checkanswer click
        $('input[name="check"]').click(function (e) {

            if (e.target) {

                console.log(e.target.id + ' clicked');

                // find the current question
                var $currentQuestion = $duplicatedQuestion = null;
                $('.wpProQuiz_listItem').each(function () {
                    if (
                        typeof this.style == "undefined" ||
                        typeof this.style.display == "undefined" ||
                        this.style.display != 'none'
                    ) {
                        $currentQuestion = $(this);
                        return false; // break the each loop
                    }
                });

                if ($currentQuestion == null) {
                    return false;
                }

                $currentQuestionList = $currentQuestion.find('.wpProQuiz_questionList').not('.storedAttempt')

                //check if the check radio list is incorrect
                if ($currentQuestionList.find('input[type=radio]:checked').closest('li').hasClass('wpProQuiz_answerIncorrect')) {

                    console.log('choice is incorrect');
                    //hide feedback
                    //$('div.wpProQuiz_response').hide();
                    //hide correct feedback
                    $currentQuestion.find('div.wpProQuiz_response').hide();
                    $currentQuestion.find('div.wpProQuiz_response div.wpProQuiz_correct').hide();
                    $currentQuestion.find('div.wpProQuiz_response div.wpProQuiz_incorrect').hide();

                    $currentQuestionList.find('input[type=radio]:checked').closest('li').siblings().removeClass('wpProQuiz_answerCorrect');

                    //show checkanswer
                    $('input[name=check]').show();
                    //change opacity and disable next button
                    $('input[name=check]').css('opacity', '.1');
                    $('input[name=check]').attr('disabled', 'disabled');
                    //show next button
                    $('input[name=next]').show();
                    //change opacity and disable next button
                    $('input[name=next]').css('opacity', '.1');
                    $('input[name=next]').attr('disabled', 'disabled');
                    $('input[name=tryAgain]').show();


                } else { //radio list item is correct

                    console.log('choice correct');
                    $('input[name=tryAgain]').hide();
                    $('input[name=next]').show();
                    //change opacity and disable next button
                    $('input[name=next]').css('opacity', '5');
                    $('input[name=next]').removeAttr('disabled');

                    //show correct feedback
                    $currentQuestion.find('div.wpProQuiz_response').show();
                    $currentQuestion.find('div.wpProQuiz_response div.wpProQuiz_correct').show();
                    $currentQuestion.find('div.wpProQuiz_response div.wpProQuiz_incorrect').hide();

                    $('input[name=check]').hide();
                    //add correct class to the correct radio button closest list item
                    //$('input[type=radio]:checked').closest('li').addClass('wpProQuiz_answerCorrect');

                }

            }//end e.target

        }); //end input type check

       }, 100); //end set timeout function



    });

  }//end retry quiz category check






  //Function Show Show continue on non-clinical pages.
  showContinueOnNonClinicalPage();

  //Function Show Show continue on clinical pages.
  showContinueOnClinicalPage();

  //Function Show Show continue on objectives pages.
  showContinueOnObjectivesPage();


  if ( $('body').hasClass('single-sfwd-lessons') ||  $('body').hasClass('single-sfwd-topic')  ) {


    console.log('lesson or topic');

      if ( $('body').hasClass('category-notesen')  || $('body').hasClass('category-notesfr')   ) {



       // console.log('lesson or topic has class notesen or notesfr');

       // console.log('lesson or topic and has category notesEn or notesFr');

         $('div.Notes').sisyphus({
                //locationBased: true

                locationBased: false,
                customKeySuffix: 'course_' + course_id,


                onSave: function() {

                  console.log('Saved');

                }

          }); // save the reflection answers through localStorage


        $('a.btnNotes').click(function() {

          console.log('button notes clicked');

          var pageTitle = $('article h2').text(); //get page title
          console.log('pageTitle ' + pageTitle);

          var currentDate = new Date();
          var newCurrentDate = new Date();
          var dateToString = newCurrentDate.toDateString();

          //$('#date').html(Globalize.format(today,'D','de'));
          //var dateFrench = Globalize.format(dateToString,'D','fr');
          console.log('currentDate ' + currentDate);
          console.log('dateToString ' + dateToString);


          var existingNotesContent;

          if ( $('div.Notes textarea#MyNotes').val() == '' ) {
            //console.log('does not contain the current page title');
            console.log('textarea is empty');

            $('div.Notes textarea#MyNotes').val(pageTitle + '\n');



          } else {

            console.log('textarea is not empty');

            existingNotesContent = $('div.Notes textarea#MyNotes').val();
            console.log('existingNotesContent ' + existingNotesContent);

            $('div.Notes textarea#MyNotes').val(existingNotesContent + '\n\n' + pageTitle  + '\n');


          }







        });


    } else {

        //console.log('lesson or topic and DOES NOT have category notesEn or notesFr');

    }

          // if ( !$('div.Notes textarea#MyNotes:contains(pageTitle)') ) {


          //   $('div.Notes div#MyNotes').append(pageTitle + '<br>'); //Add pageTitle and a br tag

          // }//test if the textarea contains the title text already

  }






}(jQuery));


$(function(){
        // Enabling Popover Example 1 - HTML (content and title from html tags of element)
        //$("[data-toggle=popover]").popover();

        $('[rel="popover"]').popover({
              container: 'body',
              html: true,
              content: function () {
                  var theData = $($(this).data('popover-content')).addClass('hide');
                  var clone = $($(this).data('popover-content')).clone(true).removeClass('hide');
                  return clone;
              }
          }).click(function(e) {
              e.preventDefault();

          });
});



$('.attest-completion').click(function() {
      $('.xapi-activity-labels').toggleClass('active');
});

// ______________________________________________________________________

//Code for all functions called above

// ______________________________________________________________________


 //Function Show Show continue on clinical pages.
  function showContinueOnClinicalPage() {

    if  ( $('article').hasClass('category-optionalclinicalen') || $('article').hasClass('category-optionalclinicalfr') ) {


        console.log('article has a class of category-optionalclinicalen or category-optionalclinicalfr');

        if (theLanguage == "fr-FR") {

          $('article.category-optionalclinicalfr div.entry-content').find('p').first().find('i').html("<div class='optional'>Les membres du corps professoral non cliniciens DOIVENT faire cette activité. <br>Les cliniciens membres du corps professoral peuvent omettre cette activité.</div>");


        } else {

          $('article.category-optionalclinicalen div.entry-content').find('p').first().find('i').html("<div class='optional'>Non-clinical faculty MUST complete this activity. <br>Clinical faculty may skip this activity.</div>");


        }



        if ( $("article.category-optionalclinicalen form#sfwd-mark-complete button, article.category-optionalclinicalfr form#sfwd-mark-complete button").length > 0 ) {

              console.log('mark complete exists');


          $('form#sfwd-mark-complete').show();
          $('form#sfwd-mark-complete button').show();
          $('form#sfwd-mark-complete span.theText').show();
          $('div.continue').hide();


        } else {

              console.log('mark complete DOES NOT exists');


          $('div.continue').show();
          $('form#sfwd-mark-complete').hide();
          $('form#sfwd-mark-complete button').hide();
          $('form#sfwd-mark-complete span.theText').hide();



        }

    }

  }

  //Function Show Show continue on non-clinical pages.
  function showContinueOnNonClinicalPage() {

    if  ( $('article').hasClass('category-optionalen') || $('article').hasClass('category-optionalfr') ) {


        console.log('article has a class of category-optionalen or category-optionalfr');

        if (theLanguage == "fr-FR") {

          $('article.category-optionalfr div.entry-content').find('p').first().find('i').html("<div class='optional'>Membre du corps professoral clinique doit ABSOLUMENT compléter cette activité. <br>Faculté non clinique peut sauter cette activité.</div>");
          $('div.wpProQuiz_incorrect h4').text('Rétroaction');

        } else {

          $('article.category-optionalen div.entry-content').find('p').first().find('i').html("<div class='optional'>Clinical faculty MUST complete this activity.<br> Non-clinical faculty may skip this activity.</div>");
          $('div.wpProQuiz_incorrect h4').text('Feedback');

        }



        if ( $("article.category-optionalen form#sfwd-mark-complete button, article.category-optionalfr form#sfwd-mark-complete button").length > 0 ) {

              console.log('mark complete exists');


          $('form#sfwd-mark-complete').show();
          $('form#sfwd-mark-complete button').show();
          $('form#sfwd-mark-complete span.theText').show();
          $('div.continue').hide();


        } else {

              console.log('mark complete DOES NOT exists');


          $('div.continue').show();
          $('form#sfwd-mark-complete').hide();
          $('form#sfwd-mark-complete button').hide();
          $('form#sfwd-mark-complete span.theText').hide();



        }

    }

  }


  //Function Show continue button on objectives page (first page)
  function showContinueOnObjectivesPage() {

       if  ( $('article').hasClass('category-objectivesen') || $('article').hasClass('category-objectivesfr') ) {

        console.log('article has a class of category-objectivesen or category-objectivesfr');


            if ( $("article.category-objectivesen form#sfwd-mark-complete button, article.category-objectivesfr form#sfwd-mark-complete button").length > 0 ) {

              console.log('mark complete exists');


              $('form#sfwd-mark-complete').show();
              $('form#sfwd-mark-complete button').show();
              $('form#sfwd-mark-complete span.theText').show();
              $('div.continue').hide();


            } else {

              console.log('mark complete DOES NOT exists');


              $('div.continue').show();
              $('form#sfwd-mark-complete').hide();
              $('form#sfwd-mark-complete button').hide();
              $('form#sfwd-mark-complete span.theText').hide();

            }


      }

  }





//Function that toggle the notepad
function toggleNotes() {

  if ( $('aside.sidebar div.Notes').hasClass('opened') ) {

    $('aside.sidebar div.Notes').css('display','block');

  } else {

    $('aside.sidebar div.Notes').css('display','none');

  }

}


//Function that show learndash quiz elements when the page is a lesson and has a class of category-certificat. Hides other unwanted elements. Changes the text of the quiz name.
function pageIsLessonAndArticleHasCertificateCategory() {

  if ( $('body').hasClass('single-sfwd-lessons') && $('article').hasClass('category-certificate') || $('article').hasClass('category-certificate-fr')   ) {

    $('div#learndash_quizzes').show();
    $('div#quiz_heading').hide();
    $('div#quiz_list div.is_not_sample div.list-count').hide();

    if (theLanguage === "fr-FR") {

      $('div#quiz_list div.is_not_sample h4').find('a').text('Cliquez ici pour confirmer avoir complété le cours.');

    } else {

      $('div#quiz_list div.is_not_sample h4').find('a').text('Click here to confirm completion.');

    }

  }

}


//Function to hide the message section completed on quiz, lessons or topic pages.
function hideCompletedMessageForQuizLessonsTopicsPages() {

  if ( $('body').hasClass('single-sfwd-quiz') ||  $('body').hasClass('single-sfwd-lessons') || $('body').hasClass('single-sfwd-topic')  )  {

    $('div.sectionCompleted').hide();

  }

}


//Hide the credits when the body tag has a class of page
function hideCreditsWhenBodyHasClassOfPage() {

  if ( $('body').hasClass('page') ) {

    $('section#credits').hide();

  }

}



//Change the text of the next button on lesson page that have quizzes
function changeTextOfnextButtonforQuizOnLessonPages() {

  if ( $('body').hasClass('single-sfwd-lessons') ) {

    if (theLanguage === "fr-FR") {

      $('input[name=next].wpProQuiz_button').val('Question suivante');

    } else {

      $('input[name=next].wpProQuiz_button').val('Next question');

    }

  }

}


//Change the confirmation message text for gravity form
function changeConfirmationMessgeTextForGravityForm() {

  if (theLanguage === "fr-FR") {

    $("div.gform_confirmation_message").text("Merci de nous avoir contacter! Nous prendrons contact avec vous sous peu.");

  } else {

    $("div.gform_confirmation_message").text("Thanks for contacting us! We will get in touch with you shortly.");

  }

}


//Change the text of various elements for pages with a category of certificat or certificate
function changeTextForPageWithCategoryCertificatOrCertificate() {

  if ( $('article').hasClass('category-certificat') || $('article').hasClass('category-certificate') || $('article').hasClass('category-certificate-fr')  ) {

    if (theLanguage === "fr-FR") {

      $("div.quiz_continue_link").find("a").text("Cliquez ici pour récapituler le cours");
      $("input.wpProQuiz_QuestionButtonwpProQuiz_QuestionButton").val("Continuer");
      $("div.wpProQuiz_question_text p").text("Déclaration d'achèvement");
      $("p.wpProQuiz_certificate").addClass("printCertificate");
      //$("input.wpProQuiz_questionInput").prepend("li.wpProQuiz_questionListItem");
      //$("li.wpProQuiz_questionListItem").find('label').text("Je certifie que j'ai examiné le contenu du cours entier.");
      //$(' <i class="fa-file-pdf-o"></i>').insertAfter('p.wpProQuiz_certificate');
      $('h4.wpProQuiz_header').hide();
      $('input.wpProQuiz_QuestionButton').val('I agree');

    } else {

      $('div.quiz_continue_link').find('a').text('Click here to review the course');
      $('input.wpProQuiz_QuestionButton').val('Continue');
      $('div.wpProQuiz_question_text p').text("Statement of Completion");
      $('p.wpProQuiz_certificate').addClass('printCertificate');
      //$("input.wpProQuiz_questionInput").prepend("li.wpProQuiz_questionListItem");
      //$("li.wpProQuiz_questionListItem").find('label').text("I certify that I have reviewed the entire course content.");
      //$(' <i class="fa-file-pdf-o"></i>').insertAfter('p.wpProQuiz_certificate');
      $('h4.wpProQuiz_header').hide();
      $('input.wpProQuiz_QuestionButton').val('I agree');
      //$('input[name=next].wpProQuiz_button').val('Continue');

    }

  }

}



//Function to display and hide elements on quiz pages
function hideShowElementsOnQuizPages() {

  if ( $('body').hasClass('single-sfwd-quiz') ) {

    $('h1.entry-title').show();

    //if (theLanguage === "fr-FR") {
      //$('a#quiz_continue_link').text();
    // } else {
      // $('a#quiz_continue_link').text();
    //}

    $('p.wpProQuiz_certificate').show();
    $('p.wpProQuiz_certificate').append(' <i class="fa fa-file-pdf-o fa-fw"> </i>');

    if (theLanguage === "fr-FR") {

      $('p.wpProQuiz_certificate').find('a').text('Obtenez votre certificat');

    } else {

      $('p.wpProQuiz_certificate').find('a').text('Generate your certificate');

    }

    $('input.wpProQuiz_QuestionButton').val('I agree');

  }

}



//Code for the ilearnPeds page
function iLearnPedsChangeElements() {

  if ($('body').hasClass('ilearn-peds') ){

    console.log('page residency');

    if (theLanguage === "fr-FR") {

      $('<a class="btn btnNotes" role="button">Voir et écrire vos notes</a> ').appendTo('aside.sidebar');
      $('<div class="Notes" aria-hidden="true"><textarea id="MyNotes" name="MyNotes" placeholder="Nous vous encourageons à entrer quelques notes / questions que vous pourriez avoir en parcourant le module afin de les utiliser pour la discussion plus tard." rows="40"  onkeyup="textAreaAdjust(this)" style="overflow:hidden" ></textarea></div>').insertAfter('a.btnNotes');

    } else {

      $('<a class="btnNotes" role="button">View or write your notes</a>').appendTo('aside.sidebar');
      $('<div class="Notes" aria-hidden="true"><textarea id="MyNotes" name="MyNotes" placeholder="We encourage you to enter some notes/questions you may have as you go through the module to use them for the discussion later on." rows="40"  onkeyup="textAreaAdjust(this)" style="overflow:hidden"></textarea></div>').insertAfter('a.btnNotes');

    }


    $("aside.sidebar a.notes").wrap('<div class="notepad"></div>');
    $('div.notepad a.notes').append(' <span class="fa fa-pencil-square-o fa-fw"> </span>');

    //Hides the sidebar div element  with class myProgress
    $('aside.sidebar div.Notes').hide();


    if (theLanguage === "fr-FR") {

      $("div.notepad a.notes").text('Voir et écrire vos notes');

    } else {

      $("div.notepad a.notes").text('View and write your notes');

    }


    //Toggles the textarea#MyNotes on click event on to add notes
    $("aside.sidebar a.btnNotes").click(function (c) {

      c.preventDefault();

      $('aside.sidebar div.Notes').toggle( "slow", function() {
        // Animation complete.
      });

    });

  }

}


//Function with code for the front(home) page
function changingElementsOnTheHomePage() {


  //Code for the home page
  if ( $('body').hasClass('home page') ) {

    $('div#mainBranding div.topNavigation li').last().removeClass('menu-item').addClass('language');
    $('div#mainBranding div.topNavigation li').last().attr('role', 'list-item');
    //$('div#mainBranding div.topNavigation li.signInBtn').css('display', 'block');

    if (theLanguage === "fr-FR") {

      console.log();

      $('section#courses-list ul.nav li:nth-child(1)').find('a').text('Pré-externat');
      $('section#courses-list ul.nav li:nth-child(2)').find('a').text('Externat');
      $('section#courses-list ul.nav li:nth-child(3)').find('a').text('Résidence');
      $('section#courses-list dt.button-enroll').find('a').text('Suivez le cours');
      $('section#courses-list div.buttons').find('a').text('Voir la liste complète de cours');

    } else {

      $('section#courses-list ul.nav li:nth-child(1)').find('a').text('Pre-clerkship');
      $('section#courses-list ul.nav li:nth-child(2)').find('a').text('Clerkship');
      $('section#courses-list ul.nav li:nth-child(3)').find('a').text('Residency');
      $('section#courses-list dt.button-enroll').find('a').text('Take the course');
      $('section#courses-list div.buttons').find('a').text('View the complete list of courses');

    }

  }

}


//Function with code for the login or register page
function loginRegisterPageCode() {

  if ( $('body').hasClass('login') || $('body').hasClass('register') || $('body').hasClass('connexion')  || $('body').hasClass('inscription')  ) {

    $('section#credits').hide();
    $('div#topBranding').hide();


    //$('div#theme-my-login div.errors').contents().appendTo('p.error');


    if (theLanguage === "fr-FR") {

      $('div.passwordRetrieval div.notOne45').find('p.message').text("Donnez-nous votre nom d'utilisateur et / ou e-mail. Nous vous ferons parvenir un lien par courriel afin que vous puissiez créer votre nouveau mot de passe.");


     // $('article#article').find(" Log In ").replace('');

    } else {

      $('div.passwordRetrieval div.notOne45').find('p.message').text("Provide us your username and/or email. We will send you a link via email to help you create your new password.");

    }



    $("a.forgotPassword").click(function(e){

      e.preventDefault();

      $("section#Login article.register div.passwordRetrieval").toggle(function(){

      });

    });



    $("a.one45AccountRetrieval2").click(function(e){

      e.preventDefault();

      $("section#Login article.register div.passwordRetrieval div.notOne45").toggle(function(){

      });

    });


    $("p.One45No a").click(function(e){

      e.preventDefault();

      $("section#Login article.register div.registerForm").toggle(function(){

      });

    });

  }

}


//Function to on hide paragraph with class of message on register page
function registerPageCode() {

  if ( $('body').hasClass('register') || $('body').hasClass('inscription')  ) {

    $('p.message').hide();

  }

}


//Code to change the arrow to a square for the elements in the widget sidebar menu when they do not have topics
function changeArrowToSquare() {

  $('.lesson_incomplete.list_arrow:not(.flippable)').removeClass('list_arrow').addClass('list-square-grey');
  $('.list_arrow.lesson_completed:not(.flippable)').removeClass('list_arrow').addClass('list-square-green');
  $('.list_arrow.flippable.lesson_completed.collapse').removeClass('list_arrow').addClass('list-square-green');
  $('.list_arrow.flippable.lesson_completed.expand').removeClass('list_arrow').addClass('list-square-green');

}


//Adds the class addCompleted to the div element lesson_completed and adding the green checkmark
function addCompletedClassToLesson() {

  if ($('div.list_lessons').siblings().hasClass('lesson_completed')) {

    $('div.lesson_completed').siblings().addClass('addCompleted');

  }

}



//Adds the class addIncomplete to the div element lesson_incomplete and adding the faded greycheckmark
function addIncompleteClassToLesson() {

  //Adds the class addIncomplete to the div element lesson_incomplete and adding the faded greycheckmark
  if ($('div.list_lessons').siblings().hasClass('lesson_incomplete')){

    $('div.lesson_incomplete').siblings().addClass('addIncomplete');

  }

}


//Function to change the text on course description page
function changeTextCourseDescriptionPage() {

  if (theLanguage === "fr-FR") {

    $("div.expand_collapse a").text('Développer tout');
    $('h4#learndash_course_content_title').text('Contenu du cours');
    $('section#credits h2').text('Remerciements');
    $('div.learndash_join_button > form > input[type=submit]').val('Connectez-vous et suivez ce cours');

  } else {

    $("div.expand_collapse a").text('Expand All');
    $('h4#learndash_course_content_title').text('Course content');
    $('section#credits h2').text('Credits');
    $('div.learndash_join_button > form > input[type=submit]').val('Log in and take this Course');

  }

}

//Function to check if topics list expanded or collapsed
  function checkTopicExpandedORCollapsed() {

    $("div.expand_collapse a:first-child").click(function(e){

      e.preventDefault();

      $(".learndash .learndash_topic_dots").slideToggle( 'slow', 'swing', function(){

        if ($(this).is(':visible')) {

          if (theLanguage === "fr-FR") {

            $("div.expand_collapse a").text('Réduire tout');

          } else {

            $("div.expand_collapse a").text('Collapse All');

          }

          $('div.expand_collapse').removeClass('collapsed').addClass('expanded');
          //$('div.expand_collapse').css('background-color', '#ccc');
          //$('div.expand_collapse').addClass('glyphicon icon-plus-sign');

        } else {

          if (theLanguage === "fr-FR") {

            $("div.expand_collapse a").text('Développer tout');

          } else {

            $("div.expand_collapse a").text('Expand All');

          }


          $('div.expand_collapse').removeClass('expanded').addClass('collapsed');
          //$('div.expand_collapse').css('background-color', 'white');
          //$('div.expand_collapse').addClass('glyphicon icon-minus-sign');
        }

      });

        //$('div.expand_collapse').css('background-color', 'white');
        //$('div.expand_collapse').removeClass('glyphicon icon-minus-sign').addClass('glyphicon icon-plus-sign');
        //$("div.expand_collapse a").text('Expand All');

    });

  }

  function disabledLeassonProgression() {
    
  }

    //removing the photo when on inside pages
  function resettingTitlesinsidePages() {

    if ($('body').hasClass('page') || $('body').hasClass('single-sfwd-lessons') || $('body').hasClass('single-sfwd-courses') || $('body').hasClass('single-sfwd-topic') || $('body').hasClass('single-sfwd-quiz') ) {


        $('div.theHeader ').addClass('col-xs-12 col-sm-12 col-md-6 col-lg-6');
        $('div.topNavigation').addClass('col-xs-12 col-sm-12 col-md-6 col-lg-6');
        $('ul.topRightNav').addClass('pull-right');
        $('div#mainBranding').removeClass('container2').addClass('container');



    } else {


        $('div.theHeader ').removeClass('col-xs-12 col-sm-12 col-md-6 col-lg-6');
        $('div.topNavigation').removeClass('col-xs-12 col-sm-12 col-md-6 col-lg-6');
        $('ul.topRightNav').removeClass('pull-right');
        $('div#mainBranding').removeClass('container').addClass('container2');



    }

  }
