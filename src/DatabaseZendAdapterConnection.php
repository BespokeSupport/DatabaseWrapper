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

use Zend\Db\Adapter\Adapter;

/**
 * Class DatabaseZendConnection
 * @package BespokeSupport\DatabaseWrapper
 */
class DatabaseZendAdapterConnection extends DatabaseAbstract
{
    /**
     * @var Adapter
     */
    protected $database;

    /**
     * {@inheritdoc}
     */
    public function __construct($database)
    {
        if (!($database instanceof Adapter)) {
            throw new DatabaseWrapperException('Invalid database object');
        }

        $this->database = $database;
    }

    /**
     * {@inheritdoc}
     */
    public function find($table, $id, $primaryColumn = 'id')
    {
        // sql injection protection :(
        $table = preg_replace('#[^a-zA-Z0-9_-]#', '', $table);
        $primaryColumn = preg_replace('#[^a-zA-Z0-9_-]#', '', $primaryColumn);

        $sql = <<<TAG
            SELECT
            *
            FROM $table
            WHERE
            $primaryColumn = :$primaryColumn
            LIMIT 1
TAG;

        $result = $this->database->query($sql, array($primaryColumn => $id));

        $row = $result->current();

        return $row;
    }

    /**
     * {@inheritdoc}
     */
    public function findBy($table, array $findArray)
    {
        if (!is_array($findArray) || !count($findArray)) {
            return false;
        }

        // sql injection protection :(
        $table = preg_replace('#[^a-zA-Z0-9_-]#', '', $table);

        $whereStmt = '';
        foreach ($findArray as $key => $value) {
            $whereStmt .= " AND {$key} = :" . $key;
        }

        $sql = <<<TAG
            SELECT
            *
            FROM {$table}
            WHERE (1=1)
            {$whereStmt}
TAG;

        $result = $this->database->query($sql, $findArray);

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function findOneBy($table, array $findArray)
    {
        if (!is_array($findArray) || !count($findArray)) {
            return false;
        }

        // sql injection protection :(
        $table = preg_replace('#[^a-zA-Z0-9_-]#', '', $table);

        $whereStmt = '';
        foreach ($findArray as $key => $value) {
            $whereStmt .= " AND {$key} = :" . $key;
        }

        $sql = <<<TAG
            SELECT
            *
            FROM {$table}
            WHERE (1=1)
            {$whereStmt}
            LIMIT 1
TAG;

        $result = $this->database->query($sql, $findArray);

        $row = $result->current();

        return $row;
    }

    /**
     * @return \PDO
     */
    public function getPdo()
    {
        // TODO create PDO
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function insert($table, array $values)
    {
        // TODO
    }

    /**
     * Begin Transaction
     */
    public function transactionBegin()
    {
        $this->database->getDriver()->getConnection()->beginTransaction();
    }

    /**
     * End Transaction
     */
    public function transactionCommit()
    {
        $this->database->getDriver()->getConnection()->commit();
    }

    /**
     * Rollback Transaction
     */
    public function transactionRollback()
    {
        $this->database->getDriver()->getConnection()->rollback();
    }
}
