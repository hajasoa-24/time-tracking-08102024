<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

define('DEFAULT_PAGE_TITLE', 'TimeTracking');
//STATUS
define('READYTOWORK', 1);
define('WORKING', 2);
define('PAUSED', 3);
define('DONEWORKING', 4);

//DEFAULT PASSWORD USER
define('DEFAULT_USER_PWD', '123456');

define('MAXSHIFTHOUR', 0);
//ROLES
define('ROLE_ADMIN', '1');
define('ROLE_AGENT', '2');
define('ROLE_SUP', '3');
define('ROLE_CADRE', '4');
define('ROLE_ADMINRH', '5');
define('ROLE_CADRE2', '6');
define('ROLE_DIRECTION', '7');
define('ROLE_COSTRAT', '8');
define('ROLE_CLIENT', '9');
define('ROLE_REPORTING', '10');

//SITES
define('SITE_SETEX', '1');
define('SITE_MCR', '2');
define('SITE_TNL', '3');
define('SITE_SETEX2', '4');

define('SECURITE_SERVICE_ID', 14);
define('TRANSPORT_SERVICE_ID', 15);
define('MEDICAL_SERVICE_ID', 16);
define('SUPPORTADMIN_SERVICE_ID', 17);

//MAPPING ETAT DES CONGES
define('A_VALIDER_SUP', '1');
define('A_VALIDER_CADRE2', '2');
define('A_VALIDER_DIR', '3');
define('A_TRAITER_RH', '4');
define('REFUSE', '5');
define('VALIDE', '6');
define('REPOS_MALADIE', '7');
define('ASSISTANCE_MATERNELLE', '8');
define('AUTRES', '9');
define('A_VALIDER_COSTRAT', '10');
define('CONGE_ENCOURS', '11');
define('CONGE_TERMINE', '12');
define('CONGE_DE_MATERNITE','13');



define('TYPECONGE_CONGE', '1');
define('TYPECONGE_PERMISSION', '2');

define('HEURE_NON_RETOUR', 17);

define('DROIT_PERMISSION', 80);

define('JOUR_CONGE_MENSUEL', 2.5);

define('PATH_IMPORT_CONGE', '/IMPORT');

define('CAMPAGNE_HOMELAND', '5');

define('APPEL_HOMELAND', 1);
define('MAIL_HOMELAND', 2);
define('AFFECTATION_HOMELAND', 3);
define('AUTRES_HOMELAND', 4);
define('COMPTA_HOMELAND', 5);
define('JURIDIQUE_HOMELAND', 6);
define('PEDED_HOMELAND', 7);
define('IMMA_HOMELAND', 8);
define('SINISTRE_HOMELAND', 9);
define('TECHNIQUE_HOMELAND', 10);
define('MAJHBO_HOMELAND', 11);
define('DISPATCH_HOMELAND', 12);

define('ED_PED_CERT', 1);
define('SUIVI_EXPLICATION', 2);
define('CARNET_ENTRETIENT', 6);

define('LIST_ABSENCESANORMALES', ["Fin contrat", "Démission", "Abandon de poste"]);

//le format devrait suivre le format de MYSQL INTERVAL
define('CONGE_DAYS_NOTIFICATION', '2 DAY');

define('CAMPAGNE_RELAISCOLIS', '33');

// STATUS ACTIVITY
define('MCP_STATUS_ENCOURS', 0);
define('MCP_STATUS_ENPAUSE', 1);
define('MCP_STATUS_TERMINE', 2);

//ETAT RESSOURCE
define('ETATRESSOURCE_PROD_DEFAULT', 1);
define('ETATRESSOURCE_FORMATION_DEFAULT', 2);


//CA 
define('TECHNODEV_CA_PATERN', '%montant%');
define('FREQUENCECALCUL_PRIME_DAY', 'day' );
define('FREQUENCECALCUL_PRIME_MONTH', 'month' );

define('DEFAULT_PRIMEPROFIL', 1);
define('PRIME_BONUS', 'BONUS');
define('PRIME_MALUS', 'MALUS');
define('PRIME_LISTAJUSTEUR', [46]);
define('PRIME_LISTVALIDATEURS', [46]);