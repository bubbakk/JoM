function Categories() {

    var THAT = this;

    /*
       Variable: level
       Needed to select right table, level or sublevel
    */
    THAT.level      = undefined;

    /*
       Variable: parent_id
       Needed if want to get parented subcategories
    */
    THAT.parent_id  = undefined;

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
