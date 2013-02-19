<?php

// Database configuration
$config['DB']['type'] = 'mysql';   // [mysql|sqlite]

// Specific database settings
    // MySQL
    $config['DB']['mysql']['username']  = '';       // database connection username
    $config['DB']['mysql']['password']  = '';
    $config['DB']['mysql']['host']      = 'localhost';
    $config['DB']['mysql']['dbname']    = '';           // database name
    // SQLite
    $config['DB']['sqlite']['filename'] = '';    // sqlite database name

$config['SERVER']['domain']       = 'http:\/\/localhost';
$config['SERVER']['domain_path']  = 'JoM';
$config['SERVER']['install_path'] = '/var/www/JoM/demo/';

// tables definition
define('TABLES_PREFIX', '');

// TODO: decide sqlite DB filename definition policy
//$config_SQLite_filename = './SQLitaDB/'.$config_DB['dbname'];

$config['APP']['job_name']  = '**JOB**';
$config['APP']['jobs_name'] = '**JOBS**';
$config['APP']['lang']      = 'eng';



