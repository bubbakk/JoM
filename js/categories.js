function Categories(level) {

    var THAT = this;

    /*
     * Variable: data_table
     * Class instance of table data structure management
     */
    THAT.data_table_obj = new JOM__Table_Data_Structure();

    /*
       Variable: gui_widget
       The form field associated
    */
    THAT.gui_widget = undefined;




/****************
 * DATA METHODS *
 ***************/
{
    /*
       Function: assign_data
       Assign table data
    */
    THAT.assign_data = function(data) {
        THAT.data_table_obj.assign_json_data(data);
    }

    THAT.reset_filters = function() {
        THAT.data_table_obj.reset_filters();
    }

    THAT.set_filter = function(fieldname, fieldvalue) {
        return THAT.data_table_obj.add_filter(fieldname, fieldvalue);
    }

    THAT.get_data = function() {
        return THAT.data_table_obj.get_filtered_data();
    }
}




/***************
 * GUI METHODS *
 **************/
{
    /*
     * Function: GUI__update
     * If exists, set the associated widget (generally form field) data
     *
     * Parameters:
     *  categories - Contain JSON data array. Passing this parameter is a shortcut to set property and call this method. If
     *             not passed, the property <THAT.categories> is used.
     */
    THAT.GUI__update = function(categories, value_field, text_field)
    {
        if ( categories !== undefined )THAT.assign_data(categories);

        if ( THAT.gui_widget !== undefined ) {
            THAT.gui_widget.update_data(THAT.get_data(), value_field, text_field);
        }
    }
}



}




function Categories_old() {

    var THAT = this;

    /*
       Variable: level
       Level of data parenting. Level 0 is the first
    */
    THAT.level      = undefined;

    /*
       Variable: parent_id
       Needed if want to get parented subcategories
    */
    THAT.parent_id  = new Array();

    /*
       Variable: categories
       JSON data object
    */
    THAT.categories = undefined;

    /*
       Variable: gui_widget
       The form field associated
    */
    THAT.gui_widget = undefined;

    /*
       Variable: nonce
       JSON object that contains nonce and timestamp fields
    */
    THAT.nonce      = new Object();

    /*
     * Variable: context
     * String value representing object component (is sent via Ajax call and returned back without changes)
     */
    THAT.context    = undefined;



/****************
 * DATA METHODS *
 ***************/
{
    /*
     * Function: load
     * Load categories data
     *
     * Parameters:
     *   level - category level filter. If not passed or undefined, is set to 1 (is the category table to query)
     *   parent_id - if specified, and query level > 1, specifies the id filter (needed for sub-categories)
     */
    THAT.load = function(level, parent_id) {

        var req = '';
        if ( THAT.level     === undefined ) THAT.level = 1;

        if ( THAT.parent_id === undefined ) parent_qs = ''
        else                                parent_qs = '&p=' + THAT.parent_id;

        // set nonce property
        if ( level == 1 ) THAT.nonce = NONCES.cat_lod[THAT.context].categories;
        else
        if ( level == 2 ) THAT.nonce = NONCES.cat_lod[THAT.context].issues;

        $.ajax({
            url:      'ard.php',
            data:     'd=cat&r=lod&n=' + THAT.nonce.nonce + '&t=' + THAT.nonce.timestamp + '&c=' + THAT.context +
                      '&l=' + THAT.level + parent_qs,
            type:     'GET',
			dataType: 'JSON'
        })
        .done(function(data){
            ;   // nothing to do here...
        });
    }

    THAT.add = function(level, parent_level, subject, description) {
    }

    THAT.remove = function(level, id) {
    }

    THAT.apply_changes_to_server = function(level, id) {
    }

    THAT.filter_by_level = function(level){}
}
///////////////// END DATA METHODS



/***************
 * GUI METHODS *
 **************/
{
    /*
     * Function: GUI__update
     * If exists, set the associated widget (generally form field) data
     *
     * Parameters:
     *  categories - Contain JSON data array. Passing this parameter is a shortcut to set property and call this method. If
     *             not passed, the property <THAT.categories> is used.
     */
    THAT.GUI__update = function(categories)
    {
        /*if ( categories != undefined ) */ THAT.categories = categories;

        if ( THAT.gui_widget !== undefined ) {
            THAT.gui_widget.update_data(THAT.categories, 'id', 'name');
        }
    }
}
///////////////// END GUI METHODS

}
