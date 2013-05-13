<?php

function retrieve_jobs_list($user_id_fltr, $status_id_fltr)
{

    $query = 'SELECT Job_subject, Job_description, Job_start_datetime, Job_priority, '.
             '       Status_name, '
             '       Category_1_name, Category_2_name, '.
             '       User_nickname '.
             '  FROM '.TBL_JOBS.' INNER JOIN '.TBL_CATEGORIES_1.' ON '.TBL_JOBS.'.Job_category_level_1 = '.TBL_CATEGORIES_1.'.Category_1_id '
             '                    INNER JOIN '.TBL_CATEGORIES_2.' ON '.TBL_JOBS.'.Job_category_level_2 = '.TBL_CATEGORIES_2.'.Category_2_id '
             '                    INNER JOIN '.TBL_STATUSES.'     ON '.TBL_JOBS.'.Job_status           = '.TBL_STATUSES.'.Status_id '
             '                    INNER JOIN '.TBL_USERS.'        ON '.TBL_JOBS.'.Job_assigned_to_User = '.TBL_USERS.'.User_id '
             ' WHERE trashed <> 1 ';

    echo $query;
}
