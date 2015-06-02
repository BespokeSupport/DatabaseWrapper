<?php
/**
 * Functions to grab DB
 */

use Doctrine\DBAL\DriverManager;

/**
 * @return \PDO
 */
function getPdo()
{
    $pdo = new \PDO(
        CREDENTIALS_TYPE.':host='.CREDENTIALS_HOST.';dbname='.CREDENTIALS_NAME,
        CREDENTIALS_USER,
        CREDENTIALS_PASS
    );

    if (!defined('TEST_AVAILABILITY_PDO')) {
        define('TEST_AVAILABILITY_PDO', ($pdo)?true:false);
    }

    return $pdo;
}

/**
 * @return \Doctrine\DBAL\Connection
 */
function getDoctrineConnection()
{
    $connectionParams = array(
        'dbname' => CREDENTIALS_NAME,
        'user' => CREDENTIALS_USER,
        'password' => CREDENTIALS_PASS,
        'host' => CREDENTIALS_HOST,
        'driver' => 'pdo_mysql',
    );

    $doctrineConnection = DriverManager::getConnection($connectionParams, null);

    return $doctrineConnection;
}

/**
 * @return \Zend\Db\Adapter\Adapter
 */
function getZendAdapter()
{
    return new Zend\Db\Adapter\Adapter(
        array(
            'driver' => 'Pdo_Mysql',
            'database' => CREDENTIALS_NAME,
            'username' => CREDENTIALS_USER,
            'password' => CREDENTIALS_PASS
        )
    );
}

/**
 * @return mysqli
 */
function getMysqli()
{
    return new mysqli(
        CREDENTIALS_HOST,
        CREDENTIALS_USER,
        CREDENTIALS_PASS,
        CREDENTIALS_NAME
    );
}
