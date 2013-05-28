function Categories() {

    var THAT = this;

    /*
       Variable: level
       Needed to select right table, level or sublevel
    */
    THAT.level          = undefined;

    /*
       Variable: parent_id
       Needed if want to get parented subcategories
    */
    THAT.parent_id      = undefined;

    /*
       Variable: categories
       JSON data object
    */
    THAT.categories     = undefined;

    /*
       Variable: level
       Element container of the categories (starting with SELECT tag)
    */
    THAT.jq_pointer     = undefined;

    /*
       Variable: nonce
       JSON object that contains nonce and timestamp fields
    */
    THAT.nonce          = new Object();

    /*
     * Variable: context
     * String value representing object component (is sent via Ajax call and returned back without changes)
     */
    THAT.context    = undefined;

    THAT.load = function(level, parent_id) {

        var req = '';
        if ( THAT.level     === undefined ) THAT.level = 1;

        if ( THAT.parent_id === undefined ) parent_qs = ''
        else                                parent_qs = '&p=' + THAT.parent_id;

        $.ajax({
            url:      'ard.php',
            data:     'd=cat&r=lod&n=' + THAT.nonce.nonce + '&t=' + THAT.nonce.timestamp + '&c=' + THAT.context +
                      '&l=' + THAT.level + parent_qs,
            type:     'GET',
			dataType: 'JSON'
        })
        .done(function(data){
            ;   // nothing to do here...
        });
    }

    THAT.add = function(level, parent_level, subject, description) {
    }

    THAT.remove = function(level, id) {
    }

    THAT.apply_changes_to_server = function(level, id) {
    }

    THAT.get_list = function(level) {
    }
}
