<?php
//
// AJAX requests dispatcher
//


//
// init
//
define('DIR_BASE', './');
require_once(DIR_BASE.'cfg/user_config.php');
require_once(DIR_BASE.'cfg/config.php');
require_once(DIR_LIB.'generic_lib.php');
require_once(DIR_LIB.'nonce_lib.php');
require_once(DIR_OOL.'bbkk_base_class.php');
require_once(DIR_OOL.'bbkk_pdo.php');
require_once(DIR_OOL.'bbkk_session_manager.php');

$retval['success'] = false;

// DOMAINS
$domains = array('cat' => 'categories',                         // categories
                 'usr' => 'users'                               // users
                );
// REQUESTS for DOMAINS
$requests = array('categories' => array('lod' => 'load'),       // categories
                  'users'      => array('lin' => 'login',       // users
                                        'lot' => 'logout'
                                       )
                 );







// read NONCE
if ( post_or_get('n')===false) {
    $retval['err_msg'] = 'Missing parameter';
    $retval['dbg_msg'] = 'Missing n parameter';
    json_output_and_die($retval);
}
// read TIMESTAMP
if ( post_or_get('t')===false) {
    $retval['err_msg'] = 'Missing parameter';
    $retval['dbg_msg'] = 'Missing t parameter';
    json_output_and_die($retval);
}
// read DOMAIN
if ( post_or_get('d')===false) {
    $retval['err_msg'] = 'Missing parameter';
    $retval['dbg_msg'] = 'Missing d parameter';
    json_output_and_die($retval);
}
// read REQUEST
if ( post_or_get('r')===false) {
    $retval['err_msg'] = 'Missing parameter';
    $retval['dbg_msg'] = 'Missing r parameter';
    json_output_and_die($retval);
}
$domain    = post_or_get('d'); // echo 'domain: '.$domain."\n";
$request   = post_or_get('r'); // echo 'request: '.$request."\n";
$nonce     = post_or_get('n'); // echo 'nonce: '.$nonce."\n";
$timestamp = post_or_get('t'); // echo 'nonce: '.$nonce."\n";

$command = '/' . $domains[$domain] . '/' . $requests[$domains[$domain]][$request];







//
// database connection
//
$PDO = open_database($config['DB']['type'], $config['DB'][$config['DB']['type']]);  // open DB
$DBH = $PDO->get_dbh();                                                             // get the handler
//
// session manager
//
$SMAN = new BBKK_Session_Manager(TBL_SESSIONS, $DBH);   // constructor
$SMAN->debug_on_screen = false;
$SMAN->salt = $config['SALT'];                          // explicitly set application salt
$SMAN->start_session('', false);                        // starting session



// check nonce
$nonce_check = check_nonce( $command, 0, session_id(), $timestamp, $config['SALT'], $config['HASH_ALG'], $nonce, NONCE_EXPIRE, $DBH);
if ( !($nonce_check === true) ) {
    // if nonce is not valid, reload the page
    $retval['err_msg'] = 'Nonce not accepted';
    $retval['dbg_msg'] = 'Something in nonce check failed';
    $retval['usr_msg'] = 'Request too old. Please <a href="'.$_SERVER['HTTP_REFERER'].'">reload page</a>.';
    json_output_and_die($retval);
}






// dispatch_request
if ( array_key_exists($domain, $domains) &&
     array_key_exists($request, $requests[$domains[$domain]])
   ) {
    require_once(DIR_LIB . 'requests_' . $domains[$domain] . '.php');

    // check if user is logged in

    // update user's last visit datetime
    $_SESSION['user']['last_visit'] = time();

    dispatch_request($requests[$domains[$domain]][$request]);
}
else {
    $retval['err_msg'] = 'Wrong parameter value';
    $retval['dbg_msg'] = 'Domain ' . $domain . ' and/or request ' . $request . ' does not exist';
    json_output_and_die($retval);
    break;
}



// session session defaults
if ( !isset($_SESSION['user']['is_logged_in']) )    $_SESSION['user']['is_logged_in']   = false;
if ( !isset($_SESSION['user']['last_visit']) )      $_SESSION['user']['last_visit']     = time();



json_output_and_die($retval);
