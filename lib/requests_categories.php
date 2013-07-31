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

            $tbl_name = 'Category_' . $CAT->level;
            foreach ( $CAT->category_data as $key => $value ) {
                $retval['data'][] = array( 'id'          => $value[ $tbl_name . '_id'],
                                           'name'        => $value[ $tbl_name . '_name'],
                                           'description' => $value[ $tbl_name . '_description'] );
            }

            return true;

            break;
        default:
            echo "CANT'T BE HERE!!!!\n";
            break;
    }
}
