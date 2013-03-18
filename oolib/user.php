<?php

/*
   Class: JOM_User
   This class implements all user data management
 */
class JOM_User extends BBKK_Base_Class {


    private $pdo_dbh    = null;        // PDO database class
    private $table_name = null;        // users table name

    private $stmt_login = null;

    private $user_data  = null;

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


    /*
       Function: authenticate
       Test if given user (as username or email) and password hash are right.

       Parameters:
         $user_or_email - username or user email to check
         $password_hash - password hash to check

       Returns:
         true if a user is authenticad, false otherwise
    */
    public function authenticate($user_or_email = '', $password_hash = '') {
        // check if PDO connection is set
        if ( $this->pdo_dbh == null ) {
            $this->set_error($this->USR_ERR_MSG__DATABASE_CONNECT,
                             'Pointer to object $_pdo_dbh is  ' . gettype($_pdo_dbh) . ' type and class ' . get_class($_pdo_dbh). '. Should be PDO',
                             __LINE__,
                             E_ERROR);
            return false;
        }

        if ( $this->stmt_login == null ) {
            try {
                $this->stmt_login = $this->pdo_dbh->prepare('SELECT * '.
                                                           '  FROM ' . $this->table_name . ' '.
                                                           ' WHERE (User_username = :user_name OR User_contacts_email = :user_email) '.
                                                           '   AND User_password_hash = :password '.
                                                           ' LIMIT 1');
            }
            catch (PDOException $e)
            {
                $this->set_error($this->USR_ERR_MSG__PREPARE_STATEMENT,
                                 $e->getMessage(),
                                 __LINE__,
                                 E_ERROR);
                return false;
            }
        }

        try {
            $this->log_info('Binding parameters and executing query.');

            $this->stmt_login->bindParam(':user_name',  $user_or_email, PDO::PARAM_STR);
            $this->stmt_login->bindParam(':user_email', $user_or_email, PDO::PARAM_STR);
            $this->stmt_login->bindParam(':password',   $password_hash, PDO::PARAM_STR);
            $this->stmt_login->execute();

            $this->user_data = $this->stmt_login->fetch(PDO::FETCH_OBJ );        // fetch first column (Session_data)
            $this->stmt_login->closeCursor();

            print "<pre>";
            var_dump($this->user_data);
            print "</pre>";

            if ( $this->user_data === false ) {
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
