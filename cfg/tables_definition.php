<?php

//
// Table Job
//
$JOM__job_table_fields = array();
{
    // array values order: 1: field name; 2: default value; 3: pdo parmeter type; 4: is_pkey
    //                 |             name             |default|     type     |is key|
    $basic_table_data
        = array( array('Job_id',                         null, PDO::PARAM_INT, true),
                 array('Job_subject',                    '',   PDO::PARAM_STR),
                 array('Job_description',                '',   PDO::PARAM_STR),
                 array('Job_category_level_1',           1,    PDO::PARAM_INT),
                 array('Job_category_level_2',           1,    PDO::PARAM_INT),
                 array('Job_category_level_3',           1,    PDO::PARAM_INT),
                 array('Job_is_favourite',               0,    PDO::PARAM_INT),
                 array('Job_tags',                       null, PDO::PARAM_STR),
                 array('Job_priority',                   null, PDO::PARAM_INT),
                 array('Job_creation_datetime',          null, PDO::PARAM_INT),
                 array('Job_deadline_datetime',          null, PDO::PARAM_INT),
                 array('Job_percent_completed',          null, PDO::PARAM_INT),
                 array('Job_assigned_to_user_id',        null, PDO::PARAM_INT),
                 array('Job_assigned_to_chainedgroup_A', null, PDO::PARAM_INT),
                 array('Job_assigned_to_chainedgroup_B', null, PDO::PARAM_INT),
                 array('Job_assigned_to_chainedgroup_C', null, PDO::PARAM_INT),
                 array('Job_assigned_to_chainedgroup_D', null, PDO::PARAM_INT),
                 array('Job_assigned_to_freegroup_A',    null, PDO::PARAM_INT),
                 array('Job_assigned_to_freegroup_B',    null, PDO::PARAM_INT),
                 array('Job_assigned_to_freegroup_C',    null, PDO::PARAM_INT),
                 array('Job_assigned_to_freegroup_D',    null, PDO::PARAM_INT),
                 array('Job_trashed',                    0, PDO::PARAM_INT)
               );

    foreach ( $basic_table_data as $key => $value ) {
        $JOM__job_table_fields[$value[0]] = array('name'          => $value[0],
                                                  'default'       => $value[1],
                                                  'pdo_parm_type' => $value[2],
                                                  'value'         => $value[1],
                                                  'is_changed'    => false);
        if ( isset($value[3]) )
            $JOM__job_table_fields[$value[0]]['is_pkey'] = $value[3];
    }
}
