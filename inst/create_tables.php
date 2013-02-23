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
    $PDO->dbname = $config['DB'][[$config['DB']['type']]['dbname'];
    if ( !$PDO->open_database() ) {
        $retval['err_msg'] = 'Could not open sqlite database';
        $retval['dbg_msg'] = 'PDO database open failed';
        json_output_and_die($retval);
    }
