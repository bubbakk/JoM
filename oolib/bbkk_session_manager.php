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

    private $pdo_dbh   = null;          // PDO database class
    private $read_stmt = null;          // read statement
    private $key_stmt  = null;          // key generation statement
    private $gc_stmt   = null;          // garbage collector statement


    /*
       Function: __construct
       The constructor calls <BBKK_Base_Class.__construct> to set base properties and registers session custom handlers.
       Also sets session handlers functions and registers the call to session_write_close() on script shutdown
    */
    public function __construct()
    {
        $this->auto_log = true;                     // set auto-log errors feature on
        parent::__construct(__FILE__, __CLASS__);

        // set our custom session functions.
        session_set_save_handler(array($this, 'open'), array($this, 'close'), array($this, 'read'), array($this, 'write'), array($this, 'destroy'), array($this, 'gc'));

        // This line prevents unexpected effects when using objects as save handlers.
        register_shutdown_function('session_write_close');
    }


    public function __set($name, $value) {
        // set salt property
        if ( $name==='salt' && is_string($value) && !empty($value) ) {
            $this->salt = $value;
        }
        else {
            parent::__set($name, $value);
        }
    }

    /*
       Function: session_start
       Set needed parameters, database connection handler and start session

       Parameters:
         $session_name - session name wanted
         $secure       - pass true if server uses HTTPS
         $_pdo_dbh     - database connection handler

       Returns:
         boolean value according to method success
    */
    public function start_session($session_name, $secure, $_pdo_dbh) {

        // set actual method's name
        $this->method = __METHOD__;

        // check PDO database handler passed
        if ( gettype($_pdo_dbh)!='object' || !(get_class($_pdo_dbh)==='PDO') ) {
            $this->set_error('DB connection error: please submit bug',
                             'Il puntatore all\'oggetto $_pdo_dbh è di tipo  '.gettype($_pdo_dbh).', classe e non PDO '.get_class($_pdo_dbh),
                             __LINE__,
                             E_ERROR);
            return false;
        }
        $this->pdo_dbh = $_pdo_dbh;                 // set PDO database handler pointer

        if ( empty($session_name) ) $session_name = $this->default_session_name;
        // Check if hash is available
        if ( in_array($this->session_hash, hash_algos()) ) {
            // Set the has function.
            ini_set('session.hash_function', $this->session_hash);
        }
        // How many bits per character of the hash.
        // The possible values are '4' (0-9, a-f), '5' (0-9, a-v), and '6' (0-9, a-z, A-Z, "-", ",").
        ini_set('session.hash_bits_per_character', 5);

        // Force the session to only use cookies, not URL variables.
        ini_set('session.use_only_cookies', 1);

        // Get session cookie parameters
        $cookieParams = session_get_cookie_params();
        // Set the parameters
        session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $this->httponly);
        // Change the session name
        session_name($session_name);
        // Now we cat start the session
        session_start();
        // This line regenerates the session and delete the old one.
        // It also generates a new encryption key in the database.
        session_regenerate_id(true);

        return true;
    }


    function open() {
        ;       // nothing to do here
    }

    function close() {
        ;       // won't close database connection: is used by other scripts
    }

    /*
       Function: read
       Read a variable and return its value

       Parameters:
         $session_id - session id (previousely saved in database)
    */
    function read($session_id) {
        /*
        // prepare the statement only once
        if( !($this->read_stmt === null) ) {
            $query = 'SELECT Session_data FROM '.TBL_SESSIONS.' WHERE id = :session_id LIMIT 1';
            $this->read_stmt = $this->pdo_dbh->prepare($query);
        }
        $this->read_stmt->bindParam(":session_id", $session_id);
        $this->read_stmt->execute();

        ?????????????

        $this->read_stmt->bind_result($data);
        $this->read_stmt->fetch();
        $key  = $this->getkey($id);
        $data = $this->decrypt($data, $key);

        return $data;
    */
    }


    function write($session_id, $data) {
/*
        // set actual method's name
        $this->method = __METHOD__;

        // Get unique key
        $key = $this->getkey($session_id);
        // Encrypt the data
        $data = $this->encrypt($data, $key);

        $time = time();
        if(!isset($this->w_stmt)) {
            $this->w_stmt = $this->db->prepare("REPLACE INTO sessions (id, set_time, data, session_key) VALUES (?, ?, ?, ?)");
        }

        $this->w_stmt->bind_param('siss', $id, $time, $data, $key);
        $this->w_stmt->execute();
        return true;
*/
    }


    function destroy() {
        ;       // nothing to do here
    }


    function gc($max) {

        // set actual method's name
        $this->method = __METHOD__;

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

        if( $this->gc_stmt === null ) {
            try {
                $this->gc_stmt = $this->db->prepare("DELETE FROM sessions WHERE set_time < :exipration_time");
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

        $old = time() - $max;

        try {
            $this->gc_stmt->bind_param(':exipration_time', $old);
            $this->gc_stmt->execute();
        }
        catch (PDOException $e)
        {
            $this->set_error('Database interaction error: please submit bug',
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

        $generate_new = false;

        // if no valid $session_id is passed, want to generate a new one
        if ( !isset($session_id) || $session_id === null || empty($session_id) ) {
            $generate_new = true;
        }

        if ( !$generate_new )
        {
            // check that session table name constant is set
            if ( !defined('TBL_SESSIONS') ) {
                $this->set_error('Coding error: please submit bug',
                                 'Constant TBL_SESSIONS for session table name is not set',
                                  __LINE__,
                                  E_ERROR);
                return false;
            }

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
            if( !($this->key_stmt === null) ) {
                try {
                    $this->key_stmt = $this->pdo_dbh->prepare('SELECT Session_key FROM '.TBL_SESSIONS.' WHERE Session_id = :session_id LIMIT 1');
                }
                catch (PDOException $e)
                {
                    $this->set_error('Database query prepare error: please submit bug',
                                     $e->getMessage(),
                                     __LINE__,
                                     E_ERROR);
                    return false;
                }

                try {
                    $this->key_stmt->bind_param(':session_id', $session_id);
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
            }
            // if no row is found, have to generate a new random key
            if ( $row === false ) {
                $generate_new = true;
            }
            // else return the existing one
            else {
                $key = $row;
                if ( defined('JOM_DEBUG') && JOM_DEBUG ) { echo 'returned existing key: '.$key.'<br>'; }
            }
        }

        if ( $generate_new ) {
            // 23 random characters key
            $key = hash($this->session_hash, uniqid(mt_rand(1, mt_getrandmax()), true) );
            if ( defined('JOM_DEBUG') && JOM_DEBUG ) { echo 'generating new random key: '.$key.'<br>'; }
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