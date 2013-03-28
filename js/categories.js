function Categories() {

    /*
       Variable: level
       Needed to select right table, level or sublevel
    */
    this.level          = undefined;

    /*
       Variable: parent_id
       Needed if want to get parented subcategories
    */
    this.parent_id      = undefined;

    /*
       Variable: categories
       JSON data object
    */
    this.categories     = undefined;

    /*
       Variable: level
       Element container of the categories (starting with SELECT tag
    */
    this.jq_pointer     = undefined;

    /*
       Variable: nonce
       JSON object that contains nonce and timestamp fields
    */
    this.nonce          = new Object();

    this.load = function(level, parent_id) {

        if ( this.level     === undefined ) this.level = 1;
        if ( this.parent_id === undefined ) parent_qs = ''
        else                                parent_qs = '&p=' + parent_id;

        $.ajax({
            url:      'ard.php',
            data:     'd=cat&r=lod&n=' + this.nonce.nonce + '&t=' + this.nonce.timestamp + '&l=' + this.level + parent_qs,
            type:     'GET',
			dataType: 'JSON'
        })
        .done(function(data){
            JOM['new_job'].categories.categories = data.data;
            JOM['new_job'].set_categories_list();
        });
    }

    this.add = function(level, parent_level, subject, description) {
    }

    this.remove = function(level, id) {
    }

    this.apply_changes_to_server = function(level, id) {
    }

    this.get_list = function(level) {
    }
}
