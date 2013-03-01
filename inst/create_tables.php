<?php

//
// init
//
define('DIR_BASE', '../');
require_once(DIR_BASE.'cfg/user_config.php');
require_once(DIR_BASE.'cfg/config.php');
require_once(DIR_LIB.'generic_lib.php');
require_once(DIR_OOL.'bbkk_base_class.php');
require_once(DIR_OOL.'bbkk_pdo.php');

$retval['success'] = false;

//
// create tables
//
$PDO = open_database($config['DB']['type'], $config['DB'][$config['DB']['type']]);  // open DB

require_once('tables_specification.php');                                           // open tables creation array
$DBH = $PDO->get_dbh();

$res = $DBH->exec($tables['Companies'][$config['DB']['type']]);                     // execute query for Companies
if ( $res===false ) die(__LINE__ . " - ".print_r($DBH->errorInfo(), true));
$res = $DBH->exec($tables['Users'][$config['DB']['type']]);                         // execute query for Users
if ( $res===false ) die(__LINE__ . " - ".print_r($DBH->errorInfo(), true));
$res = $DBH->exec($tables['Jobs'][$config['DB']['type']]);                          // execute query for Jobs
if ( $res===false ) die(__LINE__ . " - ".print_r($DBH->errorInfo(), true));
$res = $DBH->exec($tables['Categories_A'][$config['DB']['type']]);                  // execute query for Categories A
if ( $res===false ) die(__LINE__ . " - ".print_r($DBH->errorInfo(), true));
$res = $DBH->exec($tables['Categories_B'][$config['DB']['type']]);                  // execute query for Categories B
if ( $res===false ) die(__LINE__ . " - ".print_r($DBH->errorInfo(), true));

$retval['success'] = true;
json_output_and_die($retval);
