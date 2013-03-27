<?php

// echo __FILE__." loaded\n";
require_once(DIR_OOL.'category.php');

function dispatch_request($request)
{
    // echo "Request: $request\n";
    switch ( $request )
    {
        case 'load':

            global $DBH, $retval, $config;
            global $command;

            $CAT = new JOM_Cateogry(TBL_CATEGORIES_GENERIC, $DBH);  // constructor
            $CAT->level     = post_or_get('l');                     // set level property
            $CAT->parent_id = post_or_get('p');                     // set parent property

            $CAT->load();                               // get categories according to parameters preiousely set

            // generate a new nonce/timestamp for ajax call
            $json_nonce = generate_json_values($command, 0, session_id(), $config['SALT'], $config['HASH_ALG']);
            $retval['new_timestamp'] = $json_nonce['timestamp'];
            $retval['new_nonce']     = $json_nonce['nonce'];
            return;

            break;
        default:
            echo "CANT'T BE HERE!!!!\n";
            break;
    }
}
