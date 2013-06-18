/*
 *
 *
 *
 */
function gui_select_standard(jQ_ptr)
{
    /*
     * Variable: THAT
     * Fix javacript closure scoping
     */
    var THAT = this;

    /*
     * Variable: jq_pointer
     * jQuery object that point to top widget level
     */
    THAT.jq_pointer = undefined;

    /*
     * Variable: auto_enable_switch
     * If true, set the field enabled where there is at least one option to select, to disabled there is no one
     */

    THAT.auto_enable_switch = true;

    /*
     * Variable: NAME_block
     * Block used to populate field
     */
    var option_block = undefined

    /*
     * Function: update_data
     * Passing JSON object, populate widget with values
     *
     * Parameters:
     *   DATA - JSON data object
     *   keyfieldname - name for the JSON field value
     *   valuefieldname - name for the JSON field text
     */
    THAT.update_data = function(data, valuefieldname, textfieldname)
    {
        // clear old data
        THAT.clear_data();

        if ( data === undefined ) data = new Array();

        // se new data
        for ( var i = 0 ; i < data.length ; i++ )
        {
            value = data[i][valuefieldname];
            text  = data[i][textfieldname];

            // set new block data
            cloned_option = option_block.clone();  // clone base <option> block
            cloned_option.val(value);                   // set value
            cloned_option.html(text);                   // set text

            // append
            THAT.jq_pointer.append(cloned_option);      // append to list
        }

        // if auto_enable_switch is true, auto enable/disable field
        if ( THAT.auto_enable_switch ) {
            if ( i == 0 )
                THAT.enable(false);
            else
                THAT.enable();
        }

        // update, if needed, selectpicker bootstrap object
        if ( THAT.jq_pointer.hasClass('selectpicker') ) {
            THAT.jq_pointer.selectpicker('refresh');
        }
    }

    /*
     * Function: clear_data
     * Clear contained values/texts
     */
    THAT.clear_data  = function() {
        THAT.jq_pointer.children().remove();

        // if auto_enable_switch is true, auto enable/disable field
        if ( THAT.auto_enable_switch ) {
            THAT.enable(false);
        }
    }

    /*
     * Function: default_status
     * Select first option and eventually trigger event
     *
     * Parameters:
     *   trigger_change - boolean value: can trigger 'change' event
     */
    THAT.default_status = function(trigger_change) {
        // set the value
        THAT.jq_pointer.val(0);
        // trigger change event
        if ( trigger_change!== undefined && trigger_change === true ) {
            THAT.jq_pointer.trigger('change');
        }
    }

    /*
     * Function: enable
     * Set the field enabled/disabled
     *
     * Parameters:
     *   status - if undefined or true field will be enabled, else disabled
     */
    THAT.enable = function(status) {
        if ( status === undefined || status ) {
            if ( THAT.jq_pointer.prop("disabled") ) {
                THAT.jq_pointer.prop("disabled", false);
                //THAT.jq_pointer.removeAttr("disabled");
                //THAT.jq_pointer.removeClass("disabled");
            }
        }
        else {
            if ( THAT.jq_pointer.prop("disabled") ) {
                THAT.jq_pointer.prop("disabled", true);
                //THAT.jq_pointer.attr("disabled", "disabled");
                //THAT.jq_pointer.addClass("disabled");
            }
        }
    }

    // constructor
        // setting jquery DOM object HTML pointer
        THAT.jq_pointer = jQ_ptr;
        // detaching and clearing options
        option_block = THAT.jq_pointer.children().eq(0).detach();
        THAT.jq_pointer.children().remove();
    // end constructory
}
