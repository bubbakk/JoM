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

            $JOB = new JOM_Job(TBL_JOBS, $DBH);  // constructor

            // check and set all passed parameters
            $JOB->subject           = post_or_get('s');
            $JOB->description       = post_or_get('ds');
            $JOB->category_level1   = post_or_get('c');
            $JOB->category_level2   = post_or_get('i');
            $JOB->start_datetime    = post_or_get('sd');
            $JOB->priority          = post_or_get('p');


            // save new job
            $JOB->save();

            break;
        default:
            echo "CANT'T BE HERE!!!!\n";
            break;
    }
}
