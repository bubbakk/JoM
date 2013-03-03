<?php

// AJAX requests dispatcher

//
// init
//
define('DIR_BASE', './');
require_once(DIR_BASE.'cfg/user_config.php');
require_once(DIR_BASE.'cfg/config.php');
require_once(DIR_LIB.'generic_lib.php');
require_once(DIR_OOL.'bbkk_base_class.php');
require_once(DIR_OOL.'bbkk_pdo.php');

$retval['success'] = false;




// read DOMAIN request
if ( post_or_get('d')===false) {
    $retval['err_msg'] = 'Missing parameter';
    $retval['dbg_msg'] = 'Missing d parameter';
    json_output_and_die($retval);
}
$domain = post_or_get('d');

$domains = array('categories' => 'cat');

// dispatch_request
if ( in_array($domain, $domains) ) {
    require_once(DIR_LIB.array_search($domain, $domains).'.php');
    dispatch_request();
}
else {
    $retval['err_msg'] = 'Wrong parameter value';
    $retval['dbg_msg'] = 'Domain ' . $domain . ' does not exist';
    json_output_and_die($retval);
    break;
}


$retval['success'] = true;
json_output_and_die($retval);
