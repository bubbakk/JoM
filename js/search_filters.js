function Search_Filters_GUI() {

    var THAT = this;

    /*
     * Variable: filters
     *   JSON object containing all filter instances
     *
     * See:
     *   <create_filters>
     */
    THAT.filters = new Object();

    /*
       Variable: filters_values
       Contains filters values. If fields are set to undefined, the filter is considered to be disabled.
       *Warning*: the structure must be updated "manually".

       See also:
         <update_filters_values>
     */
    THAT.filters_values = new Object();

    THAT.search_button = undefined;

    THAT.$issue_load = undefined;

    /*
     * Function: create_filters
     *   create instances for requested filters
     *
     * Parameters:
     *   filters_array - array that holds strings of all filter to be initialized
     */
    THAT.create_filters = function(filters_array)
    {
        for ( var i = 0 ; i < filters_array.length ; i++ )
        switch (filters_array[i])
        {
            case 'filter_by_status':
                THAT.filters.filter_by_status = new Statuses();
                break;
            case 'filter_by_creation_date':
                THAT.filters.filter_by_creation_date = {
                    gui_widget: undefined,
                    context:    undefined
                }
            case 'filter_by_category':
                THAT.filters.filter_by_category = new Categories();
                THAT.filters.filter_by_category.level = 1;
                break;
            case 'filter_by_issue':
                THAT.filters.filter_by_issue = new Categories();
                THAT.filters.filter_by_issue.level = 2;
                break;
            default:
                console.warn('Filter not yet implemented');
                break;
        }
    }

    /*
     * Function: init_category_events
     */
    THAT.init_category_events = function() {
        THAT.filters.filter_by_category.gui_widget.jq_pointer.unbind().on('change', function(){
            if ( $(this).hasClass("disabled") ) return;
            JOM.search_filters.update_issues();
        });
    }

    /*
     * Function: update_issues
     */
    THAT.update_issues = function() {
        THAT.$issue_load.fadeIn();

        THAT.filters.filter_by_issue.reset_filters();
        THAT.filters.filter_by_issue.set_filter("id_category_1", THAT.filters.filter_by_category.gui_widget.jq_pointer.val());
        THAT.filters.filter_by_issue.GUI__update(undefined, "id", "name");

        // set enabled/disabled status according to its related selector
        var disable = !$(".jom_enable_control[data-apply-to=\"jom_filter_by_issue\"]").prop("checked");
        $("#jom_filter_by_issue").prop("disabled", disable);
        $("#jom_filter_by_issue").selectpicker('refresh');

        THAT.$issue_load.fadeOut();
    }

    /*
     * Function: init_filter
     *   Call the load() function of filter object
     *
     * Parameters:
     *   filter_name: name of the filter wanted to be initialized
     *   nonce_obj: nonce JSON object, needed for Ajax requests
     *   $jQ_ptr: jQuery pointer to the HTML widget
     *
     *  TODO:
     *   - implement checks (eg: load function can not exist, $jQ_ptr can have length!=1, ...
     */
    THAT.init_filter = function(filter_name, nonce_obj, $jQ_ptr)
    {
        THAT.filters[filter_name].nonce         = nonce_obj;
        THAT.filters[filter_name].jq_pointer    = $jQ_ptr;
        THAT.filters[filter_name].load();
    }


    /*
     * Function: init_search
     * Initialize search button events
     *
     * Parameters:
     *   $jQ_ptr - jquery button pointer
     *   $jQ_ptr - jquery button pointer
     */
    THAT.init_search = function($jQ_ptr) {
        if ( $jQ_ptr.length == 1 ) {
            THAT.search_button = $jQ_ptr;
            THAT.search_button.on('click', function(){
                JOM.search_filters.do_search();
            });
        }
        else {
            THAT.search_button = undefined;
        }
    }

    THAT.do_search = function()
    {
        THAT.update_filters_values();

        // setting job_list search filters
        // category
        if ( THAT.filters_values.filter_by_category !== undefined )
            JOM.job_list.search_filters.category = parseInt( THAT.filters_values.filter_by_category, 10);
        // issue
        if ( THAT.filters_values.filter_by_issue !== undefined )
            JOM.job_list.search_filters.issue = parseInt( THAT.filters_values.filter_by_issue, 10);
        // status
        if ( THAT.filters_values.filter_by_status !== undefined )
            JOM.job_list.search_filters.status = parseInt( THAT.filters_values.filter_by_status, 10);
        // start date
        if ( THAT.filters_values.filter_by_creation_date !== undefined ) {
            // creating date start
            var _dummy_date_now       = new Date();
            var _dummy_date_displaced = new Date();
            _dummy_date_displaced.setDate(_dummy_date_now.getDate() - parseInt(THAT.filters_values.filter_by_creation_date) );
            var date_start = Math.floor(_dummy_date_displaced.getTime() / 1000);
            JOM.job_list.search_filters.start_datetime = date_start;
        }

        // load list
        JOM.job_list.DATA__load_job_list();

    }

    /*
       Fucnction: update_filters_values
       Read form fields values and populate <filters_values> public JSON data structure property. If form field
       is disabled, the corresponding value is set to undefined

       See also:
         <filters_values>
    */
    THAT.update_filters_values = function()
    {
        var keys = Object.keys(THAT.filters);
        for ( var i = 0 ; i < keys.length ; i++ ) {
            key = keys[i];
            THAT.filters_values[key] = undefined;
            if ( !THAT.filters[key].gui_widget.jq_pointer.prop("disabled") ) {
                THAT.filters_values[key] = THAT.filters[key].gui_widget.jq_pointer.val();
            }
        }
    }


    // constructor
        THAT.$issue_load    = $("#jom_filter_by_issue").next();
        THAT.$issue_load.hide();
    // end constructor

}
