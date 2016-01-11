<?php
/**
 * Database Wrapper
 *
 * PHP Version 5
 *
 * @author   Richard Seymour <web@bespoke.support>
 * @license  MIT
 * @link     https://github.com/BespokeSupport/DatabaseWrapper
 */

namespace BespokeSupport\DatabaseWrapper;

use Doctrine\DBAL\Connection;
use Zend\Db\Adapter\Adapter;

/**
 * Class DatabaseAbstract
 * @package BespokeSupport\DatabaseWrapper
 */
abstract class DatabaseAbstract implements DatabaseWrapperInterface
{
    /**
     * @var \PDO|Connection|Adapter
     */
    protected $database;

    /**
     * @param $database
     * @throws DatabaseWrapperException
     */
    public function __construct($database)
    {
    }

    /**
     * @return Connection|\PDO|Adapter
     */
    public function getDatabase()
    {
        return $this->database;
    }
}
