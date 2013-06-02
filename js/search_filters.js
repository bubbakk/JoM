function Search_Filters_GUI() {

    var THAT = this;

    // filter objects set
    THAT.filters = new Object();

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

    THAT.init_filter = function(filter_name, nonce_obj, $jQ_ptr)
    {
        THAT.filters[filter_name].nonce         = nonce_obj;
        THAT.filters[filter_name].jq_pointer    = $jQ_ptr;
        THAT.filters[filter_name].load();
    }

}
