<?php

/*
   Class: JOM_Job
   This class implements all job data management
 */
class JOM_Job extends BBKK_Base_Class {

    private $pdo_dbh    = null;        // PDO database class
    private $table_name = null;        // users table name


    // Job data
    public $job_data            = null;

    /*
       Function: __construct
       The constructor calls <BBKK_Base_Class.__construct> to set base properties and registers session custom handlers.
       Also sets PDO database handler pointer
    */
    public function __construct($_table_name = '', $_pdo_dbh = '')
    {
        $this->auto_log = true;                     // set auto-log errors feature on
        parent::__construct(__FILE__, __CLASS__);

        // set actual method's name
        $this->method = __METHOD__;

        $this->log_info('Constructor called');

        // check that session table name constant is set
            if ( empty($_table_name) || !is_string($_table_name) ) {
                $this->set_error($this->USR_ERR_MSG__MISSING_PARAMETER,
                                 'Session table name is not defined set correctly: '. var_dump($_table_name),
                                  __LINE__,
                                  E_ERROR);
                return false;
            }
            $this->table_name = $_table_name;           // set session table_name private property

        // check PDO database handler passed
        if ( empty($_pdo_dbh) || gettype($_pdo_dbh) != 'object' || !(get_class($_pdo_dbh) === 'PDO') ) {
            $this->set_error($this->USR_ERR_MSG__DATABASE_CONNECT,
                             'Pointer to object $_pdo_dbh is  ' . gettype($_pdo_dbh) . ' type and class ' . get_class($_pdo_dbh). '. Should be PDO',
                             __LINE__,
                             E_ERROR);
            return false;
        }
        // set PDO database handler pointer
        $this->pdo_dbh = $_pdo_dbh;

    }

    /*
       Function: save
       Insert or update a job into table
    */
    public function save() {

        // INSERT
        if ( $this.id == null ) {
            $query = 'INSERT INTO ' . $this->$table_name . ' ' .
                     '            ($subject,          $description, $category_level1, $category_level2, $category_level3, $tags, '             .
                                  '$priority,         $creation_datetime, $percent_completed, $attachment_1, $attachment_2, $deadline_datetime,' .
                                  '$assigned_to_user, $assigned_to_cg_A, $assigned_to_cg_B, $assigned_to_cg_C, '                        .
                                  '$assigned_to_cg_D, $assigned_to_fg_A, $assigned_to_fg_B, $assigned_to_fg_C, $assigned_to_fg_D, '      .
                                  '$trashed) '.
                     '     VALUES ( :subject,         :description, :cat1,            :cat2,            :cat3,            :tags) ';

            $stmt_save = $this->pdo_dbh->prepare($query);
        }
        // UPDATE
        else {
            $query = 'UPDATE ' . $this->$table_name . ' ' .
                     '   SET ' . $fields_list . ') '.
        }
    }

    /*
       Function: reset_to_defaults
       reset job fields to default values
    */
    public function reset_to_defaults() {

        $this->job_data('id')                   = null;
        $this->job_data('subject')              = '';
        $this->job_data('description')          = '';
        $this->job_data('category_level1')      = 0;
        $this->job_data('category_level2')      = 0;
        $this->job_data('category_level3')      = 0;
        $this->job_data('tags')                 = '';
        $this->job_data('priority')             = 0;
        $this->job_data('creation_datetime')    = 0;
        $this->job_data('percent_completed')    = 0;
        $this->job_data('attachment_1')         = null;
        $this->job_data('attachment_2')         = null;
        $this->job_data('deadline_datetime')    = 0;
        $this->job_data('assigned_to_user')     = 0;
        $this->job_data('assigned_to_cg_A')     = 0;
        $this->job_data('assigned_to_cg_B')     = 0;
        $this->job_data('assigned_to_cg_C')     = 0;
        $this->job_data('assigned_to_cg_D')     = 0;
        $this->job_data('assigned_to_fg_A')     = 0;
        $this->job_data('assigned_to_fg_B')     = 0;
        $this->job_data('assigned_to_fg_C')     = 0;
        $this->job_data('assigned_to_fg_D')     = 0;
        $this->job_data('trashed')              = 0;
    }
}
