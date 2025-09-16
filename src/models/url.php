<?php
    
    class Url {
        private $pdo;

        public function __construct() {
            $db = new Database();
            $this->pdo = $db->connect();
        }

        public function create($originalUrl, $shortCode, $userId) {
            $urlCount = $this->countUserUrls($userId);
            if ($urlCount >= 5) {
                throw new \Exception('Has alcanzado el límite máximo de 5 URLs acortadas');
            }
            $stmt = $this->pdo?->prepare("INSERT INTO urls (original_url, short_code, user_id) VALUES (?,?,?)");
            return $stmt->execute([$originalUrl, $shortCode, $userId]);
        }

        public function findByShortCode($shortCode) {
            $stmt = $this->pdo?->prepare("SELECT * FROM urls WHERE short_code = ?");
            $stmt->execute([$shortCode]);
            return $stmt->fetch();
        }

        public function findByUserId($userId) {
            $stmt = $this->pdo?->prepare("SELECT * FROM urls WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
            $stmt->execute([$userId]);
            return $stmt->fetchAll();
        }

        public function countUserUrls($userId) {
            $stmt = $this->pdo?->prepare("SELECT COUNT(*) as total FROM urls WHERE user_id = ?");
            $stmt->execute([$userId]);
            return $stmt->fetch()['total'];
        }
    }