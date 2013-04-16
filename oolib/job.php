<?php

/*
   Class: JOM_Job
   This class implements all job data management
 */
class JOM_Job extends BBKK_Base_Class {

    private $pdo_dbh    = null;        // PDO database class
    private $table_name = null;        // users table name


    // Job data
    public $job_data    = null;


    private $stmt_save  = null;

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
        if ( $this.id == null )
        {
            if ( $this->stmt_save === null )
            {
                $query = 'INSERT INTO ' . $this->$table_name . ' ' .
                         '            (Job_subject,           Job_description, '                                                    .
                         '             Job_category_level1,   Job_category_level2,   Job_category_level3,  Job_tags, '              .
                         '             Job_priority,          Job_creation_datetime, Job_start_datetime,   Job_deadline_datetime, ' .
                         '             Job_percent_completed, Job_attachment_1,      Job_attachment_2,     Job_assigned_to_user, '  .                         .                                                                                 .
                         '             Job_assigned_to_cg_A,  Job_assigned_to_cg_B,  Job_assigned_to_cg_C, Job_assigned_to_cg_D, '  .
                         '             Job_assigned_to_fg_A,  Job_assigned_to_fg_B,  Job_assigned_to_fg_C, Job_assigned_to_fg_D, '  .
                         '             Job_trashed) '                                                                               .
                         '     VALUES (:subject,              :description, '                                                       .
                         '             :category_level1,      :category_level2,      :category_level3,     :tags, '                 .
                         '             :priority,             :creation_datetime,    :start_datetime,      :deadline_datetime, '    .
                         '             :percent_completed,    :attachment_1,         :attachment_2,        :assigned_to_user, '     .
                         '             :assigned_to_cg_A,     :assigned_to_cg_B,     :assigned_to_cg_C,    :assigned_to_cg_D, '     .
                         '             :assigned_to_fg_A,     :assigned_to_fg_B,     :assigned_to_fg_C,    :assigned_to_fg_D, '     .
                         '             :trashed) ';

                $this->stmt_save = $this->pdo_dbh->prepare($query);
            }

            $this->bind_param_or_null( 'subject',           $this->stmt_save, PDO::PARAM_STR);  // can't be bull
            $this->bind_param_or_null( 'description',       $this->stmt_save, PDO::PARAM_STR);
            $this->bind_param_or_null( 'category_level1',   $this->stmt_save, PDO::PARAM_INT);
            $this->bind_param_or_null( 'category_level2',   $this->stmt_save, PDO::PARAM_INT);
            $this->bind_param_or_null( 'category_level3',   $this->stmt_save, PDO::PARAM_INT);
            $this->bind_param_or_null( 'tags',              $this->stmt_save, PDO::PARAM_STR);

            $this->bind_param_or_null( 'priority',          $this->stmt_save, PDO::PARAM_INT);
            $this->bind_param_or_null( 'creation_datetime', time(),           PDO::PARAM_INT);  // set automatically
            $this->bind_param_or_null( 'start_datetime',    $this->stmt_save, PDO::PARAM_INT);
            $this->bind_param_or_null( 'deadline_datetime', $this->stmt_save, PDO::PARAM_INT);
            $this->bind_param_or_null( 'percent_completed', $this->stmt_save, PDO::PARAM_INT);

            $this->bind_param_or_null( 'attachment_1',      $this->stmt_save, PDO::PARAM_LOB);
            $this->bind_param_or_null( 'attachment_2',      $this->stmt_save, PDO::PARAM_LOB);

            $this->bind_param_or_null( 'assigned_to_user',  $this->stmt_save, PDO::PARAM_INT);
            $this->bind_param_or_null( 'assigned_to_cg_A',  $this->stmt_save, PDO::PARAM_INT);
            $this->bind_param_or_null( 'assigned_to_cg_B',  $this->stmt_save, PDO::PARAM_INT);
            $this->bind_param_or_null( 'assigned_to_cg_C',  $this->stmt_save, PDO::PARAM_INT);
            $this->bind_param_or_null( 'assigned_to_cg_D',  $this->stmt_save, PDO::PARAM_INT);

            $this->bind_param_or_null( 'assigned_to_fg_A',  $this->stmt_save, PDO::PARAM_INT);
            $this->bind_param_or_null( 'assigned_to_fg_B',  $this->stmt_save, PDO::PARAM_INT);
            $this->bind_param_or_null( 'assigned_to_fg_C',  $this->stmt_save, PDO::PARAM_INT);
            $this->bind_param_or_null( 'assigned_to_fg_D',  $this->stmt_save, PDO::PARAM_INT);
            $this->bind_param_or_null( 'trashed',           $this->stmt_save, PDO::PARAM_INT);  // can't be null: 0|1


        }
        // UPDATE
        else {
            $query = 'UPDATE ' . $this->$table_name . ' ' .
                     '   SET ' . $fields_list . ') '.
        }
    }

    /*
       Function: reset_job_data_to_nulls
       Reset all job fields/properties to null values
    */
    public function reset_job_data_to_nulls() {

        $this->job_data('id')                   = null;
        $this->job_data('subject')              = null;
        $this->job_data('description')          = null;
        $this->job_data('category_level1')      = null;
        $this->job_data('category_level2')      = null;
        $this->job_data('category_level3')      = null;
        $this->job_data('tags')                 = null;
        $this->job_data('priority')             = null;
        $this->job_data('creation_datetime')    = null;
        $this->job_data('start_datetime')       = null;
        $this->job_data('deadline_datetime')    = null;
        $this->job_data('percent_completed')    = null;
        $this->job_data('attachment_1')         = null;
        $this->job_data('attachment_2')         = null;
        $this->job_data('assigned_to_user')     = null;
        $this->job_data('assigned_to_cg_A')     = null;
        $this->job_data('assigned_to_cg_B')     = null;
        $this->job_data('assigned_to_cg_C')     = null;
        $this->job_data('assigned_to_cg_D')     = null;
        $this->job_data('assigned_to_fg_A')     = null;
        $this->job_data('assigned_to_fg_B')     = null;
        $this->job_data('assigned_to_fg_C')     = null;
        $this->job_data('assigned_to_fg_D')     = null;
        $this->job_data('trashed')              = null;
    }


    /*
       Function: bind_param_or_bind_null

    */
    private function bind_param_or_bind_null($prm_name, &$stmt, $pdo_prm_type) {
        if ( $this->job_data('description') === null )
            $stmt->bindValue(':' . $prm_name, null, PDO::PARAM_NULL);
        else
            $stmt->bindValue(':' . $prm_name, $this->job_data($prn_name), $pdo_prm_type);

        return $stmt;
    }
}
