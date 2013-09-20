
NONCES = {
    cat_lod: {                      // domain/request
        new_job: {                  // context
            categories: {           // object name
                nonce: false,
                timestamp: false
            },
            issues: {               // object name
                nonce: false,
                timestamp: false
            }
        }
    }
};

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
                     $("#jom_job_list_table").find('[data-toggle="tooltip"]').tooltip({delay: JOM.conf.GUI.tooltip.delay});
                    break;
                }
            case 'usr/lst':
                {
                    JOM.job_list.users_list.users = JSON_response.data;
                    break;
                }
            default:
                alert(domain + '/' + request + " not defined");
                break;

        }

    });


function jom_init(dateformat) {


    // NEW JOB FORM ELEMENTS
    {
        // date and datepicker component in job creation form
        var now_text = jsJOMlib__date_formatted(JOM.conf.dateformat, JOM.conf.date_separator);
        var $input_date = $("#form_new_job [name='creation_date']");
        $input_date.val(now_text);
        $input_date.parent().attr("data-date", now_text);
        $input_date.parent().attr("data-date-format", JOM.conf.dateformat);

        // call datepicker object creation
        $input_date.parent().datepicker({
            weekStart: 0
        })
        .on('changeDate', function(){
           $("#form_new_job [name='creation_date']").parent().datepicker('hide');
           $("#form_new_job [name='creation_date']").trigger('change');
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
            // is job deleted ?
            var is_deleted = $(this).parents('tr').filter(":first").data('job_is_deleted');     // integer value 1 or 0
            if ( is_deleted===undefined ) is_deleted = $(this).parents('tr').filter(":first").prev().data('job_is_deleted');

            // job id
            var id_job = $(this).parents('[class="jom_job_summary"]').data('job_id');
            if ( id_job===null ) id_job = $(this).parents('[class="jom_job_details"]').data('job_id');

            // SHOW/HIDE DETAILS
            if ( $(this).hasClass("jom_show_details_btn") )
            {
                var $tr_infos = $(this).parents('tr').next();

                if ( is_deleted ) {
                    $(this).parents('tr').filter(":first").css('background-color', JOM.conf.GUI.jobs_list.delete_bgcolor);
                }
                else {
                    if ( $tr_infos.is(':hidden') ) {
                        $(this).parents('tr').css("background-color", JOM.conf.GUI.jobs_list.summary_selected_for_details_bgcolor);
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
                    // delete button
                        $(this).attr("data-original-title", "restore job");
                        $(this).removeClass("btn-primary").addClass("btn-warning");
                        $(this).children("i").removeClass("icon-trash").addClass("icon-medkit");
                    // row elements style
                        $(this).parents('tr').filter(":first").find('td').addClass('jom_deleted');
                        $(this).parents('tr').filter(":first").find('td:first-child').removeClass('jom_deleted');
                        $(this).parents('tr').filter(":first").find('td:last-child').removeClass('jom_deleted');
                        $(this).parents('tr').filter(":first").find('td:last-child').children('button:not(".jom_delete_btn")').addClass('jom_deleted');
                        $(this).parents('tr').filter(":first").next().addClass('jom_deleted');
                    // set general row style
                        $(this).parents('tr').filter(":first").find('td').css('padding', '2px 8px 2px 8px');
                        $(this).parents('tr').css('background-color', JOM.conf.GUI.jobs_list.delete_bgcolor);
                    // x-editable elements
                        $(this).parents('tr').find('.x_editable').editable('disable');
                }
                else {
                    // set row contained elements styles
                    // delete button
                        $(this).attr("data-original-title", "delete job");
                        $(this).removeClass("btn-warning").addClass("btn-primary");
                        $(this).children("i").removeClass("icon-medkit").addClass("icon-trash");
                    // row elements style
                        $(this).parents('tr').filter(":first").find('*').removeClass('jom_deleted');
                        $(this).parents('tr').filter(":first").next().removeClass('jom_deleted');
                    // set general row style
                        $(this).parents('tr').filter(":first").find('td').attr('style', '');
                        $(this).parents('tr').attr('style', '');
                    // x-editable elements
                        $(this).parents('tr').find('.x_editable').editable('enable');
                }
                // set deleted data to HTML DOM element
                var new_value_is_deleted = 1 - is_deleted;
                $(this).parents('tr').data('job_is_deleted', new_value_is_deleted);
                // save database data
                JOM.job_list.DATA__update_field(id_job, 'Job_trashed',  new_value_is_deleted);
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

                // set database data
                JOM.job_list.DATA__update_field(id_job, 'Job_is_favourite',  $(this).data('val'));
            }
        });

    // END jobs list elements
}
