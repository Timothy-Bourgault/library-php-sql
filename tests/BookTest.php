<?php
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */
    //TO RUN TESTS IN TERMINAL:
    //export PATH=$PATH:./vendor/bin
    //phpunit tests
    require_once "src/Book.php";
    $server = 'mysql:host=localhost;dbname=library_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class BookTest extends PHPUnit_Framework_TestCase
    {

        protected function tearDown()
        {
            Book::deleteAll();
        }

        function test_getTitle()
        {
            //Arrange
            $test_book = new Book("Moby Dick");

            //Act
            $output = $test_book->getTitle();

            //Assert
            $this->assertEquals("Moby Dick", $output);
        }

        function test_save()
        {
            //Arrange
            $test_book = new Book("Moby Dick");
            $test_book->save();

            //Act
            $output = Book::getAll();

            //Assert
            $this->assertEquals($test_book, $output[0]);
        }

        function test_update_title()
        {
            //Arrange
            $test_book = new Book("Moby Dick");
            $test_book->save();

            //Act
            $test_book->updateTitle("Harry Potter");
            $output = $test_book->getTitle();

            //Assert
            $this->assertEquals("Harry Potter", $output);
        }

        function test_delete_book()
        {
            //Arrange
            $test_book = new Book("Moby Dick");
            $test_book->save();

            //Act
            $test_book->delete();
            $output = Book::getAll();

            //Assert
            $this->assertEquals([], $output);
        }

        function test_findId()
        {
            $test_book = new Book("Moby Dick");
            $test_book->save();

            $output = Book::find($test_book->getId());

            $this->assertEquals($test_book, $output);

        }

        function test_findTitle()
        {
            $test_book = new Book("Moby Dick");
            $test_book->save();

            $output = Book::find($test_book->getTitle());

            $this->assertEquals($test_book, $output);

        }

        function test_addAuthor()
        {
            $test_book = new Book("Moby Dick");
            $test_book->save();
            $test_author = new Author("Herman Melville");
            $test_author->save();

            $test_book->addAuthor($test_author);
            $output = $test_book->getBooksAuthors();

            $this->assertEquals([$test_author], $output);

        }

        function test_getNumberOfCopies()
        {
            $test_book = new Book("Moby Dick");
            $test_book->save();

            $output = $test_book->getNumberOfCopies();

            $this->assertEquals(1, $output);
        }

    }
        // export PATH=$PATH:./vendor/bin first and then you will only have to run  $ phpunit tests
?>
