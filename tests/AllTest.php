<?php
/**
 * Database Wrapper.
 *
 * PHP Version 5
 *
 * @category DB
 *
 * @author   Richard Seymour <web@bespoke.support>
 * @license  MIT http://opensource.org/licenses/MIT
 * @tag      DB
 *
 * @link     https://github.com/BespokeSupport/DatabaseWrapper
 */
use BespokeSupport\DatabaseWrapper\DatabaseWrapperException;

/**
 * Class AllTest.
 *
 * @category DB
 *
 * @author   Richard Seymour <web@bespoke.support>
 * @license  MIT http://opensource.org/licenses/MIT
 * @tag      DB
 *
 * @link     https://github.com/BespokeSupport/DatabaseWrapper
 */
class AllTest extends \PHPUnit_Framework_TestCase
{
    protected static $tests = [];

    /**
     * From tests.functions.php.
     *
     * @return array
     */
    public static function setUpBeforeClass()
    {
        return self::$tests = unserialize(TESTS_ARRAY);
    }

    /**
     * Which tests?
     *
     * @return array
     */
    public static function getTests()
    {
        return self::$tests;
    }

    /**
     * Are we running any tests?
     *
     * @return void
     */
    public function testTestsToBeRun()
    {
        $this->assertGreaterThan(0, count(self::$tests));
    }

    /**
     * @return void
     */
    public function testId()
    {
        foreach (self::$tests as $test) {
            $databaseConnection = $test['get_connection']();

            $database = new $test['class']($databaseConnection);
            /**
             * @var \BespokeSupport\DatabaseWrapper\DatabaseWrapperInterface
             */
            $row = $database->find(TEST_DATABASE_TABLE, TEST_ID_AVAIL);

            $this->assertTrue(is_object($row));
            $this->assertTrue(isset($row->id));
            $this->assertEquals(1, $row->id);
        }
    }

    /**
     * @return void
     */
    public function testNonStandardId()
    {
        $nonStandardColumn = TEST_DATABASE_TABLE_NO_ID;

        foreach (self::$tests as $test) {
            $databaseConnection = $test['get_connection']();

            $database = new $test['class']($databaseConnection);
            /**
             * @var \BespokeSupport\DatabaseWrapper\DatabaseWrapperInterface
             */
            $row = $database->find(TEST_DATABASE_TABLE_NO_ID, TEST_ID_NON_STANDARD, $nonStandardColumn);

            $this->assertTrue(is_object($row));
            $this->assertTrue(isset($row->$nonStandardColumn));
            $this->assertEquals(TEST_ID_NON_STANDARD, $row->$nonStandardColumn);
        }
    }

    /**
     * @return void
     */
    public function testIdUnknown()
    {
        foreach (self::$tests as $test) {
            $databaseConnection = $test['get_connection']();

            $database = new $test['class']($databaseConnection);
            /**
             * @var \BespokeSupport\DatabaseWrapper\DatabaseWrapperInterface
             */
            $row = $database->find(TEST_DATABASE_TABLE, TEST_ID_UNKNOWN);
            $this->assertFalse(is_object($row));
        }
    }

    /**
     * @return void
     */
    public function testFindOneBy()
    {
        foreach (self::$tests as $test) {
            $databaseConnection = $test['get_connection']();

            $db = new $test['class']($databaseConnection);

            $row = $db->findOneBy(
                TEST_DATABASE_TABLE,
                [
                    'id' => TEST_ID_AVAIL,
                ]
            );

            $this->assertTrue(is_object($row));
            $this->assertTrue(isset($row->id));
            $this->assertEquals(1, $row->id);
        }
    }

    /**
     * @return void
     */
    public function testFindAllBy()
    {
        foreach (self::$tests as $test) {
            $databaseConnection = $test['get_connection']();

            $database = new $test['class']($databaseConnection);
            /**
             * @var \BespokeSupport\DatabaseWrapper\DatabaseWrapperInterface
             */
            $rows = $database->findBy(
                TEST_DATABASE_TABLE,
                [
                    'id' => TEST_ID_AVAIL,
                ]
            );

            $this->assertNotFalse($rows);
            $this->assertCount(1, $rows);
        }
    }

    /**
     * @return void
     */
    public function testFindErrorBlankArray()
    {
        foreach (self::$tests as $test) {
            $databaseConnection = $test['get_connection']();

            $database = new $test['class']($databaseConnection);
            $row = $database->findOneBy(
                TEST_DATABASE_TABLE,
                [

                ]
            );

            $this->assertFalse(is_object($row));
        }
    }

    /**
     * @return void
     */
    public function testFindByErrorBlankArray()
    {
        foreach (self::$tests as $test) {
            $databaseConnection = $test['get_connection']();

            $database = new $test['class']($databaseConnection);
            /**
             * @var \BespokeSupport\DatabaseWrapper\DatabaseWrapperInterface
             */
            $row = $database->findBy(
                TEST_DATABASE_TABLE,
                [

                ]
            );

            $this->assertFalse($row);
            $this->assertFalse(is_object($row));
        }
    }

    /**
     * @return void
     */
    public function testFindErrorNonArray()
    {
        foreach (self::$tests as $test) {
            $databaseConnection = $test['get_connection']();

            $database = new $test['class']($databaseConnection);
            /*
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

    /**
     * @return void
     */
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
