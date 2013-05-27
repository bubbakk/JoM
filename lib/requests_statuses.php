<?php

// echo __FILE__." loaded\n";
require_once(DIR_OOL.'status.php');

function dispatch_request($request)
{
    // echo "Request: $request\n";
    switch ( $request )
    {
        case 'load':

            global $DBH, $retval, $config;

            require_once(DIR_OOL.'job_list.php');

            $statuses_data = statuses_data_retrieve($DBH);

            if ( count($statuses_data) == 0 ) {
                $retval['success'] = false;
            }
            else {
                $retval['success'] = true;
                $retval['data']    = statuses_data_format($statuses_data);
            }

            return true;

            break;
        default:
            echo "CANT'T BE HERE!!!!\n";
            break;
    }
}
