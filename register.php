// register.php
<?php include 'inc/header.php'; require_once 'inc/db.php';
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $name = trim($_POST['name'] ?? ''); $email = trim($_POST['email'] ?? ''); $password = $_POST['password'] ?? '';
  if ($name && $email && $password) {
    $hash = password_hash($password, PASSWORD_BCRYPT);
    $id = insert('users', ['role_id'=>2,'name'=>$name,'email'=>$email,'password_hash'=>$hash]);
    echo "<p>Registered as #$id. Please login.</p>";
  } else { echo "<p>All fields are required.</p>"; }
}
?>
<h1>Register</h1>
<form method="post">
  <label><strong>Name:</strong> <input name="name" required></label>
  <label><strong>Email:</strong> <input type="email" name="email" required></label>
  <label><strong>Password:</strong> <input type="password" name="password" required></label>
  <button>Sign up</button>
</form>
<?php include 'inc/footer.php'; ?>
