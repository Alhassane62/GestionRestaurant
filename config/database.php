<?php


class Database
{

        private $serverName = 'localhost\SQLEXPRESS';
        private $database = 'GestionRestaurant';

        private $username = 'restaurant';
        private $password = 'alhassane123';


        public function getConnection()
        {
                try {
                       
                        $dsn = "sqlsrv:Server={$this->serverName};Database={$this->database};TrustServerCertificate=true";

                        if ($this->username === null || $this->username === '') {
                                $conn = new PDO($dsn);
                        } else {
                                $conn = new PDO($dsn, $this->username, $this->password);
                        }

                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                        return $conn;
                } catch (Throwable $exception) {
                        throw new RuntimeException("Connexion SQL Server impossible. Verifiez config/database.php, SQL Server et la base GestionRestaurant.");
                }
        }
}
