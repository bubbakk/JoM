function JoB() {

    var THAT = this;

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

    THAT.update_field = function(id, fieldname, new_value, callback)
    {

        var request = 'd=job&r=upd';
        var data    = 'i=' + id + '&f=' + fieldname + '&v=' + new_value;
        var secure  = 'n=' + THAT.nonce.nonce + '&t=' + THAT.nonce.timestamp;

        $.ajax({
            url:      'ard.php',
            data:     request + '&' + data + '&' + secure + '&c=' + THAT.context,
            type:     'GET',
			dataType: 'JSON'
        })
        .done(function(data){
            ;   // nothing to do here...
        });

        if ( callback !== undefined ) {
            callback();
        }

    }
}
