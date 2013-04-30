
    // Ajax callbacks dispatcher
    $(document).ajaxComplete(function(event, xhr, settings)
    {
        // get request parameters
        var domain  = jsJOMlib__getParameterByName(settings.url, 'd');
        var request = jsJOMlib__getParameterByName(settings.url, 'r');
        // JSON object data
        JSON_response = JSON.parse(xhr.responseText);

        // special responses
        if ( JSON_response.cmd !== undefined ) {
            switch ( JSON_response.cmd ) {
                case "redirect":
                    window.location = JSON_response.url + '?' + JSON_response.querystring;
                    break;
                default:
                    alert("Command " + JSON_response.cmd + " not yet implemented");
                    break;
            }
            return;
        }

        switch ( domain + '/' + request )
        {
            case 'cat/lod':
                {
                    // parse the level
                    var level = jsJOMlib__getParameterByName(settings.url, 'l');
                    if ( parseInt(level, 10) === 1 ) {
                        JOM['new_job'].categories.categories      = JSON_response.data;
                        JOM['new_job'].set_categories_list();
                        JOM['new_job'].categories.nonce.nonce     = JSON_response.new_nonce;
                        JOM['new_job'].categories.nonce.timestamp = JSON_response.new_timestamp;
                    }
                    else
                    if ( parseInt(level, 10) === 2 ) {
                        JOM['new_job'].issues.categories      = JSON_response.data;
                        JOM['new_job'].set_issues_list();
                        JOM['new_job'].issues.nonce.nonce     = JSON_response.new_nonce;
                        JOM['new_job'].issues.nonce.timestamp = JSON_response.new_timestamp;
                    }

                    break;
                }
            case 'job/new':
                {
                    if ( JSON_response.success == true ) {
                        $(".jom_message_saving").fadeOut('normal', function(){
                            $(".jom_message_save_ok").fadeIn();
                        });
                    }
                    else {
                        $(".jom_message_saving").fadeOut('normal', function(){
                            $(".jom_message_save_ko").fadeIn();
                        });
                    }

                    break;
                }
        }



    });


function jom_init(dateformat) {

    var date_spearator = '/';

    // date and datepicker component in job creation form
    var now_text = jsJOMlib__date_formatted(dateformat, date_spearator);
    var $input_date = $("#form_new_job [name='creation_date']");
    $input_date.val(now_text);
    $input_date.parent().attr("data-date", now_text);
    $input_date.parent().attr("data-date-format", dateformat);
    // call datepicker object creation
    $input_date.parent().datepicker({
        weekStart: 0
    })
    .on('changeDate', function(){
           $("#form_new_job [name='creation_date']").parent ().datepicker('hide');
           $("#jom_create_job_modal .modal-body .alert").show();
           $("#jom_create_job_modal .modal-body .alert").slideDown();
    });

    $alert = $("#jom_create_job_modal .modal-body .alert").hide();

    $("#form_new_job").css("margin-bottom", "0");
}
