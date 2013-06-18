Version 0.4: Ticket listing
===========================
Under development.

TODOS:
- create time format (I18N) (hh:mm.ss) and insert in job list details

18.05.2013
----------
- various GUI tests on giving filters a nice placement in the page.... actually is not optimal
- tried/tested checkbox stylish plugin
- added checkboxes, with tooltips, to enable/disable each filter
- changed datepicker with a more friendly select to have creation-date filtering

13.05.2013
----------
- datepicker fixed

12.05.2013
----------
- plugged datepicker into its field in search filters blocks... fixes needed
- theme tests for search filters
- added "search" button in application page

11.05.2013
----------
- fixed session destroy method: query was wrong
- now also the application.php script have its own session expiration check with redirection to login page
- implemented jom_immediate_redirect() parametric function for redirection
- fixed constants in user_config.template: removed protocol specification in URI constant
- removed redirect parameter within JSON reply from ard.php: was the deprecated HTTP_REFERER

07.05.2013
----------
- implemented issues dynamic load on category change in search filters

05.05.2013
----------
- reverted font-awesome to local and updated to latest version

02.05.2013
----------
- continues refactoring new_job -> issues object (like categories)
- fixed some issues on avascript gui objects and form field component
- added auto_enable_switch property in javascript gui_select_standard class: now when update_data() and/or clear_data() are called, the enable() method is called
- modified update_data() method in javascript gui_select_standard class
- modified clear_data() method in javascript gui_select_standard class
- modified enable() method in javascript gui_select_standard class
- made font-awesome locally installed (and pathed the various font formats file path search)

01.05.2013
----------
- refactoring new_job -> issues object (like categories)

30.05.2013
----------
- refactored new_job -> categories object: now it works using gui_select_standard class

29.05.2013
----------
- created gui_select_standard javascript object: the aim is to have one only interface to populate widget values
- created gui_WIDGET_TEMPLATE.js to have a code start for future HTML widgets implementation
- refactored code according to new javascript code introduced (see above)

28.05.2013
----------
- added categories and issues objects instances in search_filter javascript class
- added base setup for categories and issues in application
- added context for ajax callback dispatcher for categories load

27.05.2013
----------
- fixed login fields look
- now statuses are correctly loaded and select/combo data field populated
- added remote load for Font-awesome 3.1.1

26.05.2013
----------
- created search_filter javascript object
- created statuses javascript object
- added parameter 'c' sent by each ajax call: is the call context (needed in centralized callback dispatcher)
- added in ard.php code to read and send back the context (variable 'c')

24.05.2013
----------
- added bootstrap select javascript plugin (see humans.txt)

20.05.2013
----------
- fixed problem in JSON data return (was UTF8 encoding): added recursive_utf8_encode() in generic_lib.php to have recursive UTF8 encoding of all
  returned strings contained in the JSON data field

17.05.2013
----------
- job row element adds correctly
- now live event on info icon press fires correctly
- created global JOM.conf object containing dateformat and dateseparator; changed classes accordingly.
- now details are correctly inserted (the date is converted according i18n format)
- "favourite" star correctly toggles
- click events on buttons are correctly managed
- splitted session variable (as user setting) for data format and separator. Added dateformat_human and separator_human (for example, datepicker
  plugin does not support custom formats)

16.05.2013
----------
- job summary row in the job list is correctly filles with data

15.05.2013
----------
- tested and fixed install procedure (most of all, db creation) after Statuses table add
- added Logger table and install table creation code too
- reset default values in save job routines for categories_is to 1 (instead of null)
- job query created successfully: data are returned
- job list data are now returned in JSON format


13.05.2013
----------
- created Job_List_GUI javascript class with minimal ajax load method call
- created Job_List_GUI object instance and load method call in application
- created backend structures, file and functions for server side request dispatch

09-10.05.2013
-------------
- created gui layout for job infos
- added Statuses table (contains job possible statuses) and sample data

08.05.2013
----------
- fixed open/close job info row
- enhanched job info





Version 0.3: Ticket creation
============================
Completed.

TODOS:
- better implement datepicker (changing date in the input fields does not replect datepicker widget)
- check UTF-8 encoding on POST sending data method in Ajax call
- check new job bad answer "save ko" possible error messages
- implement a message in the login page (for example to inform about session timeout)
- implement also redirect in the login page
- in the new job form, an alert appears when the first date is picked


04.05.2013
----------
- formalized new job form states in a class
- set form messages (saving, ok, ko) as form elements
- refactored and fixed code

