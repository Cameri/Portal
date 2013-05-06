<?php
/*
 * Handles the conflict between xdebug
 * and the flourish debug system
 */
if(!extension_loaded('xdebug')) {
    if(DEVELOPMENT) {
        fCore::enableErrorHandling('html');
        fCore::enableExceptionHandling('html');
    }
}

if(DEBUG) {
    fCore::enableDebugging(true);
    fCore::registerDebugCallback(Util::handleDebug);
}

/*
 * Open session
 */
fSession::setLength('1day', '1week');
fSession::open();

/*
 * Save active server to session
 */
if(fRequest::check('server'))
    fSession::set('server', fRequest::get('server', 'string'));

/*
 * Define db values
 */
$db_file = 'none';
if(fSession::get('server'))
    $db_file = __INC__ . 'config/db_' . fSession::get('server', 'string') .'.php';

if(!file_exists($db_file))
    $db_file =  __INC__ . 'config/db.php';

include $db_file;
define('DB_HOST', $db_values['host']);
define('DB_PORT', $db_values['port']);
define('DB_USER', $db_values['user']);
define('DB_PW', $db_values['pw']);
define('DB_DATABASE', $db_values['database']);
define('DB_PREFIX', $db_values['prefix']);
define('DB_TYPE', $db_values['type']);

/*
 * Initialize cache
 */
try {
    $cache = new fCache('directory', __ROOT__ . 'cache/files');
    $cacheSingle = new fCache('file', __ROOT__ . 'cache/singlecache');
    Util::cleanSkinCache();
} catch(fEnvironmentException $e) {
    echo $e->getMessage();
    $e->printTrace();
    exit();
}

/*
 * Initializes ORM
 */
include_once __INC__ . 'orm.php';


/*
 * Initializes the language module
 */
$lang = new Language(Util::getOption('language', fSession::get('lang', 'en')));
$lang->load('errors');
fText::registerComposeCallback('pre', array($lang, 'translate'));

/*
 * Set timezones and time formats
 */
fTimestamp::setDefaultTimezone(Util::getOption('timezone', fTimestamp::getDefaultTimezone()));
if(Util::getOption('time_format', 24) == 24)
    fTimestamp::defineFormat('std', 'H:i - d.m.Y');
else
    fTimestamp::defineFormat('std', 'g:i a - d.m.Y');

fTimestamp::defineFormat('day', 'D d.m.Y');

/*
 * Sets login page for admin panel
 */
fAuthorization::setLoginPage('?page=login');

/*
 * Include ajax call handling
 * Handles for example api calls
 */
include_once __INC__ . 'ajax.php';

/**
 * Automatically includes classes
 *
 *
 * @param  string $class_name  Name of the class to load
 *
 * @throws fEnvironmentException
 * @return void
 */
function __autoload($class_name) {
    $flourish_file = __INC__ . 'flourish/' . $class_name . '.php';
    if(file_exists($flourish_file))
        return require $flourish_file;

    $file = __INC__ . 'classes/' . $class_name . '.php';
    if(file_exists($file))
        return require $file;

    $file = __INC__ . 'classes/orm/' . $class_name . '.php';
    if(file_exists($file))
        return require $file;

    $file = __INC__ . str_replace(array('_', "\0"), array('/', ''), $class_name) . '.php';
    if(is_file($file))
        return require $file;


    throw new fEnvironmentException('The class ' . $class_name . ' could not be loaded here: ' . $file);
}