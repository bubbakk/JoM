function Job_List_GUI() {

    var THAT = this;

    THAT.$job_row_summary   = undefined;
    THAT.$job_row_details   = undefined;

    THAT.nonce              = new Object();

    THAT.context            = undefined;

    /*
       Variable: search_filters
         JSON object that contains search filters to apply to load function

        See also:
          <DATA__reset_filters>
    */
    THAT.search_filters     = new Object();

//////////////////
// DATA Methods //
//////////////////
{
    /*
       Function: DATA__reset_filters
         set all search_filters fields to undefined

       See also:
         <DATA__reset_filters>
    */
    DATA__reset_filters = function() {
        THAT.search_filters     = {
            category:       undefined,      // categories
            issue:          undefined,
            start_datetime: undefined,      // dates
            end_datetime:   undefined,
            user:           undefined,      // owner/group
            status:         undefined       // data
        }
    }

    THAT.DATA__load_job_list = function() {

        var data_field = 'd=job&r=lst&n=' + THAT.nonce.nonce + '&t='  + THAT.nonce.timestamp + '&c=' + THAT.context;

        // extended search data
        if ( THAT.search_filters.category       !== undefined ) data_field += '&a=' + THAT.search_filters.category;
        if ( THAT.search_filters.issue          !== undefined ) data_field += '&i=' + THAT.search_filters.issue;
        if ( THAT.search_filters.start_datetime !== undefined ) data_field += '&x=' + THAT.search_filters.start_datetime;
        if ( THAT.search_filters.status         !== undefined ) data_field += '&s=' + THAT.search_filters.status;

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

        // after every search, filters are reset
        DATA__reset_filters();
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
