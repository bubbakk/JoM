<?php

// setting this constant to 'true', the script is forced to die() if an E_ERROR is set
define('BBKK_BASE_CLASS__DIE_ON_E_ERRORS', 'true');      // can be set to 'true' or 'false'


/*
   Class: BBKK_Base_Class
   This class implements common useful methods and properties for all classes. Common usage
  is for *error and message logging*.

 */
class BBKK_Base_Class {

    /*
       Variable: $filename
       File name of the class.
    */
    protected    $filename    = '';

    /*
       Variable: $classname
       The name of the class.
    */
    protected    $classname   = '';

    /*
       Variable: $method
       The method name (generally set a while before logging).
    */
    protected    $method      = '';

    /*
       Variable: $line
       The code line (generally set a while before logging).
       Default is a null value (0 could be interpreted as the first line)
    */
    protected    $line        = null;

    /*
       Variable: $version
       Class version. Is a free string.
    */
    protected    $version     = null;

    /*
       Variable: $error
       If true, the class or super-class in in error state.
    */
    protected    $error       = false;

    /*
       Variable: $error_text_usr
       A string message that specifies the error/warning/message. Is user-oriented.
    */
    protected    $error_text_usr  = '';

    /*
       Variable: $error_text_dbg
       A string message that specifies the error/warning/message. Is programmer-oriented.
    */
    public    $error_text_dbg  = '';

    /*
       Variable: $error_type
       Values are the same as PHP constants (see http://php.net/manual/en/errorfunc.constants.php)
    */
    protected $error_type  = 0;

    protected $auto_log    = false;  // set if the error have to be always logged once set

    /*
       Variable: $logger
       Instance of logger object. Must implement a specific interface. By default is not set
    */
    private   $logger      = null;


   /*
    * Constructor: __construct
    * The constructor sets filename and classname properties
    *
    * Parameters:
    *   $file_name  - file name of the calling class (caller can pass the PHP's __FILE__ magic variable)
    *   $class_name - calling class name (caller can pass the PHP's __CLASS__ magic variable)
    */
    public function __construct($file_name = '', $class_name = '')
    {
      //// Preliminary checks
        // Wrong parameter make the script die and the programmer warned

        // parameter must be passed
        if ( empty($file_name) || !is_string($file_name) ) {
            die('File name not set correctly - '. __METHOD__ .' ('. __LINE__ .')');
        }
        if ( empty($class_name) || !is_string($class_name) ) {
            die('Class name not set correctly - '. __METHOD__ .' ('. __LINE__ .')');
        }

        // the file must have .php extension
        $path_parts = pathinfo($file_name);
        if ( strtolower($path_parts['extension']) != 'php' ) {
            die('Only .php files allowed - '. __METHOD__ .' ('. __LINE__ .')');
        }
      //// Preliminary checks end

      //// Set properties
        $this->filename  = $file_name;
        $this->classname = $class_name;
    }


   /*
    * Fuction: __set
    * Set private property via PHP overload magic function
    *
    * When the $error_type property is set, there can be set some automatic operations:
    *  . if the protected property $auto_log is set to true, forces the script to log the error status
    *  . if the constant 'BBKK_BASE_CLASS__DIE_ON_E_ERRORS' is defined to 'true' and the value passed is an E_ERROR, the
    *    script is forced to die(), writing all error data
    *
    * @param   string    the private property name
    * @param   int       the error status
    *
    */
    public function __set($name, $value)
    {
        if ( $name === 'error_type' )
        {
            if ( !is_int($value) || ($value != E_NOTICE && $value != E_WARNING && $value != E_ERROR) )
                die('Error type '.$value.' not admitted - '.__METHOD__.' ('.__LINE__.')');
            else
                $this->error_type = $value;

            // evaluate if the error have to be logged
            if ( $this->auto_log === true )
            {
                $this->log_error(var_export(debug_backtrace(), true), false, false, false );
            }

            // evaluate if the script have to die() because of an E_ERROR
            if ( $value === E_ERROR && defined('BBKK_BASE_CLASS__DIE_ON_E_ERRORS') && BBKK_BASE_CLASS__DIE_ON_E_ERRORS === 'true')
                die($this->get_error_text());
        }
    }

    public function __get($name)
    {
        if ( $name === 'error_type' ) return $this->error_type;
    }

    public function set_error($msg = '', $method = '', $line = null)
    {
      //// Preliminary checks
        // Wrong parameters make the script die and the programmer warned

        // no message, no error
        if ( empty($msg) )
            die('Empty message is not admitted - '. __METHOD__ .' ('. __LINE__ .')');
        // if error line is passed, it must be an integer value
        if ( !is_null($line) && !is_int($line) )
            die('Error line must be an integer - '. __METHOD__ .' ('. __LINE__ .')');

      //// Setting error status and data
        //
        $this->error        = true;
        $this->error_text   = $msg;
        $this->error_method = $method;
        $this->error_line   = $line;
        $this->error_type   = $type;

        return true;
    }

    public function reset_error_state()
    {
        $this->error        = false;
        $this->error_text   = '';
        $this->error_type   = 0;

        return true;
    }

    public function get_error_text()
    {
        $method_text = ( empty($this->error_method) ? '' : htmlentities($this->error_method)        );
        $line_text   = ( is_null($this->error_line) ? '' : ' ('.htmlentities($this->error_line).')' );

        $hash        = ( empty($method_text) && empty($line_text) ? '' : ' - ' );

        return htmlentities($this->error_text).' in file <strong>'.$this->filename.'</strong>'.$hash.$method_text.$line_text;
    }



///// LOGGING METHODS

    public function set_logger($logger_object = null)
    {
      //// Preliminary checks
        // Wrong parameter make the script die and the programmer warned
        if ( $logger_object==null || get_class($logger_object!='BBKK_Logger' ) || !in_array('log_this', get_class_methods($logger_object)) )
            die('Logger object passed is not correct - '. __METHOD__ .' ('. _LINE__ .')');

      //// Setting the private class pointer to logger
        $this->logger = $logger_object;

        return true;
    }

    public function log_error($debug_info = '', $ext1 = false, $ext2 = false, $ext3 = false )
    {
      //// Preliminary checks
        // If logger is not set, this method returns false and does nothing else
        if ( $this->logger == null )
            return false;

      //// Setting log informations to be logged
        //
        $this->logger->file_name       = $this->filename;
        $this->logger->method_name     = $this->error_method;
        $this->logger->line_number     = $this->error_line;
        $this->logger->debug_info      = $debug_info;
        $this->logger->programmer_text = $this->error_text;
        $this->logger->type            = $this->error_type;

        if ( $ext1 ) $this->logger->extended_data_1 = $ext1;
        if ( $ext2 ) $this->logger->extended_data_2 = $ext2;
        if ( $ext3 ) $this->logger->extended_data_3 = $ext3;

        // finally, log the informations
        $this->logger->log_this();
    }
}
?>
