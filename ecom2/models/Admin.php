<?php
// models/Admin.php
require_once __DIR__ . '/../config/database.php';

class Admin {
    
    // Aggregate platform high-level metric indexes
    public static function getKPIStats() {
        $db = Database::getConnection();
        
        $totalRevenue = $db->query("SELECT SUM(total_amount) AS total FROM orders")->fetch()['total'] ?? 0;
        $totalOrders  = $db->query("SELECT COUNT(id) AS total FROM orders")->fetch()['total'] ?? 0;
        $totalUsers   = $db->query("SELECT COUNT(id) AS total FROM users")->fetch()['total'] ?? 0;
        $totalItems   = $db->query("SELECT SUM(quantity) AS total FROM order_items")->fetch()['total'] ?? 0;

        return [
            'revenue' => (float)$totalRevenue,
            'orders'  => (int)$totalOrders,
            'users'   => (int)$totalUsers,
            'items'   => (int)$totalItems
        ];
    }

    // Capture running chronological revenue histories for chart rendering trends
    public static function getDailyRevenueTrend() {
        $db = Database::getConnection();
        
        // Groups financial transaction values matching localized date strings
        $stmt = $db->query("
            SELECT DATE(created_at) AS order_date, SUM(total_amount) AS revenue, COUNT(id) AS volume 
            FROM orders 
            GROUP BY DATE(created_at) 
            ORDER BY order_date ASC 
            LIMIT 30
        ");
        return $stmt->fetchAll();
    }

    // Segment platform preferences matching user payment mechanism metrics
    public static function getPaymentBreakdown() {
        $db = Database::getConnection();
        
        $stmt = $db->query("
            SELECT payment_method, COUNT(id) AS usage_count, SUM(total_amount) AS revenue 
            FROM orders 
            GROUP BY payment_method
        ");
        return $stmt->fetchAll();
    }
}
