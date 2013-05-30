/*
 *
 *
 *
 */
function gui_widgetname(jQ_ptr)
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
    var NAME_block = undefined

    /*
     * Function: update_data
     * Passing JSON object, populate widget with values
     *
     * Parameters:
     *   DATA - JSON data object
     *   keyfieldname - name for the JSON field value
     *   valuefieldname - name for the JSON field text
     */
    THAT.update_data = function(DATA, valuefieldname, textfieldname) {
        alert("Implement me");
    }

    /*
     * Function: clear_data
     * Clear contained values/texts
     */
    THAT.clear_data  = function() {
        THAT.jq_pointer.children().remove();
    }

    // constructor
        // setting jquery DOM object HTML pointer
        THAT.jq_pointer = jQ_ptr;
        // do something like detach block, clear HTML sample data, se events, ....
    // end constructory
}
