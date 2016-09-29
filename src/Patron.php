<?php
    class Patron
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
            $GLOBALS['DB']->exec("INSERT INTO patrons (name) VALUES ('{$this->name}');");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        static function getAll()
        {
            $returned_patrons = $GLOBALS['DB']->query('SELECT * FROM patrons;');
                $patron_array = array();
                foreach($returned_patrons as $patron) {
                    $name = $patron['name'];
                    $id = $patron['id'];
                    $new_patron = new Patron($name, $id);
                    array_push($patron_array, $new_patron);
                }
                return $patron_array;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM patrons;");
        }

        function updateName($new_name)
        {
            $GLOBALS['DB']->exec("UPDATE patrons SET name = '{$new_name}' WHERE id = {$this->id};");
            $this->name = $new_name;
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM patrons WHERE id = {$this->id};");
        }

        static function find($search)
        {
            $patrons = Patron::getAll();
            $found_patron = null;
            foreach ($patrons as $patron)
            {
                if ($patron->getId() == $search || $patron->getName() == $search ){
                    $found_patron = $patron;
                }
            }
            return $found_patron;
        }

        function checkoutCopy($book)
        {
            $copy_id = $book->getAvailableCopy();
            $date = new DateTime();
            $date->add(new DateInterval('P21D'));
            $due_date = $date->format('Y-m-d');
            if ($copy_id){
                $GLOBALS['DB']->exec("INSERT INTO checkouts (copy_id, patron_id, due_date) VALUES ({$copy_id}, {$this->getId()}, '{$due_date}');");
                $GLOBALS['DB']->exec("UPDATE copies SET status = 'checked out' WHERE id = {$copy_id};");
                return 1;
            } else {
                return 0;
            }
        }


    }
?>
