<?php

/*
 * Copyright 2013, 2013 Andrea Ferroni
 *
 * This file is part of "JoM|The Job Manager".
 *
 * "JoM|The Job Manager" is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * "JoM|The Job Manager" is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Nome-Programma.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

// Database configuration
$config['DB']['type'] = '#DBTYPE#';   // [mysql|sqlite]

// Specific database settings
    // MySQL
    $config['DB']['mysql']['username'] = '#DBUSER#';       // database connection username
    $config['DB']['mysql']['password'] = '#DBPASS#';
    $config['DB']['mysql']['host']     = '#DBHOST#';
    $config['DB']['mysql']['dbname']   = '#DBNAME_MYSQL#';           // database name
    // SQLite
    $config['DB']['sqlite']['dbname']  = '#DBNAME_SQLITE#';    // sqlite database name

$config['SERVER']['domain']       = 'localhost';
$config['SERVER']['domain_path']  = 'jom_git/JoM';
$config['SERVER']['install_path'] = '/var/www/JoM/demo/';

// Application salt
$config['SALT']     = '#APP_SALT#';
$config['HASH_ALG'] = 'sha256';

// tables definition
define('TABLES_PREFIX', '#TBLPRFX#');

// TODO: decide sqlite DB filename definition policy
//$config_SQLite_filename = './SQLitaDB/'.$config_DB['dbname'];

$config['APP']['job_name']  = '**JOB**';
$config['APP']['jobs_name'] = '**JOBS**';
$config['APP']['lang']      = 'eng';



