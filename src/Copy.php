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

        function updateCopyStatus($new_status)
        {
            $GLOBALS['DB']->exec("UPDATE copies SET status = '{$new_status}' WHERE id = {$this->id};");
            $this->status = $new_status;

        }

        function returnCopy()
        {
            $GLOBALS['DB']->exec("UPDATE copies SET status = 'available' WHERE id = {$this->id};");
            $this->status = "available";
        }

        static function updatePastDue()
        {
            $checked_out_copies = $GLOBALS['DB']->query("SELECT checkouts.* FROM copies JOIN checkouts ON (copies.id = checkouts.copy_id) WHERE copies.status = 'checked out';");
            $past_due_copies = array();
            foreach ($checked_out_copies as $copy) {
                $copy_id = $copy['copy_id'];
                $today = new DateTime();
                $due_date = new DateTime($copy['due_date']);
                if ($today > $due_date) {
                    $GLOBALS['DB']->exec("UPDATE copies SET status = 'past due' WHERE id = {$copy_id};");
                }
            }
        }
    }
?>
