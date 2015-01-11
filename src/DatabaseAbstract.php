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
 * Class DatabaseAbstract
 * @package BespokeSupport\DatabaseWrapper
 */
abstract class DatabaseAbstract implements DatabaseWrapperInterface
{
    /**
     * @var object
     */
    protected $database;
    /**
     * @param $database
     * @throws DatabaseWrapperException
     */
    public function __construct($database) {}
}
