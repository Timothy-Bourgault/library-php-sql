<?php
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */
    //TO RUN TESTS IN TERMINAL:
    //export PATH=$PATH:./vendor/bin
    //phpunit tests
    require_once "src/Patron.php";
    require_once "src/Book.php";
    require_once "src/Copy.php";
    require_once "src/Author.php";
    $server = 'mysql:host=localhost;dbname=library_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class PatronTest extends PHPUnit_Framework_TestCase
    {

        protected function tearDown()
        {
            Book::deleteAll();
            Copy::deleteAll();
            Author::deleteAll();
            Patron::deleteAll();
        }

        function test_getName()
        {
            //Arrange
            $test_patron = new Patron("Bob Jones");

            //Act
            $output = $test_patron->getName();

            //Assert
            $this->assertEquals("Bob Jones", $output);
        }

        function test_save()
        {
            //Arrange
            $test_patron = new Patron("Bob Jones");
            $test_patron->save();

            //Act
            $output = Patron::getAll();

            //Assert
            $this->assertEquals($test_patron, $output[0]);
        }

        function test_update_name()
        {
            //Arrange
            $test_patron = new Patron("Bob Jones");
            $test_patron->save();

            //Act
            $test_patron->updateName("Bob Williams");
            $output = $test_patron->getName();

            //Assert
            $this->assertEquals("Bob Williams", $output);
        }

        function test_delete_patron()
        {
            //Arrange
            $test_patron = new Patron("Bob Jones");
            $test_patron->save();

            //Act
            $test_patron->delete();
            $output = Patron::getAll();

            //Assert
            $this->assertEquals([], $output);
        }

        function test_findId()
        {
            $test_patron = new Patron("Bob Jones");
            $test_patron->save();

            $output = Patron::find($test_patron->getId());

            $this->assertEquals($test_patron, $output);

        }

        function test_findName()
        {
            $test_patron = new Patron("Bob Jones");
            $test_patron->save();

            $output = Patron::find($test_patron->getName());

            $this->assertEquals($test_patron, $output);

        }

        function test_checkOut()
        {
            $test_patron = new Patron("Bob Jones");
            $test_patron->save();
            $test_book = new Book("Moby Dick");
            $test_book->save();
            $test_author = new Author("Herman Melville");
            $test_author->save();

            $output = $test_patron->checkoutCopy($test_book);

            $this->assertEquals(1, $output);

        }

    }
        // export PATH=$PATH:./vendor/bin first and then you will only have to run  $ phpunit tests
?>
