<?php

/*
 * Copyright 2013, 2013 Andrea Ferroni
 *
 * This file is part of "JoM|The Job Manager".
 *
 * "JoM|The Job Manager" is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * "JoM|The Job Manager" is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Nome-Programma.  If not, see <http://www.gnu.org/licenses/>.
 *
 */


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



//
// possible calls set
//
// DOMAINS
$domains = array('cat' => 'categories',                         // categories
                 'sta' => 'statuses',                           // statuses
                 'usr' => 'users',                              // users
                 'job' => 'job'                                 // job
                );
// REQUESTS for DOMAINS
$requests = array('categories' => array('lod' => 'load'),       // categories
                  'statuses'   => array('lod' => 'load'),       // statuses
                  'users'      => array('lin' => 'login',       // users
                                        'lot' => 'logout'
                                       ),
                  'job'        => array('new' => 'new',         // job
                                        'lst' => 'list'
                                       )
                 );
// default return falue
$retval['success'] = false;




//
// variables read
//
/*
 * Some names are reserved for the application, and can't be used for custom
 * uses. That are:
 *  - n: the nonce
 *  - t: timestanp (associated to nonce)
 *  - d: request domanin
 *  - r: request code
 *  - c: context. Is returned to client: important to make the client application aware about the
 *       context the data applies
 */
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
// read CONTEXT
if ( post_or_get('c')===false) {
    $retval['err_msg'] = 'Missing parameter';
    $retval['dbg_msg'] = 'Missing r parameter';
    json_output_and_die($retval);
}
$domain    = post_or_get('d'); // echo 'domain:  '.$domain."\n";
$request   = post_or_get('r'); // echo 'request: '.$request."\n";
$nonce     = post_or_get('n'); // echo 'nonce:   '.$nonce."\n";
$timestamp = post_or_get('t'); // echo 'nonce:   '.$nonce."\n";
$context   = post_or_get('c'); // echo 'context: '.$context."\n";

$full_domain  = $domains[$domain];
$full_request = $requests[$full_domain][$request];

$command = '/' . $full_domain . '/' . $full_request;



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
// check session variables existance and set default values if not found
$session_vars_check = check_session_variables();
// if loggin in, do not have to check session
if ( $full_domain != 'users' && $full_request != 'login' ) {
    // else, check that the session is active
    if ( $session_vars_check === -1 || $session_vars_check === -2 )
    {
        // destroy the session
        session_destroy();
        // prepare and output JSON data for redirect
        $retval['cmd']         =  'redirect';
        $retval['url']         =  './login.php';
        $retval['querystring'] =  'r=exp';          // redirect reason: session expired
        json_output_and_die($retval);
    }
}



//
// nonce check
//
if ( !JOM_DEBUG ) {
    $nonce_check = check_nonce( $command, 0, session_id(), $timestamp, $config['SALT'], $config['HASH_ALG'], $nonce, NONCE_EXPIRE, $DBH);
    if ( !($nonce_check === true) ) {
        // if nonce is not valid, reload the page
        $retval['err_msg'] = 'Nonce not accepted';
        $retval['dbg_msg'] = 'Something in nonce check failed';
        $retval['usr_msg'] = 'Request too old. Please <a href="'.$_SERVER['HTTP_REFERER'].'">reload page</a>.';
        json_output_and_die($retval);
    }
}



//
// request dispatch
//
if ( array_key_exists($domain, $domains) &&
     array_key_exists($request, $requests[$full_domain])
   ) {
    require_once(DIR_LIB . 'requests_' . $full_domain . '.php');

    // check if user is logged in
    // ?????????????

    // update user's last visit datetime
    $_SESSION['user']['last_visit'] = time();

    if ( dispatch_request($full_request) ) {
        $retval['success'] = true;
    }
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



// UTF-8 encode (if any data exist)
if ( isset($retval['data']) ) {
    $retval['data'] = recursive_utf8_encode($retval['data']);
}
$retval['ctx'] = $context;

json_output_and_die($retval);
