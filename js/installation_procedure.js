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