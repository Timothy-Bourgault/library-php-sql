<?php
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */
    //TO RUN TESTS IN TERMINAL:
    //export PATH=$PATH:./vendor/bin
    //phpunit tests
    require_once "src/Copy.php";
    require_once "src/Patron.php";
    require_once "src/Book.php";
    require_once "src/Copy.php";
    require_once "src/Author.php";
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

        function test_updatePastDue()
        {
            //Arrange
            $test_patron = new Patron("Bob Jones");
            $test_patron->save();
            $test_book = new Book("Moby Dick");
            $test_book->save();
            $test_author = new Author("Herman Melville");
            $test_author->save();

            $test_patron->checkoutCopy($test_book);

            //Act
            Copy::updatePastDue();
            $copy = $test_book->getCopies()[0];
            $output = $copy->getStatus();

            //Assert
            $this->assertEquals('checked out', $output);

        }

        function test_returnCopy()
        {
            $test_patron = new Patron("Bob Jones");
            $test_patron->save();
            $test_book = new Book("Moby Dick");
            $test_book->save();
            $test_author = new Author("Herman Melville");
            $test_author->save();

            $test_patron->checkoutCopy($test_book);
            $copy = $test_book->getCopies()[0];
            $copy->returnCopy();
            $output = $copy->getStatus();

            $this->assertEquals("available", $output);

        }
    }
?>
