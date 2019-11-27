(function($) {
    $(document).ready(function(){
        // get the config parameters, exit if not found
        if (typeof(window.module_launch) == undefined) {
            return;
        }

        track_clicks_external_module_links();
    });

    function track_clicks_external_module_links() {
        $('.xapi-activity[data-post-id]').each(function() {
            var module_id = $(this).data('post-id');
            $(this).find('.grassblade_launch_link.register_click').click(function(e) {

                register_click(module_id);

                if ($(this).attr('target') != '_blank') {
                    var location = $(this).attr('href');
                    e.preventDefault();
                    setTimeout(function () {
                      window.location = location;
                    }, 500);
                }
            });
        });
    }

    function register_click(module_id) {
        $.ajax({
            type: "POST",
            url: window.module_launch.ajax_url,
            data: {
                action: window.module_launch.action.register_click,
                module_id: module_id,
            }
        });
    }
})(jQuery);
