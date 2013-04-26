function New_Job_GUI() {

    var THAT = this;

    // class useful data
        THAT.dateformat     = undefined;
        THAT.dateseparator  = undefined;

    // jQuery fields pointers
        THAT.$form          = undefined;
        THAT.$subject       = undefined;
        THAT.$description   = undefined;
        THAT.$category      = undefined;
        THAT.$issue         = undefined;
        THAT.$start_date    = undefined;
        THAT.$priority      = undefined;
        THAT.$assign_to_me  = undefined;
        THAT.$open_details  = undefined;
        // buttons
        THAT.$clone_last    = undefined;
        THAT.$clear         = undefined;
        THAT.$save          = undefined;

    // Objects
    THAT.categories     = undefined;
    THAT.issues         = undefined;

    // Data
    THAT.nonce          = new Object();
    THAT.category_selected = undefined;

    /*
       Variable: form_data
       JSON object aimed to store the form job data
    */
    THAT.form_data = new Object();
    THAT.form_data = {
        subject:        undefined,
        description:    undefined,
        category:       undefined,
        issue:          undefined,
        start_date:     undefined,
        priority:       undefined,
        assign_to_me:   undefined,
        open_details:   undefined
    }



/////////////////
// GUI Methods //
/////////////////
{
    /*
       Function: GUI__clear_form_data
       Erase/reset form in a default state
    */
    THAT.GUI__clear_form_data = function()
    {
        THAT.$subject.val('');
        THAT.$description.val('');
        THAT.$category.val(0);
        THAT.$category.trigger('change');
        THAT.$issue.val(0);
        THAT.$priority.find('a[class~="active"]').removeClass("active");
        THAT.$assign_to_me.prop('checked', false);
        THAT.$open_details.prop('checked', false);

        return false;
    }


    /*
       Function: GUI__set_field_alert
       Change the state of the field to inform about an error. Set also an action-callback
       that makes the signal disappear

       Parameters:
         $el - jQuery form element that should change status
    */
    THAT.GUI__set_field_alert = function($el)
    {
        $el.addClass("error");

        // if INPUT...
        if ( $el.find('input').length > 0 ) {
            // ...remove error status on keypress
            $el.unbind('keypress').on('keypress', function(){
                $(this).removeClass("error");
            });
        }
        else
        // if GROUP BUTTON...
        if ( $el.find('a[class="btn"]').length > 0 ) {
            // ...remove error status on keypress
            $el.unbind('click').on('click', function(){
                $(this).removeClass("error");
            });
        }
    }


    /*
       Function: GUI__fields_to_saving_msg
       Fadeout form fields and fadein "saving" message; disable also modal buttons
    */
    THAT.GUI__fields_to_saving_msg = function() {
        THAT.$form.fadeOut('normal', function(){
            JOM.new_job.$form.next(".jom_message").fadeIn();
        });
        THAT.$clear.addClass("disabled");
        THAT.$close.addClass("disabled");
        THAT.$save.addClass("disabled");
    }
}




//////////////////
// DATA Methods //
//////////////////
{
    /*
       Function: DATA_read_and_check_data
       Validate data contained in form fields and assign to <THAT.form_data> property

       Returns:
         true if all checks are ok, false if one fails
    */
    THAT.DATA_read_and_check_data = function()
    {
        var is_all_right = true;

        // SUBJECT
        // can't be empty; can't be only made of spaces
        THAT.form_data.subject      = THAT.$subject.val();
        if ( THAT.form_data.subject == '' || trim(THAT.form_data.subject) == '' ) {
            THAT.GUI__set_field_alert(THAT.$subject.parent().parent());
            is_all_right = false;
        }

        // DESCRIPTION, CATEGORY and ISSUE
        THAT.form_data.description  = THAT.$description.val();
        THAT.form_data.category     = THAT.$category.val();
        THAT.form_data.issue        = THAT.$issue.val();

        // DATE START
        // check date correctness also against format
        THAT.form_data.start_date   = THAT.$start_date.val();
        if ( !jsJOMlib__check_date_string(THAT.form_data.start_date, THAT.dateformat, THAT.dateseparator) ) {
            THAT.GUI__set_field_alert(THAT.$start_date.parent().parent());
            is_all_right = false;
        }

        // PRIORITY
        // one must be selected
        THAT.form_data.priority     = THAT.$priority.find('a[class~="active"]').data("val");
        if ( THAT.form_data.priority === null || THAT.form_data.priority === undefined ) {
            THAT.GUI__set_field_alert(THAT.$priority.parent().parent());
            is_all_right = false;
        }

        // ASSIGN and OPEN
        THAT.form_data.assign_to_me = THAT.$assign_to_me.prop('checked');
        THAT.form_data.open_details = THAT.$open_details.prop('checked');

        return is_all_right;
    }
}


    THAT.save_data = function()
    {
        var fd = JOM.new_job.form_data;

        // convert date in javascript Date object
        var start_date_obj            = jsJOMlib__string_date_to_object(THAT.dateformat, THAT.dateseparator, fd.start_date);
        var start_date_unix_timestamp = Math.floor(start_date_obj.getTime() / 1000) -
                                        start_date_obj.getTimezoneOffset() * 60;        // timezone drift
        var data_field = 'd=job&r=new&n=' + THAT.nonce.nonce                     + '&t='  + THAT.nonce.timestamp               +
                         '&s=' + encodeURIComponent(fd.subject)                  + '&ds=' + encodeURIComponent(fd.description) +
                         '&c=' + fd.category                                     + '&i=' + fd.issue                            +
                         '&sd=' + start_date_unix_timestamp                      + '&p=' + fd.priority                         +
                         '&a=' + (fd.assign_to_me ? 1 : 0)                       + '&o=' + (fd.open_details ? 1 : 0);

        $.ajax({
            url:      'ard.php',
            data:     data_field,
            type:     'GET',
            contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			dataType: 'JSON'
        })
        .done(function(data){
            ;   // nothing to do here...
        });

    }

    THAT.get_categories = function() {
        THAT.categories.load();
    }

/////// CATEGORIES
    THAT.set_categories_list = function() {
        var option_el = THAT.$category.children().eq(0).detach();
        for ( var i = 0 ; i < THAT.categories.categories.length ; i++ ) {
            category = THAT.categories.categories[i];
            new_option = $(option_el).clone();
            $(new_option).val(category.id);
            new_option.attr("title", category.description);
            new_option.text(category.name);
            THAT.$category.append(new_option);
        }
    }
////// end CATEGORIES



// ISSUES
    THAT.set_issues_list = function() {
        var option_el = THAT.$issue.children().eq(0).detach();

        if ( THAT.issues.categories == undefined ) {
            THAT.$issue.children().remove();
            new_option = $(option_el).clone();
            $(new_option).attr("value", " ");
            $(new_option).attr("title", " ");
            $(new_option).text("");
            THAT.$issue.append(new_option);
            THAT.set_issues_status('disabled');
            return;
        };

        THAT.set_issues_status('enabled');
        THAT.$issue.children().remove();
        for ( var i = 0 ; i < THAT.issues.categories.length ; i++ ) {
            issue = THAT.issues.categories[i];
            new_option = $(option_el).clone();
            $(new_option).attr("value", issue.id);
            $(new_option).attr("title", issue.description);
            $(new_option).text(issue.name);
            THAT.$issue.append(new_option);
        }
    }

    THAT.update_issues = function() {
        THAT.set_issues_status("load");
        THAT.issues.parent_id = THAT.$category.val();
        THAT.issues.load();
    }

    THAT.set_issues_status = function(status) {
        if ( status === 'disabled' ) {
            if ( THAT.$issue.attr("disabled") == undefined )
                THAT.$issue.attr("disabled", "disabled");
            THAT.$issue_load.fadeOut();
        }
        else
        if ( status === 'enabled' ) {
            if ( THAT.$issue.attr("disabled") == "disabled" )
                THAT.$issue.removeAttr("disabled");
            JOM['new_job'].$issue_load.fadeOut();
        }
        else
        if ( status === 'start' ) {
            THAT.$issue.attr("disabled", "disabled");
            THAT.$issue_load.hide();
        }
        else
        if ( status === 'load' ) {
             if ( THAT.$issue.attr("disabled") == undefined )
                THAT.$issue.attr("disabled", "disabled");
            THAT.$issue_load.fadeIn();
        }
    }
////// end ISSUES


    THAT.init_events = function() {
        // CLEAR BUTTON
        THAT.$clear.unbind().on('click', function(){
            if ( $(this).hasClass("disabled") ) return;
            JOM['new_job'].GUI__clear_form_data();
        });
        // SELECT CATEGORY
        THAT.$category.unbind().on('change', function(){
            if ( $(this).hasClass("disabled") ) return;
            if ( JOM['new_job'].issues.categories != JOM['new_job'].$category.val() ) {
                JOM['new_job'].update_issues();
            }
        });
        // SAVE BUTTON
        THAT.$save.unbind().on('click', {new_job_obj: THAT}, function(e){
            if ( $(this).hasClass("disabled") ) return;
            // read form data and check
            if ( JOM.new_job.DATA_read_and_check_data() ) {
                // save if allright
                JOM.new_job.GUI__fields_to_saving_msg();
                JOM.new_job.save_data();
            }
        });
    }


    // constructor
        THAT.$form          = $("#form_new_job");
        THAT.$clone_last    = $("#form_new_job [name='clonelast']");
        THAT.$subject       = $("#form_new_job [name='subject']");
        THAT.$description   = $("#form_new_job [name='description']");
        THAT.$category      = $("#form_new_job [name='category']");
        THAT.$issue         = $("#form_new_job [name='issue']");
        THAT.$start_date    = $("#form_new_job [name='creation_date']");
        THAT.$priority      = $("#form_new_job [name='priority']");
        THAT.$assign_to_me  = $("#form_new_job [name='assign_to_me']");
        THAT.$open_details  = $("#form_new_job [name='open_details']");
        THAT.$issue_load    = $("#form_new_job [name='issue']").next();

        THAT.$priority.find('a').eq(0).data("val", "5");
        THAT.$priority.find('a').eq(1).data("val", "10");
        THAT.$priority.find('a').eq(2).data("val", "15");

        THAT.$clear         = $("#jom_create_job_modal").find('.modal-footer').find("[name='clear']");
        THAT.$close         = $("#jom_create_job_modal").find('.modal-footer').find("[name='close']");
        THAT.$save          = $("#jom_create_job_modal").find('.modal-footer').find("[name='save']");

        THAT.categories       = new Categories();
        THAT.categories.level = 1;

        THAT.issues           = new Categories();
        THAT.issues.level     = 2;
    // end constructor

}
