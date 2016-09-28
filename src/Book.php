<?php
    class Book
    {
        private $id;
        private $title;

        function __construct($title, $id = null)
        {
           $this->title = $title;
           $this->id = $id;
        }

        function getId()
        {
            return $this->id;
        }

        function setId($new_id)
        {
            $this->id = $new_id;
        }

        function getTitle()
        {
            return $this->title;
        }

        function getNumberOfCopies()
        {
            $returned_copies = $GLOBALS['DB']->query("SELECT * FROM copies WHERE book_id = {$this->id};");
            $number_of_copies = count($returned_copies);
            return $number_of_copies;
        }

        function getBooksAuthors()
        {
            $returned_books_authors = $GLOBALS['DB']->query("SELECT authors.* FROM authors
                JOIN authors_books ON (authors_books.author_id = authors.id)
                JOIN books ON (authors_books.book_id = books.id)
                WHERE books.id = {$this->getId()};");
            $authors = array();
            foreach($returned_books_authors as $author) {
                $name = $author['name'];
                $id = $author['id'];
                $new_author = new Author($name, $id);
                array_push($authors, $new_author);
            }
            return $authors;
        }

        function addAuthor($author)
        {
            $GLOBALS['DB']->exec("INSERT INTO authors_books (book_id, author_id) VALUES ({$this->id}, {$author->getId()});");
        }

        function setTitle($new_title)
        {
            $this->title = $new_title;
        }

        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO books (title) VALUES ('{$this->title}');");
            $this->id = $GLOBALS['DB']->lastInsertId();
            $GLOBALS['DB']->exec("INSERT INTO copies (book_id) VALUES ({$this->id});");
        }

        static function getAll()
        {
            $returned_books = $GLOBALS['DB']->query('SELECT * FROM books;');
                $book_array = array();
                foreach($returned_books as $book) {
                    $title = $book['title'];
                    $id = $book['id'];
                    $new_book = new Book($title, $id);
                    array_push($book_array, $new_book);
                }
                return $book_array;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM books;");
            $GLOBALS['DB']->exec("DELETE FROM copies;");
        }

        function updateTitle($new_title)
        {
            $GLOBALS['DB']->exec("UPDATE books SET title = '{$new_title}' WHERE id = {$this->id};");
            $this->title = $new_title;
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM books WHERE id = {$this->id};");
            $GLOBALS['DB']->exec("DELETE FROM copies WHERE book_id = {$this->id};");
        }

        static function find($search)
        {
            $books = Book::getAll();
            $found_book = null;
            foreach ($books as $book)
            {
                if ($book->getId() == $search || $book->getTitle() == $search) {
                    $found_book = $book;
                }
            }
            return $found_book;
        }
    }
?>
