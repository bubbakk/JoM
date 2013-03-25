function New_Job_GUI() {

    this.$clone_last    = undefined;
    this.$clear         = undefined;
    this.$subject       = undefined;
    this.$description   = undefined;
    this.$category      = undefined;
    this.$issue         = undefined;
    this.$assign_to_me  = undefined;
    this.$open_details  = undefined;

    this.clear_form_data = function() {
        this.$subject.val('');
        this.$description.val('');
        this.$assign_to_me.prop('checked', false);
        this.$open_details.prop('checked', false);
        this.$category.val(0);
        this.$issue.val(0);

        return false;
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


    // end constructor
}
