<?php

define('DIR_BASE', '../');
require_once(DIR_CFG . 'config.php');
require_once(DIR_LIB . 'generic_lib.php');

$retval['success'] = false;
define('CONFIG_TEMPLATE', 'user_config.template.php');
define('CONFIG_FILENAME', 'user_config.php');

// load template config file
$template_file = file_get_contents(DIR_BASE.CONFIG_TEMPLATE);

if ( !$template_file ) {
    $retval['err_msg'] = 'Can not open config template file. Please check permissions.';
    output_and_die($retval);
}
else {
    // reading input parameters

    // NOTE: add security code for possibile PHP code injection?
    $dbtype = strtolower( post_or_get('dbtype') );
    $dbname = post_or_get('dbname');
    if ( $dbtype=='SQLite' ) {
        $dbuser = '';
        $dbpass = '';
        $dbhost = '';
    }
    else if ( $dbtype=='MySQL' ) {
        $dbuser = post_or_get('dbuser');
        $dbpass = post_or_get('dbpass');
        $dbhost = post_or_get('dbhost');
    }

    // replace occurrences in the template
    // NOTE: add strip/replace unwanted chars
    // NOTE: SQL injection check
    $template_file = str_replace("#DBTYPE#", $dbtype, $template_file);
    $template_file = str_replace("#DBNAME#", $dbname, $template_file);
    $template_file = str_replace("#DBUSER#", $dbuser, $template_file);
    $template_file = str_replace("#DBPASS#", $dbpass, $template_file);
    $template_file = str_replace("#DBHOST#", $dbhost, $template_file);

    // save the file
    if ( file_put_contents(CONFIG_FILENAME, $template_file) === FALSE ) {
        $retval['err_msg'] = 'Can not save configuration file. Please check permissions in cfg/ directory';
        output_and_die($retval);
    }

    $retval['success'] = true;
    output_and_die($retval);
}

