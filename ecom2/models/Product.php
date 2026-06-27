<?php
// models/Product.php
require_once __DIR__ . '/../config/database.php';

class Product {
    public static function getAll() {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM products");
        return $stmt->fetchAll();
    }

    public static function getById($id) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public static function create($name, $price, $image) {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO products (name, price, image) VALUES (?, ?, ?)");
        return $stmt->execute([$name, (float)$price, $image]);
    }

        public static function update($id, $name, $price, $image) {
        $db = Database::getConnection();
        
        // IF NO NEW IMAGE FILE WAS SUPPLIED: Simply patch text elements
        if (empty($image)) {
            $stmt = $db->prepare("UPDATE products SET name = ?, price = ? WHERE id = ?");
            return $stmt->execute([$name, (float)$price, (int)$id]);
        }
        
        // IF A NEW IMAGE IS PROVIDED: Clean up the old image file before tracking the new path
        try {
            $stmtOld = $db->prepare("SELECT image FROM products WHERE id = ?");
            $stmtOld->execute([(int)$id]);
            $oldProduct = $stmtOld->fetch();

            if ($oldProduct && !empty($oldProduct['image']) && $oldProduct['image'] !== $image) {
                $oldImagePath = $oldProduct['image'];

                // Verify it points to a local upload path asset
                if (strpos($oldImagePath, 'uploads/') === 0) {
                    $absoluteOldPath = __DIR__ . '/../' . $oldImagePath;
                    if (file_exists($absoluteOldPath)) {
                        unlink($absoluteOldPath); // Evict the orphaned file from storage
                    }
                }
            }
        } catch (\Exception $e) {
            error_log("Failed to evict overwritten file asset during update: " . $e->getMessage());
        }

        // Save the new product info along with the updated image path string
        $stmt = $db->prepare("UPDATE products SET name = ?, price = ?, image = ? WHERE id = ?");
        return $stmt->execute([$name, (float)$price, $image, (int)$id]);
    }


    public static function delete($id) {
        $db = Database::getConnection();

        try {
            // 1. Fetch the product details to identify its current image file path notation
            $stmt = $db->prepare("SELECT image FROM products WHERE id = ?");
            $stmt->execute([(int)$id]);
            $product = $stmt->fetch();

            if ($product && !empty($product['image'])) {
                $imagePath = $product['image'];

                // Safe Check: Ensure we only target files within our local uploads folder
                // This prevents accidentally deleting seed external network URLs or system assets
                if (strpos($imagePath, 'uploads/') === 0) {
                    
                    // Build the absolute file path relative to this model's location
                    $absoluteFilePath = __DIR__ . '/../' . $imagePath;

                    // Verify if the physical file exists on the hard drive disk before unlinking
                    if (file_exists($absoluteFilePath)) {
                        unlink($absoluteFilePath); // Delete the local file from server storage
                    }
                }
            }

            // 2. Drop the record row completely from the MySQL products table
            $deleteStmt = $db->prepare("DELETE FROM products WHERE id = ?");
            return $deleteStmt->execute([(int)$id]);

        } catch (\Exception $e) {
            error_log("Failed to clean up file asset or delete product: " . $e->getMessage());
            return false;
        }
    }
}
