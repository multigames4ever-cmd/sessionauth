<?php
session_start();
require "db.php";

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid username or password.";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Login</title>
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
  <h2>Log In Your Account</h2>
  <p class="sub">Login to your account</p>

  <?php if ($error): ?><div class="err"><?= htmlspecialchars($error) ?></div><?php endif; ?>

  <form method="POST" action="">
    <label>Username</label>
    <input type="text" name="username" placeholder="Enter username" required>
    <label>Password</label>
    <input type="password" name="password" placeholder="Enter password" required>
    <button type="submit">Login</button>
  </form>
  <div class="foot">No account yet? <a href="register.php">Register here</a></div>
</div>
</body>
</html>