<?php
/**
 * Database Wrapper.
 *
 * PHP Version 5
 *
 * @author   Richard Seymour <web@bespoke.support>
 * @license  MIT
 *
 * @link     https://github.com/BespokeSupport/DatabaseWrapper
 */

namespace BespokeSupport\DatabaseWrapper;

use Zend\Db\Adapter\Adapter;

/**
 * Class DatabaseWrapperZend.
 */
class DatabaseWrapperZend extends AbstractDatabaseWrapper
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

        parent::__construct($database);
    }

    /**
     * {@inheritdoc}
     */
    public function find($table, $primaryKey, $primaryColumn = 'id')
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

        $result = $this->database->query($sql, [$primaryColumn => $primaryKey]);

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
            $whereStmt .= " AND {$key} = :".$key;
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
            $whereStmt .= " AND {$key} = :".$key;
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
     * {@inheritdoc}
     */
    public function getPdo()
    {
        // TODO create PDO
    }

    /**
     * {@inheritdoc}
     */
    public function insert($table, array $values)
    {
        // TODO
    }

    /**
     * {@inheritdoc}
     */
    public function sqlFetchAll($sql, array $params = [])
    {
        return $this->database->query($sql, $params);
    }

    /**
     * {@inheritdoc}
     */
    public function sqlFetchOne($sql, array $params = [])
    {
        $result = $this->database->query($sql, $params);

        $row = $result->current();

        return $row;
    }

    /**
     * {@inheritdoc}
     */
    public function sqlInsertUpdate($sql, array $params = [])
    {
        // TODO
    }

    /**
     * {@inheritdoc}
     */
    public function transactionBegin()
    {
        $this->database->getDriver()->getConnection()->beginTransaction();
    }

    /**
     * {@inheritdoc}
     */
    public function transactionCommit()
    {
        $this->database->getDriver()->getConnection()->commit();
    }

    /**
     * {@inheritdoc}
     */
    public function transactionRollback()
    {
        $this->database->getDriver()->getConnection()->rollback();
    }
}
