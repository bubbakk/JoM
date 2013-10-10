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

// include general libraries files, open datatabase, check session
require_once('./lib/general_application_init.php');




//
// load all categories for level 1
//
$categories_level_1 = load_all_categories(1);
$tbl_name = 'Category_1';
foreach ( $categories_level_1 as $key => $value ) {
    $CAT_1_json_data[] = array( 'id'            => $value[ $tbl_name . '_id'],
                                'name'          => $value[ $tbl_name . '_name'],
                                'description'   => $value[ $tbl_name . '_description'] );
}

//
// Load all Categories for level 2
//
$categories_level_2 = load_all_categories(2);
$tbl_name = 'Category_2';
foreach ( $categories_level_2 as $key => $value ) {
    $CAT_2_json_data[] = array( 'id'            => $value[ $tbl_name . '_id'],
                                'id_category_1' => $value[ $tbl_name . '_id_Category_1'],
                                'name'          => $value[ $tbl_name . '_name'],
                                'description'   => $value[ $tbl_name . '_description'] );
}

//
// Load all Statuses
//
$statuses = load_all_statuses();
$tbl_name = 'Status';
foreach ( $statuses as $key => $value ) {
    $Statuses_json_data[] = array( 'id'       => $value[ $tbl_name . '_id'],
                                   'name'     => $value[ $tbl_name . '_name'],
                                   'is_final' => $value[ $tbl_name . '_is_final'] );
}




$job_id = post_or_get('j');


?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link href="./humans.txt"                       rel="author"     type="text/plain">
    <!-- style sheets -->
    <link href="./css/bootstrap.min.css"                    rel="stylesheet" type="text/css" media="screen">
    <link href="./css/jasny-bootstrap-responsive.min.css"   rel="stylesheet" type="text/css" media="screen">
    <link href="./css/jasny-bootstrap.min.css"              rel="stylesheet" type="text/css" media="screen">
    <link href="./css/font-awesome.min.css"                 rel="stylesheet" type="text/css" media="screen">
    <link href="./css/jom_default_style.css"                rel="stylesheet" type="text/css" media="screen">
    <link href="./css/datepicker.css"                       rel="stylesheet" type="text/css" media="screen">
    <link href="./css/bootstrap-select.min.css"             rel="stylesheet" type="text/css" media="screen">
    <link href="./css/prettyCheckable.css"                  rel="stylesheet" type="text/css" media="screen">
    <link href="./js/lib/bootstrap-editable/bootstrap-editable/css/bootstrap-editable.css" rel="stylesheet" type="text/css" media="screen">
    <!-- external libraries -->
    <!-- jQuery -->
    <script language="javascript" type="text/javascript" src="./js/lib/jquery-1.9.0.min.js"></script>
    <!-- Boostrap extensions -->
    <script language="javascript" type="text/javascript" src="./js/lib/bootstrap-datepicker.js"></script>
    <script language="javascript" type="text/javascript" src="./js/lib/bootstrap-select.min.js"></script>
    <script language="javascript" type="text/javascript" src="./js/lib/prettyCheckable.js"></script>
    <script language="javascript" type="text/javascript" src="./js/lib/bootstrap-editable/bootstrap-editable/js/bootstrap-editable.min.js"></script>
    <!-- JoM libraries -->
    <script language="javascript" type="text/javascript" src="./js/generic_lib.js"></script>
    <title>***</title>
    <script>
    $(document).ready(function() {

        // set GUI start status
        $(".container").fadeIn();       // fadeIn GUI

        JOM = new Object();

        // Application configuration
        JOM.conf = {
            lang:                '<?php echo $_SESSION['user']['settings']['i18n']['language']; ?>',
            dateformat:          '<?php echo $_SESSION['user']['settings']['i18n']['dateformat']; ?>',
            dateseparator:       '<?php echo $_SESSION['user']['settings']['i18n']['dateseparator']; ?>',
            dateformat_human:    '<?php echo $_SESSION['user']['settings']['i18n']['dateformat_human']; ?>',
            dateseparator_human: '<?php echo $_SESSION['user']['settings']['i18n']['dateseparator_human']; ?>',
            GUI: {
                tooltip: {
                    delay: { show: 700, hide: 100 }
                }
            }
        };

        // edit pencil icon show/hide
            $(".jom__edit_field").css("opacity", "0");
            $(".jom__job_editable").on("mouseenter", function(){
                //$(this).find(".jom__edit_field").css("visibility", "visible");
                //$(this).find(".jom__edit_field").hide();
                //$(this).find(".jom__edit_field").fadeIn('fast');
                $(".jom__edit_field").css("opacity", "0");
                $(this).parent().find(".jom__edit_field").animate({opacity: 1}, {duration: 300});
            });
            $(".jom__job_editable").parent().on("mouseleave", function(){
                //$(this).find(".jom__edit_field").css("visibility", "hidden");
                $(this).find(".jom__edit_field").animate({opacity: 0}, {duration: 200});
            });

        // x-editable code
            $.fn.editable.defaults.mode = 'popup';
            $(".jom__job_editable").editable();
    });
    </script>
    <style>
        .row-fluid [class*="span"] { min-height: 2px; }
    </style>
