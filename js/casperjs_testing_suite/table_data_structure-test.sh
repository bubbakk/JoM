#!/bin/bash

echo
echo "Creating javascript file for test... "
cat "../table_data_structure.js" "../generic_lib.js" "../lib/jquery-1.9.0.min.js" "./table_data_structure-test.js" > "./casper_temp_test.js"
echo "done."

echo
echo "Runnin test..."
casperjs test ./casper_temp_test.js

echo
echo "Goodbye."
echo
