<?php
define('DIR_BASE', './');
require_once(DIR_BASE.'cfg/config.php');
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link href="./humans.txt"                rel="author" type="text/plain">
    <link href="./css/bootstrap.min.css"     rel="stylesheet" type="text/css" media="screen">
    <link href="./css/font-awesome.min.css"  rel="stylesheet" type="text/css" media="screen">
    <link href="./css/jom_default_style.css" rel="stylesheet" type="text/css" media="screen">
    <script language="javascript" type="text/javascript" src="./js/lib/jquery-1.9.0.min.js"></script>
    <script language="javascript" type="text/javascript" src="./js/generic_lib.js"></script>
    <script language="javascript" type="text/javascript" src="./js/new_job.js"></script>
    <title>***</title>
    <script>
    $(document).ready(function() {
        $(".container").fadeIn();

        JOM = new Object();
        JOM['new_job'] = new New_Job_GUI();
        JOM['new_job'].init_events();
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
            <p style="border-bottom: 1px solid #EEE; padding-bottom: 9px;">
              <button class="btn" name="clonelast">Clone last</button>
              <button class="btn" name="clear">Clear</button>
            </p>
          <div class="control-group">
            <label class="control-label" for="inputEmail">Subject</label>
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
                <option>A</option>
                <option>B</option>
              </select>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="issue">Issue</label>
            <div class="controls">
              <select name="issue">
                <option>A</option>
                <option>B</option>
              </select>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="assign_to_me">Assign to me</label>
            <div class="controls">
              <input type="checkbox" name="assign_to_me">
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="open_details">Open details</label>
            <div class="controls">
              <input type="checkbox" name="open_details">
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true" name="close">Close</button>
        <button class="btn btn-primary" name="save">Save</button>
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
