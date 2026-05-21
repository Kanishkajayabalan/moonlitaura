<?php
include 'config.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $message = "All fields are required!";
    } else {
        // Check user
        $query = "SELECT id, password FROM users WHERE username='$username'";
        $result = $conn->query($query);

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $username;
                header("Location: index.php");
                exit();
            } else {
                $message = "Invalid password!";
            }
        } else {
            $message = "User not found!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <title>Login - Moonlit Aura</title>

    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #ff9a9e, #fecfef, #a1f0ed, #ffd6a5);
            background-attachment: fixed;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-box {
            background: #f2f2f2;
            width: 320px;
            padding: 40px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        h2 {
            margin-bottom: 30px;
            color: #333;
        }

        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 15px;
            box-sizing: border-box;
        }

        .password-box {
            position: relative;
        }

        .password-box input {
            padding-right: 45px;
        }

        .password-box i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: black;
        }

        button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 5px;
            background: #4da3f0;
            color: white;
            font-size: 18px;
            cursor: pointer;
            margin-top: 15px;
        }

        button:hover {
            background: #3682c4;
        }

        #message {
            margin-top: 15px;
            font-size: 14px;
            padding: 10px;
            border-radius: 5px;
            color: white;
            display: none;
        }

        .success {
            background: #4caf50;
        }

        .error {
            background: #f44336;
            display: block;
        }

        .register-link {
            margin-top: 15px;
            font-size: 14px;
        }

        .register-link a {
            color: #4da3f0;
            text-decoration: none;
        }

        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="login-box">
        <h2>Login</h2>

        <form method="POST">
            <input type="text" name="username" id="username" placeholder="Username" required>

            <div class="password-box">
                <input type="password" name="password" id="password" placeholder="Enter Password" required>
                <i class="fa-solid fa-eye" id="eye"></i>
            </div>

            <button type="submit">Login</button>
        </form>

        <?php if ($message): ?>
            <div id="message" class="error"><?php echo $message; ?></div>
        <?php endif; ?>

        <div class="register-link">
            Don't have an account? <a href="register.php">Register here</a>
        </div>
    </div>

    <script>
        $("#eye").click(function () {
            let password = $("#password");

            if (password.attr("type") === "password") {
                password.attr("type", "text");
                $("#eye")
                    .removeClass("fa-eye")
                    .addClass("fa-eye-slash");
            } else {
                password.attr("type", "password");
                $("#eye")
                    .removeClass("fa-eye-slash")
                    .addClass("fa-eye");
            }
        });
    </script>
</body>

</html>
