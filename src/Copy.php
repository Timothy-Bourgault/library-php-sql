<?php
    class Copy
    {
        private $id;
        private $book_id;
        private $status;

        function __construct($book_id, $status = "available", $id = null)
        {
           $this->book_id = $book_id;
           $this->id = $id;
           $this->status = $status;
        }

        function getId()
        {
            return $this->id;
        }

        function setId($new_id)
        {
            $this->id = $new_id;
        }

        function getBookId()
        {
            return $this->book_id;
        }

        function setBookId($new_book_id)
        {
            $this->book_id = $new_book_id;
        }

        function getStatus()
        {
            return $this->status;
        }

        function setStatus($new_status)
        {
            $this->status = $new_status;
        }

        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO copies (book_id, status) VALUES ({$this->book_id}, '{$this->status}');");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        static function getAll()
        {
            $returned_copies = $GLOBALS['DB']->query('SELECT * FROM copies;');
            $copy_array = array();
            foreach($returned_copies as $copy) {
                $book_id = $copy['book_id'];
                $id = $copy['id'];
                $status = $copy['status'];
                $new_copy = new Copy($book_id, $status, $id);
                array_push($copy_array, $new_copy);
            }
            return $copy_array;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM books;");
            $GLOBALS['DB']->exec("DELETE FROM copies;");
        }

        function getCopyIds($book)
        {
            $returned_copies = $GLOBALS['DB']->query("SELECT * FROM copies WHERE book_id = {$book->id};");
            $copy_ids = array();
            foreach($returned_copies as $copy) {
                $id = $copy['id'];
                array_push($copy_ids, $id);
            }
            return $copy_ids;
        }

        function getAvailableCopies($book)
        {
            $returned_copies = $GLOBALS['DB']->query("SELECT * FROM copies WHERE book_id = {$book->id} AND status = 'available';");
            $copy_ids = array();
            foreach($returned_copies as $copy) {
                $id = $copy['id'];
                array_push($copy_ids, $id);
            }
            return $copy_ids;
        }

        function updateCopyStatus()
        {
            $GLOBALS['DB']->query("SELECT * FROM checkouts WHERE copy_id = {$this->id}";);

        }
    }
?>
