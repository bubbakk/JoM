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
define('DIR_BASE', './');
require_once(DIR_BASE.'cfg/user_config.php');
require_once(DIR_BASE.'cfg/config.php');
require_once(DIR_LIB.'generic_lib.php');
require_once(DIR_LIB.'nonce_lib.php');
require_once(DIR_OOL.'bbkk_base_class.php');
require_once(DIR_OOL.'bbkk_pdo.php');
require_once(DIR_OOL.'bbkk_session_manager.php');

require_once(DIR_LIB.'load_all__functions.php');



//
// database connection
//
$PDO = open_database($config['DB']['type'], $config['DB'][$config['DB']['type']]);  // open DB
$DBH = $PDO->get_dbh();                                                             // get the handler



//
// session manager
//
$SMAN = new BBKK_Session_Manager(TBL_SESSIONS, $DBH);   // constructor
$SMAN->debug_on_screen = false;
$SMAN->salt = $config['SALT'];                          // explicitly set application salt
$SMAN->start_session('', false);                        // starting session



//
// check if signed in
//
if ( !isset($_SESSION["user"]["is_logged_in"]) ) {
    // destroy the session
    jom_clear_session();
    // immediate redirect
    jom_immediate_redirect($config['SERVER']['domain'],
                           $config['SERVER']['domain_path'],
                           'login.php',
                           'r=nsi');
    // script dies in the function above
}



//
// session expired check
//
$session_vars_check = check_session_variables();        // check session variables existance and set default values if not found
// check that the session is active
if ( $session_vars_check === -1 || $session_vars_check === -2 )
{
    // destroy the session
    jom_clear_session();
    // immediate redirect
    jom_immediate_redirect($config['SERVER']['domain'],
                           $config['SERVER']['domain_path'],
                           'login.php',
                           'r=exp');
    // script dies in the function before
}
