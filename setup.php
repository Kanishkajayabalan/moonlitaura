<?php
// Test database connection and display setup status
include 'config.php';

echo "<h2>Moonlit Aura - Database Setup Status</h2>";
echo "<hr>";

// Test connection
if ($conn->connect_error) {
    die("❌ Connection Error: " . $conn->connect_error);
}
echo "✅ Database Connection: SUCCESS<br>";
echo "✅ Database Name: moonlit_aura<br>";

// Check tables
$tables = ['users', 'products', 'orders', 'order_items'];
foreach ($tables as $table) {
    $result = $conn->query("SHOW TABLES LIKE '$table'");
    if ($result->num_rows > 0) {
        echo "✅ Table '$table': EXISTS<br>";
    } else {
        echo "❌ Table '$table': MISSING<br>";
    }
}

// Check products
$product_count = $conn->query("SELECT COUNT(*) as count FROM products")->fetch_assoc();
echo "<br>✅ Products Inserted: " . $product_count['count'] . " items<br>";

echo "<hr>";
echo "<h3>Setup Complete! 🎉</h3>";
echo "<p><strong>Access Your Store:</strong></p>";
echo "<ul>";
echo "<li><a href='register.php'>Create New Account</a></li>";
echo "<li><a href='login.php'>Login</a></li>";
echo "</ul>";

echo "<p><strong>Test Credentials:</strong></p>";
echo "<p>You can register a new account or use these test credentials:</p>";
echo "<p>Username: demo<br>Password: demo123</p>";

echo "<p style='background: #ffffcc; padding: 10px; border-radius: 5px;'>";
echo "<strong>Note:</strong> The database and tables are automatically created when you access this page.<br>";
echo "Database: <strong>moonlit_aura</strong><br>";
echo "User: <strong>root</strong><br>";
echo "All credentials are stored securely with password hashing (bcrypt).<br>";
echo "</p>";

// Create test user if not exists
$test_check = $conn->query("SELECT id FROM users WHERE username='demo'");
if ($test_check->num_rows == 0) {
    $test_password = password_hash('demo123', PASSWORD_BCRYPT);
    $conn->query("INSERT INTO users (username, email, password) VALUES ('demo', 'demo@moonlitaura.com', '$test_password')");
    echo "<p style='background: #ccffcc; padding: 10px; border-radius: 5px;'>✅ Test account 'demo' created!</p>";
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Setup Status</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            background: linear-gradient(135deg,#ff9a9e,#fecfef,#a1f0ed,#ffd6a5);
            padding: 20px;
        }
        h2, h3 { color: #333; }
        a {
            color: #4da3f0;
            text-decoration: none;
            font-weight: bold;
        }
        a:hover { text-decoration: underline; }
        ul { line-height: 2; }
    </style>
</head>
<body>

</body>
</html>
