<?php



define('DIR_BASE', '../');
require_once(DIR_BASE.'cfg/config.php');
require_once(DIR_LIB.'generic_lib.php');

$retval['success'] = false;
define('CONFIG_TEMPLATE', 'user_config.template.php');
define('CONFIG_FILENAME', 'user_config.php');

// load template config file
$template_file = file_get_contents(DIR_BASE.CONFIG_TEMPLATE);

if ( $template_file === false ) {
    $retval['err_msg'] = 'Can not open config template file. Please check permissions.';
    json_output_and_die($retval);
}
else {
    // reading input parameters

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
    }
    else if ( $dbtype=='mysql' ) {
        $dbuser = post_or_get('dbu');
        $dbpass = post_or_get('dbp');
        $dbhost = post_or_get('dbh');
        $dbhost = post_or_get('dbh');
    }
    $tbl_prefix = post_or_get('tpfx');

    // replace occurrences in the template
    $template_file = str_replace("#DBTYPE#",  $dbtype,     $template_file);
    $template_file = str_replace("#DBNAME#",  $dbname,     $template_file);
    $template_file = str_replace("#DBUSER#",  $dbuser,     $template_file);
    $template_file = str_replace("#DBPASS#",  $dbpass,     $template_file);
    $template_file = str_replace("#DBHOST#",  $dbhost,     $template_file);
    $template_file = str_replace("#TBLPRFX#", $tbl_prefix, $template_file);

    // check write permissions
    if ( !is_writable(DIR_BASE.CONFIG_FILENAME) ) {
        $retval['err_msg'] = 'Configuration file '.CONFIG_FILENAME.' in base installation path is not writeable. Please check permissions';
        $retval['dbg_msg'] = 'File '.DIR_BASE.CONFIG_FILENAME.' not writeable';
        json_output_and_die($retval);
    }

    // save the file
    if ( file_put_contents(DIR_BASE.CONFIG_FILENAME, $template_file) === FALSE ) {
        $retval['err_msg'] = 'Can not save configuration file. Please check permissions in cfg/ directory';
        json_output_and_die($retval);
    }

    $retval['success'] = true;
    json_output_and_die($retval);
}

