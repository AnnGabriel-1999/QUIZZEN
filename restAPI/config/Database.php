<?php

    class Database {
        //Database Parameters
        private $host = "localhost";
        private $dbname = "quizzen";
        private $username = "root";
        private $password = "";
        private $conn;

        //Connect function
        public function connect() {
            
            try{
                // ETO
                $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->dbname, $this->username, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            } catch (PDOException $e) {
                echo "Connection error: ".$e->getMessage();
            }
            return $this->conn;
        }
    }