</head>
<body>
    <div class="container" style="width: 88%; display: none;">

<!-- NAVIGATION BAR -->
        <div class="navbar navbar-static-top">
          <div class="navbar-inner">
            <a class="brand" href="#"><strong>JoM|</strong><small>The Job Manager</small></a>
            <ul class="nav">
              <li class="active"><a href="./application.php"><i class="icon-list-ol"></i> Jobs List</a></li>
            </ul>
            <ul class="nav pull-right">
              <li class="pull-right" id="jom_messages" style="background-color: rgba(0, 109, 204, 0.01); box-shadow: inset 0 3px 8px rgba(0, 0, 176, 0.1); display: none;"><a href="#"><i class="icon-spinner icon-spin"></i> saving...</a></li>
              <li class="divider-vertical"></li>
              <li class="dropdown">
                  <a href="#" role="button" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-cog"></i> Settings <b class="caret"></b></a>
                  <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                    <li role="presentation" class="disabled"><a role="menuitem" tabindex="-1" href="#"><i class="icon-user"></i> user settings</a></li>
                    <li role="presentation" class="disabled"><a role="menuitem" tabindex="-1" href="#"><i class="icon-tasks"></i> preferences</a></li>
                    <li role="presentation" class="disabled"><a role="menuitem" tabindex="-1" href="#"><i class="icon-tags"></i> application settings</a></li>
                    <li role="presentation" class="divider"></li>
                    <li role="presentation"><a href="./signout.php"><i class="icon-signout"></i> Sign out</a></li>
                  </ul>
              <li class="divider-vertical"></li>
              <li class="pull-right"><a href="./signout.php"><i class="icon-signout"></i> Sign out</a></li>
            </ul>
          </div>
        </div>
        </div>
        </br>
        </br>


