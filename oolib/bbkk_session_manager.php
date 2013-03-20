<?php

/*
   Class: BBKK_SESSION_MANAGER
   This class implements all facilities to manage sessions
 */
class BBKK_Session_Manager extends BBKK_Base_Class {

    private $httponly             = true;         // Stop javascript being able to access the session id.
    private $session_hash         = 'sha512';     // Hash algorithm to use for the sessionid. (use hash_algos() to get a list of available hashes.)
    private $crypt_hash           = 'sha256';     // encrypt/decrypt hashing algorithm
    private $default_session_name = 'JOMsessID';  // Default session name
    private $salt                 = null;         //
    private $table_name           = null;         //
    private $do_encrypt           = false;        // is possible to enable/disable encryption!

    private $pdo_dbh     = null;        // PDO database class
    private $read_stmt   = null;        // read statement
    private $write_stmt  = null;        // write statement
    private $delete_stmt = null;        // delete statement
    private $key_stmt    = null;        // key generation statement
    private $gc_stmt     = null;        // garbage collector statement


    // ERROR MESSAGES
    private $USR_ERR_MSG__DATABASE_CONNECT    = 'DB connection error: please submit bug';
    private $USR_ERR_MSG__HASH_ALG_NOT_EXSTS  = '';     // have to set in the constructor: parametric error!
    private $USR_ERR_MSG__PREPARE_STATEMENT   = 'Database query prepare error: please submit bug';
    private $USR_ERR_MSG__BIND_EXEC_STATEMENT = 'Database interaction error: please submit bug';
    private $USR_ERR_MSG__MISSING_PARAMETER   = 'Missing parameter. Please submit bug';
    private $USR_ERR_MSG__PARAMETER_ERROR     = 'Wrong parameter data and/or type. Please submit bug';



    /*
       Function: __construct
       The constructor calls <BBKK_Base_Class.__construct> to set base properties and registers session custom handlers.
       Also sets session handlers functions and registers the call to session_write_close() on script shutdown
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
                                 'Session table name is not defined set correctly: '. var_export($_table_name, true),
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
            $this->pdo_dbh = $_pdo_dbh;                 // set PDO database handler pointer

        // set our custom session functions.
        session_set_save_handler(array($this, 'open'), array($this, 'close'), array($this, 'read'), array($this, 'write'), array($this, 'destroy'), array($this, 'gc'));

        // This line prevents unexpected effects when using objects as save handlers.
        register_shutdown_function('session_write_close');

        $this->USR_ERR_MSG__HASH_ALG_NOT_EXSTS = 'Hashing algorithm \''. $this->session_hash . '\' is not supported: please submit this message';
    }


    /*
       Function: __set
       Magic function automatically triggered when setting a property. Remember that works only when setting private properties.
       Parameters default values are not set: they always exist!

       Parameters:
         $name - property name
         $value - value to assign to property
    */
    public function __set($name, $value) {

        // set actual method's name
        $this->method = __METHOD__;

        $this->log_info('Magic method called. name: ' . $name . ' value: ' . $value);

        // set salt property
        if ( $name === 'salt' && is_string($value) && !empty($value) ) {
            $this->salt = $value;
        }
        else {
            parent::__set($name, $value);
        }
    }

