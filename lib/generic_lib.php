<?php

/*
 * Function: json_output_and_die
 * Print the passed argument in JSON format and die the script
 *
 * Parameters:
 *   $data - data to output in JSON format. Better if is an associative array
 *
 */
function json_output_and_die($data) {
    header('Content-Type: application/json');
    if ( !JOM_DEBUG ) { echo "ciao"; unset($data['dbg_msg']); }
    echo json_encode($data);
    die();
}

/*
 * Function: post_or_get
 * Print the passed argument in JSON format and die the script
 *
 * Parameters:
 *   $data - data to output in JSON format. Better if is an associative array
 *
 */
function post_or_get($name) {
    if ( isset($_GET[$name]) ) {
        return $_GET[$name];
    }

    if ( isset($_POST[$name]) ) {
        return $_POST[$name];
    }

    return false;
}