<!-- JOB DATA -->
        <!-- SUBJECT AND CODE -->
        <div class="row">
            <div class="span1 offset3 text-right">
                <i class="icon-pencil icon-white jom__edit_field"></i> <a href="#" class="label label-info jom__job_editable">#12</a>
            </div>
            <div class="span9" style="padding-bottom: 5px;">
                <i class="icon-pencil icon-white jom__edit_field"></i> <a href="#" class="jom__job_editable" style="font-size: 1.6em;">nome/titolo job</a>
            </div>
        </div>

        <!-- BASIC DATA -->
        <div class="row jom__job_basic_data">
            <div class="span8 offset4" style="border-top: 1px solid #AFAFAF;">
                <h4 style="padding-left: 0.9em;">Basic data</h4>
                <dl class="dl-horizontal">
                  <dt>description</dt>
                  <dd><i class="icon-pencil icon-white jom__edit_field"></i> <a href="#" class="jom__job_editable" data-type="textarea" data-placement="bottom"> descrizione</a></dd>
                  <br>
                  <dt>status</dt>
                  <dd><i class="icon-pencil icon-white jom__edit_field"></i> <a href="#" class="jom__job_editable">just created</a></dd>
                  <dt>priority</dt>
                  <dd><i class="icon-pencil icon-white jom__edit_field"></i> <a href="#" class="jom__job_editable">high</a></dd>
                  <dt>assigned to</dt>
                  <dd><i class="icon-pencil icon-white jom__edit_field"></i> me</dd>
                  <br>
                  <dt>started</dt>
                  <dd><i class="icon-pencil icon-white jom__edit_field"></i> 23 Settembre 2013</dd>
                  <dt>category</dt>
                  <dd><i class="icon-pencil icon-white jom__edit_field"></i> IT issue</dd>
                  <dt>issue</dt>
                  <dd><i class="icon-pencil icon-white jom__edit_field"></i> printer unavailable</dd>
                </dl>
            </div>
        </div>

        <!-- ADD NOTES AND FILES -->
        <div class="row jom__job_notes_and_files">
            <div class="span8 offset4" style="border-top: 1px solid #AFAFAF;">
                <h4 style="padding-left: 0.9em;">Add notes and files</h4>
                <form class="form-inline" style="margin-left: 1.8em;">
                    <input type="text" placeholder="add a note">
                    <button type="button" class="btn">save</button>
                </form>
                <form class="form-inline" style="margin-left: 1.8em;">
                    <div class="fileupload fileupload-new" data-provides="fileupload">
                        <div class="input-append">
                            <div class="uneditable-input span3"><i class="icon-file fileupload-exists"></i> <span class="fileupload-preview"></span></div><span class="btn btn-file"><span class="fileupload-new">Select file</span><span class="fileupload-exists">Change</span><input type="file" /></span><a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                        </div>
                        <button type="button" class="btn">upload</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- HISTORY -->
        <div class="row jom__job_history">
            <div class="span8 offset4" style="border-top: 1px solid #AFAFAF;">
                <h4 style="padding-left: 0.9em;">History</h4>
                <span class="label label-info" style="margin-left: 1.8em;">notes</span>
                <span class="label label-info" style="box-shadow: 1px 1px 3px #000000;">changes</span>
                <dl class="dl-horizontal">
                    <dt><i class="icon-remove"></i> 27/10/2013 - 09:34</dt> <dd>creata attività</dd>
                    <dt><i class="icon-remove"></i> 28/10/2013 - 15:49</dt> <dd>creata attività</dd>
                </dl>
            </div>
        </div>

        <!-- FILES -->
        <div class="row jom__job_files">
            <div class="span8 offset4" style="border-top: 1px solid #AFAFAF;">
                <h4 style="padding-left: 0.9em;">Files and documents</h4>
                <dl class="dl-horizontal">
                    <dt><i class="icon-remove"></i> uploaded 27/10/2013 - 09:34</dt> <dd><a href="">creata attività</a></dd>
                </dl>
            </div>
        </div>

<!-- RIBBON -->
    <div id="jom_version_ribbon">
        <div class="jom_label">ver.</div>
        <div class="jom_version" title="<?php print(JOM_DESC_VER);?>" onclick="javascript: $(this).next().text(jsJOMlib__get_e_commerce_bullshit()); jsJOMlib__animate_opacity($(this).next(), 1);"><?php print(JOM_VERSION);?></div>
        <div class="jom_useful_sentence" style="z-index: 999; margin-top: 44px;"></div>
    </div>

    <!-- Bootstrap core -->
    <script src="./js/lib/bootstrap.min.js"></script>
    <script src="./js/lib/jasny-bootstrap.min.js"></script>
</body>
</html>
