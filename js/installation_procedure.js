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
            alert("TODO...");
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
    //else                   target.hide();

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



function run_install_step(step) {

    var url, data, text_ok, text_err;

    switch (step)
    {
        // STEP 0: save config.inc.php from template config.inc.template.php
        case 0:

            $('#inst_fase').text('salvataggio configurazione');

            var inst_db_type     = $('#inst_db_type').val();
            var inst_db_name     = $('#inst_db_name').val();
            var inst_db_hostname = $('#inst_db_hostname').val();
            var inst_db_username = $('#inst_db_username').val();
            var inst_db_password = $('#inst_db_password').val();

            url  = './install/save_config.php';
            data = 'dbtype='  + inst_db_type     + '&dbname=' + inst_db_name     +
                   '&dbhost=' + inst_db_hostname + '&dbuser=' + inst_db_username +
                   '&dbpass=' + inst_db_password;
            text_ok  = 'salvataggio configurazione ok';
            text_err = 'errore salvataggio configurazione';

            call_ajax(url, data, text_ok, text_err, step);

            break;
        // STEP 1: check if database exists; if not, try to create it
        case 1:
            break;
        // STEP 2: drop existing tables and create new one
        case 2:
            break;
        case 3:
            break;
    }

    return false;
}

function call_ajax(ajx_url, ajx_data, ajx_text_ok, ajx_text_err, step) {
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
                $('#inst_fase').text(ajx_text_ok);
                run_install_step(++step);
                return true;                // this is the final return TRUE if everything goes right!
            }
        },
        error : function(jqxhr, text, error) {
            $('#inst_fase').text(ajx_text_err);
            alert('please check error');
            return false;
        }
    });
}
