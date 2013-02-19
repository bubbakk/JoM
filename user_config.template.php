<?php

// Database configuration
$config['DB']['type'] = '#DBTYPE#';   // [mysql|sqlite]

// Specific database settings
    // MySQL
    $config['DB']['mysql']['username']  = '#DBUSER#';       // database connection username
    $config['DB']['mysql']['password']  = '#DBPASS#';
    $config['DB']['mysql']['host']      = '#DBHOST#';
    $config['DB']['mysql']['dbname']    = '#DBNAME_MYSQL#';           // database name
    // SQLite
    $config['DB']['sqlite']['filename'] = '#DBNAME_SQLITE#';    // sqlite database name

$config['SERVER']['domain']       = 'http:\/\/localhost';
$config['SERVER']['domain_path']  = 'JoM';
$config['SERVER']['install_path'] = '/var/www/JoM/demo/';

// tables definition
define('TABLES_PREFIX', '#TBLPRFX#');

// TODO: decide sqlite DB filename definition policy
//$config_SQLite_filename = './SQLitaDB/'.$config_DB['dbname'];

$config['APP']['job_name']  = '**JOB**';
$config['APP']['jobs_name'] = '**JOBS**';
$config['APP']['lang']      = 'eng';



