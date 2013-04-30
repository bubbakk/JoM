<?php

// echo __FILE__." loaded\n";
require_once(DIR_OOL.'job.php');

function dispatch_request($request)
{
    // echo "Request: $request\n";
    switch ( $request )
    {
        case 'new':
            global $DBH, $retval, $config;
            global $command;

            $JOB = new JOM_Job(TBL_JOBS, $DBH);  // constructor
            $JOB->reset_job_data_to_defaults();

            // check and set all passed parameters
            $JOB->job_data["subject"]           = post_or_get('s');
            $JOB->job_data["description"]       = post_or_get('ds');
            $JOB->job_data["category_level_1"]  = post_or_get('c');
            $JOB->job_data["category_level_2"]  = post_or_get('i');
            $JOB->job_data["start_datetime"]    = post_or_get('sd');
            $JOB->job_data["priority"]          = post_or_get('p');

            $JOB->job_data["assigned_to_user"]  = ( post_or_get('a') == 1 ? $_SESSION['user']['id'] : 0 );

            // save new job
            $retval['success'] = $JOB->save();

            break;
        default:
            echo "CANT'T BE HERE!!!!\n";
            break;
    }
}
