/*
   Class: Table_Data_Structure
   This class simplifies JSON data table selection/filtering
*/
function JOM__Table_Data_Structure() {

    var THAT = this;
    THAT.dependencies_error = false;

    // dependecies check
    if ( !(typeof(jsJOMlib__isNumber)==="function") ) {
        console.error("[JOM Debug] - This function depends on jsJOMlib__isNumber() function. Please include it." );
        THAT.dependencies_error = true;
        return false;
    }
    if ( !(typeof(jQuery)==="function") ) {
        console.error("[JOM Debug] - This function depends on jQuery library. Please include it." );
        THAT.dependencies_error = true;
        return false;
    }
    // end dependecies check





    /*
       Variable: filters
       Array of Filter JSON Objects

       See:
         <Filter>
    */
    var filters = new Array();

    /*
       Variable: data_table
       Unfiltered data table source
    */
    var data_table = new Array();

    /*
       Class: Filter
       Parenting data structure, needed to filter results
    */
    function Filter() {
        var THAT = this;
        THAT.filter_field_name = undefined;
        THAT.filter_value      = undefined;
    }




    /*
       Function: assign_json_data
       Duplicate passed array of JSON objects and assign it to private data_table variable. Checks are
       performed to ensure that data type if correct.
    */
    THAT.assign_json_data = function(data_obj)
    {
        // basic data type checks
        if ( typeof(data_obj) !== "object" )                                     return false;
        if ( Object.prototype.toString.call(data_obj) !== "[object Array]" )     return false;
        if ( data_obj.length === 0 )                                             return false;

        // check every array entry
        try {
            for (var i = 0 ; i < data_obj.length ; i++ ) {
                if ( typeof(data_obj[i]) !== "object" ) {
                    THAT.reset_data();
                    return false;
                }
                response = jQuery.parseJSON(JSON.stringify(data_obj[i]));
            }
        }
        catch(e) {
            THAT.reset_data();
            return false;
        }
        if (response === false) return false;

        // add to data structure
        THAT.reset_data();
        for (var i = 0 ; i < data_obj.length ; i++ ) {
            response = jQuery.parseJSON(JSON.stringify(data_obj[i]));
            data_table.push(response);
        }

        return true;
    }

    /*
       Function: add_filter
       Add a filter field name and value

       Parameters:
        fieldname - name of JSON field the fieldvalue is applyed. Should be one of
                    THAT.data_table fields
        fieldvalue - value associated to fieldname. Only strings and numbers accepted
    */
    THAT.add_filter = function(fieldname, fieldvalue)
    {
        // basic checks
        if ( typeof(fieldname)!=="string" ) return false;
        if ( typeof(fieldvalue)!=="string" && typeof(fieldvalue)!=="number" ) return false;

        filter = new Filter();
        filter.filter_field_name = fieldname;
        filter.filter_value      = fieldvalue;

        filters.push(filter);

        return true;
    }

    /*
       Function: get_filtered_data
       Return a data set according to filters set. If no filters are set, the method
       returns (obviousely) the entire (unfiltered) data_table array.

       Returns:
         false - if data_table is not set
         data_table filtered array according to filters set

       Parameters:
        return_fields - array of data fields to return
    */
    THAT.get_filtered_data = function(filter_idx, retval)
    {
        // minimal checks
        if ( data_table === undefined ||
             data_table.length == 0      ) return false;
        if ( filters.length === 0)         return data_table;

        // push full data_table array into retval
        if ( filter_idx === undefined ) var filter_idx = 0;
        if ( retval     === undefined ) {
            retval = new Array();
            for ( var i = 0 ; i < data_table.length ; i++ ) {
                retval.push(data_table[i]);
            }
        }

        // if filter[idx] does not exist, return current retval array of values
        if ( filters[filter_idx] === undefined ) {
            return retval;
        }

        // recursive call
        retval = THAT.get_filtered_data(filter_idx + 1, retval);

        for ( var i = retval.length - 1 ; i >= 0 ; i-- ) {
            f_name = filters[filter_idx].filter_field_name;
            f_val  = filters[filter_idx].filter_value;
            if ( retval[i].hasOwnProperty(f_name) ) {
                if ( retval[i][f_name] != f_val ) {
                    retval.splice(i,1);
                }
            }
        }

        return retval;
    }

    /*
       Function: reset_filters
       Empty filters array
    */
    THAT.reset_filters = function()
    {
        if ( filters.length > 0 ) {
            filters.splice(0, filters.length);
        }
    }

    /*
       Function: reset_data
       Empty data array
    */
    THAT.reset_data = function()
    {
        if ( data_table.length > 0 ) {
            data_table.splice(0, data_table.length);
        }
    }


    // constructor
        ;   // nothing do to here
    // end constructor
}
