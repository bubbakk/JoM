<?php

define('DIR_BASE', '../');
require_once(DIR_BASE.'cfg/user_config.php');
require_once(DIR_BASE.'cfg/config.php');
require_once(DIR_LIB.'generic_lib.php');

require_once(DIR_OOL.'bbkk_base_class.php');
require_once(DIR_OOL.'bbkk_pdo.php');

$retval['success'] = false;

// reading input parameters


// SQLITE
if ( $config['DB']['type']==='sqlite' ) {

    // read flag "delete old database" if exist
    $flag_delete_old_db = post_or_get('def');
    if ( $flag_delete_old_db === false ) {
        $retval['err_msg'] = 'Missing parameter';
        $retval['dbg_msg'] = 'Missing def parameter';
        json_output_and_die($retval);
    }

    // check if file exist
    $sqlite_db_file = DIR_DBSQLT.$config['DB']['sqlite']['filename'];

    // have to delete a previous existing database
    if ( $flag_delete_old_db ) {
        if ( is_file($sqlite_db_file) ) {
            if ( !is_writable($sqlite_db_file) ) {
                $retval['err_msg'] = 'Can\'t delete old sqlite database';
                $retval['dbg_msg'] = 'The file '.$sqlite_db_file.' exists but is not writable';
                json_output_and_die($retval);
            }
            else {
                if ( !unlink($sqlite_db_file) ) {
                    $retval['err_msg'] = 'Can\'t delete old sqlite database. Probably the directory is not writable';
                    $retval['dbg_msg'] = 'Can\'t unlink '.$sqlite_db_file.' (even if exists and is writable). Directory may not have write permissions';
                    json_output_and_die($retval);
                }
            }
        }
    }

    // At this point, open SQLite database. If does not exist, it will be created
    $PDO = new BBKK_PDO($config['DB']['type']);
    $PDO->dbname = $sqlite_db_file;
    if ( !$PDO->open_database() ) {
        $retval['err_msg'] = 'Could not open sqlite database';
        $retval['dbg_msg'] = 'PDO database open failed';
        json_output_and_die($retval);
    }

}
else
// MYSQL
if ( $config['DB']['type']==='mysql' ) {
    // if flag is set, create new DB using super-user and super-pass
        // if flag is set remove existing previous database
    // connect to db
    $PDO = new BBKK_PDO($config['DB']['type']);
    $PDO->host     =
    $PDO->dbname   =
    $PDO->username =
    $PDO->password =

    if ( !$PDO->open_database() ) {
        $retval['err_msg'] = 'Could not open sqlite database';
        $retval['dbg_msg'] = 'PDO database open failed';
        json_output_and_die($retval);
    }
        // if flag is set, remove all tables
        // create tables
}


$retval['success'] = true;
json_output_and_die($retval);
