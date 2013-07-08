function Users() {

    var THAT        = this;

    /*
       Variable: users
       JSON data object
    */
    THAT.users   = undefined;

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
     * Load statuses data
     */
    THAT.load = function() {

        var request = 'd=usr&r=lst';
        var secure  = 'n=' + THAT.nonce.nonce + '&t=' + THAT.nonce.timestamp;

        $.ajax({
            url:      'ard.php',
            data:     request + '&' + secure + '&c=' + THAT.context,
            type:     'GET',
			dataType: 'JSON'
        })
        .done(function(data){
            ;   // nothing to do here...
        });
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
     *  statuses - Contain JSON data array. Passing this parameter is a shortcut to set property and call this method. If
     *             not passed, the property <THAT.statuses> is used.
     */
    THAT.GUI__update = function(statuses)
    {
        if ( statuses != undefined ) THAT.statuses = statuses;

        if ( THAT.gui_widget !== undefined ) {
            THAT.gui_widget.update_data(THAT.statuses, 'id', 'name');
        }
    }
}
///////////////// END GUI METHODS

}
