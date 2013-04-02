function New_Job_GUI() {

    this.$clone_last    = undefined;
    this.$clear         = undefined;
    this.$subject       = undefined;
    this.$description   = undefined;
    this.$category      = undefined;
    this.$issue         = undefined;
    this.$assign_to_me  = undefined;
    this.$open_details  = undefined;

    this.category_selected = undefined;

    this.categories     = undefined;
    this.issues         = undefined;

    this.nonces            = new Object();
    this.nonces.categories = undefined;



    this.clear_form_data = function() {
        this.$subject.val('');
        this.$description.val('');
        this.$assign_to_me.prop('checked', false);
        this.$open_details.prop('checked', false);
        this.$category.val(0);
        this.$category.trigger('change');
        this.$issue.val(0);

        return false;
    }

    this.save_data = function() {
    }

    this.get_categories = function() {
        this.categories.load();
    }

/////// CATEGORIES
    this.set_categories_list = function() {
        var option_el = this.$category.children().eq(0).detach();
        for ( var i = 0 ; i < this.categories.categories.length ; i++ ) {
            category = this.categories.categories[i];
            new_option = $(option_el).clone();
            $(new_option).val(category.id);
            new_option.attr("title", category.description);
            new_option.text(category.name);
            this.$category.append(new_option);
        }
    }
////// end CATEGORIES



// ISSUES
    this.set_issues_list = function() {
        var option_el = this.$issue.children().eq(0).detach();

        if ( this.issues.categories == undefined ) {
            this.$issue.children().remove();
            new_option = $(option_el).clone();
            $(new_option).attr("value", " ");
            $(new_option).attr("title", " ");
            $(new_option).text("");
            this.$issue.append(new_option);
            this.set_issues_status('disabled');
            return;
        };

        this.set_issues_status('enabled');
        this.$issue.children().remove();
        for ( var i = 0 ; i < this.issues.categories.length ; i++ ) {
            issue = this.issues.categories[i];
            new_option = $(option_el).clone();
            $(new_option).attr("value", issue.id);
            $(new_option).attr("title", issue.description);
            $(new_option).text(issue.name);
            this.$issue.append(new_option);
        }
    }

    this.update_issues = function() {
        this.set_issues_status("load");
        this.issues.parent_id = this.$category.val();
        this.issues.load();
    }

    this.set_issues_status = function(status) {
        if ( status === 'disabled' ) {
            if ( this.$issue.attr("disabled") == undefined )
                this.$issue.attr("disabled", "disabled");
            this.$issue_load.fadeOut();
        }
        else
        if ( status === 'enabled' ) {
            if ( this.$issue.attr("disabled") == "disabled" )
                this.$issue.removeAttr("disabled");
            JOM['new_job'].$issue_load.fadeOut();
        }
        else
        if ( status === 'start' ) {
            this.$issue.attr("disabled", "disabled");
            this.$issue_load.hide();
        }
        else
        if ( status === 'load' ) {
             if ( this.$issue.attr("disabled") == undefined )
                this.$issue.attr("disabled", "disabled");
            this.$issue_load.fadeIn();
        }
    }
////// end ISSUES


    this.init_events = function() {
        // CLEAR BUTTON
        this.$clear.unbind().on('click', function(){
            JOM['new_job'].clear_form_data();
        });
        // SELECT CATEGORY
        this.$category.unbind().on('change', function(){
            if ( JOM['new_job'].issues.categories != JOM['new_job'].$category.val() ) {
                JOM['new_job'].update_issues();
            }
        });
    }


    // constructor
        this.$clone_last    = $("#form_new_job [name='clonelast']");
        this.$clear         = $("#form_new_job [name='clear']");
        this.$subject       = $("#form_new_job [name='subject']");
        this.$description   = $("#form_new_job [name='description']");
        this.$category      = $("#form_new_job [name='category']");
        this.$issue         = $("#form_new_job [name='issue']");
        this.$assign_to_me  = $("#form_new_job [name='assign_to_me']");
        this.$open_details  = $("#form_new_job [name='open_details']");
        this.$issue_load    = $("#form_new_job [name='issue']").next();

        this.categories       = new Categories();
        this.categories.level = 1;

        this.issues           = new Categories();
        this.issues.level     = 2;
    // end constructor

}
