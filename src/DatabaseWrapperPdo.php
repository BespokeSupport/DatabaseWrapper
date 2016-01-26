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
 * Class DatabaseWrapperPdo
 * @package BespokeSupport\DatabaseWrapper
 */
class DatabaseWrapperPdo extends DatabaseAbstract
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

        $stmt = $this->database->prepare($sql);

        $stmt->bindParam($primaryColumn, $primaryKey);

        $stmt->execute();

        if ('00000' != $stmt->errorCode()) {
            $info = $stmt->errorInfo();
            throw new DatabaseWrapperException($info[2]);
        }

        $row = $stmt->fetch(\PDO::FETCH_OBJ);

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

        if ('00000' != $stmt->errorCode()) {
            $info = $stmt->errorInfo();
            throw new DatabaseWrapperException($info[2]);
        }

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

        if ('00000' != $stmt->errorCode()) {
            $info = $stmt->errorInfo();
            throw new DatabaseWrapperException($info[2]);
        }

        $result = $stmt->fetch(\PDO::FETCH_OBJ);

        return $result;
    }

    /**
     * {@inheritdoc}
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
        if (!is_array($values) || !count($values)) {
            return false;
        }

        // sql injection protection :(
        $table = preg_replace('#[^a-zA-Z0-9_-]#', '', $table);

        $keysArray = array();
        $valuesArray = array();
        $keyId = 0;
        foreach ($values as $value) {
            $keysArray[] = ":pdoKey" . $keyId;
            $valuesArray[] = $value;
            $keyId++;
        }

        $keysSql = implode(',', preg_replace('#[^a-zA-Z0-9_-]#', '', array_keys($values)));

        $valuesSql = implode(',', $keysArray);

        $sql = <<<TAG
            INSERT INTO {$table}
            ($keysSql)
            VALUES ($valuesSql)
TAG;

        $stmt = $this->database->prepare($sql);

        foreach ($valuesArray as $keyId => $value) {
            $stmt->bindValue($keysArray[$keyId], $value);
        }

        $stmt->execute();

        if ('00000' != $stmt->errorCode()) {
            $info = $stmt->errorInfo();
            throw new DatabaseWrapperException($info[2]);
        }

        return $this->database->lastInsertId();
    }

    /**
     * {@inheritdoc}
     */
    public function sqlFetchAll($sql, array $params = array())
    {
        $stmt = $this->database->prepare($sql);
        $stmt->execute($params);

        if ('00000' != $stmt->errorCode()) {
            $info = $stmt->errorInfo();
            throw new DatabaseWrapperException($info[2]);
        }

        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    /**
     * {@inheritdoc}
     */
    public function sqlFetchOne($sql, array $params = array())
    {
        $stmt = $this->database->prepare($sql);
        $stmt->execute($params);

        if ('00000' != $stmt->errorCode()) {
            $info = $stmt->errorInfo();
            throw new DatabaseWrapperException($info[2]);
        }

        return $stmt->fetch(\PDO::FETCH_OBJ);
    }

    /**
     * {@inheritdoc}
     */
    public function sqlInsertUpdate($sql, array $params = array())
    {
        $stmt = $this->database->prepare($sql);
        $stmt->execute($params);

        if ('00000' != $stmt->errorCode()) {
            $info = $stmt->errorInfo();
            throw new DatabaseWrapperException($info[2]);
        }

        if (false === stripos($sql, 'INSERT')) {
            return $stmt->rowCount();
        } else {
            return $this->database->lastInsertId();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function transactionBegin()
    {
        $this->database->beginTransaction();
    }

    /**
     * {@inheritdoc}
     */
    public function transactionCommit()
    {
        $this->database->commit();
    }

    /**
     * {@inheritdoc}
     */
    public function transactionRollback()
    {
        $this->database->rollBack();
    }
}
