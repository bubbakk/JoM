
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

                    if ( JSON_response.ctx == 'new_job' )
                    {
                        if ( parseInt(level, 10) === 1 ) {
                            JOM['new_job'].categories.categories      = JSON_response.data;
                            JOM['new_job'].categories.GUI__update(JSON_response.data, "id", "name");
                            JOM['new_job'].categories.nonce.nonce     = JSON_response.new_nonce;
                            JOM['new_job'].categories.nonce.timestamp = JSON_response.new_timestamp;
                        }
                        else
                        if ( parseInt(level, 10) === 2 ) {
                            JOM['new_job'].$issue_load.fadeOut();
                            JOM['new_job'].issues.categories      = JSON_response.data;
                            JOM['new_job'].issues.GUI__update(JSON_response.data, "id", "name");
                            JOM['new_job'].issues.nonce.nonce     = JSON_response.new_nonce;
                            JOM['new_job'].issues.nonce.timestamp = JSON_response.new_timestamp;
                        }
                    }
                    else
                    if ( JSON_response.ctx == 'search_filter' )
                    {
                        if ( parseInt(level, 10) === 1 ) {
                            // update object data and form field too
                            JOM.search_filters.filters.filter_by_category.GUI__update(JSON_response.data);
                            // update nonce
                            JOM.search_filters.filters.filter_by_category.nonce.nonce      = JSON_response.new_nonce;
                            JOM.search_filters.filters.filter_by_category.nonce.timestap   = JSON_response.new_timestamp;
                        }
                        else
                        if ( parseInt(level, 10) === 2 ) {
                            JOM.search_filters.$issue_load.fadeOut();
                            // update object data and form field too
                            JOM.search_filters.filters.filter_by_issue.GUI__update(JSON_response.data);
                            // update nonce
                            JOM.search_filters.filters.filter_by_issue.nonce.nonce          = JSON_response.new_nonce;
                            JOM.search_filters.filters.filter_by_issue.nonce.timestap       = JSON_response.new_timestamp;
                        }
                    }
                    break;
                }
            case 'job/new':
                {
                    if ( JSON_response.success == true ) {
                        JOM.new_job.GUI__set_mode("save_success");
                    }
                    else {
                        $(".jom_message_saving").fadeOut('normal', function(){
                            $(".jom_message_save_ko").fadeIn();
                        });
                    }

                    break;
                }
            case 'job/lst':
                {
                    JOM.job_list.GUI__replace_job_list(JSON_response.data);
                    break;
                }
            case 'sta/lod':
                {
                    // update object data and form field too
                    JOM.search_filters.filters.filter_by_status.GUI__update(JSON_response.data);
                    // update nonce
                    JOM.search_filters.filters.filter_by_status.nonce.nonce      = JSON_response.new_nonce;
                    JOM.search_filters.filters.filter_by_status.nonce.timestap   = JSON_response.new_timestamp;
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

    // details open
    $("#jom_job_list_table tbody").on("click", "tr td button", function( )
    {
        // catch buttons click inside table
        if ( $(this).children().eq(0).hasClass("icon-info-sign") )
        {
            var $tr_infos = $(this).parent().parent().next();

            if ( $tr_infos.is(':hidden') ) {
                $(this).parent().parent().css("background-color", "rgba(240, 240, 240, 0.9)");
            }
            else {
                $(this).parent().parent().css("background-color", "");
            }

            $(this).parent().parent().next().fadeToggle();
        }
        else
        if ( $(this).children().eq(0).hasClass("icon-pencil") ) {
            alert("Edit implementation in Version 0.5");
        }
        else
        if ( $(this).children().eq(0).hasClass("icon-trash") ) {
            alert("Remove job still not implemented");
        }
    });

    // catch img click inside table
    $("#jom_job_list_table tbody").on("click", "tr td img", function()
    {
        if ( $(this).hasClass("jom_favourite") ) {
            if ( $(this).attr('src') == './img/star_disabled.png' ) $(this).attr('src', './img/star.png');
            else
            if ( $(this).attr('src') == './img/star.png' )          $(this).attr('src', './img/star_disabled.png');
        }
    });

    $alert = $("#jom_create_job_modal .modal-body .alert").hide();

    $("#form_new_job").css("margin-bottom", "0");
}
