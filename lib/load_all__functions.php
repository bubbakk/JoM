<?php

require_once(DIR_OOL.'category.php');
require_once(DIR_OOL.'status.php');


function load_all_categories($level)
{
    global $DBH;

    // Load all Categories for level 2
    $CAT = new JOM_Cateogry(TBL_CATEGORIES_GENERIC, $DBH);
    $CAT->level = $level;
    $CAT->load();

    return $CAT->category_data;
}


function load_all_statuses()
{
    global $DBH;

    // Load Statuses
    $statuses_data = statuses_data_retrieve($DBH);

    return $statuses_data;
}
