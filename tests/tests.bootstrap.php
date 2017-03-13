<?php
/**
 * Which tests are to be run?
 * PHP Version 5.3.
 */
define('TEST_DATABASE_TABLE', 'basic');
define('TEST_DATABASE_TABLE_NO_ID', 'non_standard');
define('TEST_ID_AVAIL', 1);
define('TEST_ID_NON_STANDARD', 'AA');
define('TEST_ID_UNKNOWN', 2);

require_once dirname(__FILE__).'/../vendor/autoload.php';
require_once 'tests.functions.php';

// load in credentials
if (file_exists(dirname(__FILE__).'/tests.credentials.php')) {
    include_once dirname(__FILE__).'/tests.credentials.php';
} else {
    define('CREDENTIALS_TYPE', 'mysql');
    define('CREDENTIALS_HOST', 'localhost');
    define('CREDENTIALS_NAME', 'tests');
    define('CREDENTIALS_USER', 'root');
    define('CREDENTIALS_PASS', '');
}

$namespace = '\BespokeSupport\DatabaseWrapper';

$testsArray = [];

if (class_exists('PDO') && in_array('sqlite', PDO::getAvailableDrivers())) {
    $testsArray['pdo'] = [
        'get_connection' => 'getPdo',
        'class'          => $namespace.'\DatabaseWrapperPdo',
    ];
}

if (class_exists('Doctrine\DBAL\Connection')) {
    $testsArray['doctrine'] = [
        'get_connection' => 'getDoctrineConnection',
        'class'          => $namespace.'\DatabaseWrapperDoctrine',
    ];
}

if (class_exists('Zend\Db\Adapter\Adapter')) {
    $testsArray['zend_adapter'] = [
        'get_connection' => 'getZendAdapter',
        'class'          => $namespace.'\DatabaseWrapperZend',
    ];
}

define('TESTS_ARRAY', serialize($testsArray));
