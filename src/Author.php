<?php
    class Author
    {
        private $id;
        private $name;

        function __construct($name, $id = null)
        {
           $this->name = $name;
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

        function getName()
        {
            return $this->name;
        }

        function setName($new_name)
        {
            $this->name = $new_name;
        }

        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO authors (name) VALUES ('{$this->name}');");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        static function getAll()
        {
            $returned_authors = $GLOBALS['DB']->query('SELECT * FROM authors;');
                $author_array = array();
                foreach($returned_authors as $author) {
                    $name = $author['name'];
                    $id = $author['id'];
                    $new_author = new Author($name, $id);
                    array_push($author_array, $new_author);
                }
                return $author_array;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM authors;");
        }

        function updateName($new_name)
        {
            $GLOBALS['DB']->exec("UPDATE authors SET name = '{$new_name}' WHERE id = {$this->id};");
            $this->name = $new_name;
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM authors WHERE id = {$this->id};");
        }

        static function find($search)
        {
            $authors = Author::getAll();
            $found_author = null;
            foreach ($authors as $author)
            {
                if ($author->getId() == $search || $author->getName() == $search ){
                    $found_author = $author;
                }
            }
            return $found_author;
        }
    }
?>
