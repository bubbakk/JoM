Version 0.2
===========

14.03.2013
----------
- some changes made in BBKK_Base_Class class.
- fixed and tested in BBKK_Session_Manager, methods: constructor(), __set(), start_session()
- added generate_random_string() function in lib/generic_lib.php file
- added $config['SALT'] in user_config.template. Need to add random generation
- added encrypt and decrypt private methods in BBKK_Session_Manager class: can encode/decode any data structure via serialize/unserialize
- added 64 characters string generation during configuration save (useful for encryption tasks)
- implemented garbage collector method in BBKK_Session_Manager class

13.03.2013
----------
- added table Sessions in install procedure
- continues Session Managemen class creation
- created test script in test/ for Session Managemen class tests

11.03.2013
----------
- added new table "Login_attempts" and so all necessary data for installation scripts: tables_specification.php, config.php, create_tables.php
- fixed multiple values INSERT in SQLITE

09.03.2013
----------
- modified User_password field in Users table, from VARCHAR(5) to CHAR(128)
- added User_salt field in Users table, a CHAR(128)

08.03.2013
----------
- created HTML login page (GUI): some animations added; good styling added


Version 0.1
===========

Completed.
Still remains:
- all other table creation (other tables missing: not in the core); sample data too
- MySQL database creation is not yet implemented
- not all data passed to PHP scripts are checked
- BACK button in the (last) installation phase, that can go back to make parameters changes if something goes wrong
- if installation is successful, make appear a button to go to login page
- admin user creation


05.03.2013
----------
- fixed table creation
- fixed sample data creation

03.03.2013
----------
- created js/categories.js; first class definition for categories and subcategories for data interaction
- created ard.php (Ajax Request Dispatcher) in root folder: parses passed 'd' parameter (domain) and opens a PHP contained in ./lib
- renamed tables Categories_[A|B] in Categories_[1|2]

28.02.2013
----------
- table Users created
- table Jobs for sqlite created
- User sample data created

27.02.2013
----------
- working on main application page design: added a modal window for Job creation
- added humans.txt and linked in install and application

26.02.2013
----------
- added Jobs table
- started main application page design
- Javascript function animate_opacity() moved from installation_procedure.js to generic_lib.js

25.02.2013
----------
- sample data creation routines implemented and working.
- installation procedure correctly call all scripts


24.02.2013
----------
- fixed password input field to regular type="password"
- now the installer can create tables in both implemented databases type (mysql and sqlite). Tables prepend correctly managed
- created first table "Companies"
- created tabke "Users"

23.02.2013
----------
- started tables creation
- created inst/create_tables.php script for tables creation (not DB dependant)
- created inst/tables_specification.php; contains an array of tables and CREATE SQL statement, for each DBMS
- fixed user_config_template.php: the array index od DB name is now the same in SQLite ad MySQL (so that there can be one only database open procedure)

21.02.2013
----------
- documented BBKK_Base_Class properties
- created (compiled) first documentation files via naturalDocs
- chiamata alla set_database.php per database MySQL ora attiva
- fix minori nella GUI di installazione



20.02.2013
----------
- added PDO and Base_Class PHP classes
- in PDO, added sqlite database open
- now the inst/set_database.php script creates/opens correctly the SQLite database file

19.02.2013
----------
- simplified ajax calls for installation
- inst/save_config.php reviewed: more error checks and messages; almost finished
- global improvements to save configuration procedure
- minor fixes
- now the installation info result block shows an icon that contains (on hover) the error explanation
- made STEP a global variable
- made the progress bar work
- checked config file save: save_config.php should be ok. Needed more tests, mainly checks against SQL injection
- created the structure for database check/create

18.02.2013
----------
- ribbon improvement

17.02.2013
----------
- updated directory and consts for config.php and installer procedure
- import libraries in install procedure

16.02.2013
----------
- rebuilt in bootstrap-style the install steps; added also a progress bar

15.02.2013
----------
- SQLite specific parameters (form fields) and interactivity (GUI dynamics)
- splitted install.css stylesheet file and created a new generic default style for entire application
- fixed "for" label's attribute
- added description (title) hover the version ribbon element

14.02.2013
----------
- GUI fix: unique buttons "go back" and "proceed"
- better installation steps progression management
- smoother transitions

13.02.2013
----------
- more syling tests: fixed ribbon, animate opacity, scaffolding
- splitted old config.php into 2 files: cnf/config.php and user_config.php (and its template version)
- install.php now includes cnf/config.php: so that the version ribbon contains a dynamic label according to config file
- upgraded also inline css style

12.02.2013
----------
- added Bootstrap components (css, js, imgs)
- minor fix in install.php file
- some install.php page graphic remake

Version 0.0
===========

Completed.
