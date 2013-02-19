<?php

define('DIR_BASE', '../');
require_once(DIR_BASE.'cfg/config.php');
require_once(DIR_BASE.'/user_config.php');
require_once(DIR_LIB.'generic_lib.php');

$retval['success'] = false;

// reading input parameters

// DBTYPE
if ( post_or_get('dbt')===false) {
    $retval['err_msg'] = 'Missing parameter';
    $retval['dbg_msg'] = 'Missing dbt parameter';
    json_output_and_die($retval);
}
$dbtype = strtolower( post_or_get('dbt') );
if ( $dbtype!='mysql' && $dbtype!='sqlite' ) {
    $retval['err_msg'] = 'Wrong parameter value';
    $retval['dbg_msg'] = 'Parameter dbtype should be a valid value (by now, only mysql or sqlite)';
    json_output_and_die($retval);
}



// SQLITE
if ( $config['DB']['type']==='sqlite' ) {

    // read flag "delete old database" if exist
    $flag_delete_old_db = post_or_get('def');
    if ( $flag_delete_old_db === false ) {
        $retval['err_msg'] = 'Missing parameter';
        $retval['dbg_msg'] = 'Missing def parameter';
        json_output_and_die($retval);
    }
    // read flag "delete tables" if database exists
    $flag_delete_tables = post_or_get('ctbl');
    if ( $flag_delete_tables === false ) {
        $retval['err_msg'] = 'Missing parameter';
        $retval['dbg_msg'] = 'Missing ctbl parameter';
        json_output_and_die($retval);
    }

    // check if file exist
    $file_to_check = DIR_DBSQLT.$config['DB']['sqlite']['filename'];

    // have to delete a previous existing database
    if ( $flag_delete_old_db ) {
    }

    // At this point, open existing SQLite database. If does not exist, it will be created


    // once opened, check if have to clear tables
    if ( $flag_delete_tables ) {
    }

}

$retval['success'] = true;
json_output_and_die($retval);
