function Statuses() {

    var THAT        = this;

    /*
       Variable: statuses
       JSON data object
    */
    THAT.statuses   = undefined;

    /*
       Variable: gui_widget
       If set, is used in <GUI__update> to call its update_data() method.
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

    THAT.load = function(level, parent_id) {

        $.ajax({
            url:      'ard.php',
            data:     'd=sta&r=lod&n=' + THAT.nonce.nonce + '&t=' + THAT.nonce.timestamp + '&c=' + THAT.context,
            type:     'GET',
			dataType: 'JSON'
        })
        .done(function(data){
            ;   // nothing to do here...
        });
    }

    THAT.GUI__update = function(statuses)
    {
        if ( statuses != undefined ) THAT.statuses = statuses;

        if ( THAT.gui_widget !== undefined ) {
            THAT.gui_widget.update_data(statuses, 'id', 'name');
        }
    }

}
