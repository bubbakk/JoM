<?php

function jobs_list_retrieve($DBH, $filters)
{
    $TP = TABLES_PREFIX;

    $job_category_level_1 =
        ( $filters['job_category_level_1'] === false ?
          ''                                          :
          '   AND '.$TP.'Job_category_level_1 = '.$filters['job_category_level_1'].' '
        );
    $job_category_level_2  =
        ( $filters['job_category_level_2'] === false ?
          ''                                         :
          '   AND '.$TP.'Job_category_level_2 = '.$filters['job_category_level_2'].' '
        );
    $job_creation_datetime =
        ( $filters['job_creation_datetime'] === false ?
          ''                                          :
          '   AND '.$TP.'Job_creation_datetime >= '.$filters['job_creation_datetime'].' '
        );
    $job_status =
        ( $filters['job_status'] === false ?
          ''                               :
          '   AND '.$TP.'Job_status = '.$filters['job_status'].' '
        );



    $query = 'SELECT '.$TP.'Job_id, Job_subject, '.$TP.'Job_description, '.$TP.'Job_start_datetime, '.$TP.'Job_priority, '.
             '       '.$TP.'Status_name, '.
             '       '.$TP.'Category_1_name, '.$TP.'Category_2_name, '.
             '       '.$TP.'User_nickname,   '.$TP.'User_id '.
             '  FROM '.TBL_JOBS.' INNER JOIN '.TBL_CATEGORIES_1.' ON '.TBL_JOBS.'.'.$TP.'Job_category_level_1 = '.TBL_CATEGORIES_1.'.'.$TP.'Category_1_id '.
             '                    INNER JOIN '.TBL_CATEGORIES_2.' ON '.TBL_JOBS.'.'.$TP.'Job_category_level_2 = '.TBL_CATEGORIES_2.'.'.$TP.'Category_2_id '.
             '                    INNER JOIN '.TBL_STATUSES.'     ON '.TBL_JOBS.'.'.$TP.'Job_status           = '.TBL_STATUSES.    '.'.$TP.'Status_id '.
             '                    INNER JOIN '.TBL_USERS.'        ON '.TBL_JOBS.'.'.$TP.'Job_assigned_to_User = '.TBL_USERS.       '.'.$TP.'User_id '.
             ' WHERE 1 '.
             $job_category_level_1.
             $job_category_level_2.
             $job_creation_datetime.
             $job_status.
             '   AND '.$TP.'Job_trashed <> 1 ';


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
        $retval[$key]['id']             = $value[$TP.'Job_id'];
        $retval[$key]['subject']        = $value[$TP.'Job_subject'];
        $retval[$key]['owner']          = $value[$TP.'User_nickname'];
        $retval[$key]['owner_id']       = $value[$TP.'User_id'];
        $retval[$key]['status']         = $value[$TP.'Status_name'];
        $retval[$key]['description']    = $value[$TP.'Job_description'];
        $retval[$key]['started']        = $value[$TP.'Job_start_datetime'];
        $retval[$key]['category']       = $value[$TP.'Category_1_name'];
        $retval[$key]['issue']          = $value[$TP.'Category_2_name'];
    }

    return $retval;
}