30.04.2013
----------
- added in session the last_visit value for user after login (requests_users.php - login)
- added checks and renew for the last_visit session user variable in the ard.php. If session expired, return some ajax redirect infos
- added check code in check_session_variables() in lib/generic_lib.php file
- added 2 other messages in the new job modal for OK and KO
- now, if job save is successful, a message is printed; refine the code using new_job class methods!
- redirect on session expire works in application.php script
- BUG FIX: if loggin in, do not have to check if session expired

26.04.2013
----------
- now text parameters passed using GET methods are correctly URLencoded/decoded
- now the login operation sets in SESSION also user's id and full name (first name and last name)
- JOB SAVE DATA completed using GET method
- minor fixes

24.04.2013
----------
- added utf8_decode in GET PHP parameter to correctly convert charset in generic_lib.php function get_or_post()

22.04.2013
----------
- changed method name from reset_job_data_to_nulls to reset_job_data_to_defaults

19.04.2013
----------
- removed attachment fields in the Job table
- coding and testing save job methods

17.04.2013
----------
- added jsJOMlib__string_date_to_object() function to generic_lib.js
- now the ajax save job call sends a unix timestamp as start date parameter

16.04.2013
----------
- in JOM_Job class, renamed reset_to_defaults() to reset_job_data_to_nulls() and chanded behaviour according to its name
- removed all "NOT NULL" in fields for JoB table creation SQL query in tables_specifications.php
- created new field in jobs table: start_datetime; changed fields order (all *_datetime are now in sequence)
- documented some New_Job_GUI javascript class properties and methods; started creating namespacing for methods


15.04.2013
----------
- in JOM_Job class, added reset_to_defaults() method
- fixed animate_opacity() function rename in jsJOMlib__animate_opacity() in install.php


14.04.2013
----------
- created structures to make the request work in Ajax Request Dispatcher
- created requests_job.php file to manage the job requests
- added constructor to JOM_Job class
- fixed tables name in tables creation during install procedure

13.04.2013
----------
- now values are correctly sent to server

11.04.2013
----------
- fixed new-economy message position: now is readable
- before sending new job data to server, modal form buttons are disabled, fields disappear, and a "saving" message appears

10.04.2013
----------
- in new-job form
  - added save button event
  - added read values on save
  - stripped some space (20px!) from the form margin bottom
  - fixed jQuery clear button object pointer caching (bad selector)
- creted jom_init() function and copied inside init GUI opations (such as datepicker initializazion, ...)
- changed license from GPLv3 to AGPLv3 (deleted LICENSE.md file and created LICENSE.txt file in place)
- added license headers in root files... todo all the remaining...
- added default date separator character in config.php as I18N default parm and corresponsing session (with default) parameter
- added in new_job javascript class, methods for read and check data
- added javasctipt library for date check against format and separator
- in new_job javascript class assigned this to var THAT (can prevent javascript scope tipical issues)
- added trim javascirpt function from php.js
- completed implementation for read data and check before save; this includes highlight the field containing error

09.04.2013
----------
- continues datepicker bootstrap plugin integration... some stupid problems make me waste time....
- ...now fixed!

08.04.2013
----------
- in login page, fixed the login button icon change

05.04.2013
----------
- rebuild naturaldocs documentation: naturaldocs -i /var/www/jom_git/JoM/ -o HTML /var/www/jom_git/JoM/docs/ -p /var/www/jom_git/JoM/docs_proj/ -r
- fixed serious bug in bbkk_session_manager class, read() method: could not read stored values!
- created variable $query in bbkk_session_manager class to debug/log. Added also log information for every parameter binding.
- now date field in new-job modal is set correctly by default width current date
- datepicker boostrap plugin calendar now works correctly in new-job modal

03.04.2013
----------
- CSS patch for tags
- removed tag field in ticket creation fieldset
- added data field in ticket creation fieldset
- added Datepicker for Bootstrap plugin
- refactored functions name in JS library in lib/generic_lib.js: added namespacing
- added documentation to jsJOMlib__getParameterByName() JS function
- now datepicker opens on date input field in the "new job" fieldset

02.04.2013
----------
- changed issues GUI interaction from hidden/shown to disabled/enabled
- added load icon during issues load
- in new_job javascript class added function that manages issues list status

30.03.2013
----------
- added priority field for "Create new Job" form
- added tags field for "Create new Job" form
- added XOXCO jQuery tags input plugin to use tags

