function Statuses() {

    var THAT        = this;

    /*
       Variable: statuses
       JSON data object
    */
    THAT.statuses   = undefined;

    /*
       Variable: level
       Element container of the categories (starting with SELECT tag)
    */
    THAT.jq_pointer = undefined;

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

    THAT.GUI__set_statuses_data = function(statuses)
    {
        // detach first option tag
        var option_to_clone = THAT.jq_pointer.children().eq(0);
        // clear all options
        THAT.jq_pointer.children().remove();

        for ( var i = 0 ; i < statuses.length ; i++ ) {
            new_option = option_to_clone.clone();
            new_option.val(statuses[i].id);
            new_option.html(statuses[i].name);
            THAT.jq_pointer.append(new_option);
        }

        if ( THAT.jq_pointer.hasClass("selectpicker") ) {
            THAT.jq_pointer.selectpicker('refresh');
        }
    }

}
