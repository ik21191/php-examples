<?php
// models/Order.php
require_once __DIR__ . '/../config/database.php';

class Order {
    public static function saveOrder($userId, $mockPaymentId, $paymentMethod, $totalAmount, $cartItems) {
        $db = Database::getConnection();

        try {
            // Begin transactional tracking context
            $db->beginTransaction();

            // 1. Insert records into the master orders table
            $stmt = $db->prepare("INSERT INTO orders (user_id, mock_payment_id, payment_method, total_amount) VALUES (?, ?, ?, ?)");
            $stmt->execute([$userId, $mockPaymentId, $paymentMethod, $totalAmount]);
            
            // Capture the generated internal serial primary key index auto-increment ID
            $orderId = $db->lastInsertId();

            // 2. Loop through and save individual cart items inside the order_items table
            $itemStmt = $db->prepare("INSERT INTO order_items (order_id, product_id, product_name, price, quantity) VALUES (?, ?, ?, ?, ?)");
            
            foreach ($cartItems as $productId => $item) {
                $itemStmt->execute([
                    $orderId,
                    $productId,
                    $item['name'],
                    $item['price'],
                    $item['quantity']
                ]);
            }

            // Commit transaction state definitively to the MySQL storage engine
            $db->commit();
            return true;

        } catch (\Exception $e) {
            // Rollback changes immediately if an error surfaces
            $db->rollBack();
            error_log("Database Order Transaction Error Block: " . $e->getMessage());
            return false;
        }
    }

    public static function getOrdersByUserId($userId) {
        $db = Database::getConnection();
        
        // Fetch all orders matching the target user identifier
        $stmt = $db->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$userId]);
        $orders = $stmt->fetchAll();

        // Loop and attach specific sub-items to each individual master entry row
        foreach ($orders as $key => $order) {
            $itemStmt = $db->prepare("SELECT * FROM order_items WHERE order_id = ?");
            $itemStmt->execute([$order['id']]);
            $orders[$key]['items'] = $itemStmt->fetchAll();
        }

        return $orders;
    }
}
