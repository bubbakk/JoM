<?php

define('JOM_DEBUG',   'true');
define('JOM_LOG',     'true');
define('JOM_VERSION', '0.0.1');         // see MILESTONES.txt for versioning


// Database configuration
$config['DB']['type'] = '#DBTYPE#';   // [mysql|sqlite]

// Specific database settings
    // MySQL
    $config['DB']['mysql']['username']  = '#DBUSER#';       // database connection username
    $config['DB']['mysql']['password']  = '#DBPASS#';
    $config['DB']['mysql']['host']      = '#DBHOST#';
    $config['DB']['mysql']['dbname']    = '#DBNAME#';           // database name
    // SQLite
    $config['DB']['sqlite']['filename'] = '#DBNAME#.sqlite';    // sqlite database name

$config['SERVER']['domain']       = 'http:\/\/localhost';
$config['SERVER']['domain_path']  = 'JoM';
$config['SERVER']['install_path'] = '/var/www/JoM/demo/';


// TODO: decide sqlite DB filename definition policy
//$config_SQLite_filename = './SQLitaDB/'.$config_DB['dbname'];

$config['APP']['job_name']  = '**JOB**';
$config['APP']['jobs_name'] = '**JOBS**';
$config['APP']['lang']      = 'eng';


// directories definition
//define('DIR_BLK',  'blk/');
//define('DIR_CAC',  'cache/');
define('DIR_LIB',  'lib/');
define('DIR_CSS',  'css/');
//define('DIR_GUI',  'gui/');
define('DIR_I18N', 'i18n/');
define('DIR_IMG',  'imgs/');
define('DIR_JS',   'js/');
define('DIR_LOG',  'log/');
define('DIR_OOL',  'oolib/');
define('DIR_SQLT', 'sqlite/');



// tables definition
define('TABLES_PREFIX',           'jom_');
define('TBL_USERS',               TABLES_PREFIX.'users');
define('TBL_USERS_ACL',           TABLES_PREFIX.'users_acl');
define('TBL_PROBLEMS_CATEGORIES', TABLES_PREFIX.'problems_categories');
define('TBL_LOGGER',              TABLES_PREFIX.'log');

