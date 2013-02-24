<?php

define('DIR_BASE', '../');
require_once(DIR_BASE.'cfg/user_config.php');
require_once(DIR_BASE.'cfg/config.php');
require_once(DIR_LIB.'generic_lib.php');

require_once(DIR_OOL.'bbkk_base_class.php');
require_once(DIR_OOL.'bbkk_pdo.php');

$retval['success'] = false;

// create tables
    $PDO = new BBKK_PDO($config['DB']['type']);
    $DB_cfg = $config['DB'][$config['DB']['type']];
    $PDO->dbname = $DB_cfg['dbname'];
    if ( !empty($DB_cfg['host']) ) {
        $PDO->dbname   = $DB_cfg['dbname'];
        $PDO->host     = $DB_cfg['host'];
        $PDO->username = $DB_cfg['username'];
        $PDO->password = $DB_cfg['password'];
    }
    else {
        $PDO->dbname   = DIR_DBSQLT.$DB_cfg['dbname'];
    }
    if ( !$PDO->open_database() ) {
        $retval['err_msg'] = 'Could not open '.$config['DB']['type'].' database';
        $retval['dbg_msg'] = 'PDO database open failed';
        json_output_and_die($retval);
    }

    require_once('tables_specification.php');
    $DBH = $PDO->get_dbh();

    $res = $DBH->exec($tables['Companies'][$config['DB']['type']]);
    if ( $res===false ) die(__LINE__ . " - ".print_r($DBH->errorInfo(), true));;
    $res = $DBH->exec($tables['Users'][$config['DB']['type']]);
    if ( $res===false ) die(__LINE__ . " - ".print_r($DBH->errorInfo(), true));;


$retval['success'] = true;
json_output_and_die($retval);
