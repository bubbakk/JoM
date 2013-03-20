<?php

// echo __FILE__." loaded\n";

function dispatch_request($request)
{
    // echo "Request: $request\n";
    switch ( $request )
    {
        case 'login':
            // user is not logged in
            $_SESSION['user']['is_logged_in'] = false;
            break;
        case 'logout':
            break;
        default:
            echo "CANT'T BE HERE!!!!\n";
            break;
    }
}
