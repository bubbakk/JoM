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
     * Variable: NAME_block
     * Block used to populate widget
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
        THAT.jq_pointer.children().remove();

        // se new data
        for ( var i = 0 ; i < data.length ; i++ )
        {
            value = data[i][valuefieldname];
            text  = data[i][textfieldname];

            // set new block data
            cloned_option = this.option_block.clone();  // clone base <option> block
            cloned_option.val(value);                   // set value
            cloned_option.html(text);                   // set text

            // append
            THAT.jq_pointer.append(cloned_option);      // append to list
        }

        // update, if needed, selectpicker bootstrap object
        if ( THAT.jq_pointer.hasClass('selectpicker') ) {
            THAT.jq_pointer.selectpicker('refresh');
        }
    }

    // constructor
        // setting jquery DOM object HTML pointer
        THAT.jq_pointer = jQ_ptr;
        // detaching and clearing options
        this.option_block = THAT.jq_pointer.children().eq(0).detach();
        THAT.jq_pointer.children().remove();
    // end constructory
}
