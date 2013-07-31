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

    header('Location: '.$new_location);                     // redirect immediately
    die();                                                  // won't execute: redirected in the line above
}


/*
 * Function: jom_clear_session
 *   Clear session and cookie data and finally destroy the session
 *
 * Returns:
 *   false if session is not initialized, true if everythig is fine
 */
function jom_clear_session() {

    // check whether the session is started or not
    if ( !isset($_SESSION) ) return false;

    // Unset session variables
    $_SESSION = array();

    // If it's desired to kill the session, also delete the session cookie.
    // Note: This will destroy the session, and not just the session data!
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Finally, destroy the session
    session_destroy();

    return true;
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
   Function: jom_pdo__create_fields_list
    Create comma separated table field or parameters (for binding) list.

   Parameters:
       - table_fields: properly formatted array containig table fields data and metadata
       - add_pkey: true|false; if set to true, return also the fields set as primary key
       - only_changes: true|false; if set to true, return only fields that have the field 'is_changed' set to true
       - for_bindings: true|false; is set to true, prepend every field value

   Returns:
     false on error, the comma separated list of table fields

   See:
     <table_fields>
*/
function jom_pdo__create_fields_list($table_fields = false, $add_pkey = false, $only_changes = false, $for_binding = false)
{
    if ( $table_fields === false ) return false;

    $retval_array = array();

    // if creating a list to bind parameters, have to prepend a ':'
    $bind_prepend = '';
    if ( $for_binding === true ) $bind_prepend = ':';

    foreach ($table_fields as $key => $field) {

        if ( $add_pkey === false && isset($field['is_pkey']) && $field['is_pkey'] === true ) {
            ;   // do not add primary key field
        }
        else {
            if ( $only_changes === true && isset($field['is_changed']) && $field['is_changed'] === false ) {
                ;   // do not add value if is not changed
            }
            else {
                array_push($retval_array, $bind_prepend.$field['name']);
            }
        }
    }

    return implode(', ', $retval_array);    // return comma separated list
}


/*
   Function: jom_pdo__reset_table_fields_data_to_defaults
   Reset all job fields/properties values to defauls; set also the 'is_changed' array field to false
*/
function jom_pdo__reset_table_fields_data_to_defaults(&$table_fields)
{
    foreach ($table_fields as $key => $field) {
        $table_fields[$key]['value']      = $table_fields[$key]['default'];
        $table_fields[$key]['is_changed'] = false;
    }
}


/*
   Function: bind_values
    DESCRIBE ME
*/
function jom_pdo__bind_values(&$stmt, $table_fields = false, $add_pkey = false, $only_changes = false)
{
    if ( $table_fields === false ) return false;

    foreach ($table_fields as $key => $field)
    {
        if ( $add_pkey === false && isset($field['is_pkey']) && $field['is_pkey'] === true ) {
            ;   // do not bind values id field is primary key
        }
        else {
            if ( $only_changes === true && isset($field['is_changed']) && $field['is_changed'] === false ) {
                ;   // do not bind values if the value is not changed
            }
            else
            {
                // bind parameter name
                $bind_param_name = ':' . $field['name'];
                // PDO:: value type
                $bind_type = $field['pdo_parm_type'];
                if ( $field['value'] === null )
                    $bind_type = PDO::PARAM_NULL;

                // do statement bind
                // DEBUG - echo "bind: " . $bind_param_name . ", " . var_export($field['value'], true) . ", " . $bind_type . "\n<br>";
                if ( !$stmt->bindValue($bind_param_name, $field['value'], $bind_type) ) {
                    die("no bind!");
                }
            }
        }
    }
}


function jom_pdo__create_key_value_list($table_fields = false, $add_pkey = false, $only_changes = false)
{
    $retval_array = array();

    foreach ($table_fields as $key => $field) {
        if ( $add_pkey === false && isset($field['is_pkey']) && $field['is_pkey'] === true ) {
            ;   // do not add primary key field
        }
        else {
            if ( $only_changes === true && isset($field['is_changed']) && $field['is_changed'] === false ) {
                ;   // do not add value if is not changed
            }
            else {
                //echo "element=> field name: " . $field['name'] . " = :" . $field['name'] . "\n<br>";
                array_push($retval_array, $field['name'] . " = :" . $field['name']);
            }
        }
    }

    return implode(', ', $retval_array);    // return comma separated list
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
