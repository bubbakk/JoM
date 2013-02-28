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
// Insert sample data
//

// open DB
$PDO = open_database($config['DB']['type'], $config['DB'][$config['DB']['type']]);
// when is complete, replace with specific company object methods
$query_company_data = 'INSERT INTO '.TBL_COMPANIES.' '.
                      '            (Company_name, Company_address, Company_geo_location, Company_main_telephone_number, Company_piva) '.
                      '     VALUES ("Spes s.c.p.a.", "Via L. Corsi, 43", "43.351818,12.925394", "0732 25291", "01475280424")';
$DBH = $PDO->get_dbh();
$res = $DBH->exec($query_company_data);                                      // insert Company data
if ( $res===false ) die(__LINE__ . " - ".print_r($DBH->errorInfo(), true));

$query_company_data = 'INSERT INTO '.TBL_USERS.' '.
                      '            (User_id, User_firstname, User_lastname, User_username, User_password, User_nickname, '.
                      '             User_contacts_internal_telephone_number, User_contacts_telephone_number, User_contacts_mobile, '.
                      '             User_contacts_email, User_is_internal_company, User_company_id, User_chainedgroup_id_catA, '.
                      '             User_chainedgroup_id_catB, User_chainedgroup_id_catC, User_chainedgroup_id_catD, User_freegroup_id_grpA, '.
                      '             User_freegroup_id_grpB, User_freegroup_id_grpC, User_freegroup_id_grpD, User_external_id_join, User_trashed) '.
                      '     VALUES (NULL,   "Andrea",  "Ferroni",  "bubbakk",  "pippo",  "bubba",  "419",  "",  "329 xxxyyyzzzz",  "bubbakk@gmail.com",  1,  1,  1,  1,  0,  0, 0,  0,  0,  0,  0,  0);';
$DBH = $PDO->get_dbh();
$res = $DBH->exec($query_company_data);                                      // insert Company data
if ( $res===false ) die(__LINE__ . " - ".print_r($DBH->errorInfo(), true));

$retval['success'] = true;
json_output_and_die($retval);
