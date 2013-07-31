<?php

// echo __FILE__." loaded\n";
require_once(DIR_OOL.'job.php');

function dispatch_request($request)
{
    // echo "Request: $request\n";
    switch ( $request )
    {
        case 'new':
        {
            global $DBH, $retval, $config, $JOM__job_table_fields;

            $JOB = new JOM_Job(TBL_JOBS, $DBH, $JOM__job_table_fields);  // constructor

            // check and set all passed parameters
            $JOB->set_field_value('Job_subject',          post_or_get('s') );
            $JOB->set_field_value('Job_description',      post_or_get('ds'));
            $JOB->set_field_value('Job_category_level_1', post_or_get('c') );
            $JOB->set_field_value('Job_category_level_2', post_or_get('i') );
            $JOB->set_field_value('Job_start_datetime',   post_or_get('sd'));
            $JOB->set_field_value('Job_priority',         post_or_get('p') );

            $JOB->set_field_value('Job_assigned_to_user_id', ( post_or_get('a') == 1 ? $_SESSION['user']['id'] : 0 ));

            // save new job
            $retval['success'] = $JOB->save();

            return true;

            break;
        }
        case 'list':
        {
            global $DBH, $retval, $config;

            require_once(DIR_OOL.'job_list.php');

            // retrieving filters data
            // check and set all passed parameters
            $filters = array('job_category_level_1'  => post_or_get('a'),   // category
                             'job_category_level_2'  => post_or_get('i'),   // issue
                             'job_creation_datetime' => post_or_get('x'),   // creation date
                             'job_status'            => post_or_get('s')    // status
                             );

            $job_list_data = jobs_list_retrieve($DBH, $filters);

            if ( count($job_list_data) == 0 ) {
                $retval['success'] = false;
            }
            else {
                $retval['success'] = true;
                $retval['data']    = jobs_list_data_format($job_list_data);
            }

            return true;

            break;
        }
        case 'update':
        {
            global $DBH, $retval, $config, $JOM__job_table_fields;

            $JOB = new JOM_Job(TBL_JOBS, $DBH, $JOM__job_table_fields);  // constructor

            $JOB->set_field_value('Job_id',         post_or_get('i'));
            $JOB->set_field_value(post_or_get('f'), post_or_get('v'));  // the field name to update is dinamically passed... secure ?

            // update job data
            $retval['success'] = $JOB->save();

            return true;

            break;
        }
        default:
            echo "CANT'T BE HERE!!!!\n";
            break;
    }
}
