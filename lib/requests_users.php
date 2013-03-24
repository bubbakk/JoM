<?php

// echo __FILE__." loaded\n";
require_once(DIR_OOL.'user.php');

function dispatch_request($request)
{
    // echo "Request: $request\n";
    switch ( $request )
    {
        case 'login':

            global $DBH, $retval, $config;
            global $command;

            // impose that user is not logged in
            $_SESSION['user']['is_logged_in'] = false;
            $USR = new JOM_User(TBL_USERS, $DBH);                                   // constructor
            $user_auth = $USR->authenticate(post_or_get('u'), post_or_get('p'));    // try to authenticate

            // not authenticated
            if ( !$user_auth ) {
                $retval['success'] = false;
                $retval['usr_msg'] = 'Wrong username/e-mail and password combination';
                // if user is not valid, have to create a new nonce
                $json_nonce = generate_json_values($command, 0, session_id(), $config['SALT'], $config['HASH_ALG']);
                $retval['new_timestamp'] = $json_nonce['timestamp'];
                $retval['new_nonce']     = $json_nonce['nonce'];
                return;
            }
            // authenticated successfully
            $_SESSION['user']['is_logged_in'] = true;
            $retval['success'] = true;
            $retval['usr_msg'] = 'Authenticated';

            break;
        case 'logout':
            $_SESSION['user']['is_logged_in'] = false;
            break;
        default:
            echo "CANT'T BE HERE!!!!\n";
            break;
    }
}
