<?php
session_start();
// Database configuration
$db_host = "localhost";
$db_user = "root";
$db_pass = "Kani@2007";
$db_name = "moonlit_aura";

// Create connection
$conn = new mysqli($db_host, $db_user, $db_pass);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if not exists
$create_db = "CREATE DATABASE IF NOT EXISTS $db_name";
if ($conn->query($create_db) === TRUE) {
    // Connect to the database
    $conn->select_db($db_name);
} else {
    die("Error creating database: " . $conn->error);
}

// Create tables if they don't exist

// Users table
$create_users = "CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$conn->query($create_users);

// Products table
$create_products = "CREATE TABLE IF NOT EXISTS products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    rating DECIMAL(3, 1) DEFAULT 5.0,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$conn->query($create_products);

// Orders table
$create_orders = "CREATE TABLE IF NOT EXISTS orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    payment_method VARCHAR(50),
    status VARCHAR(50) DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
)";
$conn->query($create_orders);

// Order items table
$create_order_items = "CREATE TABLE IF NOT EXISTS order_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT DEFAULT 1,
    price DECIMAL(10, 2),
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
)";
$conn->query($create_order_items);

// Insert sample products if table is empty
$check_products = "SELECT COUNT(*) as count FROM products";
$result = $conn->query($check_products);
$row = $result->fetch_assoc();

if ($row['count'] == 0) {
    $insert_products = "INSERT INTO products (name, description, price, rating, image) VALUES 
    ('Handmade Silver Necklace', 'Elegant handcrafted premium necklace.', 1299, 4.8, 'handmade silver necklace.webp'),
    ('Organic Lavender Candle', 'Natural soy wax relaxing candle.', 599, 4.6, 'lavender candle.jpg'),
    ('Crochet Handbag', 'Stylish handmade cotton crochet bag.', 1499, 5.0, 'crochet handbag.jpg'),
    ('Premium Resin Art Frame', 'Beautiful handcrafted resin wall art.', 2199, 4.7, 'resin art frame.webp'),
    ('Wooden Jewelry Box', 'Premium handcrafted storage box.', 1899, 4.9, 'wood box.webp'),
    ('Handmade Diary', 'Eco-friendly recycled paper journal.', 799, 4.5, 'handmade diary.webp'),
    ('Clay Pot Set', 'Traditional handcrafted clay pots.', 1299, 5.0, 'clay pot.jpg'),
    ('Macrame Wall Hanging', 'Boho-style handmade décor piece.', 1099, 4.6, 'wall hanging.jpeg'),
    ('Handmade Organic Soap Pack', 'Organic herbal soap collection.', 499, 4.9, 'soap.jpg'),
    ('Customized Name Frame', 'Personalized handcrafted gift frame.', 1699, 5.0, 'customized frame.avif')";
    
    $conn->query($insert_products);
}

session_start();
?>