    /*
       Function: start_session
       Set needed parameters, database connection handler and start session

       Parameters:
         $session_name - session name wanted
         $secure       - pass true if server uses HTTPS
         $_pdo_dbh     - database connection handler

       Returns:
         boolean value according to method success
    */
    public function start_session($session_name = '', $secure = '', $_pdo_dbh = '') {

        // set actual method's name
        $this->method = __METHOD__;

        $this->log_info('Starting session: session_name: ' . $session_name . ' secure: ' . $secure);

        // set a default session name
        if ( empty($session_name) ) {
            $session_name = $this->default_session_name;
            $this->log_info('Setting default session name: ' . $this->default_session_name);
        }

        // Check if hashing algorithm is available
        if ( in_array($this->session_hash, hash_algos()) ) {
            // Set the has function.
            ini_set('session.hash_function', $this->session_hash);
        }
        else {
            $this->set_error($this->USR_ERR_MSG__HASH_ALG_NOT_EXSTS,
                             'Il puntatore all\'oggetto $_pdo_dbh è di tipo  '.gettype($_pdo_dbh).', classe e non PDO '.get_class($_pdo_dbh),
                             __LINE__,
                             E_ERROR);
            return false;
        }

        // Check secure paramter passed
        if ( !is_bool($secure) ) {
            $this->set_error($this->USR_ERR_MSG__PARAMETER_ERROR,
                             'Secure paramter has wrong data type: '. var_export($secure, true),
                              __LINE__,
                              E_ERROR);
            return false;
        }


        // How many bits per character of the hash.
        // The possible values are '4' (0-9, a-f), '5' (0-9, a-v), and '6' (0-9, a-z, A-Z, "-", ",").
        ini_set('session.hash_bits_per_character', 5);

        // Force the session to only use cookies, not URL variables.
        ini_set('session.use_only_cookies', 1);

        // Get session cookie parameters
        $cookieParams = session_get_cookie_params();
        $this->log_info('Cookie parameters: '. var_export($cookieParams, true));
        // Set the parameters
        session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $this->httponly);
        // Change the session name
        session_name($session_name);
        // Now we cat start the session
        session_start();
        $this->log_info('Session started');
        // This line regenerates the session and delete the old one.
        // It also generates a new encryption key in the database.
        //session_regenerate_id(true);
        //$this->log_info('Session regenerate id');

