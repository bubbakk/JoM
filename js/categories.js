function Categories() {

    /*
       Variable: level
       Needed to select right table, level or sublevel
    */
    this.level          = undefined;

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

    this.load = function(level, parent_id) {

        if ( level     === undefined ) level = 1;
        if ( parent_id === undefined ) parent_id = ''
        else                           parent_id = '&p=' + parent_id;

        $.ajax({
            url:      'ard.php',
            data:     'd=categories&r=read&l=' + level + parent_id,
            type:     'GET',
			dataType: 'JSON'
        })
        .done(function(){
            alert("OK");
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
