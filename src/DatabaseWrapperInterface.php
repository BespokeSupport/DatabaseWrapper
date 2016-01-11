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
 * Interface DatabaseWrapperInterface
 * @package BespokeSupport\WrapperDatabase
 */
interface DatabaseWrapperInterface
{
    /**
     * @param $table
     * @param $id
     * @param string $primaryColumn
     * @return object
     */
    public function find($table, $id, $primaryColumn = 'id');

    /**
     * @param $table
     * @param array $findBy
     * @return array
     */
    public function findBy($table, array $findBy);

    /**
     * @param $table
     * @param array $findArray
     * @return object
     */
    public function findOneBy($table, array $findArray);

    /**
     * @param $table
     * @param array $values
     * @return array
     */
    public function insert($table, array $values);
}
