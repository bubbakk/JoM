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



//
// init
//
define('DIR_BASE', '../');
require_once(DIR_BASE.'cfg/config.php');
require_once(DIR_LIB.'generic_lib.php');

$retval['success'] = false;
define('CONFIG_TEMPLATE', 'user_config.template.php');
define('CONFIG_FILENAME', 'user_config.php');



//
// template config file load
//
$template_file = file_get_contents(DIR_BASE.CONFIG_TEMPLATE);

if ( $template_file === false ) {
    $retval['err_msg'] = 'Can not open config template file. Please check permissions.';
    json_output_and_die($retval);
}



//
// reading input parameters
//
// DBTYPE
if ( post_or_get('dbt')===false) {
    $retval['err_msg'] = 'Missing parameter';
    $retval['dbg_msg'] = 'Missing dbt parameter';
    json_output_and_die($retval);
}
$dbtype = strtolower( post_or_get('dbt') );
if ( $dbtype!='mysql' && $dbtype!='sqlite' ) {
    $retval['err_msg'] = 'Wrong parameter value';
    $retval['dbg_msg'] = 'Parameter dbtype should be a valid value (by now, only mysql or sqlite)';
    json_output_and_die($retval);
}

$dbname = post_or_get('dbn');
if ( $dbtype=='sqlite' ) {
    $dbuser = '';
    $dbpass = '';
    $dbhost = '';
    $dbname_mysql  = '';
    $dbname_sqlite = $dbname.'.sqlite';
}
else if ( $dbtype=='mysql' ) {
    $dbuser = post_or_get('dbu');
    $dbpass = post_or_get('dbp');
    $dbhost = post_or_get('dbh');
    $dbname_mysql  = $dbname;
    $dbname_sqlite = '';
}
$tbl_prefix = post_or_get('tpfx');


//
// random 64 characters string generate (uses extended symbols)
//
$random_salt = generate_random_string(64, false);



//
// occurrences in the template substitution
//
$template_file = str_replace("#DBTYPE#",        $dbtype,        $template_file);
$template_file = str_replace("#DBNAME_MYSQL#",  $dbname_mysql,  $template_file);
$template_file = str_replace("#DBNAME_SQLITE#", $dbname_sqlite, $template_file);
$template_file = str_replace("#DBUSER#",        $dbuser,        $template_file);
$template_file = str_replace("#DBPASS#",        $dbpass,        $template_file);
$template_file = str_replace("#DBHOST#",        $dbhost,        $template_file);
$template_file = str_replace("#TBLPRFX#",       $tbl_prefix,    $template_file);
$template_file = str_replace("#APP_SALT#",      $random_salt,   $template_file);



//
// destination folder write permissions check
//
if ( !is_writable(DIR_CFG) ) {
    $retval['err_msg'] = 'Configuration directory '.DIR_CFG.' is not writeable. Please check write permissions';
    $retval['dbg_msg'] = 'Directory '.DIR_CFG.' not writeable';
    json_output_and_die($retval);
}



//
// destination file write permissions check
//
if ( !is_writable(DIR_CFG.CONFIG_FILENAME) ) {
    $retval['err_msg'] = 'Configuration file '.DIR_CFG.CONFIG_FILENAME.' is not writeable. Please check write permissions';
    $retval['dbg_msg'] = 'File '.DIR_BASE.CONFIG_FILENAME.' not writeable';
    json_output_and_die($retval);
}



//
// file save
//
if ( file_put_contents(DIR_CFG.CONFIG_FILENAME, $template_file) === FALSE ) {
    $retval['err_msg'] = 'Can not save configuration file. Please check permissions in cfg/ directory';
    json_output_and_die($retval);
}



//
// result output
//
$retval['success'] = true;
json_output_and_die($retval);


