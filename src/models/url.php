<?php
    namespace App\models;

use App\Config\Database;
use PDO;

    class Url {
        private $pdo;

        public function __construct() {
            $db = new Database();
            $this->pdo = $db->connect();
        }

        public function create($originalUrl, $shortCode, $userId) {
            $stmt = $this->pdo?->prepare("INSERT INTO urls (original_url, short_code, user_id) VALUES (?,?,?)");
            return $stmt->execute([$originalUrl, $shortCode, $userId]);
        }

        public function findByShortCode($shortCode) {
            $stmt = $this->pdo?->prepare("SELECT * FROM urls WHERE short_code = ?");
            $stmt->execute([$shortCode]);
            return $stmt->fetch();
        }

        public function findByUserId($userId) {
            $stmt = $this->pdo?->prepare("SELECT * FROM urls WHERE user_id = ? ORDER BY created_at DESC");
            $stmt->execute([$userId]);
            return $stmt->fetchAll();
        }
    }