<?php

    class Database {
        private $pdo;

        public function connect() {
            if($this->pdo === null){
                try {
                    $dsn = 'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_NAME'] . ';charset=utf8mb4';
                    $this->pdo = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS'],[
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]);
                } catch (PDOException $e) {
                    die('Database connection failed: ' . $e->getMessage());
                }
            }
            return $this->pdo;
        }
    }
