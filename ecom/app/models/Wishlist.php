<?php
// models/Wishlist.php
require_once __DIR__ . '/../db/Database.php';

class Wishlist {
    
    public static function toggle($userId, $productId) {
        $db = Database::getConnection();
        
        // Check if the item is already wishlisted
        $stmt = $db->prepare("SELECT id FROM wishlists WHERE user_id = ? AND product_id = ?");
        $stmt->execute([(int)$userId, (int)$productId]);
        $exists = $stmt->fetch();

        if ($exists) {
            // Remove it if it is already there
            $deleteStmt = $db->prepare("DELETE FROM wishlists WHERE user_id = ? AND product_id = ?");
            $deleteStmt->execute([(int)$userId, (int)$productId]);
            return 'removed';
        } else {
            // Add it if it isn't there
            $insertStmt = $db->prepare("INSERT INTO wishlists (user_id, product_id) VALUES (?, ?)");
            $insertStmt->execute([(int)$userId, (int)$productId]);
            return 'added';
        }
    }

    public static function getByUserId($userId) {
        $db = Database::getConnection();
        
        // Relational JOIN to pull catalog image, title, and pricing details matching favorites
        $stmt = $db->prepare("
            SELECT p.* FROM wishlists w 
            JOIN products p ON w.product_id = p.id 
            WHERE w.user_id = ? 
            ORDER BY w.created_at DESC
        ");
        $stmt->execute([(int)$userId]);
        return $stmt->fetchAll();
    }

    public static function getUserWishlistIds($userId) {
        $db = Database::getConnection();
        if (!$userId) return [];
        
        $stmt = $db->prepare("SELECT product_id FROM wishlists WHERE user_id = ?");
        $stmt->execute([(int)$userId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN, 0); // Returns a single-dimension array like [1, 3]
    }
}
