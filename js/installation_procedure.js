function go_to_step(step) {
    var actual_step = $("body").data('step');
    actual_step += step;
    $("body").data('step', actual_step);

    switch(actual_step)
    {
        case 0:
            // make unneeded elements disappear and, as a callback, make all next appear
            animate_opacity( $("#jom_rowback"), 0);
            animate_opacity( $("#jom_rowgoon"), 0);
            animate_opacity( $("#fieldset_1"), 0, function(){
                // buttons back/proceed
                animate_opacity( $("#jom_rowgoon"), 1);
                $("#jom_rowgoon").children('div').eq(0).attr("class", "span6 offset3 text-center");
                $("#jom_rowgoon").find('a').contents().first()[0].textContent = 'Proceed';
                $("#jom_rowgoon").find('a').removeClass("btn-success").addClass("btn-primary");
                $("#jom_rowgoon").find('i').attr('class','icon-chevron-right');

                // show fieldset 1
                animate_opacity( $("#fieldset_0"), 1);
            });
            animate_opacity( $("#fieldset_2"), 0, function(){
                // buttons back/proceed
                animate_opacity( $("#jom_rowgoon"), 1);
                $("#jom_rowgoon").children('div').eq(0).attr("class", "span6 offset3 text-center");

                // show fieldset 1
                animate_opacity( $("#fieldset_0"), 1);
            });

            break;
        case 1:
            animate_opacity( $("#jom_rowback"), 0);
            animate_opacity( $("#jom_rowgoon"), 0);
            animate_opacity( $("#fieldset_0"), 0, function(){
                // buttons back/proceed
                animate_opacity( $("#jom_rowback"), 1);
                animate_opacity( $("#jom_rowgoon"), 1);
                $("#jom_rowgoon").find('a').contents().first()[0].textContent = 'Install ';
                $("#jom_rowgoon").find('a').removeClass("btn-primary").addClass("btn-success");
                $("#jom_rowgoon").find('i').attr('class','icon-ok-circle');

                if ( $("#inst_db_type").val() === "MySQL" ) {
                    // show fieldset 1
                    animate_opacity( $("#fieldset_1"), 1);
                    $("#jom_rowgoon").children('div').eq(0).attr("class", "span12 text-center");
                }
                else
                if ( $("#inst_db_type").val() === "SQLite" ) {
                    // show fieldset 2
                    animate_opacity( $("#fieldset_2"), 1);
                }
            });
            break;
        case 2:
            animate_opacity( $("#jom_rowback"), 0);
            animate_opacity( $("#jom_rowgoon"), 0);
            animate_opacity( $("#fieldset_1"), 0, function(){
                animate_opacity( $("#jom_install_feedback_bar"), 1, function(){
                    // setting progress bar to 5%
                    $(".progress > .bar").attr("style","width: 5%");
                    $(".progress > .bar").html("5%");
                    var dummy = setTimeout("run_install_step()", 1000);
                });
            });
            animate_opacity( $("#fieldset_2"), 0);

        default:
            // if this state does not exist, set the step back
            $("body").data('step', --actual_step);
    }

}

function create_new_database_toggled(check) {
    if (check) {
        animate_opacity($("#fieldset_1_b"), 1, function() {
            $("#inst_db_superuser").removeAttr('readonly', true);
            $("#inst_db_superpass").removeAttr('readonly', true);

            $("#inst_db_removeifany").attr('readonly', true);
        });
        animate_opacity($("#inst_db_removeifany").parent().parent(), 0.1);

    }
    else {
        animate_opacity($("#fieldset_1_b"), 0.1, function() {
            $("#inst_db_superuser").attr('readonly', true);
            $("#inst_db_superpass").attr('readonly', true);

            $("#inst_db_removeifany").removeAttr('readonly');
        });
        animate_opacity($("#inst_db_removeifany").parent().parent(), 1);
    }
}

function delete_existing_file_toggled(check) {
    if (check) {
        $("#inst_db_removeifanyfile").attr('readonly', true);
        animate_opacity($("#inst_db_removeifanyfile").parent().parent(), 0.1);
    }
    else {
        $("#inst_db_removeifanyfile").removeAttr('readonly');
        animate_opacity($("#inst_db_removeifanyfile").parent().parent(), 1);
    }
}

function animate_opacity(target, opacity_val, callback)
{
    if ( opacity_val > 0 ) target.show();

    if ( callback===undefined ) {
        target.animate({
            opacity: opacity_val,
        }, 500, function(){
            if ( opacity_val == 0 ) target.hide();
        });
    }
    else {
        target.animate({
            opacity: opacity_val,
        }, 500, function(){
            callback();
            if ( opacity_val == 0 ) target.hide();
        });
    }
}



var $new_blk = undefined;
var $msg_blk = undefined;
var $res_blk = undefined;
var STEP     = undefined;

