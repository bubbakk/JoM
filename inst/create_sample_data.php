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

// open DB and get connection handler
$PDO = open_database($config['DB']['type'], $config['DB'][$config['DB']['type']]);
$DBH = $PDO->get_dbh();


//
// COMPANY
//

// when is complete, replace with specific company object methods
$query_company_data = 'INSERT INTO '.TBL_COMPANIES.' '.
                      '            (Company_name, Company_address, Company_geo_location, Company_main_telephone_number, Company_piva) '.
                      '     VALUES ("Spes s.c.p.a.", "Via L. Corsi, 43", "43.351818,12.925394", "0732 25291", "01475280424")';
$res = $DBH->exec($query_company_data);                                      // insert Company data
if ( $res===false ) die(__LINE__ . " - ".print_r($DBH->errorInfo(), true));


//
// USER
//
$hashed_password = openssl_digest ( 'pippo' , 'sha512' );
$query_user_data = 'INSERT INTO '.TBL_USERS.' '.
                      '            (User_id, User_firstname, User_lastname, User_username, User_password_hash, User_salt, User_nickname, '.
                      '             User_contacts_internal_telephone_number, User_contacts_telephone_number, User_contacts_mobile, '.
                      '             User_contacts_email, User_is_internal_company, User_company_id, User_chainedgroup_id_catA, '.
                      '             User_chainedgroup_id_catB, User_chainedgroup_id_catC, User_chainedgroup_id_catD, User_freegroup_id_grpA, '.
                      '             User_freegroup_id_grpB, User_freegroup_id_grpC, User_freegroup_id_grpD, User_external_id_join, User_trashed) '.
                      '     VALUES (NULL,   "Andrea",  "Ferroni",  "bubbakk",  "'.$hashed_password.'", "", "bubba",  "419",  "",  "329 xxxyyyzzzz",  "bubbakk@gmail.com",  1,  1,  1,  1,  0,  0, 0,  0,  0,  0,  0,  0);';
$res = $DBH->exec($query_user_data);                                      // insert Company data
if ( $res===false ) die(__LINE__ . " - ".print_r($DBH->errorInfo(), true));


//
// CATEGORIES_1
//
$query_categories_1_mysql   = 'INSERT INTO '.TBL_CATEGORIES_1.' '.
                      '            (Category_1_id, Category_1_name, Category_1_description, Category_1_trashed) '.
                      '     VALUES (0, "-- nessuna --", "", 0), '.
                      '            (1, "SPES - Assistenza informatica", "Problematiche con PC, rete, sistema operativo, stampanti, configurazioni, telefonia, server, ecc...", 0), '.
                      '            (2, "Sviluppo software", "Lavori di architettura e creazione codice", 0); ';
$query_categories_1_sqlite  = 'INSERT INTO '.TBL_CATEGORIES_1.' '.
                      '            (Category_1_id, Category_1_name, Category_1_description, Category_1_trashed) '.
                      '     VALUES (0, "-- nessuna --", "", 0); '.
                      '        INSERT INTO '.TBL_CATEGORIES_1.' '.
                      '            (Category_1_id, Category_1_name, Category_1_description, Category_1_trashed) '.
                      '     VALUES (2, "Sviluppo software", "Lavori di architettura e creazione codice", 0); '.
                      '        INSERT INTO '.TBL_CATEGORIES_1.' '.
                      '            (Category_1_id, Category_1_name, Category_1_description, Category_1_trashed) '.
                      '     VALUES (1, "SPES - Assistenza informatica", "Problematiche con PC, rete, sistema operativo, stampanti, configurazioni, telefonia, server, ecc...", 0);';
if ( $config['DB']['type']==='mysql' ) $query_categories_1 = $query_categories_1_mysql;
else                                   $query_categories_1 = $query_categories_1_sqlite;
$res = $DBH->exec($query_categories_1);                                      // insert Categories_A data
if ( $res===false ) die(__LINE__ . " - ".print_r($DBH->errorInfo(), true));


//
// CATEGORIES_2
//
$query_categories_2_mysql  = 'INSERT INTO '.TBL_CATEGORIES_2.' '.
                      '            (Category_2_id, Category_2_id_Category_1, Category_2_name, Category_2_description, Category_2_trashed) '.
                      '     VALUES (1, 1, "Disco o memoria danneggiata", "Problematiche di varia natura su disco fisso o altro sistema di memorizzazione", 0), '.
                      '            (2, 1, "Configurazione/verifica gateway/DNS/NATP", "Configurazione di apparati di rete per accessi e controlli", 0), '.
                      '            (3, 2, "Sviluppo GUI", "Architettura e sviluppo interfaccia utente", 0), '.
                      '            (4, 2, "Sviluppo logiche server-side", "Architettura e sviluppo di software per interazione remota con il server", 0), '.
                      '            (0, 0, "-- nessuna --", "", 0); ';
$query_categories_2_sqlite = 'INSERT INTO '.TBL_CATEGORIES_2.' '.
                      '            SELECT 1 AS "Category_2_id", 1 AS "Category_2_id_Category_1", "Disco o memoria danneggiata" AS "Category_2_name", "Problematiche di varia natura su disco fisso o altro sistema di memorizzazione" AS "Category_2_description", 0 AS "Category_2_trashed" '.
                      '      UNION SELECT 2, 1, "Configurazione/verifica gateway/DNS/NATP", "Configurazione di apparati di rete per accessi e controlli", 0 '.
                      '      UNION SELECT 3, 2, "Sviluppo GUI", "Architettura e sviluppo interfaccia utente", 0 '.
                      '      UNION SELECT 4, 2, "Sviluppo logiche server-side", "Architettura e sviluppo di software per interazione remota con il server", 0 '.
                      '      UNION SELECT 0, 0, "-- nessuna --", "", 0 ';
if ( $config['DB']['type']==='mysql' ) $query_categories_2 = $query_categories_2_mysql;
else                                   $query_categories_2 = $query_categories_2_sqlite;
$res= $DBH->exec($query_categories_2);                                      // insert Categories_B data
if ( $res===false ) die(__LINE__ . " - ".print_r($DBH->errorInfo(), true));



$retval['success'] = true;
json_output_and_die($retval);
