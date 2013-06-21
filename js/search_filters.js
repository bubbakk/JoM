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
        THAT.filters.filter_by_issue.parent_id = THAT.filters.filter_by_category.gui_widget.jq_pointer.val();
        THAT.filters.filter_by_issue.load();
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
        /*
        var data_field = 'd=job&r=new&n=' + THAT.nonce.nonce                     + '&t='  + THAT.nonce.timestamp               +
                         '&s=' + encodeURIComponent(fd.subject)                  + '&ds=' + encodeURIComponent(fd.description) +
                         '&c=' + fd.category                                     + '&i=' + fd.issue                            +

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
        */
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
        THAT.filters_values.filter_by_status = undefined;
        if ( !THAT.filters.filter_by_status.gui_widget.jq_pointer.prop("disabled") ) {
            THAT.filters_values.filter_by_status = THAT.filters.filter_by_status.gui_widget.jq_pointer.val();
        }

        THAT.filters_values.filter_by_creation_date = undefined;
        if ( !THAT.filters.filter_by_creation_date.gui_widget.jq_pointer.prop("disabled") ) {
            var days_before = parseInt(THAT.filters.filter_by_creation_date.gui_widget.jq_pointer.val(), 10);
            // creating date start
            var _dummy_date_now = new Date();
            var _dummy_date_displaced = new Date();
            _dummy_date_displaced.setDate(_dummy_date_now.getDate() - days_before); // minus the date
            THAT.filters_values.filter_by_creation_date = Math.floor(_dummy_date_now.getTime() / 1000);
        }

        THAT.filters_values.filter_by_category = undefined;
        if ( !THAT.filters.filter_by_category.gui_widget.jq_pointer.prop("disabled") ) {
            THAT.filters_values.filter_by_category = THAT.filters.filter_by_category.gui_widget.jq_pointer.val();
        }

        THAT.filters_values.filter_by_issue = undefined;
        if ( !THAT.filters.filter_by_issue.gui_widget.jq_pointer.prop("disabled") ) {
            THAT.filters_values.filter_by_issue = THAT.filters.filter_by_issue.gui_widget.jq_pointer.val();
        }
    }


    // constructor
        THAT.$issue_load    = $("#jom_filter_by_issue").next();
        THAT.$issue_load.hide();
    // end constructor

}
