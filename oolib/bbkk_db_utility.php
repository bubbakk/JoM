<?php

die("DO NOT LOAD ME!!!");

/**
  * This class implements all facilities to interact with users
  *
  * Exception are only thrown when errors happen. When a method fails normally because can't find data or similar
  * events, it has mechanisms to alert programmer, such a false return or -1 or via a property that need to be
  * checked.
  *
  */
class BBKK_DB_UTILITY extends BBKK_Base_Class{

    /*
       Variable: $table_fields
         Array containing data about fields for the 'Job' table. Each index must have an associative array containing
       the following fields:
         - name:       the name of the field
         - default:    default value
         - value:      is the value set for the field
         - is_pkey:    if exists, is set to true|false. Means that the field is primary key for the table
         - is_changed: can be true or false; means that the contained value is changed
     */
    public $table_fields = null;


    public function __construct() {
        $this->auto_log = true;                     // set auto-log errors feature on
        parent::__construct(__FILE__, __CLASS__);

        $this->log_info('Constructor called');
    }

    /*
       Function: reset_data_to_nulls
       Reset all job fields/properties values to defauls; set also the 'is_changed' array field to false
    */
    public function reset_data_to_defaults()
    {
        foreach ($this->table_fields as $key => $field) {
            $this->table_fields[$key]['value']      = $this->table_fields[$key]['default'];
            $this->table_fields[$key]['is_changed'] = false;
        }
    }


    /*
       Function: create_fields_list
        Create comma separated table field or parameters (for binding) list.

       Parameters:
           - add_pkey: true|false; if set to true, return also the fields set as primary key
           - only_changes: true|false; if set to true, return only fields that have the field 'is_changed' set to true
           - for_bindings: true|false; is set to true, prepend every field value

       Returns:
         false on error, the comma separated list of table fields

       See:
         <table_fields>
    */
    public function create_fields_list($add_pkey = false, $only_changes = false, $for_binding = false)
    {
        $retval_array = array();

        // if creating a list to bind parameters, have to prepend a ':'
        $bind_prepend = '';
        if ( $for_binding === true ) $bind_prepend = ':';

        foreach ($this->table_fields as $key => $field) {
            //
            // USE tables_fields_check_and_fix PRIVATE FUNCTION
            //
            if ( !isset($field['name']) ) {
                $this->set_error('Fix needed', 'Missing "name" field for the table_fields variable', __LINE__, __METHOD__);
                return false;
            }

            if ( $add_pkey === false && isset($field['is_pkey']) && $field['is_pkey'] === true ) {
                ;   // do not add primary key field
            }
            else {
                if ( $only_changes === true && isset($field['is_changed']) && $field['is_changed'] === false ) {
                    ;   // do not add value if is not changed
                }
                else {
                    array_push($retval_array, $bind_prepend.$field['name']);
                }
            }
        }

        return implode(', ', $retval_array);    // return comma separated list
    }


    public function create_key_value_list($add_pkey = false, $only_changes = false)
    {
        $retval_array = array();

        foreach ($this->table_fields as $key => $field) {
            //
            // USE tables_fields_check_and_fix PRIVATE FUNCTION
            //
            if ( !isset($field['name']) ) {
                $this->set_error('Fix needed', 'Missing "name" field for the table_fields variable', __LINE__, __METHOD__);
                return false;
            }

            if ( $add_pkey === false && isset($field['is_pkey']) && $field['is_pkey'] === true ) {
                ;   // do not add primary key field
            }
            else {
                if ( $only_changes === true && isset($field['is_changed']) && $field['is_changed'] === false ) {
                    ;   // do not add value if is not changed
                }
                else {
                    echo "element=> field name: " . $field['name'] . " = :" . $field['name'] . "\n<br>";
                    array_push($retval_array, $field['name'] . " = :" . $field['name']);
                }
            }
        }

        return implode(', ', $retval_array);    // return comma separated list
    }

    /*
       Function: bind_values
        DESCRIBE ME
    */
    public function bind_values(&$stmt, $add_pkey = false, $only_changes = false)
    {
        foreach ($this->table_fields as $key => $field)
        {
            if ( $add_pkey === false && ( isset($field['is_pkey']) && $field['is_pkey'] === true ) ) {
                ;   // do not bind values id field is primary key
            }
            else {
                if ( $only_changes === true && isset($field['is_changed']) && $field['is_changed'] === false ) {
                    ;   // do not bind values if the value is not changed
                }
                else
                {
                    // bind parameter name
                    $bind_param_name = ':' . $field['name'];
                    // PDO:: value type
                    $bind_type = $field['pdo_parm_type'];
                    if ( $field['value'] === null )
                        $bind_type = PDO::PARAM_NULL;

                    // do statement bind
                    echo "bind: " . $bind_param_name . ", " . var_export($field['value'], true) . ", " . $bind_type . "\n<br>";
                    if ( !$stmt->bindValue($bind_param_name, $field['value'], $bind_type) ) {
                        die("no bind!");
                    }
                }
            }
        }
    }



    /*
      CHECK ME, DOCUMENT ME
    */
    private function tables_fields_check_and_fix() {
        $new_table_fields = array();
        foreach ($this->table_fields as $key => $field) {

            $new_table_field = array();

            if ( isset($field['name']) && isset($field['default']) ) {
                // name
                $new_table_field['name']    = $field['name'];
                // default
                $new_table_field['default'] = $field['default'];
                // value
                if ( !isset($field['value']) ) {
                    $new_table_field['value'] = $field['default'];
                }
                // is_changed
                if ( !isset($new_table_fields['is_changed']) ) {
                    $new_table_field['is_changed'] = false;
                }
                array_push($new_table_fields, $new_table_field);
            }
        }

        $this->new_table_fields = $new_table_fields;
    }
}
