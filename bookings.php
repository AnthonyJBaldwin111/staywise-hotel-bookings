// bookings.php
<?php include 'inc/header.php'; require_once 'inc/db.php';
$user_id = 2; // demo user
$bookings = fetchAll("SELECT b.*, r.name AS room_name, h.name AS hotel_name
  FROM bookings b
  JOIN rooms r ON b.room_id=r.id
  JOIN hotels h ON r.hotel_id=h.id
  WHERE b.user_id=? ORDER BY b.created_at DESC", [$user_id]); ?>
<h1>My bookings</h1>
<?php foreach ($bookings as $b): ?>
  <article>
    <h3>#<?php echo (int)$b['id']; ?> — <?php echo htmlspecialchars($b['hotel_name']); ?> / <?php echo htmlspecialchars($b['room_name']); ?></h3>
    <p><?php echo htmlspecialchars($b['check_in']); ?> to <?php echo htmlspecialchars($b['check_out']); ?> (<?php echo (int)$b['nights']; ?> nights)</p>
    <p>Status: <?php echo htmlspecialchars($b['status']); ?> — Total: $<?php echo number_format($b['total'],2); ?></p>
  </article>
<?php endforeach; ?>
<?php include 'inc/footer.php'; ?>