function run_install_step() {

    var url, data, text_ok, text_err;

    // operating statuses
    var SAVE_CONFIG               = 0;
    // Database operations
    var SQLITE_DB                 = 1;
    var MYSQL_DB                  = 2;
    // tables operations
    var DB_TABLES_CREATE          = 3;
    var DB_TABLES_SOME_DATA       = 4;

    // error statuses
    var ERROR_SAVE_CONFIG         = 100;
    var ERROR_DB_CONNECTION       = 101;
    var ERROR_ON_DELETE_TABLES    = 102;
    var ERROR_ON_CREATE_TABLES    = 103;
    var ERROR_EXAMPLE_DATA_INSERT = 104;

    // step
    if ( STEP === undefined ) STEP = SAVE_CONFIG;


    var $prog_bar = $('#jom_install_feedback_bar').find('[class="bar"]');

//animate_opacity( $("#jom_install_feedback_messages"), 1 );

    $new_blk = $("#jom_install_feedback_messages").clone();
    $new_blk.attr('id', '#jom_install_feedback_messages_step' + STEP );
    $msg_blk  = $new_blk.children().eq(0).children().eq(0);
    $res_blk  = $new_blk.children().eq(1).children().eq(0);

    var inst_db_type             = $('#inst_db_type').val();                                   // MySQL / SQLite
    var inst_db_name             = $('#inst_db_name').val();                                   // MySQL / SQLite

    var flag_db_createdb         = ( $('#inst_db_createdb').is(':checked') ? 1 : 0 );          // MySQL
    var flag_db_deltbl_mysql     = ( $('#inst_db_deliftbl_mysql').is(':checked') ? 1 : 0 );    // MySQL
    var flag_db_delfile          = ( $('#inst_db_delprevfile').is(':checked') ? 1 : 0 );       // SQLite
    var flag_db_deltbl_sqlite    = ( $('#inst_db_deliftbl_sqlite').is(':checked') ? 1 : 0 );   // SQLite

    var inst_db_hostname         = $('#inst_db_hostname').val();                               // MySQL
    var inst_db_username         = $('#inst_db_username').val();                               // MySQL
    var inst_db_password         = $('#inst_db_password').val();                               // MySQL
    var inst_db_tblprefix        = $('#inst_db_tableprepend').val();                           // MySQL / SQLite
    var inst_db_tblprefix_sqlite = $('#inst_db_tableprependfile').val();                       // MySQL / SQLite

    switch (STEP)
    {
////////// 1. SAVE CONFIGURATION
        //    SAVE_CONFIG step: save config.inc.php from template config.inc.template.php
        case SAVE_CONFIG:

            // setting progress bar to 15%
            $(".progress > .bar").attr("style","width: 15%");
            $(".progress > .bar").html("15%");

            // setting message feedback
            $msg_blk.html('<strong>Setting</strong> configuration file');
            $("#jom_msgs_container").append($new_blk);
            animate_opacity($new_blk, 1);
            animate_opacity($msg_blk, 1);

            text_ok  = '<i class="icon-check"></i> <strong>saved</strong>';
            text_err = '<i class="icon-warning-sign" title="#REPLACE_ME#"></i> <strong>not saved</strong>';

            // Ajax call
            url  = './inst/save_config.php';
            if ( inst_db_type === "MySQL") {
                data = 'dbt='  + inst_db_type     + '&dbn='  + inst_db_name     +
                       '&dbh=' + inst_db_hostname + '&dbu='  + inst_db_username +
                       '&dbp=' + inst_db_password + '&tpfx=' + inst_db_tblprefix;
                call_ajax(url, data, text_ok, text_err, function(){
                    STEP = MYSQL_DB;
                    run_install_step();
                });
            }
            else
            if ( inst_db_type === "SQLite") {
                data = 'dbt='  + inst_db_type + '&dbn=' + inst_db_name + '&tpfx=' + inst_db_tblprefix_sqlite;
                call_ajax(url, data, text_ok, text_err, function(){
                    STEP = SQLITE_DB;
                    run_install_step();
                });
            }

            break;
////////// ...END

////////// 2. CHECK/CLEAR/SAVE DATABASE
        //    SQLITE_DB: check and/or create database according to flags
        case SQLITE_DB:
            // setting progress bar to 35%
            $(".progress > .bar").attr("style","width: 35%");
            $(".progress > .bar").html("35%");
            break;
        //    MYSQL_DB: check and/or create database according to flags
        case MYSQL_DB:
            // setting progress bar to 35%
            $(".progress > .bar").attr("style","width: 35%");
            $(".progress > .bar").html("35%");
            break;
////////// ...END
        case 3:
            break;
    }

    return false;
}

function call_ajax(ajx_url, ajx_data, ajx_text_ok, ajx_text_err, callback) {
    var ajx_type = 'GET';

    $.ajax({
        url : ajx_url,
        type : ajx_type,
        dataType : 'json',
        data : ajx_data,
        success : function(r) {
            if ( r == undefined || r.success == undefined ) {
                alert('server script error');
                return false;
            }
            else
            if ( r.success ) {
                // print result
                $res_blk.html(ajx_text_ok);                             // set result block text...
                animate_opacity($res_blk.parent(), 1);                  // ..and show it

                var dummy = setTimeout(function(){callback();}, 1000);
                return true;                // this is the final return TRUE if everything goes right!
            }
            else
            if ( !r.success ) {
                ajx_text_err = ajx_text_err.replace("#REPLACE_ME#", r.err_msg);
                $res_blk.html(ajx_text_err);                            // set result block text...
                $res_blk.removeClass("alert-success", "alert-error");   // ..and show it
                animate_opacity($res_blk.parent(), 1);

                return false;
            }
        },
        error : function(jqxhr, text, error) {
            $res_blk.html(ajx_text_err);
            $res_blk.removeClass("alert-success", "alert-error");
            animate_opacity($res_blk.parent(), 1);

            return false;
        }
    });
}
