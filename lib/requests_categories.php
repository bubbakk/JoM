<?php

// echo __FILE__." loaded\n";
require_once(DIR_OOL.'category.php');

function dispatch_request($request)
{
    // echo "Request: $request\n";
    switch ( $request )
    {
        case 'load':
            break;
        default:
            echo "CANT'T BE HERE!!!!\n";
            break;
    }
}