        return true;
    }

    function open() {

        // set actual method's name
        $this->method = __METHOD__;

        $this->log_info('Called open().');

        return true;       // nothing to do here
    }

    function close() {
        // set actual method's name
        $this->method = __METHOD__;

        $this->log_info('Called open().');

        return true;       // won't close database connection: is used by other scripts
    }

    /*
       Function: read
       Read a variable and return its value

       Parameters:
         $session_id - session id (previousely saved in database)
    */
    function read($session_id = '') {

        // set actual method's name
        $this->method = __METHOD__;

        $this->log_info('Called read(). session_id: ' . $session_id);


        // prepare the statement only once
        if( $this->read_stmt === null )
        {
            $this->log_info('Statement does not exist: creating new one.');
            try {
                $this->read_stmt = $this->pdo_dbh->prepare('SELECT Session_data, Session_key FROM ' . $this->table_name . ' WHERE Session_id = :session_id LIMIT 1');
                $this->log_info('SELECT Session_data, Session_key FROM ' . $this->table_name . ' WHERE Session_id = :session_id LIMIT 1');
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

            $this->read_stmt->bindParam(":session_id", $session_id, PDO::PARAM_STR);
            $this->read_stmt->execute();
            $session_data = $this->read_stmt->fetchColumn();        // fetch first column (Session_data)
            $session_key  = $this->read_stmt->fetchColumn(1);       // fetch second column (Session_key)
        }
        catch (PDOException $e)
        {
            $this->set_error($this->USR_ERR_MSG__BIND_EXEC_STATEMENT,
                             $e->getMessage(),
                             __LINE__,
                             E_ERROR);
            return false;
        }

        // Decrypt data
        if ( $this->do_encrypt ) {
            $data = $this->decrypt($session_data, $session_key);
        }
        else {
            //$data = unserialize($session_data);
            $data = $session_data;
        }

        $this->log_info('All done.');

        return $data;
    }


    function write($session_id = '', $session_data = '') {

        // set actual method's name
        $this->method = __METHOD__;

        $this->log_info('Called write(). session_id: ' . $session_id . ' data: ' . $session_data);

        // Get unique key
        $session_key = $this->getkey($session_id);
        $this->method = __METHOD__;                 // annoying manual method property reset after method call

        // Encrypt data
        if ( $this->do_encrypt ) {
            $data = $this->encrypt($session_data, $session_key);
        }
        else {
            //$data = serialize($session_data);
            $data = $session_data;
        }
        $this->method = __METHOD__;                 // annoying manual method property reset after method call

        $time = time();

        // Try first to UPDATE.
        if( $this->write_stmt === null )
        {
            $this->log_info('Statement does not exist: creating new one.');
            try {
                $this->write_stmt = $this->pdo_dbh->prepare('REPLACE INTO ' . $this->table_name . ' (Session_id, Session_set_time, Session_data, Session_key) VALUES (:session_id, :set_time, :data, :session_key)');
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

            $this->write_stmt->bindParam(':session_id', $session_id,    PDO::PARAM_STR);
            $this->write_stmt->bindParam(':set_time',   $time,          PDO::PARAM_INT);
            $this->write_stmt->bindParam(':data',       $data,          PDO::PARAM_STR);
            $this->write_stmt->bindParam(':session_key',$session_key,   PDO::PARAM_STR);
            $this->write_stmt->execute();

            $this->log_info('Affected ' . $this->write_stmt->rowCount() . ' rows');
        }
        catch (PDOException $e)
        {
            $this->set_error($this->USR_ERR_MSG__BIND_EXEC_STATEMENT,
                             $e->getMessage(),
                             __LINE__,
                             E_ERROR);
            return false;
        }

        $this->log_info('All done.');
        return true;
    }


    function destroy($session_id = '') {
        // set actual method's name
        $this->method = __METHOD__;

        $this->log_info('Called destroy()');

        // check that session id is passed
        if ( empty($session_id) || !is_string($session_id) ) {
            $this->set_error($this->USR_ERR_MSG__MISSING_PARAMETER,
                             'Session table name is not defined set correctly: '. var_export($_table_name, true),
                              __LINE__,
                              E_ERROR);
            return false;
        }

        if( $this->delete_stmt === null )
        {
            $this->log_info('Statement does not exist: creating new one.');

            try {
                $this->delete_stmt = $this->pdo_dbh->prepare('DELETE FROM ' . $this->table_name . ' WHERE id = :session_id');
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

            $this->delete_stmt->bindParam(':session_id', $session_id, PDO::PARAM_STR);
            $this->delete_stmt->execute();
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


    function gc($max) {
        // set actual method's name
        $this->method = __METHOD__;

        $this->log_info('Called the garbage collector.');


        if( $this->gc_stmt === null ) {
            try {
                $this->gc_stmt = $this->pdo_dbh->prepare('DELETE FROM ' . $this->table_name . ' WHERE set_time < :exipration_time');
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

        $old = time() - $max;

        try {
            $this->log_info('Deleting garbage.');
            $this->gc_stmt->bindParam(':exipration_time', $old, PDO::PARAM_INT);
            $this->gc_stmt->execute();
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


    /*
       Function: getkey
       Get a unique key (128 characters) for encryption. If exists in the sessions table, return it. If there is no session, return
       a new one.

       Parameters:
         $session_id - session id as stored in the sessions table

       Returns:
         unique generated key (new or existing in Session table); false if something goes wrong
    */
    private function getkey($session_id) {

        // set actual method's name
        $this->method = __METHOD__;

        $this->log_info('Called getkey()');

        $generate_new = false;

        // if no valid $session_id is passed, want to generate a new one
        if ( !isset($session_id) || $session_id === null || empty($session_id) ) {
            $generate_new = true;
        }

        if ( !$generate_new )
        {
            $this->log_info('Retrieve from database the key');

            // check that PDO pointer is set
            if ( $this->pdo_dbh != null              &&
                 gettype($this->pdo_dbh)!='object'   &&
                 !(get_class($this->pdo_dbh)==='PDO')   ) {
                $this->set_error('Database connection error: please submit bug',
                                 'Il puntatore all\'oggetto $_pdo_dbh è di tipo  '.gettype($this->pdo_dbh).', classe e non PDO '.get_class($this->pdo_dbh),
                                 __LINE__,
                                 E_ERROR);
                return false;
            }

            // if does not exist, prepare the statement for selecting session_key in the table
            if( $this->key_stmt === null ) {
                try {
                    $this->key_stmt = $this->pdo_dbh->prepare('SELECT Session_key FROM ' . $this->table_name . ' WHERE Session_id = :session_id LIMIT 1');
                }
                catch (PDOException $e)
                {
                    $this->set_error('Database query prepare error: please submit bug',
                                     $e->getMessage(),
                                     __LINE__,
                                     E_ERROR);
                    return false;
                }
            }

            try {
                $this->log_info('Querying the database: searching key.');

                $this->key_stmt->bindParam(':session_id', $session_id, PDO::PARAM_STR);
                $this->key_stmt->execute();
                $row = $this->key_stmt->fetchColumn();                      // fetches first column (Session_key: the only one requested)
            }
            catch (PDOException $e)
            {
                $this->set_error('Database interaction error: please submit bug',
                                 $e->getMessage(),
                                 __LINE__,
                                 E_ERROR);
                return false;
            }

            // if no row is found, have to generate a new random key
            if ( $row === false ) {
                $this->log_info('Key not found in the database: generating a new one.');
                $generate_new = true;
            }
            // else return the existing one
            else {
                $key = $row;
                $this->log_info('returned existing key: '.$key);
            }
        }

        if ( $generate_new ) {
            // 23 random characters key
            $key = hash($this->session_hash, uniqid(mt_rand(1, mt_getrandmax()), true) );
            $this->log_info('Generated new random key: '.$key);
        }

        return $key;
    }

    /*
       Function: encrypt
       Encrypt the data of the sessions. Do not directly use the key in the encryption, but use it to make the key hash even more random.
       Function uses serialize() before encoding

       Parameters:
         $data - data to encode (can be any type, except resources)
         $session_key - key used to encode data

       Returns:
         unique generated key (new or existing in Session table); false if something goes wrong
    */
    private function encrypt($data, $session_key) {

        // set actual method's name
        $this->method = __METHOD__;

        $this->log_info('Encryption called: session_key: '.$session_key);

        // check salt string
        if ( $this->salt === null || strlen($this->salt) < 64 ) {
            $this->set_error('Salt random string not set or too short',
                             'Salt property too short or not set',
                              __LINE__,
                              E_ERROR);
            return false;
        }

        $key       = substr(hash($this->crypt_hash, $this->salt.$session_key.$this->salt), 0, 32);
        $iv_size   = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv        = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, serialize($data), MCRYPT_MODE_ECB, $iv));

        return $encrypted;
    }

    /*
       Function: decrypt
       Decrypt the data of the sessions. Do not directly use the key in the decryption, but use it to make the key hash even more random.
       Function uses unserialize() to revert.

       Parameters:
         $data - encrypted data to decode
         $session_key - key used to encode data (will be used to decode)

       Returns:
         descripted data
    */
    private function decrypt($data, $session_key) {

        // set actual method's name
        $this->method = __METHOD__;

        $this->log_info('Decryption called: session_key: '.$session_key);

        // check salt string
        if ( $this->salt === null || strlen($this->salt) < 64 ) {
            $this->set_error('Salt random string not set or too short',
                             'Salt property too short or not set',
                              __LINE__,
                              E_ERROR);
            return false;
        }

        $key       = substr(hash($this->crypt_hash, $this->salt.$session_key.$this->salt), 0, 32);
        $iv_size   = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv        = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $decrypted = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, base64_decode($data), MCRYPT_MODE_ECB, $iv);

        return unserialize($decrypted);
    }

}
