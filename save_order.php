<?php
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];
$items = json_decode($_POST['items'], true);
$payment_method = $_POST['payment_method'];

// Calculate total
$total = 0;
foreach ($items as $item) {
    $total += $item['price'];
}

// Insert order
$insert_order = "INSERT INTO orders (user_id, total_amount, payment_method, status) 
                 VALUES ($user_id, $total, '$payment_method', 'Completed')";

if ($conn->query($insert_order) === TRUE) {
    $order_id = $conn->insert_id;
    
    // Insert order items
    foreach ($items as $item) {
        $product_id = $item['id'];
        $price = $item['price'];
        
        $insert_item = "INSERT INTO order_items (order_id, product_id, quantity, price) 
                        VALUES ($order_id, $product_id, 1, $price)";
        $conn->query($insert_item);
    }
    
    echo json_encode(['success' => true, 'order_id' => $order_id]);
} else {
    echo json_encode(['error' => $conn->error]);
}
?>
