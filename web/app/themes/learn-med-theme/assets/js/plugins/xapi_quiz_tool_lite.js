(function($) {
    $(document).ready(function(){
        // get the config parameters, exit if not found
        if (typeof(window.xapi_quiz_tool_lite) == undefined) {
            return;
        }
        var config = window.xapi_quiz_tool_lite;

        // on clicking the quiz-tool-lite button, send response via ajax
        $("#questionDiv .checkAnswer").click(function(e){

            var answer = question_id = type = null;

            if (1 == $(this).siblings("textarea").size()) {
                // is a reflectionText question
                answer = $(this).siblings("textarea").first().val();
                question_id = $(this).siblings("textarea").first().attr('id').match(/[1-9][0-9]*/)[0];
            } else if (1 == $(this).parent("#questionDiv").find("input[type=radio]:checked").size()) {
                // is a radio question
                answer = $(this).parent("#questionDiv").find("input[type=radio]:checked").first().val();
                question_id =  $(this).parent("#questionDiv").find("input[type=radio]:checked").first().attr('name').match(/[1-9][0-9]*/)[0];
            } else if (1 <= $(this).parent("#questionDiv").find("input[type=checkbox]:checked").size()) {
                // is a checkbox question
                answer = [];
                $(this).parent("#questionDiv").find("input[type=checkbox]:checked").each(function(i){
                    answer.push($(this).attr('name').match(/[1-9][0-9]*$/)[0]);
                });
                question_id =  $(this).parent("#questionDiv").find("input[type=checkbox]:checked").first().attr('name').match(/[1-9][0-9]*/)[0];
            }
            if (answer && question_id) {
                $.ajax({
                    type: "POST",
                    url: window.xapi_quiz_tool_lite.ajax_url,
                    data: {
                        action: 'xapi_quiz_tool_lite_save_answer',
                        post_id: config.post_id,
                        question_id: question_id,
                        answer: answer
                    }
                });
            }

        });

        $("#questionDiv .checkAnswer").each(function() {

            var $question = $(this).parent("#questionDiv");
            var question_id = get_question_id($question);

            $.ajax({
                url: window.xapi_quiz_tool_lite.ajax_url,
                data: {
                    action: 'xapi_quiz_tool_lite_retrieve_answer',
                    post_id: config.post_id,
                    question_id: question_id
                }
            }).done(function(data){
                if (data.answer) {
                    var question_type = get_question_type($question);

                    if ('reflectionText' == question_type) {
                        $("#refectiveTextBoxID" + data.question_id).val(data.answer);
                        // display the feedback box
                        // $('div#questionDiv span.correct').closest('div').removeClass('closed');
                        // $('div#questionDiv span.correct').closest('div').addClass('opened');
                        // $('div#questionDiv span.correct').closest('div').css('display','');

                        $('div#questionDiv div.correctFeedbackDiv').closest('div').removeClass('closed');
                        $('div#questionDiv div.correctFeedbackDiv').closest('div').addClass('opened');
                        $('div#questionDiv div.opened').parent('div').css('display','block');

                        //ensure that the mark complete and continue button shows when there is an answer
                        if ( $("form#sfwd-mark-complete button").length > 0 ) {

                            console.log('Mark complete does EXIST');
                            $('form#sfwd-mark-complete').show();
                            $('form#sfwd-mark-complete span.theText').show();
                            $('div.continue').hide();

                        } else {

                            console.log('Mark complete does NOT exist');

                            $('form#sfwd-mark-complete').hide();
                            $('form#sfwd-mark-complete span.theText').hide();
                            $('div.continue').show();


                        }


                    } else if ('radio' == question_type) {
                        if (1 <= $("#option" + data.answer).size()) {
                            $("#option" + data.answer).prop('checked', true);
                            // display the feedback box
                            console.log('data.is_correct: ' + (null !== data.is_correct));
                            if (null !== data.is_correct) {
                                if (data.is_correct) {
                                    $('div#questionDiv span.correct').closest('div').removeClass('closed');
                                    $('div#questionDiv span.correct').closest('div').addClass('opened');
                                    $('div#questionDiv span.correct').closest('div').css('display','');



                                } else {
                                    $('div#questionDiv span.incorrect').closest('div').removeClass('closed');
                                    $('div#questionDiv span.incorrect').closest('div').addClass('opened');
                                    $('div#questionDiv span.incorrect').closest('div').css('display','');
                                }

                            }

                            //ensure that the mark complete and continue button shows when there is an answer
                            if ( $("form#sfwd-mark-complete button").length > 0 ) {

                                console.log('Mark complete does EXIST');
                                $('form#sfwd-mark-complete').show();
                                $('form#sfwd-mark-complete span.theText').show();
                                $('div.continue').hide();

                            } else {

                                console.log('Mark complete does NOT exist');

                                $('form#sfwd-mark-complete').hide();
                                $('form#sfwd-mark-complete span.theText').hide();
                                $('div.continue').show();


                            }


                        }
                    } else if ('checkbox' == question_type) {
                        an_answer_matches_a_choice = false
                        for(var i = 0; i < data.answer.length; i++) {
                            if (1 <= $("#option" + data.answer[i]).size()) {
                                $("#option" + data.answer[i]).prop('checked', true);
                                an_answer_matches_a_choice = true;
                            }
                        }
                        if (an_answer_matches_a_choice) {
                            evaluate_answers = $('div#questionDiv .checkAnswer').attr('onclick');
                            eval(evaluate_answers);
                        }
                    }
                }
            });
        });

    });

    function get_question_id($el) {
        var question_type = get_question_type($el);

        if ('reflectionText' == question_type) {
            return $el.find("textarea").first().attr('id').match(/[1-9][0-9]*/)[0];
        } else if ('radio' == question_type) {
            return $el.find("input[type=radio]").first().attr('name').match(/[1-9][0-9]*/)[0];
        } else if ('checkbox' == question_type) {
            return $el.find("input[type=checkbox]").first().attr('name').match(/[1-9][0-9]*/)[0];
        }
    }

    function get_question_type($el) {
        if (1 == $el.find("textarea").size()) {
            return 'reflectionText';
        } else if (1 <= $el.find("input[type=radio]").size()) {
            return 'radio';
        } else if (1 <= $el.find("input[type=checkbox]").size()) {
            return 'checkbox';
        }
    }

})(jQuery);
