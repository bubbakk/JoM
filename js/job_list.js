function Job_List_GUI() {

    var THAT = this;

    THAT.$job_row_summary   = undefined;
    THAT.$job_row_details   = undefined;

    THAT.nonce              = new Object();

//////////////////
// DATA Methods //
//////////////////
{
    THAT.DATA__load_job_list = function() {

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
}


/////////////////
// GUI Methods //
/////////////////
{
    THAT.GUI__replace_job_list = function(job_list) {

        var $new_summary = undefined;
        var $summary_els = THAT.$job_row_summary.find('td');
        var $details_els = THAT.$job_row_details.find('dd');

        THAT.$job_table_list.children().remove();

        for ( var i = 0 ; i < job_list.length ; i++ ) {

            // setting summary row
            $summary_els.eq(1).text("#" + job_list[i].id);
            $summary_els.eq(2).text(job_list[i].subject);
            $summary_els.eq(3).text(job_list[i].owner);

            $new_summary = THAT.$job_row_summary.clone();
            $new_summary.attr("id", "sum_row_" + i);
            THAT.$job_table_list.append($new_summary);

            // setting details
            $details_els.eq(0).text(job_list[i].status);
            $details_els.eq(1).text(job_list[i].description);
            $details_els.eq(2).text(
                jsJOMlib__date_formatted(
                    JOM.conf.dateformat_human,
                    JOM.conf.dateseparator_human,
                    new Date( job_list[i].started * 1000 )
                    )
                );
            $details_els.eq(3).text(job_list[i].category);
            $details_els.eq(4).text(job_list[i].issue);

            $new_details = THAT.$job_row_details.clone();
            $new_summary.attr("id", "det_row_" + i);
            THAT.$job_table_list.append($new_details);


        }
    }
}


    // constructor
        THAT.$job_table_list  = $("#jom_job_list_table > tbody");
        THAT.$job_row_summary = $("#jom_job_row_summary").detach();
        THAT.$job_row_details = $("#jom_job_row_details").detach();

    // end constructor

}
