<?php
// TEST job and bbkk_db_utility classes
define('DIR_BASE', '../');
require_once(DIR_BASE.'cfg/user_config.php');
require_once(DIR_BASE.'cfg/config.php');
require_once(DIR_LIB.'generic_lib.php');
require_once(DIR_OOL.'bbkk_base_class.php');
require_once(DIR_OOL.'bbkk_pdo.php');
require_once(DIR_OOL.'job.php');
require_once(DIR_BASE.'cfg/tables_definition.php');

$PDO = open_database($config['DB']['type'], $config['DB'][$config['DB']['type']]);  // open DB
$DBH = $PDO->get_dbh();                                                             // get the handler

$JOB = new JOM_Job(TBL_JOBS, $DBH, $JOM__job_table_fields);  // constructor



//
// INSERT
//

$JOB->obj_db_utility->table_fields['Job_subject']['value'] = "ciccio";

// base query
$query = 'INSERT INTO ' . TBL_JOBS . ' ';
// create fields list: exclude id, include default valued fields, not for binding
$fields_list = $JOB->obj_db_utility->create_fields_list(false, false, false);
$query .= ' ('.$fields_list.') ';
// create values parameters for binding
$fields_bind_list = $JOB->obj_db_utility->create_fields_list(false, false, true);
$query .= ' VALUES ('.$fields_bind_list.') ';

echo $query."<br>";
$stmt_save = $DBH->prepare($query);

$JOB->obj_db_utility->bind_values($stmt_save, false, false);

if ( !$stmt_save->execute() ) {
    print_r($stmt_save->errorInfo());
}


//
// UPDATE
//
$JOB->obj_db_utility->table_fields['Job_id']['value'] = 1;
$JOB->obj_db_utility->table_fields['Job_subject']['value'] = "puzza";
$JOB->obj_db_utility->table_fields['Job_subject']['is_changed'] = "true";

// base query
$query =  'UPDATE ' . TBL_JOBS . ' ';
// create fields list: exclude id, include default valued fields, not for binding
$field_value_pairs_list = $JOB->obj_db_utility->create_key_value_list();
$query .= '   SET ' . $field_value_pairs_list . ' ';
$query .= ' WHERE Job_id = '. $JOB->obj_db_utility->table_fields['Job_id']['value'];
$stmt_update = $DBH->prepare($query);
$JOB->obj_db_utility->bind_values($stmt_update, false, true);
if ( !$stmt_update->execute() ) {
    print_r($stmt_update->errorInfo());
}

echo "$query<br>ok";

