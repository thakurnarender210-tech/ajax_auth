<?php
    class Database
    {
        private $conn;

        private $host = "localhost";
        private $username = "root";
        private $password = "";
        private $dbname = "ajax_auth";

        public function connect()
        {
            $conn = new mysqli(
                $this->host,
                $this->username,
                $this->password,
                $this->dbname
            );
            return $conn;
        }
    }
?>