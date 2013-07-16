<?php

function users_list_retrieve($DBH, $filters)
{
    $query = 'SELECT User_id, User_nickname '.
             '  FROM '.TBL_USERS.' '.
             ' WHERE 1 '.
             '   AND User_trashed <> 1 '.
             ' ORDER BY User_nickname ';


    $stmt_read = $DBH->prepare($query);
    $stmt_read->execute();
    $users_list_data = $stmt_read->fetchAll();
    $stmt_read->closeCursor();

    return $users_list_data;
}


function users_list_data_format($users_list_raw_data)
{
    $retval = array();
    foreach( $users_list_raw_data as $key => $value ) {
        $retval[$key]['id']             = $value['User_id'];
        $retval[$key]['nickname']       = $value['User_nickname'];
    }

    return $retval;
}
