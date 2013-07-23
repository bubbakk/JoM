<?php

include_once(DIR_CFG.'tables_definition.php');

/*
   Class: JOM_Job
   This class implements all job data management
 */
class JOM_Job extends BBKK_Base_Class {

    private $pdo_dbh        = null;     // PDO database class
    private $table_name     = null;     // users table name

    private $table_fields   = null;     // table fields array containing data and metadata

    private $stmt_save      = null;
    private $stmt_update    = null;

    private $ADD_PKEY     = true;
    private $ONLY_CHANGES = true;
    private $BIND         = true;

    /*
       Function: __construct
       The constructor calls <BBKK_Base_Class.__construct> to set base properties and registers session custom handlers.
       Also sets PDO database handler pointer
    */
    public function __construct($_table_name = '', $_pdo_dbh = '', $table_fields_data = false)
    {
        $this->auto_log = true;                     // set auto-log errors feature on
        parent::__construct(__FILE__, __CLASS__);

        $this->log_info('Constructor called');

        // check that session table name constant is set
        if ( empty($_table_name) || !is_string($_table_name) ) {
            $this->set_error($this->USR_ERR_MSG__MISSING_PARAMETER,
                             'Job table name is not defined set correctly: '. var_dump($_table_name),
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

        // inititializing table fields
        if ( $table_fields_data ) {
            $this->table_fields = $table_fields_data;
            jom_pdo__reset_table_fields_data_to_defaults($this->table_fields);
        }
    }


    /*
       Function: set_field_value
        DESCRIBE ME
    */
    public function set_field_value($field_name, $value) {
        if ( !isset($this->table_fields[$field_name]) ) return false;
        $this->table_fields[$field_name]['value']      = $value;
        $this->table_fields[$field_name]['is_changed'] = true;
    }


    /*
       Function: save
       Insert or update a job into table
    */
    public function save()
    {
        // INSERT
        if ( $this->table_fields['Job_id']['value'] === null )
        {
            if ( $this->stmt_save === null )
            {
                // base query
                $query = 'INSERT INTO ' . $this->table_name . ' ';

                // create fields list: exclude id, include default valued fields, not for binding
                $fields_list = jom_pdo__create_fields_list($this->table_fields, !$this->ADD_PKEY, !$this->ONLY_CHANGES, !$this->BIND);
                $query .= ' ('.$fields_list.') ';

                // create values parameters for binding
                $fields_bind_list = jom_pdo__create_fields_list($this->table_fields, !$this->ADD_PKEY, !$this->ONLY_CHANGES, $this->BIND);
                $query .= ' VALUES ('.$fields_bind_list.') ';

                $this->stmt_save = $this->pdo_dbh->prepare($query);
            }

            // bind values; do not bind primary key; bind also not-changed values
            jom_pdo__bind_values($this->stmt_save, $this->table_fields, !$this->ADD_PKEY, !$this->ONLY_CHANGES);

            if ( !$this->stmt_save->execute() ) {
                print_r($this->pdo_dbh->errorInfo());
                $this->set_error($this->USR_ERR_MSG__BIND_EXEC_STATEMENT,
                                 'Error in pdo->execute() command',
                                 __LINE__,
                                 E_ERROR);
                return false;
            }

        }
        // UPDATE
        else {
            // base query
            $query =  'UPDATE ' . $this->table_name . ' ';
            // create fields list: exclude id, include default valued fields, not for binding
            $field_value_pairs_list = jom_pdo__create_fields_list($this->table_fields, !$this->ADD_PKEY, $this->ONLY_CHANGES, !$this->BIND);
            $query .= '   SET ' . jom_pdo__create_key_value_list($this->table_fields, !$this->ADD_PKEY, $this->ONLY_CHANGES);
            $query .= ' WHERE Job_id = :Job_id';
echo $query;
            $this->stmt_update = $this->pdo_dbh->prepare($query);

            // bind values; do not bind primary key; bind also not-changed values
            jom_pdo__bind_values($this->stmt_update, $this->table_fields, !$this->ADD_PKEY, $this->ONLY_CHANGES );
            $this->stmt_update->bindValue($this->table_fields['Job_id']['name'],
                                          $this->table_fields['Job_id']['value'],
                                          $this->table_fields['Job_id']['pdo_parm_type']);


            if ( !$this->stmt_update->execute() ) {
                print_r($this->pdo_dbh->errorInfo());
                $this->set_error($this->USR_ERR_MSG__BIND_EXEC_STATEMENT,
                                 'Error in pdo->execute() command',
                                 __LINE__,
                                 E_ERROR);
                return false;
            }
        }

        return true;
    }
}
