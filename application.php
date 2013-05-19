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
check_session_variables();                              // check session variables existance and set default values if not found

?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link href="./humans.txt"                rel="author" type="text/plain">
    <link href="./css/bootstrap.min.css"     rel="stylesheet" type="text/css" media="screen">
    <link href="./css/font-awesome.min.css"  rel="stylesheet" type="text/css" media="screen">
    <link href="./css/jom_default_style.css" rel="stylesheet" type="text/css" media="screen">
    <link href="./css/datepicker.css"        rel="stylesheet" type="text/css" media="screen">
    <script language="javascript" type="text/javascript" src="./js/lib/jquery-1.9.0.min.js"></script>
    <script language="javascript" type="text/javascript" src="./js/lib/bootstrap-datepicker.js"></script>
    <script language="javascript" type="text/javascript" src="./js/application.js"></script>
    <script language="javascript" type="text/javascript" src="./js/generic_lib.js"></script>
    <script language="javascript" type="text/javascript" src="./js/new_job.js"></script>
    <script language="javascript" type="text/javascript" src="./js/job_list.js"></script>
    <script language="javascript" type="text/javascript" src="./js/categories.js"></script>
    <title>***</title>
    <script>
    $(document).ready(function() {

        // set GUI start status
        $(".container").fadeIn();       // fadeIn GUI


        JOM = new Object();

        JOM.conf = {
            lang:                '<?php echo $_SESSION['user']['settings']['i18n']['language']; ?>',
            dateformat:          '<?php echo $_SESSION['user']['settings']['i18n']['dateformat']; ?>',
            dateseparator:       '<?php echo $_SESSION['user']['settings']['i18n']['dateseparator']; ?>',
            dateformat_human:    '<?php echo $_SESSION['user']['settings']['i18n']['dateformat_human']; ?>',
            dateseparator_human: '<?php echo $_SESSION['user']['settings']['i18n']['dateseparator_human']; ?>'
        }

        JOM.new_job = new New_Job_GUI();
        JOM.new_job.init_events();
        JOM.new_job.set_issues_status('disabled');

        JOM.new_job.categories.nonce = <?php echo generate_json_javascript_values( '/categories/load', 0, session_id(), $config['SALT'], $config['HASH_ALG'] ); ?>;
        JOM.new_job.issues.nonce     = <?php echo generate_json_javascript_values( '/categories/load', 0, session_id(), $config['SALT'], $config['HASH_ALG'] ); ?>;
        JOM.new_job.nonce            = <?php echo generate_json_javascript_values( '/job/new',         0, session_id(), $config['SALT'], $config['HASH_ALG'] ); ?>;
        JOM.new_job.get_categories();

        JOM.job_list = new Job_List_GUI();
        JOM.job_list.nonce           = <?php echo generate_json_javascript_values( '/job/list',        0, session_id(), $config['SALT'], $config['HASH_ALG'] ); ?>;
        JOM.job_list.DATA__load_job_list();

        jom_init('dd/mm/yyyy');

    });
    </script>
    <style>
        #jom_create_job_modal .control-group { margin-bottom: 5px; }
        #jom_create_job_modal .jom_message_saving  { display: none; }
        #jom_create_job_modal .jom_message_save_ok { display: none; }
        #jom_create_job_modal .jom_message_save_ko { display: none; }
        .table caption { font-size: 19px; font-weight: bold; font-variant: small-caps; background-color: rgba(220, 220, 220, 0.3); padding: 5px 0; }
        .table th, .table td { border-top-color: #AAA; }
        .table .details { display: none; }
        .table .details dl.dl-horizontal { padding-top: 0; margin-top: 0; padding-bottom: 0; margin-bottom: 10px; }
        .table .details .dl-horizontal dt { width: 110px; padding-top: 0; margin-top: 0; }
        .table .details .dl-horizontal dd { margin-left: 125px; }
        .table .details dl.dl-horizontal hr  { height: 5px; visibility:hidden; margin: 0; padding: 0; }
    </style>
</head>
<body>
    <div class="container" style="width: 88%; display: none;">

<!-- NAVIGATION BAR -->
        <div class="navbar navbar-static-top">
          <div class="navbar-inner">
            <a class="brand" href="#"><strong>JoM|</strong><small>The Job Manager</small></a>
            <ul class="nav">
              <li class="active"><a href="#"><i class="icon-list-ol"></i> Job List</a></li>
              <li><a href="#">Link</a></li>
              <li class="text-right"><a href="#"><i class="icon-cog"></i> Settings</a></li>
            </ul>
            <form class="navbar-search pull-left">
              <input type="text" class="search-query" placeholder="Search job">
            </form>
          </div>
        </div>

        </br>

<!-- button NEW JOB and filters -->
        <div class="row" style="margin-bottom: 10px;margin-top: 20px;">
<!-- COLUMN SX -->
            <div class="span2 text-center">
                <a href="#jom_create_job_modal" role="button" class="btn btn-large btn-primary" data-toggle="modal" data-target="#jom_create_job_modal" onclick="javascript: JOM.new_job.GUI__set_mode('input');"><i class="icon-plus-sign icon-white"></i> New Job</a>
            </div>
            <div class="span1 text-right">
                <i class="icon-arrow-right icon-2x"></i>
            </div>
            <div class="span3">
                Filter by status: <a>open</a><br/>
                Filter by user: <a>Andrea Ferroni</a><br/>
            </div>
            <div class="span3">
                Filter by date: <a>last week</a><br/>
                Filter by sto cavolo: <a>cippalippa</a>
            </div>
            <div class="span3">
                Filter by date: <a>last week</a><br/>
                Filter by sto cavolo: <a>cippalippa</a>
            </div>
        </div>

<!-- JOB LIST VALUES -->
        <div class="row">
            <div class="span2">

            </div>
            <div class="span10">
                <table class="table" id="jom_job_list_table">
                    <caption>Elenco lavori</caption>
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
                        <tr id="jom_job_row_summary">
                            <td><button class="btn btn-mini btn-success" type="button"><i class="icon-info-sign icon-white"></i></button></td>
                            <td>#1</td>
                            <td>primo job</td>
                            <td>me</td>
                            <td>
                                <button class="btn btn-mini btn-primary" type="button"><i class="icon-pencil icon-white"></i></button>
                                <button class="btn btn-mini btn-primary" type="button"><i class="icon-trash icon-white"></i></button>
                            </td>
                        </tr>
                        <tr id="jom_job_row_details" class="details">
                            <td colspan="1" style="background-color: rgba(240, 240, 240, 0.4); text-align: center;">
                                <img class="jom_favourite" src="./img/star_disabled.png">
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
                </table>
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
              <select name="category">
                <option value="" title=""></option>
              </select>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="issue">Issue</label>
            <div class="controls">
              <select name="issue">
                <option value="" title=""></option>
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
