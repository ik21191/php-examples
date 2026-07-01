<?php
require_once __DIR__ . '/../db/Database.php';

class Review {
    public static function create($productId, $userId, $rating, $comment) {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO reviews (product_id, user_id, rating, comment) VALUES (?, ?, ?, ?)");
        return $stmt->execute([(int)$productId, (int)$userId, (int)$rating, trim($comment)]);
    }

    public static function getByProductId($productId) {
        $db = Database::getConnection();
        // JOIN allows us to print the real user name alongside their star submission text
        $stmt = $db->prepare("
            SELECT r.*, u.name as user_name 
            FROM reviews r 
            JOIN users u ON r.user_id = u.id 
            WHERE r.product_id = ? 
            ORDER BY r.created_at DESC
        ");
        $stmt->execute([(int)$productId]);
        return $stmt->fetchAll();
    }

    public static function getAverageRating($productId) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT AVG(rating) as avg_rating, COUNT(id) as total_count FROM reviews WHERE product_id = ?");
        $stmt->execute([(int)$productId]);
        return $stmt->fetch();
    }
}
