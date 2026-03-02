// login.php
<?php include 'inc/header.php'; require_once 'inc/db.php';
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $email = $_POST['email'] ?? ''; $password = $_POST['password'] ?? '';
  $user = fetchOne("SELECT u.*, r.name AS role_name FROM users u JOIN roles r ON u.role_id=r.id WHERE email=?", [$email]);
  if ($user && password_verify($password, $user['password_hash'])) {
    $_SESSION['user'] = $user; echo "<p>Welcome, ".htmlspecialchars($user['name'])."</p>";
  } else { echo "<p>Invalid email or password.</p>"; }
}
?>
<h1>Login</h1>
<form method="post">
  <label><strong>Email:</strong> <input type="email" name="email" required></label>
  <label><strong>Password:</strong> <input type="password" name="password" required></label>
  <button>Login</button>
</form>
<?php include 'inc/footer.php'; ?>
