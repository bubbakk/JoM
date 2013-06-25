<?php

/*
 * Function: json_output_and_die
 *   Print the passed argument in JSON format and die the script
 *
 * Parameters:
 *   $data - data to output in JSON format. Better if is an associative array
 *
 */
function json_output_and_die($data) {
    header('Content-Type: application/json');
    if ( !JOM_DEBUG ) { echo "JOM_DEBUG is on"; unset($data['dbg_msg']); }
    echo json_encode($data);
    die();
}


/*
 * Function: jom_immediate_redirect
 *   Output immediately an header 'Location' to force browser redirection. Warning: if successful this script terminates PHP script execution.
 *   The output Location will be: http://$domain/$path/$page (see parameters)
 *
 * Parameters:
 *  $domain - non-empty string
 *  $path - sub-domain relative path; if empty string, will not be considered
 *  $page - destination page; non-empty string
 *  $params - if non-empty, represents url-formatted parameters to pass as GET (without prepending '?')
 *
 * Returns:
 *  nothing if succesful (browser redirect will be immediate if the output is not buffered). Return 1 on wrong parameter(s) passing
 */
function jom_immediate_redirect($domain, $path, $page, $params = '') {
    if ( empty($domain) || !is_string($domain) ) return 1;
    if ( empty($page)   || !is_string($page) )   return 1;

    // building location
    $new_location = 'http://'.$domain;                      // domain
    if ( !empty($path) ) $new_location .= '/'.$path;        // path
    $new_location .= '/' . $page;                           // page
    if ( !empty($params) ) $new_location .= '?'.$params;    // parameters

    header('Location: '.$new_location);
}


/*
 * Function: recursive_utf8_encode
 * Walk recursively into passed argument and UTF8 encode every string found
 *
 * Returns:
 *   passed data structure with UTF8 encoded strings
 *
 * Parameters:
 *   $data - string or array containing any kind of data type
 *
 */
function recursive_utf8_encode($data) {

    if ( is_string($data) ) return utf8_encode($data);

    if ( is_array($data) ) {
        foreach ( $data as $key => $value ) {
            $data[$key] = recursive_utf8_encode($data[$key]);
        }
    }

    return $data;
}

/*
 * Function: post_or_get
 *   Try to return GET or POST value of the passed name variable.
 *
 * Returns:
 *   The string value of the passed variable name. If the variable is not defined, return boolean false
 *
 * Parameters:
 *   $data - data variable to read in GET or POST
 *
 */
function post_or_get($name) {
    if ( isset($_GET[$name]) ) {
        return utf8_decode($_GET[$name]);
    }

    if ( isset($_POST[$name]) ) {
        return $_POST[$name];
    }

    return false;
}


/*
 * Function: open_database
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


/*
 * Function: generate_random_string
 * Insert description here
 */
function generate_random_string($length = 10, $only_strings = true) {

    $characters = ( $only_strings ? '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ' :
                                    '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ*-+=|!£$%&^?@#[]{}_,.;:<>' );
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}


/*
 * Function: check_session_variables
 * Insert description here
 */
function check_session_variables() {
    if ( !isset($_SESSION['user']['settings']['i18n']['language']) ) {
        $_SESSION['user']['settings']['i18n']['language'] = I18N_LANGUAGE;
    }
    if ( !isset($_SESSION['user']['settings']['i18n']['dateformat']) ) {
        $_SESSION['user']['settings']['i18n']['dateformat'] = I18N_DATEFORMAT;
    }
    if ( !isset($_SESSION['user']['settings']['i18n']['dateseparator']) ) {
        $_SESSION['user']['settings']['i18n']['dateseparator'] = I18N_DATESEPARATOR;
    }
    if ( !isset($_SESSION['user']['settings']['i18n']['dateformat_human']) ) {
        $_SESSION['user']['settings']['i18n']['dateformat_human'] = I18N_DATEFORMAT_HUMAN;
    }
    if ( !isset($_SESSION['user']['settings']['i18n']['dateseparator_human']) ) {
        $_SESSION['user']['settings']['i18n']['dateseparator_human'] = I18N_DATESEPARATOR_HUMAN;
    }

    // check visit_time and, if time expired, redirect to login page
    if ( !isset($_SESSION['user']['last_visit']) )                   return -1;     // no last_visit information
    if ( time() - $_SESSION['user']['last_visit'] > SESSION_EXPIRE ) return -2;     // session expired (see config.php)

    return true;
}
