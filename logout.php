// logout.php
<?php include 'inc/header.php'; $_SESSION=[]; session_destroy(); echo "<p>You have been logged out.</p>"; include 'inc/footer.php'; ?>
