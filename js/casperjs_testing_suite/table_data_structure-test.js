
casper.test.begin('Data Table: basic tests', function suite(test) {

    var test_int    = 4;
    var test_string = "pippo";
    var test_array = new Array();
    test_array.push({"id": 4});
    test_array.push(1);
    test_array.push(2);

    var OBJ = new JOM__Table_Data_Structure();
    test.assertType(OBJ, "object", "Check JOM__Table_Data_Structure object instancing.");
    test.assertFalsy(OBJ.dependencies_error, "Functions/libraries dependencies check.");
    test.assertFalsy(OBJ.get_filtered_data(), "Getting filtered data when data is not set.");
    test.assertFalsy(OBJ.assign_json_data(test_int), "Assign a non-array-of-JSON object: an integer.");
    test.assertFalsy(OBJ.assign_json_data(test_string), "Assign a non-array-of-JSON object: a string.");
    test.assertFalsy(OBJ.assign_json_data(test_array), "Assign an array of not-all-JSON objects (mixed array).");

    test_array.push(function(){alert("4");});
    test.assertFalsy(OBJ.assign_json_data(test_array), "Assign an array containing a JSON object and a function.");

    test_array.pop();
    test_array.pop();
    test_array.pop();
    test.assertTruthy(OBJ.assign_json_data(test_array), "Assign an array of a single JSON object.");

    test.assertEquals(OBJ.get_filtered_data(), test_array, "Getting filtered data without a set filter.");

    test.done();
});


casper.test.begin('Data Table: filters tests', function suite(test) {

    var data_table_1 = [{"id":"1","name":"-- nessuna --","description":""},
                        {"id":"2","name":"Assistenza informatica","description":"Problematiche con PC, rete, sistema operativo, stampanti, configurazioni, telefonia, server, ecc..."},
                        {"id":"3","name":"Sviluppo software","description":"Lavori di architettura e creazione codice"}];

    var data_table_2 = [{"id":"1","id_category_1":"1","cat_2":"A","name":"-- nessuna --","description":""},
                        {"id":"2","id_category_1":"2","cat_2":"A","name":"Disco o memoria danneggiata","description":"Problematiche di varia natura su disco fisso o altro sistema di memorizzazione"},
                        {"id":"3","id_category_1":"2","cat_2":"B","name":"Configurazione/verifica gateway/DNS/NATP","description":"Configurazione di apparati di rete per accessi e controlli"},
                        {"id":"4","id_category_1":"3","cat_2":"B","name":"Sviluppo GUI","description":"Architettura e sviluppo interfaccia utente"},
                        {"id":"5","id_category_1":"3","cat_2":"C","name":"Sviluppo logiche server-side","description":"Architettura e sviluppo di software per interazione remota con il server"}];

    var OBJ = new JOM__Table_Data_Structure();
    test.assertTruthy(OBJ.assign_json_data(data_table_1), "Assign an array of JSON objects.");

    test.assertFalsy(OBJ.add_filter(4,4), "Assign a wrong field name (not a string).");
    test.assertFalsy(OBJ.add_filter("id",{"id":4}), "Assign a wrong fiel value (not a string and not a number).");

    test.assertTruthy(OBJ.add_filter("id", 4), "Assign a well defined filter");
    test.assertEquals(OBJ.get_filtered_data(), new Array(), "Getting filtered data when filter does not match anything.");

    OBJ.reset_filters();
    test.assertTruthy(OBJ.add_filter("id", "2"), "Assign a well defined filter");
    test.assertEquals(OBJ.get_filtered_data(), [data_table_1[1]], "Getting filtered data using the filter above.");


    test.assertTruthy(OBJ.assign_json_data(data_table_2), "Assign an second and more comples array of JSON objects.");
    OBJ.reset_filters();
    test.assertTruthy(OBJ.add_filter("id_category_1", "2"), "Setting one filter");
    test.assertEquals(OBJ.get_filtered_data(), [data_table_2[1], data_table_2[2]], "Getting filtered data using one filter.");
    test.assertTruthy(OBJ.add_filter("cat_2", "B"), "Adding one other filter");
    test.assertEquals(OBJ.get_filtered_data(), [data_table_2[2]], "Getting filtered data using two filters.");
    test.assertTruthy(OBJ.add_filter("id", 3), "Adding one other filter");
    test.assertEquals(OBJ.get_filtered_data(), [data_table_2[2]], "Getting filtered data using three filters.");
    test.assertTruthy(OBJ.add_filter("id", 5), "Adding one other filter");
    test.assertEquals(OBJ.get_filtered_data(), [], "Getting filtered data using four filters: no data using all filters defined");

    test.done();
});
