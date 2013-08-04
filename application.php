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
// not yet signed in check
//
if ( !isset($_SESSION["user"]["is_logged_in"]) ) {
    // destroy the session
    jom_clear_session();
    // immediate redirect
    jom_immediate_redirect($config['SERVER']['domain'],
                           $config['SERVER']['domain_path'],
                           'login.php',
                           'r=nsi');
    // script dies in the function before
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

?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link href="./humans.txt"                       rel="author"     type="text/plain">
    <link href="./css/bootstrap.min.css"            rel="stylesheet" type="text/css" media="screen">

    <!-- <link href="./css/bootstrap-combined.no-icons.min.css" rel="stylesheet" type="text/css" media="screen"> -->
    <link href="./css/font-awesome.min.css" rel="stylesheet" type="text/css" media="screen">

    <link href="./css/jom_default_style.css"        rel="stylesheet" type="text/css" media="screen">
    <link href="./css/datepicker.css"               rel="stylesheet" type="text/css" media="screen">
    <link href="./css/bootstrap-select.min.css"     rel="stylesheet" type="text/css" media="screen">
    <link href="./css/prettyCheckable.css"          rel="stylesheet" type="text/css" media="screen">
    <link href="./js/lib/bootstrap-editable/bootstrap-editable/css/bootstrap-editable.css" rel="stylesheet" type="text/css" media="screen">
    <!-- external libraries -->
    <script language="javascript" type="text/javascript" src="./js/lib/jquery-1.9.0.min.js"></script>
    <script language="javascript" type="text/javascript" src="./js/lib/bootstrap-datepicker.js"></script>
    <script language="javascript" type="text/javascript" src="./js/lib/bootstrap-select.min.js"></script>
    <script language="javascript" type="text/javascript" src="./js/lib/prettyCheckable.js"></script>
    <script language="javascript" type="text/javascript" src="./js/lib/bootstrap-editable/bootstrap-editable/js/bootstrap-editable.min.js"></script>
    <!-- JoM libraries -->
    <script language="javascript" type="text/javascript" src="./js/application.js"></script>
    <script language="javascript" type="text/javascript" src="./js/search_filters.js"></script>
    <script language="javascript" type="text/javascript" src="./js/generic_lib.js"></script>
    <script language="javascript" type="text/javascript" src="./js/new_job.js"></script>
    <script language="javascript" type="text/javascript" src="./js/job_list.js"></script>
    <script language="javascript" type="text/javascript" src="./js/job.js"></script>
    <script language="javascript" type="text/javascript" src="./js/categories.js"></script>
    <script language="javascript" type="text/javascript" src="./js/statuses.js"></script>
    <script language="javascript" type="text/javascript" src="./js/users.js"></script>
    <script language="javascript" type="text/javascript" src="./js/gui_select.js"></script>
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
                },
                jobs_list: {
                    summary_selected_for_details_bgcolor: "rgba(240, 240, 240, 0.9)",
                    delete_bgcolor: "rgba(252, 106, 106, 0.3)"
                }
            }
        };

        // application runtime values
        JOM.runtime_data = {
            new_job_saved_successfully: false
        };

        // init NEW JOB objects
        JOM.new_job = new New_Job_GUI();
        JOM.new_job.init_events();
        JOM.new_job.set_issues_status('disabled');

        JOM.new_job.categories.nonce    = <?php echo generate_json_javascript_values( '/categories/load', 0, session_id(), $config['SALT'], $config['HASH_ALG'] ); ?>;
        JOM.new_job.categories.context  = 'new_job';
        JOM.new_job.issues.nonce        = <?php echo generate_json_javascript_values( '/categories/load', 0, session_id(), $config['SALT'], $config['HASH_ALG'] ); ?>;
        JOM.new_job.issues.context      = 'new_job';
        JOM.new_job.nonce               = <?php echo generate_json_javascript_values( '/job/new',         0, session_id(), $config['SALT'], $config['HASH_ALG'] ); ?>;
        JOM.new_job.get_categories();

        // init JOB LIST objects
        JOM.job_list = new Job_List_GUI();
        JOM.job_list.nonce              = <?php echo generate_json_javascript_values( '/job/list',        0, session_id(), $config['SALT'], $config['HASH_ALG'] ); ?>;
        JOM.job_list.context            = 'job_list';
        JOM.job_list.users_list.nonce   = <?php echo generate_json_javascript_values( '/users/list',      0, session_id(), $config['SALT'], $config['HASH_ALG'] ); ?>;
        JOM.job_list.DATA__load_users_list();
        JOM.job_list.DATA__load_job_list();

        // init SEARCH FILTERS objects
        JOM.search_filters = new Search_Filters_GUI();
        JOM.search_filters.create_filters(new Array('filter_by_status', 'filter_by_creation_date', 'filter_by_category', 'filter_by_issue'));
        // STATUS filter
        JOM.search_filters.filters.filter_by_status.nonce       = <?php echo generate_json_javascript_values( '/statuses/load', 0, session_id(), $config['SALT'], $config['HASH_ALG'] ); ?>;
        JOM.search_filters.filters.filter_by_status.context     = 'search_filter';
        JOM.search_filters.filters.filter_by_status.gui_widget  = new gui_select_standard( $('#jom_filter_by_status'), true );
        JOM.search_filters.filters.filter_by_status.load();
        // DATE START filter
        JOM.search_filters.filters.filter_by_creation_date.context      = 'search_filter';
        JOM.search_filters.filters.filter_by_creation_date.gui_widget   = new gui_select_standard( $('#jom_filter_by_creation_date'), false );
        // CATEGORY filter
        JOM.search_filters.filters.filter_by_category.nonce     = <?php echo generate_json_javascript_values( '/categories/load', 0, session_id(), $config['SALT'], $config['HASH_ALG'] ); ?>;
        JOM.search_filters.filters.filter_by_category.context   = 'search_filter';
        JOM.search_filters.filters.filter_by_category.gui_widget  = new gui_select_standard( $('#jom_filter_by_category'), true );
        JOM.search_filters.filters.filter_by_category.load();
        // ISSUE filter
        JOM.search_filters.filters.filter_by_issue.nonce        = <?php echo generate_json_javascript_values( '/categories/load', 0, session_id(), $config['SALT'], $config['HASH_ALG'] ); ?>;
        JOM.search_filters.filters.filter_by_issue.context      = 'search_filter';
        JOM.search_filters.filters.filter_by_issue.gui_widget   = new gui_select_standard( $('#jom_filter_by_issue'), true );

        // search button
        JOM.search_filters.init_search($("#jom_search_button"));

        JOM.search_filters.init_category_events();

        jom_init('dd/mm/yyyy');

        $('.selectpicker').selectpicker();

        // collapse/expand filters button
        $("#jom_showhide_filters").on('click', function()
        {
            if ( $(this).children().eq(0).hasClass("icon-expand-alt") )
            {
                // expand
                $(this).children().eq(0).removeClass("icon-expand-alt").addClass("icon-collapse-alt");
                $(this).children().eq(1).text("filters collapse");
                $(".jom_filters_container").slideDown();
                $(".jom_filters_container .selectpicker").selectpicker('refresh');
            }
            else {
                // collapse
                $(this).children().eq(0).removeClass("icon-collapse-alt").addClass("icon-expand-alt");
                $(this).children().eq(1).text("filters expand");
                $(".jom_filters_container").slideUp();
            }
        });
        // set default expand/collapse filtres button status
        $("#jom_showhide_filters i").removeClass("icon-collapse-alt").addClass("icon-expand-alt");
        $("#jom_showhide_filters span").text("filters expand");
        // set default filters status to disabled



        // trigger base events
        dummy = setTimeout(function() {
            JOM.new_job.categories.gui_widget.jq_pointer.trigger('change');
            $(".jom_filters_container .selectpicker").prop("disabled", true);
        }, 1000);
        dummy = setTimeout(function() {
            JOM.search_filters.filters.filter_by_category.gui_widget.jq_pointer.trigger('change');
            $(".jom_filters_container .selectpicker").prop("disabled", true);
        }, 1000);


        $("input.jom_enable_control").on("click", function()
        {
            var ctrl_id  = $(this).attr("data-apply-to");
            var ctrls_id = ctrl_id.split(" ");

            for ( var i = 0 ; i < ctrls_id.length ; i++ ) {
                ctrl_id = ctrls_id[i];
                var tag_name = $("#"+ctrl_id).prop("tagName");
                if ( tag_name == "select" || tag_name == "SELECT" ) {
                    $("#"+ctrl_id).prop("disabled", !$(this).prop("checked")).selectpicker('refresh');
                }
                else {
                    $("#"+ctrl_id).prop("disabled", !$(this).prop("checked"));
                }
            }
        });

        $("input.jom_enable_control").tooltip({delay: JOM.conf.GUI.tooltip.delay});

        $('.dropdown-toggle').dropdown();

    });
    </script>
    <style>
        #jom_create_job_modal .control-group { margin-bottom: 5px; }
        #jom_create_job_modal .jom_message_saving  { display: none; }
        #jom_create_job_modal .jom_message_save_ok { display: none; }
        #jom_create_job_modal .jom_message_save_ko { display: none; }
        .table caption { font-size: 19px; font-weight: bold; font-variant: small-caps; background-color: rgba(220, 220, 220, 0.3); padding: 5px 0; }
        .table th, .table td { border-top-color: #AAA; }
        .table .jom_job_details { display: none; }
        .table .jom_job_details dl.dl-horizontal { padding-top: 0; margin-top: 0; padding-bottom: 0; margin-bottom: 10px; }
        .table .jom_job_details .dl-horizontal dt { width: 110px; padding-top: 0; margin-top: 0; }
        .table .jom_job_details .dl-horizontal dd { margin-left: 125px; }
        .table .jom_job_details dl.dl-horizontal hr  { height: 5px; visibility:hidden; margin: 0; padding: 0; }
        .jom_deleted { opacity: 0.4; }
        dd { margin-left: 20px; }
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
                    <li role="presentation"><a role="menuitem" tabindex="-1" href="#" onclick="javascript: $('.alert');"><i class="icon-user"></i> user properties</a></li>
                    <li role="presentation"><a role="menuitem" tabindex="-1" href="#"><i class="icon-tasks"></i> preferences</a></li>
                    <li role="presentation"><a role="menuitem" tabindex="-1" href="#"><i class="icon-tags"></i> application settings</a></li>
                    <li role="presentation" class="divider"></li>
                    <li role="presentation"><a href="./signout.php"><i class="icon-signout"></i> Sign out</a></li>
                  </ul>
              <li class="divider-vertical"></li>
              <li class="pull-right"><a href="./signout.php"><i class="icon-signout"></i> Sign out</a></li>
            </ul>
          </div>
        </div>

        </br>

<!-- JOB LIST FILTERS -->
        <div class="row jom_filters_container" style="display: none">
                <div class="span3 offset2">
                    <dl style="margin-top: 0;">
                        <dt><input type="checkbox" class="jom_enable_control" data-placement="bottom" title="enable status filter" data-apply-to="jom_filter_by_status"> Filter by status: </dt>
                        <dd>
                            <select id="jom_filter_by_status" class="selectpicker show-menu-arrow jom_filter" data-width="auto">
                                <option></option>
                            </select>
                        </dd>
                    </dl>
                </div>
                <div class="span2">
                    <dl style="margin-top: 0;">
                        <dt><input type="checkbox" class="jom_enable_control" data-placement="bottom" title="enable date start filter" data-apply-to="jom_filter_by_creation_date"> Filter by job creation: </dt>
                        <dd>
                            <select id="jom_filter_by_creation_date" class="selectpicker show-menu-arrow jom_filter" data-width="auto">
                                <option value="1">yesterday</option>
                                <option value="7">last week</option>
                                <option value="14">last two weeks</option>
                                <option value="30">last month</option>
                                <option value="90">last three months</option>
                                <option value="365">last year</option>
                            </select>
                        </dd>
                    </dl>
                </div>
                <div class="span3">
                    <dl style="margin-top: 0;">
                        <dt><input type="checkbox" class="jom_enable_control" data-placement="bottom" title="enable filter by category" data-apply-to="jom_filter_by_category"> Filter by category: </dt>
                        <dd>
                            <select id="jom_filter_by_category" class="selectpicker show-menu-arrow">
                                <option></option>
                            </select>
                        </dd>
                        <dt><input type="checkbox" class="jom_enable_control" data-placement="bottom" title="enable filter by issue" data-apply-to="jom_filter_by_issue"> Filter by issue: </dt>
                        <dd>
                            <select id="jom_filter_by_issue" class="selectpicker show-menu-arrow">
                                <option></option>
                            </select>
                            <i class="icon-spinner icon-spin"></i>
                        </dd>
                    </dl>
                </div>
                <div class="span2" style="padding-top: 80px">
                    <a href="#" class="btn btn-info" id="jom_search_button"><i class="icon-search icon-white"></i> search</a>
                </div>
        </div>

<!-- button NEW JOB and filters -->
        <div class="row" style="margin-bottom: 10px; margin-top: 20px;">
<!-- COLUMN SX -->
            <div class="span2 text-center">
                <a href="#jom_create_job_modal" role="button" class="btn btn-large btn-primary" data-toggle="modal" data-target="#jom_create_job_modal" onclick="javascript: JOM.new_job.GUI__set_mode('input');"><i class="icon-plus-sign icon-white"></i> New Job</a>
            </div>
<!-- COLUMN DX -->
            <div class="span2">
                <a class="btn btn-mini" href="#" id="jom_showhide_filters">
                    <i class="icon-collapse-alt"></i> <span>filters collapse</span>
                </a>
            </div>
        </div>

<!-- JOB LIST VALUES -->
        <div class="row">
            <div class="span2">

            </div>
            <div class="span10">
                <table class="table" id="jom_job_list_table">
                    <!-- <caption>Jobs list</caption> -->
                    <thead>
                        <tr>
                            <th></th>
                            <th>id</th>
                            <th style="width: 70%;">subject</th>
                            <th>owner</th>
                            <th>actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr id="jom_job_row_summary" class="jom_job_summary">
                            <td><button class="btn btn-mini btn-primary jom_show_details_btn jom_click_event" type="button" data-toggle="tooltip" title="show/hide details"><i class="icon-info-sign icon-white"></i></button></td>
                            <td>#1</td>
                            <td>primo job</td>
                            <td>me</td>
                            <td>
                                <button class="btn btn-mini btn-primary jom_edit_btn jom_click_event" type="button" data-toggle="tooltip" title="edit job data"><i class="icon-pencil icon-white"></i></button>
                                <button class="btn btn-mini btn-primary jom_delete_btn jom_click_event" type="button" data-toggle="tooltip" title="delete job"><i class="icon-trash icon-white"></i></button>
                            </td>
                        </tr>
                        <tr id="jom_job_row_details" class="jom_job_details">
                            <td colspan="1" style="background-color: rgba(240, 240, 240, 0.4); text-align: center;">
                                <img class="jom_favourite jom_click_event" src="./img/star_disabled.png">
                            </td>
                            <td colspan="4">
                                <dl class="dl-horizontal">
                                    <dt>Status: </dt> <dd>in progress</dd>
                                    <hr/>
                                    <dt>description: </dt> <dd>bla bla bla ...<br>e ancora bla bla bla</dd>
                                    <hr/>
                                    <dt>created: </dt> <dd>20/04/2013</dd>
                                    <dt>category: </dt> <dd>sample category</dd>
                                    <dt>issue: </dt> <dd>sample issue</dd>
                                </dl>
                            </td>
                        </tr>
                        <tr>
                            <td><button class="btn btn-mini btn-primary" type="button"><i class="icon-info-sign icon-white"></i></button></td>
                            <td>#2</td>
                            <td>second job</td>
                            <td>me</td>
                            <td>B1 B2 B3 B4</td>
                        </tr>
                        <tr>
                            <td><button class="btn btn-mini btn-info" type="button"><i class="icon-info-sign icon-white"></i></button></td>
                            <td>#3</td>
                            <td>terzo job</td>
                            <td>me</td>
                            <td>B1 B2 B3 B4</td>
                        </tr>
                            <td colspan="5">descrizione</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr id="jom_job_list_footer">
                            <td></td>
                            <td colspan="2"></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

<!-- ALERT/WARNING MESSAGE -->
        <div class="row" style="position: absolute; top: 128px;">
            <div class="span6 offset3" style="padding: 0 15px 0 15px; display: none" id="jom_message_container">
                <div class="alert text-center" style="box-shadow: 2px 2px 8px #AAA;">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <div id="jom_message"></div>
                </div>
            </div>
        </div>

<!-- CREATE JOB MODAL -->
    <div id="jom_create_job_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Create new Job</h3>
      </div>
      <div class="modal-body">
        <div class="alert">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <strong>Warning!</strong> Best check yo self, you're not looking too good.
        </div>
        <form class="form-horizontal" id="form_new_job">
        <fieldset>
          <div class="control-group">
            <label class="control-label" for="subject">Subject</label>
            <div class="controls">
              <input class="input-xlarge" type="text" placeholder="What job is about" name="subject">
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="description">Description</label>
            <div class="controls">
              <textarea rows="3" placeholder="Add details here" name="description"></textarea>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="category">Category</label>
            <div class="controls">
              <select name="category" class="selectpicker show-menu-arrow">
                <option></option>
              </select>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="issue">Issue</label>
            <div class="controls">
              <select name="issue" class="selectpicker show-menu-arrow">
                <option></option>
              </select>
              <i class="icon-spinner icon-spin"></i>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="tags">Started</label>
            <div class="controls">
              <div class="input-append date" id="dp3">
                <input class="input-small" name="creation_date" type="text" size="12" value=""></input>
                <span class="add-on"><i class="icon-calendar"></i></span>
              </div>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="priority">Priority</label>
            <div class="controls">
              <div class="btn-group" data-toggle="buttons-radio" name="priority">
                <a href="#" class="btn" >Low</a>
                <a href="#" class="btn">Normal</a>
                <a href="#" class="btn">High</a>
              </div>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="assign_to_me">Assign to me</label>
            <div class="controls">
              <input type="checkbox" name="assign_to_me">
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="open_details">Open details after save</label>
            <div class="controls">
              <input type="checkbox" name="open_details">
            </div>
          </div>
        </fieldset>
        </form>
        <div class="jom_message_saving">
          <div class="text-center lead" style="margin-bottom: 0">
            <i class="icon-spinner icon-spin icon-2x"></i> Saving data ...
          </div>
        </div>
        <div class="jom_message_save_ok">
          <div class="text-center lead" style="margin-bottom: 0">
            <i class="icon-ok icon-2x"></i> Job successfully created.
          </div>
        </div>
        <div class="jom_message_save_ko">
          <div class="text-center lead" style="margin-bottom: 0">
            <i class="icon-warning-sign icon-2x"></i> Sorry: error occurred.
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <a href="#" class="btn" data-dismiss="modal" aria-hidden="true" name="close">Close</a>
        <a href="#" class="btn" name="clear">Clear</a>
        <a href="#" class="btn btn-primary" name="save">Save</a>
      </div>
    </div>

<!-- RIBBON -->
    <div id="jom_version_ribbon">
        <div class="jom_label">ver.</div>
        <div class="jom_version" title="<?php print(JOM_DESC_VER);?>" onclick="javascript: $(this).next().text(jsJOMlib__get_e_commerce_bullshit()); jsJOMlib__animate_opacity($(this).next(), 1);"><?php print(JOM_VERSION);?></div>
        <div class="jom_useful_sentence" style="z-index: 999; margin-top: 44px;"></div>
    </div>

    <script src="./js/lib/bootstrap.min.js"></script>
</body>
</html>
