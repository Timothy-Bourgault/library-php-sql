<?php
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */
    //TO RUN TESTS IN TERMINAL:
    //export PATH=$PATH:./vendor/bin
    //phpunit tests
    require_once "src/Book.php";
    require_once "src/Copy.php";
    require_once "src/Author.php";
    require_once "src/Patron.php";
    $server = 'mysql:host=localhost;dbname=library_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class BookTest extends PHPUnit_Framework_TestCase
    {

        protected function tearDown()
        {
            Book::deleteAll();
            Copy::deleteAll();
            Author::deleteAll();
            Patron::deleteAll();
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

        function test_find()
        {
            $test_book = new Book("Moby Dick");
            $test_book->save();

            $output = Book::find($test_book->getId());

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

        function test_addCopy()
        {
            $test_book = new Book("Moby Dick");
            $test_book->save();
            $test_book->addCopy();

            $output = $test_book->getNumberOfCopies();

            $this->assertEquals(2, $output);

        }

        function test_getAvailableCopy()
        {
            $test_book = new Book("Moby Dick");
            $test_book->save();

            $copy_id = $test_book->getAvailableCopy();

            $this->assertTrue(is_numeric($copy_id));
        }

        function test_getCopyInfo()
        {
            $test_patron = new Patron("Bob Jones");
            $test_patron->save();
            $test_book = new Book("Moby Dick");
            $test_book->save();


            $test_patron->checkoutCopy($test_book);

            $copy = $test_book->getCopies()[0];
            $result = $test_book->getCopyInfo();
            $this->assertEquals([['status' => 'checked out', 'copy_id' => $copy->getId(), 'due_date' => '2016-10-20', 'patron_id' => $test_patron->getId()]], $result);
        }

        function test_searchTitle()
        {
            $test_book1 = new Book("Moby Dick");
            $test_book1->save();
            $test_book2 = new Book("Goodnight Moon");
            $test_book2->save();
            $test_book3 = new Book("The Great Gatsby");
            $test_book3->save();

            $result = Book::searchTitle("Moon");

            $this->assertEquals([$test_book2], $result);
        }

    }
        // export PATH=$PATH:./vendor/bin first and then you will only have to run  $ phpunit tests
?>
