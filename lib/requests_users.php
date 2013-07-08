<?php

// echo __FILE__." loaded\n";
require_once(DIR_OOL.'user.php');
require_once(DIR_OOL.'users_list.php');

function dispatch_request($request)
{
    // echo "Request: $request\n";
    switch ( $request )
    {
        case 'login':
        {
            global $DBH, $retval, $config;
            global $command;

            // impose that user is not logged in
            $_SESSION['user']['is_logged_in'] = false;
            $USR = new JOM_User(TBL_USERS, $DBH);                                   // constructor
            $user_auth = $USR->authenticate(post_or_get('u'), post_or_get('p'));    // try to authenticate

            // if not authenticated
            if ( !$user_auth ) {
                $retval['success'] = false;
                $retval['usr_msg'] = 'Wrong username/e-mail and password combination';
                // if user is not valid, have to create a new nonce
                $json_nonce = generate_json_values($command, 0, session_id(), $config['SALT'], $config['HASH_ALG']);
                $retval['new_timestamp'] = $json_nonce['timestamp'];
                $retval['new_nonce']     = $json_nonce['nonce'];
                return false;
            }

            // if authenticated successfully
            // set some session variables
            $_SESSION['user']['is_logged_in']   = true;
            $_SESSION['user']['id']             = $USR->user_data['User_id'];
            $_SESSION['user']['full_name']      = $USR->user_data['User_firstname'].' '.$USR->user_data['User_lastname'];

            $retval['success'] = true;
            $retval['usr_msg'] = 'Authenticated';

            return true;
        }
            break;
        case 'logout':
        {
            $_SESSION['user']['is_logged_in'] = false;
        }
            break;
        case 'list':
        {
            global $DBH, $retval;

            $users_list_data = users_list_retrieve($DBH, null);

            if ( count($users_list_data ) == 0 ) {
                $retval['success'] = false;
            }
            else {
                $retval['success'] = true;
                $retval['data']    = users_list_data_format($users_list_data);
            }
        }
            break;
        default:
            echo "CANT'T BE HERE!!!!\n";
            break;
    }
}
