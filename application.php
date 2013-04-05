<?php
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
    <script language="javascript" type="text/javascript" src="./js/categories.js"></script>
    <title>***</title>
    <script>
    $(document).ready(function() {

        // set GUI start status
        $(".container").fadeIn();       // fadeIn GUI

        JOM = new Object();
        JOM['new_job'] = new New_Job_GUI();
        JOM['new_job'].init_events();
        JOM['new_job'].set_issues_status('disabled');

        JOM['new_job'].categories.nonce = <?php echo generate_json_javascript_values( '/categories/load', 0, session_id(), $config['SALT'], $config['HASH_ALG'] ); ?>;
        JOM['new_job'].issues.nonce     = <?php echo generate_json_javascript_values( '/categories/load', 0, session_id(), $config['SALT'], $config['HASH_ALG'] ); ?>;
        JOM['new_job'].get_categories();

        // date and datepicker component in job creation form
        var now_text = jsJOMlib__date_formatted('<?php echo $_SESSION['user']['settings']['i18n']['dateformat']; ?>', '/');
        $("#form_new_job [name='creation_date']").val(now_text);
        $("#form_new_job [name='creation_date']").datepicker({
            format: '<?php echo $_SESSION['user']['settings']['i18n']['dateformat']; ?>',
            weekStart: 0
        });

    });
    </script>
    <style>
        #jom_create_job_modal .control-group { margin-bottom: 5px; }
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

        <div class="row">
<!-- COLUMN SX -->
            <div class="span2 text-center">
                <a href="#jom_create_job_modal" role="button" class="btn btn-large btn-primary" data-toggle="modal" data-target="#jom_create_job_modal"><i class="icon-plus-sign icon-white"></i> New Job</a>
            </div>
            <div class="span3">
                Filter by user:
            </div>
            <div class="span3">
                Filter by date:
            </div>
            <div class="span3">
                Filter by sto cavolo:
            </div>
        </div>
        <div class="row">
            <div class="span2">

            </div>
            <div class="span10">
                <table class="table table-striped">
                    <caption style="border-bottom: 1px solid gray;">Elenco lavori</caption>
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
                        <tr>
                            <td><button class="btn btn-mini btn-success" type="button"><i class="icon-info-sign icon-white"></i></button></td>
                            <td>#1</td>
                            <td>primo job</td>
                            <td>me</td>
                            <td>
                                <button class="btn btn-mini btn-primary" type="button"><i class="icon-pencil icon-white"></i></button>
                                <button class="btn btn-mini btn-primary" type="button"><i class="icon-trash icon-white"></i></button>
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
        <form class="form-horizontal" id="form_new_job">
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
              <div class="input-append date" data-date-format="dd-mm-yyyy">
                <input class="input-small" name="creation_date" type="text" size="12"></input>
                <span class="add-on"><i class="icon-calendar"></i></span>
              </div>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="priority">Priority</label>
            <div class="controls">
              <div class="btn-group" data-toggle="buttons-radio">
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
        </form>
      </div>
      <div class="modal-footer">
        <a href="#" class="btn" name="clear">Clear</a>
        <a href="#" class="btn" data-dismiss="modal" aria-hidden="true">Close</a>
        <a href="#" class="btn btn-primary">Save</a>
      </div>
    </div>

<!-- RIBBON -->
    <div id="jom_version_ribbon">
        <div class="jom_label">ver.</div>
        <div class="jom_version" title="<?php print(JOM_DESC_VER);?>" onclick="javascript: $(this).next().text(get__e_commerce_bullshit()); animate_opacity($(this).next(), 1);"><?php print(JOM_VERSION);?></div>
        <div class="jom_useful_sentence"></div>
    </div>

    <script src="./js/lib/bootstrap.min.js"></script>
</body>
</html>
