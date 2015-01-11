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

use BespokeSupport\DatabaseWrapper\DatabaseWrapperException;

/**
 * Class AllTest
 */
class AllTest extends \PHPUnit_Framework_TestCase
{
    protected static $tests = array();

    public static function setUpBeforeClass()
    {
        self::$tests = unserialize(TESTS_ARRAY);
    }

    public static function getTests()
    {
        return self::$tests;
    }

    public function testTestsToBeRun()
    {
        $this->assertGreaterThan(0, count(self::$tests));
    }

    public function testId()
    {
        foreach (self::$tests as $test) {
            $databaseConnection = $test['get_connection']();

            $database = new $test['class']($databaseConnection);
            /**
             * @var $database \BespokeSupport\DatabaseWrapper\DatabaseWrapperInterface
             */
            $row = $database->find(TEST_DATABASE_TABLE, TEST_ID_AVAIL);

            $this->assertTrue(is_object($row));
            $this->assertTrue(isset($row->id));
            $this->assertEquals(1, $row->id);
        }
    }

    public function testNonStandardId()
    {
        $nonStandardColumn = TEST_DATABASE_TABLE_NO_ID;

        foreach (self::$tests as $test) {
            $databaseConnection = $test['get_connection']();

            $database = new $test['class']($databaseConnection);
            /**
             * @var $database \BespokeSupport\DatabaseWrapper\DatabaseWrapperInterface
             */
            $row = $database->find(TEST_DATABASE_TABLE_NO_ID, TEST_ID_NON_STANDARD, $nonStandardColumn);

            $this->assertTrue(is_object($row));
            $this->assertTrue(isset($row->$nonStandardColumn));
            $this->assertEquals(TEST_ID_NON_STANDARD, $row->$nonStandardColumn);
        }
    }

    public function testIdUnknown()
    {
        foreach (self::$tests as $test) {
            $databaseConnection = $test['get_connection']();

            $database = new $test['class']($databaseConnection);
            /**
             * @var $database \BespokeSupport\DatabaseWrapper\DatabaseWrapperInterface
             */
            $row = $database->find(TEST_DATABASE_TABLE, TEST_ID_UNKNOWN);
            $this->assertFalse(is_object($row));
        }
    }

    public function testFindOneBy()
    {
        foreach (self::$tests as $test) {
            $databaseConnection = $test['get_connection']();

            $database = new $test['class']($databaseConnection);
            /**
             * @var $database \BespokeSupport\DatabaseWrapper\DatabaseWrapperInterface
             */
            $row = $database->findOneBy(
                TEST_DATABASE_TABLE,
                array(
                    'id' => TEST_ID_AVAIL
                )
            );

            $this->assertTrue(is_object($row));
            $this->assertTrue(isset($row->id));
            $this->assertEquals(1, $row->id);
        }
    }

    public function testFindAllBy()
    {
        foreach (self::$tests as $test) {
            $databaseConnection = $test['get_connection']();

            $database = new $test['class']($databaseConnection);
            /**
             * @var $database \BespokeSupport\DatabaseWrapper\DatabaseWrapperInterface
             */
            $rows = $database->findBy(
                TEST_DATABASE_TABLE,
                array(
                    'id' => TEST_ID_AVAIL
                )
            );

            $this->assertNotFalse($rows);
            $this->assertCount(1, $rows);
        }
    }

    public function testFindErrorBlankArray()
    {
        foreach (self::$tests as $test) {
            $databaseConnection = $test['get_connection']();

            $database = new $test['class']($databaseConnection);
            /**
             * @var $database \BespokeSupport\DatabaseWrapper\DatabaseWrapperInterface
             */
            $row = $database->findOneBy(
                TEST_DATABASE_TABLE,
                array(

                )
            );

            $this->assertFalse(is_object($row));
        }
    }

    public function testFindByErrorBlankArray()
    {
        foreach (self::$tests as $test) {
            $databaseConnection = $test['get_connection']();

            $database = new $test['class']($databaseConnection);
            /**
             * @var $database \BespokeSupport\DatabaseWrapper\DatabaseWrapperInterface
             */
            $row = $database->findBy(
                TEST_DATABASE_TABLE,
                array(

                )
            );

            $this->assertFalse($row);
            $this->assertFalse(is_object($row));
        }
    }

    public function testFindErrorNonArray()
    {
        foreach (self::$tests as $test) {
            $databaseConnection = $test['get_connection']();

            $database = new $test['class']($databaseConnection);
            /**
             * @var $database \BespokeSupport\DatabaseWrapper\DatabaseWrapperInterface
             */

            try {
                $row = $database->findOneBy(
                    TEST_DATABASE_TABLE,
                    false
                );
                $this->assertTrue(false);
            } catch (\Exception $e) {
                $this->assertTrue(true);
            }
        }
    }

    public function testInvalidConnectionObject()
    {
        foreach (self::$tests as $test) {

            try {
                $database = new $test['class'](false);
                $this->assertTrue(false);
            } catch (DatabaseWrapperException $e) {
                $this->assertTrue(true);
            }
        }
    }
}
