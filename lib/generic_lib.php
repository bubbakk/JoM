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


function open_database($type, $DB_cfg)
{
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
        $retval['err_msg'] = 'Could not open '.$config['DB']['type'].' database';
        $retval['dbg_msg'] = 'PDO database open failed';
        json_output_and_die($retval);
    }

    return $PDO;
}
