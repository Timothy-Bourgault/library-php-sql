<?php
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */
    //TO RUN TESTS IN TERMINAL:
    //export PATH=$PATH:./vendor/bin
    //phpunit tests
    require_once "src/Copy.php";
    $server = 'mysql:host=localhost;dbname=library_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class CopyTest extends PHPUnit_Framework_TestCase
    {

        protected function tearDown()
        {
            Copy::deleteAll();
        }

        function test_getStatus()
        {
            //Arrange
            $test_copy = new Copy(5);

            //Act
            $output = $test_copy->getStatus();

            //Assert
            $this->assertEquals("available", $output);
        }

        function test_getId()
        {
            //Arrange
            $test_copy = new Copy(5);
            $test_copy->save();

            //Act
            $output = $test_copy->getId();

            //Assert
            $this->assertTrue(is_numeric($output));
        }

        function test_save()
        {
            //Arrange
            $test_copy = new Copy(1);
            $test_copy->save();
            $test_copy2 = new Copy(2);
            $test_copy2->save();

            //Act
            $output = Copy::getAll();

            //Assert
            $this->assertEquals([$test_copy, $test_copy2], $output);
        }


    }
?>
