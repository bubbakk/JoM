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
    return ( $_POST[$name]=='' ? $_GET[$name] : $_POST[$name] );
}
