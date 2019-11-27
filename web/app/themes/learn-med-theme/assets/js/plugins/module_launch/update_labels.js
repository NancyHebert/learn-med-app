(function($) {

    var pause_update_labels = false;

    $(document).ready(function(){
        // get the config parameters, exit if not found
        if (typeof(window.module_launch) == undefined) {
            return;
        }
        var config = window.module_launch;

        $(window).focus(function(){
            update_labels();
        });

        window.setInterval(function(){
            if (pause_update_labels) { return; }
            update_labels();
        }, 5 * 1000); // 5 seconds

        enable_popovers();
    });

    function update_labels() {
        var current_statuses = {};
        $('.xapi-activity').each(function(){
            if ($(this).data('post-id')) {
                if ($(this).find('.xapi-activity-status').eq(0).data('status')) {
                    current_statuses[$(this).data('post-id')] = $(this).find('.xapi-activity-status').eq(0).data('status');
                }
            }
        });

        pause_update_labels = true;

        $.ajax({
            type: "POST",
            url: window.module_launch.ajax_url,
            data: {
                action: window.module_launch.action.update_labels,
                current_statuses: current_statuses,
            }

        }).done(function(data){

            pause_update_labels = false;

            if (data.updated_labels) {
                for (var module_id in data.updated_labels) {
                    if (!data.updated_labels.hasOwnProperty(module_id)) {
                        continue;
                    }
                    $('.xapi-activity[data-post-id=' + module_id + ']')
                        .eq(0)
                        .find('.xapi-activity-labels')
                        .replaceWith(data.updated_labels[module_id]);
                }

                enable_popovers();
            }
        });
    }

    function enable_popovers() {
        $('.xapi-activity[data-post-id]').each(function() {
            var module_id = $(this).data('post-id');
            $(this).find('[data-toggle="popover"]').popover({
              container: 'body',
              html: true,
              content: function () {
                  return $('<div class="attest-note-confirm-popover " data-post-id="' + module_id + '">' + $("#" + $(this).data('popover-content')).html() + '</div>');
              }
            }).click(function(e) {
              e.preventDefault();
            });

            $(this).find('[data-toggle="popover"]').on('shown.bs.popover', function(e){
                var popover = this;
                $('.attest-note-confirm-popover[data-post-id]').each(function() {
                    var module_id = $(this).data('post-id');
                    $(this).find('.attest-completion').each(function() {
                        if ($(this).hasClass('attest-completion-configured ')) { return; }

                        $(this).click(function(e) {
                            $(this).html('&hellip;');
                            $('.xapi-activity-labels').addClass('has-spinner');

                            e.preventDefault();
                            register_completion_attestation(module_id, function() {
                                $(popover).popover('hide');
                            });
                        });

                        $(this).addClass('attest-completion-configured');
                    });
                });
            });
        });
    }

    function register_completion_attestation(module_id, callback) {
        $.ajax({
            type: "POST",
            url: window.module_launch.ajax_url,
            data: {
                action: window.module_launch.action.register_completion_attestation,
                module_id: module_id,
            }
        }).done(callback);
    }
})(jQuery);
