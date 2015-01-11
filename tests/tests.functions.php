<?php

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
    $config = new \Doctrine\DBAL\Configuration();

    $connectionParams = array(
        'dbname' => CREDENTIALS_NAME,
        'user' => CREDENTIALS_USER,
        'password' => CREDENTIALS_PASS,
        'host' => CREDENTIALS_HOST,
        'driver' => 'pdo_mysql',
    );

    $doctrineConnection = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);

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