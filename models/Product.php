<?php
require_once(dirname(__DIR__) . '/config/db.php');

class Product {
    private $conn;

    public function __construct() {
        $db = new DB();
        $this->conn = $db->connect();
    }

    // Add a new product with separate cost price and selling price
   public function addNewProduct($product_code, $name, $description, $cost_price, $price, $quantity) {
    $stmt = $this->conn->prepare("INSERT INTO products (product_code, name, description, cost_price, price, quantity) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssddi", $product_code, $name, $description, $cost_price, $price, $quantity);
    return $stmt->execute();
}


    // Fetch all products
    public function getAllProducts() {
        $result = $this->conn->query("SELECT * FROM products ORDER BY created_at DESC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Add stock using bind_param
    public function addStock($product_id, $quantity) {
        $stmt = $this->conn->prepare("UPDATE products SET quantity = quantity + ? WHERE id = ?");
        $stmt->bind_param("ii", $quantity, $product_id);
        return $stmt->execute();
    }

    // Add stock using execute with array (mysqli doesn't support this well, use bind_param instead)
    public function addStockToProduct($product_id, $quantity) {
        $stmt = $this->conn->prepare("UPDATE products SET quantity = quantity + ? WHERE id = ?");
        $stmt->bind_param("ii", $quantity, $product_id);
        return $stmt->execute();
    }
}
