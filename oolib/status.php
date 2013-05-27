<?php

function statuses_data_retrieve($DBH)
{
    $query = '   SELECT Status_id, Status_name '.
             '     FROM '.TBL_STATUSES.' '.
             '    WHERE Status_trashed <> 1 ';
             ' ORDER BY Status_order ASC ';

    $stmt_read = $DBH->prepare($query);
    $stmt_read->execute();
    $statuses_data = $stmt_read->fetchAll();
    $stmt_read->closeCursor();

    return $statuses_data;
}

function statuses_data_format($statuses_data) {

    $retval = array();
    foreach( $statuses_data as $key => $value ) {
        $retval[$key]['id']     = $value['Status_id'];
        $retval[$key]['name']   = $value['Status_name'];
    }

    return $retval;
}