29.03.2013
----------
- fixed category/subcategory issues; now use tha very same class in Javascript too.
- Ajax callbacks are now centralized as $(document).ajaxComplete() function
- created application.js that contains $(document).ajaxComplete() callback that can manage Ajax return values in a centralized way
- added generate_json_javascript_values() function to nonce library

28.03.2013
----------
- now nonce timestamp have 5 more characters to have more randomness... is not so unfrequent that one can generate 2 nonces in the ver
  same Unix timestamp second
- fixed ajax calls for categories without have to distinguish between parenting level but the p GET parameter

28.03.2013
----------
- completed all component inveolved in fetching categories via Ajax
- fixed data creation samples for categories during install procedure
- fixed a bug in the query for nonces management (error in SQL query statement)

27.03.2013
----------
- added in application the session initialization
- added in categories javascript class the nonce parameters passing
- added generate_json_javascript_values() function in nonce-lib
- ajax call to categories is successful (application, new_job javascript class, category javascript class, ajax call, dispatcher, category PHP object)

26.03.2013
----------
- added application HTML page
- added new_job javascript class for GUI interaction
- added categories javascript for categories management
- tested load() method for category, to load 1st level, 2nd level and 2nd level with filter





Version 0.2: Login and security
===============================
Completed.

TODOS:
- implement a method in bbkk_base_class that checks $pdo variable connection and $tablename variable. Also some general user error messages, like prepare statement, connection missing, ....
- fix login fields resize
- add checks and documentation comments to nonce library
- do not check session expirement only in ajax call, but as one of the first steps of each application file
- login page is also a redirect destination for common problems such as session expiration: should add a warning message to explain the reason of redirection (parameter passed 'r')
- using sqlite database, is not possible to have correct authentication


24.03.2013
----------
- nonce library completed
- if login fails, a new nonce is generated and sent back via Ajax
- if nonce expired, a reload message is sent back
- fixed Session Manager issue

23.03.2013
----------
- added NONCE_EXPIRE constant in cfg/config.php
- nonce functions cutted&pasted in a separated lib file
- added expiration time
- added Nonces table (also in tables creation procedure in installation procedure)
- added nonce check
- if login nonce expires, a new one will be generated and sent back via Ajax

22.03.2013
----------
- replacing ft-nonce-lib with hand-created functions... still in progress...
- added check nonce against subsequent regeneration; still to add database coupling for duplication check

21.03.2013
----------
- now login.php page call ajax dispatcher passing user and hashed password parameters
- enhanced GUI interaction and effects
- added warning message for unsuccessfull login
- added redirect on successful login
- added nonce library (and testing) ft-nonce-lib
- optimized ARD
- set some session variables

20.03.2013
----------
- fixed ard.php (Ajax Requests Dispatcher) in calling right script to handle request
- more tests on SESSION
- added SESSION_EXPIRE define in config.php

20.03.2013
----------
- in login page 'show details...' text swaps with 'hide details...' on open/close
- added comments to javascript code
- replaced var_dump with var_export (because the latter returns the string: useful for debug on/off purposes)
- added boolean $debug_on_screen public property in base_class: can enable/disable log_info() method to output debug
  messages on screen
- now login page starts the session
- now the Ajax dispatcher starts session

19.03.2013
----------
- renamed field User_password in User_password_hash in the table Users
- added key in mysql table Users for User_contacts_email and in sqlite for User_contacts_email and User_username
- added pidCrypt SHA256 library load in login.php script; it correctly generates the hash for password field
- added enter bind event on login GUI fields

18.03.2013
----------
- rinominata classe in JOM_User
- aggiunta query di login e testata
- creato script di test di login

18.03.2013
----------
- creata classe bbkk_user con metodo authenticate

16.03.2013
----------
- in BBKK_Session_Manager removed the session_id regeneration on each start: in this way the session can be recovered
- in BBKK_Session_Manager, in the read() method, fixed a bug in the query

15.03.2013
----------
- in BBKK_Session_Manager created private properties for errors
- in BBKK_Session_Manager moved some class setting to constructor
- in BBKK_Session_Manager added defaults for parameters in order to avoid parameter number passing warning
- in BBKK_Session_Manager implemented destroy() method
- in BBKK_Session_Manager implemented read() method
- in BBKK_Session_Manager added possibility enable/disable encrtyption

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





Version 0.1: Installation procedure / database main tables
==========================================================
Completed.

TODOS:
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





Version 0.0: Import some base files
===================================
Completed.
