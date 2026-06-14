<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'config.php';
header('Content-Type: application/json');

// ── 1. Auth check ──────────────────────────────────────────
$user_id = $_SESSION['user_id'] ?? ($_POST['user_id'] ?? null);

if (!$user_id) {
    echo json_encode(['error' => 'Not logged in']);
    http_response_code(401);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Invalid request method']);
    http_response_code(405);
    exit();
}

// ── 2. Get POST data ───────────────────────────────────────
$user_id        = intval($user_id);
$items          = json_decode($_POST['items'] ?? '[]', true);
$payment_method = $conn->real_escape_string($_POST['payment_method'] ?? 'Unknown');

if (empty($items) || !is_array($items)) {
    echo json_encode(['error' => 'No items in order']);
    http_response_code(400);
    exit();
}

// ── 3. Calculate totals ────────────────────────────────────
$subtotal = 0;
foreach ($items as $item) {
    $subtotal += floatval($item['price'] ?? 0) * intval($item['quantity'] ?? 1);
}
$shipping_cost = 50.00;
$tax_amount    = round($subtotal * 0.05, 2);
$total_amount  = $subtotal + $shipping_cost + $tax_amount;

if ($total_amount <= 0) {
    echo json_encode(['error' => 'Invalid order amount']);
    http_response_code(400);
    exit();
}

// ── 4. Insert into orders (matching exact schema) ──────────
$sql = "INSERT INTO orders 
        (user_id, subtotal, shipping_cost, tax_amount, total_amount, payment_method) 
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(['error' => 'DB prepare error: ' . $conn->error]);
    http_response_code(500);
    exit();
}

// i = int, d = double/decimal, s = string
$stmt->bind_param('idddds',
    $user_id,
    $subtotal,
    $shipping_cost,
    $tax_amount,
    $total_amount,
    $payment_method
);

if (!$stmt->execute()) {
    echo json_encode(['error' => 'Order insert failed: ' . $stmt->error]);
    http_response_code(500);
    exit();
}

$order_id = $conn->insert_id;
$stmt->close();

// ── 5. Insert order_items ──────────────────────────────────
$item_sql  = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
$item_stmt = $conn->prepare($item_sql);

if (!$item_stmt) {
    echo json_encode(['error' => 'DB prepare error (items): ' . $conn->error]);
    http_response_code(500);
    exit();
}

foreach ($items as $item) {
    $product_id = intval($item['id']       ?? 1);
    $quantity   = intval($item['quantity'] ?? 1);
    $price      = floatval($item['price']  ?? 0);

    $item_stmt->bind_param('iiid', $order_id, $product_id, $quantity, $price);

    if (!$item_stmt->execute()) {
        echo json_encode(['error' => 'Order items insert failed: ' . $item_stmt->error]);
        http_response_code(500);
        exit();
    }
}

$item_stmt->close();

// ── 6. Success response ────────────────────────────────────
echo json_encode([
    'success'  => true,
    'order_id' => $order_id,
    'total'    => $total_amount,
    'message'  => 'Order placed successfully!'
]);
?>
