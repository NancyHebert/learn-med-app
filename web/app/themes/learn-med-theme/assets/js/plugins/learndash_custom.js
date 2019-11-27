(function($) {
	console.log( 'in learndash_custom.js in function ' );
    $(document).ready(function(){

    	console.log( 'in learndash_custom.js document ready' );

    	// get the config parameters, exit if not found
        if (typeof(window.learndash_custom) == undefined) {
            return;
        }

        
        var config = window.learndash_custom;

        console.log('config ' + config);

		var lastActiveTopic = $('div.learndash_nevigation_lesson_topics_list div.active div.learndash_topic_widget_list ul li a').last().hasClass('topicActive');
		var nextLessonInactive = $('div.lesson').closest('div.learndash_nevigation_lesson_topics_list div.active').next().siblings('div.inactive');
		var nextLessonInactiveIncomplete = $('div.lesson').closest('div.learndash_nevigation_lesson_topics_list div.active').next().find('div.addIncomplete');
		var nextLessonLink = $('div.lesson').closest('div.learndash_nevigation_lesson_topics_list div.active').next().find('div.addIncomplete div.lesson a').attr('href');
		var anyNonCompletedTopic = $('div.learndash_nevigation_lesson_topics_list div.active div.learndash_topic_widget_list ul li a').hasClass('topic-notcompleted');
		var nextTopicNotCompleted = $('div.learndash_nevigation_lesson_topics_list div.active div.learndash_topic_widget_list ul li a.topic-notcompleted:first-child').closest('li').next().find('a.topic-notcompleted').attr('href');
		var countNumberNotCompleted = $('div.learndash_nevigation_lesson_topics_list div.active div.learndash_topic_widget_list ul li a.topic-notcompleted').closest('li').length;
		var nextTopicNotCompletedLink = $('div.learndash_nevigation_lesson_topics_list div.active div.learndash_topic_widget_list ul li').find('a.topic-notcompleted').not('.topicActive').first();
		var nextTopicAfterActiveHref = $('div.learndash_nevigation_lesson_topics_list div.active div.learndash_topic_widget_list ul li a.topicActive').closest('li').next('li').find('a').first().attr('href');
		var backToTheCourse = $('.btnBackToCourses').attr('href');
		//When on last active topic



  			if ( anyNonCompletedTopic == true ) {

  				console.log( 'anyNonCompletedTopic ' + anyNonCompletedTopic);
  				console.log('new There are no completed topics in the list');
  				console.log('nextLessonLink ' + nextLessonLink);

  				$('div.learndash_nevigation_lesson_topics_list div.active div.learndash_topic_widget_list ul li a.topic-notcompleted').closest('div.list_lessons').removeClass('addCompleted').addClass('addIncomplete');


		        //When user click the mark complete button click send response via ajax
		        $("form#sfwd-mark-complete button").click(function(e) { 

		        	console.log('markcomplete clicked');
		        	console.log('sfwd-mark-complete button clicked');
		        	e.preventDefault();

		        	$.ajax({
		                    type: "POST",
		                    url: window.learndash_custom.ajax_url,
		                    data: {
		                        action: 'learndash_custom_topic_and_lesson_mark_complete',
		                        topic_id: config.topic_id 
		                    }
		            }).done(function(data){

		            	if ( data.success == true  ) { 

		            		console.log('data.success = true');

		            		console.log('We have success!');

							console.log('success'); 

							//add addCompleted class to associated lesson
						 	$('div.learndash_nevigation_lesson_topics_list div.active div.learndash_topic_widget_list ul li a.topicActive').last().addClass('topic-completed');
						 	//Remove addIncomplete class from associated lesson
						 	$('div.learndash_nevigation_lesson_topics_list div.active div.learndash_topic_widget_list ul li a.topicActive').closest('div.list_lessons').removeClass('addIncomplete');
						 	//add addCompleted class associated lesson 
						 	$('div.learndash_nevigation_lesson_topics_list div.active div.learndash_topic_widget_list ul li a.topicActive').closest('div.list_lessons').addClass('addCompleted');
						    
						 	console.log(nextLessonInactive);
						 	console.log(nextLessonLink);
						 	console.log(backToTheCourse);
						 	//window.location = nextLessonLink;
						


							if (lastActiveTopic == true) {

								console.log('On the last active topic');

								//redirect to the next lesson
								console.log('I am on the last topic');
								
								$('div.learndash_nevigation_lesson_topics_list div.active div.learndash_topic_widget_list ul li a.topicActive').closest('div.list_lessons').removeClass('addIncomplete').addClass('addCompleted');
								
								if (nextLessonInactiveIncomplete.length > 0) {

									console.log('nextLessonInactiveIncomplete ');

									console.log('nextLessonLink ' + nextLessonLink);						 	
									window.location = nextLessonLink;
									console.log(window.location = $('div.lesson').closest('div.learndash_nevigation_lesson_topics_list div.active').next().find('div.addIncomplete div.lesson a').attr('href') );

								} else {

									console.log('there are NO nextLessonInactiveIncomplete ');

									console.log('backToTheCourse ' + backToTheCourse);
									window.location = backToTheCourse;

								}
								
							

							} else {

								console.log('NOT On the last active topic');

								console.log('I am on any other topic not completed');

								//find out how many topic notcompleted
							
								if ( countNumberNotCompleted > 1 && countNumberNotCompleted != 0 ) {

									
									console.log('there are more than one topic not completed.');
									//redirect to the next topic completed
									console.log('nextTopicNotCompleted ' + nextTopicNotCompleted);
									window.location = nextTopicNotCompleted;


								} else {

									console.log('Only one topic');

									console.log('there is only one topic not completed');
									//redirect to the next lesson
									console.log('nextLessonLink ' + nextLessonLink);
									window.location = nextLessonLink;

								}



								//if on a topic and there is no other not completed 
								//go to the next lesson

								//else if I am on any other topic and there are more not completed
								//then go to the next notcompleted topic

								//window.location = nextTopicAfterActiveHref;

								

								

							}

							
						    
						} else {

							console.log('Fail!');
						   	//search through all topics for the lesson

						   		//retrieve link for the active lesson
						   		var theLanguage = $('html').attr('lang');
 
						   		var lessonActiveLink = $('div.learndash_nevigation_lesson_topics_list').find('div.active div.addIncomplete div.lesson').find('a').attr('href');
						   		

						   		var activeLessonTopicNotCompletedLink = $('div.learndash_nevigation_lesson_topics_list div.active').find('div.learndash_topic_widget_list ul li a.topic-notcompleted').first().attr('href');
						   		var activeLessonTopicNotCompletedText = $('div.learndash_nevigation_lesson_topics_list div.active').find('div.learndash_topic_widget_list ul li a.topic-notcompleted').first().text();
						   		$.trim(activeLessonTopicNotCompletedText);
								//retrieve the link of the topic-notCompleted 

								//redirect the user to the active lesson
						   		window.location = activeLessonTopicNotCompletedLink;
						   		console.log(activeLessonTopicNotCompletedLink);


        var topicActive = $('div.learndash_nevigation_lesson_topics_list div.active div.learndash_topic_widget_list ul li').find('a.topicActive');
		var activeTopicText = $('div.learndash_nevigation_lesson_topics_list div.active div.learndash_topic_widget_list ul li').find('a.topicActive').text();
  		var theLastTopicItemInListText = $('div.learndash_nevigation_lesson_topics_list div.active div.learndash_topic_widget_list ul').find('li').last().find('a').text();
		var parentLesson = $('div.learndash_nevigation_lesson_topics_list div.active div.learndash_topic_widget_list ul li').find('a.topicActive').closest('div.list_lessons');
   		var nextLessonLink = $('div.lesson').closest('div.learndash_nevigation_lesson_topics_list div.active').next('div.inactive').find('div.lesson a').attr('href');
  		var lastLessonActive = $('div.learndash_nevigation_lesson_topics_list div:last-child').hasClass('active');


								if (theLanguage === "fr-FR") {
							   		//echo errormessage to the user
							   		$("<p>S'il vous plaît cliquer sur Continuer pour vous assurer d'avoir tout complétés les leçons. </p>").appendTo( $("div.entry-content") );
							   		console.log("There was an error "); 
						   		} else {


						   			//echo errormessage to the user
							   		$("<p>Please click continue to make sure you have complete all topics</p>").appendTo( $("div.entry-content") );
							   		console.log("There was an error "); 

						   		}

						   		//mark current topic complete
						   		$('div.learndash_nevigation_lesson_topics_list div.active').find('div.learndash_topic_widget_list ul li a.topic-notcompleted').removeClass('topic-notcompleted').addClass('a.topic-completed');

						   		//change to green check mark for completed
						   		$('div.active div.list_lessons').removeClass('addIncomplete').addClass('addCompleted');
						   		
						   		//mark associated current lesson complete
						   		$('div.learndash_nevigation_lesson_topics_list div.active').find('div.lesson_incomplete').removeClass('lesson_incomplete').addClass('lesson_completed');
						   		


						   		//go to the topic that has not been completed
						   		$('div.continue').find('a').attr('href', activeLessonTopicNotCompletedLink);
								$('div.continue div.text').find('p').html(activeLessonTopicNotCompletedText);
						   		

						}

		            });



		       	});


	       	} else {


	       		return;
	       		console.log('not on the last topic active');
	       	}

       
						

    });
})(jQuery);