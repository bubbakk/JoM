<?php

/**
  * This class implements all facilities to interact with users
  *
  * Exception are only thrown when errors happen. When a method fails normally because can't find data or similar
  * events, it has mechanisms to alert programmer, such a false return or -1 or via a property that need to be
  * checked.
  *
  */
class BBKK_PDO extends BBKK_Base_Class {

    private $dbh;        // PDO object

    public $host     = '';
    public $dbname   = '';  // in case of sqlite, this is the path/to/sqlitedb
    public $username = '';
    public $password = '';

    public $db_type  = '';

    /*
       Function: __construct
       The constructor sets the database type public property and calls <BBKK_Base_Class.__construct> to set base properties
    */
    public function __construct($dbtype_)
    {
        $this->auto_log = true;                     // set auto-log errors feature on
        parent::__construct(__FILE__, __CLASS__);

        $this->db_type = strtolower($dbtype_);
    }


    /*
       Function: get_dbh
       Return the database handler
    */
    public function get_dbh() { return $this->dbh; }


    /*
       Function: open_database
       Call the specific PDO database open method
    */
    public function open_database() {
        switch ($this->db_type) {
            case 'mysql':
                return $this->pdo_mysql_connect();
                break;
            case 'sqlite':
                return $this->pdo_sqlite_connect();
                break;
            default:
                break;
        }
    }

    private function pdo_mysql_connect()
    {
        // check properties
        // TODO

        try
        {
            $this->dbh = new PDO('mysql:host='.$this->host.';dbname='.$this->dbname, $this->username, $this->password);
        }
        catch (PDOException $e)
        {
            $this->set_error($e->getMessage(), __METHOD__, __LINE__);
            $this->error_type = E_ERROR;
            return false;
        }

        return true;
    }

    private function pdo_sqlite_connect()
    {
        try
        {
            $this->dbh = new PDO('sqlite:'.$this->dbname);
        }
        catch (PDOException $e)
        {
            $this->set_error($e->getMessage(), __METHOD__, __LINE__);
            $this->error_type = E_ERROR;
            return false;
        }

        return true;
    }

    /*
       Function: close_database
       Close database connection
    */
    public function close_database() {
        $this->dbh = null;
    }
}
