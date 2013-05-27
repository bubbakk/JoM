function Search_Filters_GUI() {

    var THAT = this;

    // filter objects set
    THAT.filters = new Array();

    THAT.create_filters = function(filters_array)
    {
        for ( var i = 0 ; i < filters_array.length ; i++ )
        switch (filters_array[i])
        {
            case 'filter by status':
                THAT.filters['filter by status'] = new Statuses();
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
