function New_Job_GUI() {

    this.$clone_last    = undefined;
    this.$clear         = undefined;
    this.$subject       = undefined;
    this.$description   = undefined;
    this.$category      = undefined;
    this.$issue         = undefined;
    this.$assign_to_me  = undefined;
    this.$open_details  = undefined;

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
        this.$issue.val(0);

        return false;
    }



    this.save_data = function() {
    }

    this.get_categories = function() {
        this.categories.load();
    }

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

    this.get_issues = function() {
    }

    this.init_events = function() {
        // CLEAR BUTTOM
        this.$clear.unbind().on('click', function(){
            JOM['new_job'].clear_form_data();
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

        this.categories = new Categories();
        this.categories.level = 1;
        this.issues     = new Categories();
    // end constructor
}
