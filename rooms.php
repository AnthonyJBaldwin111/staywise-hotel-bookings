<?php include 'inc/header.php'; require_once 'inc/db.php';

$rooms = fetchAll("
  SELECT r.*, h.name AS hotel_name 
  FROM rooms r 
  JOIN hotels h ON r.hotel_id=h.id 
  WHERE r.is_active=1 
  ORDER BY h.name, r.name
");
?>
<h1>Rooms</h1>
<div class="grid">
  <?php foreach ($rooms as $r): ?>
    <div class="card">
      <?php
      // Map slug to image file
      $imgMap = [
        'business-king' => '/assets/images/rooms/businessking.jpg',
        'penthouse-suite' => '/assets/images/rooms/penthouse.jpg',
        'family-room' => '/assets/images/rooms/family.jpg',
        'queen-suite' => '/assets/images/rooms/queen.jpg'
      ];
      $img = $imgMap[$r['slug']] ?? '/assets/images/storefront.jpg';
      ?>
      <img src="<?php echo APP_URL . $img; ?>" alt="<?php echo htmlspecialchars($r['name']); ?>">
      <h3><?php echo htmlspecialchars($r['name']); ?> — <?php echo htmlspecialchars($r['hotel_name']); ?></h3>
      <p>$<?php echo number_format($r['price_per_night'],2); ?>/night • Sleeps <?php echo (int)$r['capacity']; ?></p>
      <a href="<?php echo APP_URL; ?>/room.php?slug=<?php echo urlencode($r['slug']); ?>">View</a>
    </div>
  <?php endforeach; ?>
</div>
<?php include 'inc/footer.php'; ?>
