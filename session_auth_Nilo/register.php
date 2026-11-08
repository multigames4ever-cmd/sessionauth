<?php
session_start();
require "db.php";

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $error = "Username or email already exists.";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $insert = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $insert->bind_param("sss", $username, $email, $hash);
        if ($insert->execute()) {
            $success = "Account created! You can now log in.";
        } else {
            $error = "Database error.";
        }
        $insert->close();
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Register</title>
<style>
  body{font-family:Segoe UI,Arial,sans-serif;background:#eef3f7;display:flex;justify-content:center;align-items:center;min-height:100vh;margin:0}
  .card{background:#fff;padding:32px;border-radius:12px;box-shadow:0 4px 18px rgba(0,0,0,.1);width:340px;text-align:center}
  h2{margin:8px 0 4px;color:#333}
  p.sub{color:#888;font-size:14px;margin:0 0 20px}
  label{display:block;text-align:left;font-size:13px;font-weight:600;margin:12px 0 4px;color:#444}
  input{width:100%;padding:10px;border:1px solid #d8c9c9;border-radius:6px;box-sizing:border-box}
  button{width:100%;margin-top:20px;padding:11px;background:#28a745;color:#fff;border:0;border-radius:6px;font-size:15px;cursor:pointer}
  button:hover{background:#218838}
  .msg{padding:10px;border-radius:6px;font-size:14px;margin-bottom:10px}
  .err{background:#f8d7da;color:#842029}
  .ok{background:#d1e7dd;color:#0f5132}
  a{color:#0d6efd;text-decoration:none}
  .foot{margin-top:16px;font-size:14px;color:#666}
</style>
</head>
<body>
<div class="card">
  <h2>Create Account</h2>
  <p class="sub">Sign up to get started</p>

  <?php if ($error): ?><div class="msg err"><?= htmlspecialchars($error) ?></div><?php endif; ?>
  <?php if ($success): ?><div class="msg ok"><?= htmlspecialchars($success) ?></div><?php endif; ?>

    <form method="POST" action="">
        <label>Username</label>
        <input type = "text" name = "username" placeholder = "Enter Username" required>
        <label>Email Address</label>
        <input type = "email" name = "email" placeholder = "Enter Email" required>
        <label>Password</label>
        <input type = "password" name = "password" placeholder = "Enter Password" required>
        <button type = "Submit">Register</button>
    </form>
    <div class="foot">Already have an account? <a href="index.php">Login Here</a></div>
</div>
</body>
</html>