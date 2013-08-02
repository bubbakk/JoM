
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
                            // set enabled/disabled status according to its related selector
                            var disable = !$(".jom_enable_control[data-apply-to=\"jom_filter_by_issue\"]").prop("checked");
                            $("#jom_filter_by_issue").prop("disabled", disable);
                            $("#jom_filter_by_issue").selectpicker('refresh');
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
                        JOM.runtime_data.new_job_saved_successfully = true;
                    }
                    else {
                        $(".jom_message_saving").fadeOut('normal', function(){
                            $(".jom_message_save_ko").fadeIn();
                            JOM.runtime_data.new_job_saved_successfully = false;
                        });
                    }

                    break;
                }
            case 'job/lst':
                {
                    JOM.job_list.GUI__replace_job_list(JSON_response.data);
                    break;
                }
            case 'usr/lst':
                {
                    JOM.job_list.users_list.users = JSON_response.data;
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

    // NEW JOB FORM ELEMENTS
    {
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
           $("#form_new_job [name='creation_date']").parent().datepicker('hide');
        });

        // hide alert label
        $alert = $("#jom_create_job_modal .modal-body .alert").hide();
        $("#jom_create_job_modal").on('hide', function() {
            if ( JOM.runtime_data.new_job_saved_successfully ) {
                JOM.job_list.DATA__load_job_list();
                JOM.runtime_data.job_saved_successfully = false;
            }
        });

        // set modal margin style
        $("#form_new_job").css("margin-bottom", "0");


    }
    // END new job form elements


    // SEARCH FILTERS
    {
        // date picker: start
        //~ var $input_date_filter = $("#jom_filter_by_date_start");
        //~ $input_date_filter.val(now_text);
        //~ $input_date_filter.parent().attr("data-date", now_text);
        //~ $input_date_filter.parent().attr("data-date-format", dateformat);
//~
        //~ $input_date_filter.parent().datepicker({
            //~ weekStart: 0
        //~ })
        //~ .on('changeDate', function(){
            //~ $("#jom_filter_by_date_start").parent().datepicker('hide');
        //~ });

        // date picker: end
        //~ var $input_date_filter = $("#jom_filter_by_date_end");
        //~ $input_date_filter.val(now_text);
        //~ $input_date_filter.parent().attr("data-date", now_text);
        //~ $input_date_filter.parent().attr("data-date-format", dateformat);
//~
        //~ $input_date_filter.parent().datepicker({
            //~ weekStart: 0
        //~ })
        //~ .on('changeDate', function(){
            //~ $("#jom_filter_by_date_end").parent().datepicker('hide');
        //~ });
    }
    // END search filters

    // JOBS LIST ELEMENTS
        // catch click event in the jobs table cells containing elements having 'jom_click_event' class
        $("#jom_job_list_table tbody").on("click", "tr td .jom_click_event", function(event)
        {
            var is_deleted = $(this).parents('tr').filter(":first").data('job_is_deleted');
            if ( is_deleted===undefined ) is_deleted = $(this).parents('tr').filter(":first").prev().data('job_is_deleted');

            // SHOW/HIDE DETAILS
            if ( $(this).hasClass("jom_show_details_btn") )
            {
                var $tr_infos = $(this).parents('tr').next();

                if ( is_deleted ) {
                    $(this).parents('tr').filter(":first").css('background-color', 'rgba(182, 202, 222, 0.3)');
                }
                else {
                    if ( $tr_infos.is(':hidden') ) {
                        $(this).parents('tr').css("background-color", "rgba(240, 240, 240, 0.9)");
                    }
                    else {
                        $(this).parents('tr').css("background-color", "");
                    }
                }

                $tr_infos.fadeToggle();
            }
            else
            // EDIT
            if ( $(this).hasClass("jom_edit_btn") && !is_deleted ) {
                alert("Edit implementation in Version 0.5");
            }
            else
            // DELETE/UNDELETE
            if ( $(this).hasClass("jom_delete_btn") )
            {
                if ( !is_deleted ) {
                    // set row contained elements styles
                    $(this).parents('tr').filter(":first").find('td').addClass('jom_deleted');
                    $(this).parents('tr').filter(":first").find('td:last-child').removeClass('jom_deleted');
                    $(this).parents('tr').filter(":first").find('td:last-child').children('button:not(".jom_delete_btn")').addClass('jom_deleted');
                    $(this).parents('tr').filter(":first").next().addClass('jom_deleted');
                    // set row style
                    $(this).parents('tr').filter(":first").find('td').css('padding', '2px 8px 2px 8px');
                    $(this).parents('tr').css('background-color', 'rgba(182, 202, 222, 0.3)');
                }
                else {
                    // set row contained elements styles
                    $(this).parents('tr').filter(":first").find('*').removeClass('jom_deleted');
                    $(this).parents('tr').filter(":first").next().removeClass('jom_deleted');
                    // set row style
                    $(this).parents('tr').filter(":first").find('td').attr('style', '');
                    $(this).parents('tr').attr('style', '');
                }
                // set deleted data
                $(this).parents('tr').data('job_is_deleted', 1 - is_deleted);
                //JOM.job_list.DATA__update_field(id_job, 'Job_trashed',  $(this).data('val'));
            }
            else
            // FAVOURITE
            if ( $(this).hasClass("jom_favourite") && !is_deleted )
            {
                var new_favourite_value = undefined;

                if ( $(this).data('val') == 1 ) {
                    $(this).attr('src', JOM.job_list.favourite_icons.not_favourite);
                    $(this).data('val', 0);
                }
                else
                if ( $(this).data('val') == 0 ) {
                    $(this).attr('src', JOM.job_list.favourite_icons.favourite);
                    $(this).data('val', 1);
                }
                var id_job = $(this).parent().parents('[class="jom_job_details"]').data('job_id');
                JOM.job_list.DATA__update_field(id_job, 'Job_is_favourite',  $(this).data('val'));
            }
        });

    // END jobs list elements
}
