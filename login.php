<?php
session_start();
include 'config.php';

// Handle POST (AJAX login)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    header('Content-Type: application/json');
    $response = ['success' => false, 'error' => ''];

    $username = $conn->real_escape_string($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $response['error'] = "All fields are required!";
        echo json_encode($response);
        exit();
    }

    $query = "SELECT id, username, password FROM users WHERE username='$username'";
    $result = $conn->query($query);

    if ($result && $result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $username;
            $_SESSION['logged_in'] = true;
            $response['success'] = true;
            $response['username'] = $username;
            $response['user_id'] = $user['id'];
        } else {
            $response['error'] = "Invalid password!";
        }
    } else {
        $response['error'] = "User not found!";
    }
    echo json_encode($response);
    exit();
}
// GET request - show login page
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <title>Login - Moonlit Aura</title>
    <style>
        body { display:flex; justify-content:center; align-items:center; min-height:100vh; padding:20px; background: url('handmade background.png') center/cover no-repeat fixed; background-color: #f5f5f5; }
        .login-box { width:420px; }
    </style>
</head>
<body>
    <div class="glass-card login-box">
        <div class="logo">🌙</div>
        <h2>Login</h2>

        <form id="loginForm" method="POST" action="login.php">
            <input type="text" id="username" name="username" placeholder="Username" class="form-input" required>

            <div class="password-box">
                <input type="password" id="password" name="password" placeholder="Enter Password" class="form-input" required>
                <i class="fa-solid fa-eye" id="eye"></i>
            </div>

            <button type="submit" id="loginBtn" class="btn-primary">Login</button>
        </form>

        <p id="message" class="msg"></p>

        <div class="register-link">
            Don't have an account? <a onclick="goToRegister()">Register here</a>
        </div>
    </div>

    <script>
        $("#eye").click(function(){
            let pwd = $("#password");
            if(pwd.attr("type") === "password"){
                pwd.attr("type","text");
                $("#eye").removeClass("fa-eye").addClass("fa-eye-slash");
            } else {
                pwd.attr("type","password");
                $("#eye").removeClass("fa-eye-slash").addClass("fa-eye");
            }
        });

        $("#loginForm").on("submit", function(e){
            e.preventDefault();
            let username = $("#username").val().trim();
            let password = $("#password").val().trim();
            let $msg = $("#message");

            if(!username || !password){
                $msg.html("All fields are required!").css({color:"red",display:"block"});
                return;
            }

            $("#loginBtn").text("Logging in...").prop("disabled", true);

            $.ajax({
                type: "POST",
                url: "login.php",
                data: { username, password },
                dataType: "json",
                success: function(res){
                    if(res.success){
                        localStorage.setItem('isLoggedIn','true');
                        localStorage.setItem('username', res.username);
                        localStorage.setItem('user_id', res.user_id);
                        $msg.html("✅ Login successful! Redirecting...").css({color:"green",display:"block"});
                        setTimeout(() => window.location.href = 'index.html', 1000);
                    } else {
                        $msg.html("❌ " + (res.error || "Login failed!")).css({color:"red",display:"block"});
                        $("#loginBtn").text("Login").prop("disabled", false);
                    }
                },
                error: function(){
                    $msg.html("❌ Server error. Please try again.").css({color:"red",display:"block"});
                    $("#loginBtn").text("Login").prop("disabled", false);
                }
            });
        });

        function goToRegister(){
            window.location.href = 'register.php';
        }
    </script>
</body>
</html>
