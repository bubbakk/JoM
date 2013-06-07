function Search_Filters_GUI() {

    var THAT = this;

    // filter objects set
    THAT.filters = new Object();

    THAT.$issue_load = undefined;

    THAT.create_filters = function(filters_array)
    {
        for ( var i = 0 ; i < filters_array.length ; i++ )
        switch (filters_array[i])
        {
            case 'filter_by_status':
                THAT.filters.filter_by_status = new Statuses();
                break;
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

    THAT.init_category_events = function() {
        THAT.filters.filter_by_category.gui_widget.jq_pointer.unbind().on('change', function(){
            if ( $(this).hasClass("disabled") ) return;
            JOM.search_filters.update_issues();
        });
    }

    THAT.update_issues = function() {
        THAT.$issue_load.fadeIn();
        THAT.filters.filter_by_issue.parent_id = THAT.filters.filter_by_category.gui_widget.jq_pointer.val();
        THAT.filters.filter_by_issue.load();
    }

    THAT.init_filter = function(filter_name, nonce_obj, $jQ_ptr)
    {
        THAT.filters[filter_name].nonce         = nonce_obj;
        THAT.filters[filter_name].jq_pointer    = $jQ_ptr;
        THAT.filters[filter_name].load();
    }

    // constructor
        THAT.$issue_load    = $("#jom_filter_by_issue").next();
        THAT.$issue_load.hide();
    // end constructor

}
