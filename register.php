<?php
session_start();
include 'config.php';

// Handle POST (AJAX register)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    header('Content-Type: application/json');
    $response = ['success' => false, 'error' => ''];

    $fullname = $conn->real_escape_string($_POST['fullname'] ?? '');
    $email    = $conn->real_escape_string($_POST['email'] ?? '');
    $username = $conn->real_escape_string($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    if (empty($fullname) || empty($email) || empty($username) || empty($password)) {
        $response['error'] = "All fields are required!";
        echo json_encode($response); exit();
    }

    if ($password !== $confirm) {
        $response['error'] = "Passwords do not match!";
        echo json_encode($response); exit();
    }

    if (strlen($password) < 6) {
        $response['error'] = "Password must be at least 6 characters!";
        echo json_encode($response); exit();
    }

    // Check duplicate
    $check = $conn->query("SELECT id FROM users WHERE username='$username' OR email='$email'");
    if ($check && $check->num_rows > 0) {
        $response['error'] = "Username or Email already exists!";
        echo json_encode($response); exit();
    }

    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $insert = "INSERT INTO users (username, email, password) VALUES ('$username','$email','$hashed')";

    if ($conn->query($insert)) {
        $new_id = $conn->insert_id;
        $_SESSION['user_id']  = $new_id;
        $_SESSION['username'] = $username;
        $_SESSION['logged_in'] = true;
        $response['success']  = true;
        $response['username'] = $username;
        $response['user_id']  = $new_id;
        $response['email']    = $email;
        $response['fullname'] = $fullname;
    } else {
        $response['error'] = "Registration failed. Please try again.";
    }

    echo json_encode($response); exit();
}
// GET - show register page
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <title>Register - Moonlit Aura</title>
    <style>
        body { display:flex; justify-content:center; align-items:center; min-height:100vh; padding:20px; background: url('handmade background.png') center/cover no-repeat fixed; background-color: #f5f5f5; }
        .register-box { width:420px; }
        .login-link { margin-top:20px; font-size:14px; color:#666; }
        .login-link a { color:#6a11cb; text-decoration:none; font-weight:600; cursor:pointer; }
        .login-link a:hover { text-decoration:underline; }
    </style>
</head>
<body>
    <div class="glass-card register-box">
        <div class="logo">🌙</div>
        <h2>Create Account</h2>

        <form id="registerForm" method="POST" action="register.php">
            <input type="text"     id="fullname"        name="fullname"         placeholder="Full Name"        class="form-input" required>
            <input type="email"    id="email"           name="email"            placeholder="Email Address"    class="form-input" required>
            <input type="text"     id="username"        name="username"         placeholder="Username"         class="form-input" required>

            <div class="password-box">
                <input type="password" id="password" name="password" placeholder="Enter Password" class="form-input" required>
                <i class="fa-solid fa-eye" id="eye"></i>
            </div>

            <input type="password" id="confirmPassword" name="confirm_password" placeholder="Confirm Password" class="form-input" required>

            <button type="submit" id="registerBtn" class="btn-primary">Register</button>
        </form>

        <p id="message" class="msg"></p>

        <div class="login-link">
            Already have an account? <a onclick="goToLogin()">Login here</a>
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

        $("#registerForm").on("submit", function(e){
            e.preventDefault();
            let fullname = $("#fullname").val().trim();
            let email    = $("#email").val().trim();
            let username = $("#username").val().trim();
            let password = $("#password").val().trim();
            let confirm  = $("#confirmPassword").val().trim();
            let $msg     = $("#message");

            if(!fullname || !email || !username || !password || !confirm){
                $msg.html("All fields are required!").css({color:"red", display:"block"}); return;
            }
            if(!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)){
                $msg.html("Please enter a valid email!").css({color:"red", display:"block"}); return;
            }
            if(password !== confirm){
                $msg.html("Passwords do not match!").css({color:"red", display:"block"}); return;
            }
            if(password.length < 6){
                $msg.html("Password must be at least 6 characters!").css({color:"red", display:"block"}); return;
            }

            $("#registerBtn").text("Registering...").prop("disabled", true);

            $.ajax({
                type: "POST",
                url: "register.php",
                data: { fullname, email, username, password, confirm_password: confirm },
                dataType: "json",
                success: function(res){
                    if(res.success){
                        localStorage.setItem('isLoggedIn','true');
                        localStorage.setItem('username', res.username);
                        localStorage.setItem('user_id', res.user_id);
                        $msg.html("✅ Registration successful! Redirecting...").css({color:"green", display:"block"});
                        setTimeout(() => window.location.href = 'index.html', 1000);
                    } else {
                        $msg.html("❌ " + (res.error || "Registration failed!")).css({color:"red", display:"block"});
                        $("#registerBtn").text("Register").prop("disabled", false);
                    }
                },
                error: function(){
                    $msg.html("❌ Server error. Please try again.").css({color:"red", display:"block"});
                    $("#registerBtn").text("Register").prop("disabled", false);
                }
            });
        });

        function goToLogin(){ window.location.href = 'login.php'; }
    </script>
</body>
</html>
