<?php

/*
 * Function: json_output_and_die
 * Print the passed argument in JSON format and die the script
 *
 * Parameters:
 *   $data - data to output in JSON format. Better if is an associative array
 *
 */
function json_output_and_die($data) {
    header('Content-Type: application/json');
    if ( !JOM_DEBUG ) { echo "ciao"; unset($data['dbg_msg']); }
    echo json_encode($data);
    die();
}

/*
 * Function: post_or_get
 * Try to return GET or POST value of the passed name variable.
 *
 * Returns:
 *   the value of the passed name variable. If not defined, return false
 *
 * Parameters:
 *   $data - data variable to read in GET or POST
 *
 */
function post_or_get($name) {
    if ( isset($_GET[$name]) ) {
        return $_GET[$name];
    }

    if ( isset($_POST[$name]) ) {
        return $_POST[$name];
    }

    return false;
}


/*
 * Function: post_or_get
 * Shortcut to open database PDO database according to type. Manages also errors
 *
 * Returns:
 *   PDO object pointer or doen not return at all
 *
 * Parameters:
 *   $type - database type (sqlite, mysql and other in the future)
 *   $DB_cfg - configuration array for database connection
 *
 */
function open_database($type, $DB_cfg)
{
    // create class instance
    $PDO = new BBKK_PDO($type);

    $PDO->dbname = $DB_cfg['dbname'];

    if ( !empty($DB_cfg['host']) ) {
        $PDO->dbname   = $DB_cfg['dbname'];
        $PDO->host     = $DB_cfg['host'];
        $PDO->username = $DB_cfg['username'];
        $PDO->password = $DB_cfg['password'];
    }
    else {
        $PDO->dbname   = DIR_DBSQLT.$DB_cfg['dbname'];
    }

    if ( !$PDO->open_database() ) {
        $retval['err_msg'] = 'Could not open '.$type.' database';
        $retval['dbg_msg'] = 'PDO database open failed';
        json_output_and_die($retval);
    }

    return $PDO;
}


function generate_random_string($length = 10, $only_strings = true) {

    $characters = ( $only_strings ? '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ' :
                                    '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ*-+=|!£$%&^?@#[]{}_,.;:<>' );
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

