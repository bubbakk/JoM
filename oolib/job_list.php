<?php

function jobs_list_retrieve($DBH, $filters)
{
    $job_category_level_1 =
        ( $filters['job_category_level_1'] === false ?
          ''                                          :
          '   AND Job_category_level_1 = '.$filters['job_category_level_1'].' '
        );
    $job_category_level_2  =
        ( $filters['job_category_level_2'] === false ?
          ''                                         :
          '   AND Job_category_level_2 = '.$filters['job_category_level_2'].' '
        );
    $job_creation_datetime =
        ( $filters['job_creation_datetime'] === false ?
          ''                                          :
          '   AND Job_creation_datetime >= '.$filters['job_creation_datetime'].' '
        );
    $job_status =
        ( $filters['job_status'] === false ?
          ''                               :
          '   AND Job_status = '.$filters['job_status'].' '
        );



    $query = 'SELECT Job_id, Job_subject, Job_description, Job_start_datetime, Job_priority, '.
             '       Status_name, '.
             '       Category_1_name, Category_2_name, '.
             '       User_nickname,   User_id '.
             '  FROM '.TBL_JOBS.' INNER JOIN '.TBL_CATEGORIES_1.' ON '.TBL_JOBS.'.Job_category_level_1 = '.TBL_CATEGORIES_1.'.Category_1_id '.
             '                    INNER JOIN '.TBL_CATEGORIES_2.' ON '.TBL_JOBS.'.Job_category_level_2 = '.TBL_CATEGORIES_2.'.Category_2_id '.
             '                    INNER JOIN '.TBL_STATUSES.'     ON '.TBL_JOBS.'.Job_status           = '.TBL_STATUSES.    '.Status_id '.
             '                    INNER JOIN '.TBL_USERS.'        ON '.TBL_JOBS.'.Job_assigned_to_User = '.TBL_USERS.       '.User_id '.
             ' WHERE 1 '.
             $job_category_level_1.
             $job_category_level_2.
             $job_creation_datetime.
             $job_status.
             '   AND Job_trashed <> 1 ';


    $stmt_read = $DBH->prepare($query);
    $stmt_read->execute();
    $job_list_data = $stmt_read->fetchAll();
    $stmt_read->closeCursor();

    return $job_list_data;
}

function jobs_list_data_format($job_list_raw_data)
{
    $TP = TABLES_PREFIX;

    $retval = array();
    foreach( $job_list_raw_data as $key => $value ) {
        $retval[$key]['id']             = $value['Job_id'];
        $retval[$key]['subject']        = $value['Job_subject'];
        $retval[$key]['owner']          = $value['User_nickname'];
        $retval[$key]['owner_id']       = $value['User_id'];
        $retval[$key]['status']         = $value['Status_name'];
        $retval[$key]['description']    = $value['Job_description'];
        $retval[$key]['started']        = $value['Job_start_datetime'];
        $retval[$key]['category']       = $value['Category_1_name'];
        $retval[$key]['issue']          = $value['Category_2_name'];
    }

    return $retval;
}
