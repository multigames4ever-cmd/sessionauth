<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$username = htmlspecialchars($_SESSION['username']);
$userId = (int) $_SESSION['user_id'];
$sid = session_id();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Dashboard</title>
<style>
  body{font-family:Segoe UI,Arial,sans-serif;margin:0;background:#lightgreen;color:#333}
  nav{background:#212529;color:#fff;display:flex;justify-content:space-between;align-items:center;padding:12px 40px}
  nav .left a{color:#fff;text-decoration:none;margin-right:20px}
  nav .left a.brand{font-weight:bold}
  nav .right span{margin-right:14px}
  nav .logout{background:#dc3545;color:#fff;padding:6px 12px;border-radius:6px;text-decoration:none}
  .hero{padding:60px 40px 30px}
  .hero h1{color:#0d6efd;font-size:40px;margin:0 0 10px}
  .hero p{color:#777}
  .btn{display:inline-block;background:#0d6efd;color:#fff;padding:10px 18px;border-radius:6px;text-decoration:none}
  .grid{display:flex;flex-wrap:wrap;gap:30px;padding:30px 40px}
  .box{flex:1;min-width:240px}
  .box h3{margin-top:0}
  .sid{color:#d63384;font-family:monospace;word-break:break-all}
  .small{font-size:12px;color:#888}
  code{color:#d63384}
</style>
</head>
<body>
<nav>
  <div class="left">
    <a class="brand" href="dashboard.php">SessionAuthSystem</a>
    <a href="dashboard.php">Dashboard</a>
  </div>
  <div class="right">
    <span>👤 <?= $username ?></span>
    <a class="logout" href="logout.php">Logout</a>
  </div>
</nav>

<div class="hero">
  <h1>Welcome, <?= $username ?>!</h1>
  <p>This page is protected and only visible to logged-in users.</p>
  <a class="btn" href="#details">Explore Dashboard</a>
</div>

<div class="grid" id="details">
  <div class="box">
    <h3>🛡️ Account Status</h3>
    <p>Your session is active and protected against Session Fixation using <code>session_regenerate_id()</code>.</p>
  </div>
  <div class="box">
    <h3>👤 User Details</h3>
    <p><b>User ID:</b> <?= $userId ?></p>
    <p><b>Username:</b> <?= $username ?></p>
  </div>
  <div class="box">
    <h3>🔑 Active Session ID</h3>
    <p><b>Current Session ID:</b></p>
    <p class="sid"><?= htmlspecialchars($sid) ?></p>
    <p class="small">Generated after login call to <code>session_regenerate_id()</code></p>
  </div>
  <marquee behavior="scroll " direction="left" loop="infinite">!This is a Demo Dashboard.</marquee>
</div>
</body>
</html>
