// search.php
<?php include 'inc/header.php'; require_once 'inc/db.php';
$check_in = $_GET['check_in'] ?? '';
$check_out = $_GET['check_out'] ?? '';
$available = [];
if ($check_in && $check_out) {
  $available = fetchAll("
    SELECT r.*, h.name AS hotel_name FROM rooms r
    JOIN hotels h ON r.hotel_id=h.id
    WHERE r.is_active=1 AND r.id NOT IN (
      SELECT room_id FROM bookings
      WHERE (check_in < ? AND check_out > ?) -- overlap logic
         OR (check_in >= ? AND check_in < ?)
    )
    ORDER BY h.name, r.name
  ", [$check_out, $check_in, $check_in, $check_out]);
}
?>
<h1>Search availability</h1>
<form method="get">
  <label><strong>Check-in:</strong> <input type="date" name="check_in" required value="<?php echo htmlspecialchars($check_in); ?>"></label>
  <label><strong>Check-out:</strong> <input type="date" name="check_out" required value="<?php echo htmlspecialchars($check_out); ?>"></label>
  <button>Search</button>
</form>
<?php if ($check_in && $check_out): ?>
  <h2>Available rooms</h2>
  <ul>
    <?php foreach ($available as $r): ?>
      <li><?php echo htmlspecialchars($r['hotel_name'].' — '.$r['name']); ?> ($<?php echo number_format($r['price_per_night'],2); ?>/night)</li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>
<?php include 'inc/footer.php'; ?>
