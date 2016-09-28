<?php
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */
    //TO RUN TESTS IN TERMINAL:
    //export PATH=$PATH:./vendor/bin
    //phpunit tests
    require_once "src/Author.php";
    $server = 'mysql:host=localhost;dbname=library_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class AuthorTest extends PHPUnit_Framework_TestCase
    {

        protected function tearDown()
        {
            Author::deleteAll();
        }

        function test_getName()
        {
            //Arrange
            $test_author = new Author("Herman Melville");

            //Act
            $output = $test_author->getName();

            //Assert
            $this->assertEquals("Herman Melville", $output);
        }

        function test_save()
        {
            //Arrange
            $test_author = new Author("Herman Melville");
            $test_author->save();

            //Act
            $output = Author::getAll();

            //Assert
            $this->assertEquals($test_author, $output[0]);
        }

        function test_update_name()
        {
            //Arrange
            $test_author = new Author("Herman Melville");
            $test_author->save();

            //Act
            $test_author->updateName("F. Scott Fitzgerald");
            $output = $test_author->getName();

            //Assert
            $this->assertEquals("F. Scott Fitzgerald", $output);
        }

        function test_delete_author()
        {
            //Arrange
            $test_author = new Author("Herman Melville");
            $test_author->save();

            //Act
            $test_author->delete();
            $output = Author::getAll();

            //Assert
            $this->assertEquals([], $output);
        }

        function test_findId()
        {
            $test_author = new Author("Herman Melville");
            $test_author->save();

            $output = Author::find($test_author->getId());

            $this->assertEquals($test_author, $output);

        }

        function test_findName()
        {
            $test_author = new Author("Herman Melville");
            $test_author->save();

            $output = Author::find($test_author->getName());

            $this->assertEquals($test_author, $output);

        }

    }
        // export PATH=$PATH:./vendor/bin first and then you will only have to run  $ phpunit tests
?>
