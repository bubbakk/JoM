<?php

// echo __FILE__." loaded\n";
require_once(DIR_OOL.'user.php');

function dispatch_request($request)
{
    // echo "Request: $request\n";
    switch ( $request )
    {
        case 'login':

            global $DBH, $retval;

            // user is not logged in
            $_SESSION['user']['is_logged_in'] = false;
            $USR = new JOM_User(TBL_USERS, $DBH);                                   // constructor
            $user_auth = $USR->authenticate(post_or_get('u'), post_or_get('p'));    // try to authenticate

            // not authenticated
            if ( !$user_auth ) {
                $retval['success'] = false;
                $retval['usr_msg'] = 'Wrong username/e-mail and password combination';
                return;
            }

                $retval['success'] = true;
                $retval['usr_msg'] = 'Authenticated';

            break;
        case 'logout':
            break;
        default:
            echo "CANT'T BE HERE!!!!\n";
            break;
    }
}
