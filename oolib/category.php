<?php

/*
   Class: JOM_Categorie
   This class implements all category data management
 */
class JOM_Cateogry extends BBKK_Base_Class {

    private $pdo_dbh    = null;        // PDO database class
    private $table_name = null;        // users table name

    public $level       = null;         // category level; 1 is the first level, 2 is sublevel and so on...
    public $parent_id   = null;


    private $stmt_load  = null;

    public  $category_data  = null;

    // ERROR MESSAGES
    private $USR_ERR_MSG__DATABASE_CONNECT    = 'DB connection error: please submit bug';

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

    public function load()
    {
        // check if PDO connection is set
        if ( $this->pdo_dbh == null ) {
            $this->set_error($this->USR_ERR_MSG__DATABASE_CONNECT,
                             'Pointer to object $_pdo_dbh is  ' . gettype($_pdo_dbh) . ' type and class ' . get_class($_pdo_dbh). '. Should be PDO',
                             __LINE__,
                             E_ERROR);
            return false;
        }

        $tbl_name    = $this->table_name . '_' . $this->level;
        $fld_prepend = 'Category_' . $this->level;

        $where_clause = ' WHERE 1 ';
        if ( $this->level > 1 && $this->parent_id != null && !($this->parent_id === false) ) {
            $where_clause = ' WHERE ' . $fld_prepend . '_id_Category_'.($this->level - 1).' = :parent_id ';
        }

        $query = 'SELECT * '.
                 '  FROM ' . $tbl_name .' ' .
                 $where_clause .
                 'AND ' . $fld_prepend . '_trashed != :trashed ';

        try {
            $this->stmt_load = $this->pdo_dbh->prepare($query);
        }
        catch (PDOException $e)
        {
            $this->set_error($this->USR_ERR_MSG__PREPARE_STATEMENT,
                             $e->getMessage(),
                             __LINE__,
                             E_ERROR);
            return false;
        }


        try {
            $this->log_info('Binding parameters and executing query.');

            if ( $this->level > 1 && $this->parent_id != null && !($this->parent_id === false) ) {
                $this->stmt_load->bindParam(':parent_id',  $this->parent_id, PDO::PARAM_INT);
            }
            $this->stmt_load->bindValue(':trashed', 1, PDO::PARAM_INT);
            $this->stmt_load->execute();

            $this->category_data = $this->stmt_load->fetchAll();
            $this->stmt_load->closeCursor();

/*
            echo "<pre>";
            var_dump($this->category_data);
            echo "</pre>";
*/

            if ( $this->category_data === false ) {
                return false;
            }
        }
        catch (PDOException $e)
        {
            $this->set_error($this->USR_ERR_MSG__BIND_EXEC_STATEMENT,
                             $e->getMessage(),
                             __LINE__,
                             E_ERROR);
            return false;
        }

        return true;
    }
}
