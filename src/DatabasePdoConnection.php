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

/**
 * Class DatabasePdoConnection
 * @package BespokeSupport\DatabaseWrapper
 */
class DatabasePdoConnection extends DatabaseAbstract
{
    /**
     * @var \PDO
     */
    protected $database;

    /**
     * {@inheritdoc}
     */
    public function __construct($database)
    {
        if (!($database instanceof \PDO)) {
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

        $stmt = $this->database->prepare($sql);

        $stmt->bindParam($primaryColumn, $id);

        $stmt->execute();

        $row = $stmt->fetch(\PDO::FETCH_OBJ);;

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
        $keyId = 0;
        foreach ($findArray as $key => $value) {
            $key = preg_replace('#[^a-zA-Z0-9_-]#', '', $key);
            $whereStmt .= " AND {$key} = :pdoKey" . $keyId;
            $keyId++;
        }

        $sql = <<<TAG
            SELECT
            *
            FROM {$table}
            WHERE (1=1)
            {$whereStmt}
TAG;

        $stmt = $this->database->prepare($sql);

        $keyId = 0;
        foreach ($findArray as $key => $value) {
            $stmt->bindValue(':pdoKey' . $keyId, $value);
            $keyId++;
        }

        $stmt->execute();

        $result = $stmt->fetchAll(\PDO::FETCH_OBJ);

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
        $keyId = 0;
        foreach ($findArray as $key => $value) {
            $key = preg_replace('#[^a-zA-Z0-9_-]#', '', $key);
            $whereStmt .= " AND {$key} = :pdoKey" . $keyId;
            $keyId++;
        }

        $sql = <<<TAG
            SELECT
            *
            FROM {$table}
            WHERE (1=1)
            {$whereStmt}
            LIMIT 1
TAG;

        $stmt = $this->database->prepare($sql);

        $keyId = 0;
        foreach ($findArray as $key => $value) {
            $stmt->bindValue(':pdoKey' . $keyId, $value);
            $keyId++;
        }

        $stmt->execute();

        $result = $stmt->fetch(\PDO::FETCH_OBJ);

        return $result;
    }

    /**
     * @return \PDO
     */
    public function getPdo()
    {
        return $this->database;
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
        $this->database->beginTransaction();
    }

    /**
     * End Transaction
     */
    public function transactionCommit()
    {
        $this->database->commit();
    }

    /**
     * Rollback Transaction
     */
    public function transactionRollback()
    {
        $this->database->rollBack();
    }
}
