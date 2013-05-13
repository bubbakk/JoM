function Job_List_GUI() {

    var THAT = this;

    THAT.nonce          = new Object();


    THAT.load_job_list = function() {

        var data_field = 'd=job&r=lst&n=' + THAT.nonce.nonce + '&t='  + THAT.nonce.timestamp;

        $.ajax({
            url:      'ard.php',
            data:     data_field,
            type:     'GET',
            contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			dataType: 'JSON'
        })
        .done(function(data){
            ;   // nothing to do here...
        });
    }

    // constructor

    // end constructor

}
