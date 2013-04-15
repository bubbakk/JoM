<?php

// echo __FILE__." loaded\n";
require_once(DIR_OOL.'user.php');

function dispatch_request($request)
{
    // echo "Request: $request\n";
    switch ( $request )
    {
        case 'new':
            global $DBH, $retval, $config;
            global $command;

            break;
        default:
            echo "CANT'T BE HERE!!!!\n";
            break;
    }
}
