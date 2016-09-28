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


    }
?>
