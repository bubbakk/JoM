Version 0.1
===========

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
