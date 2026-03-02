<?php include 'inc/header.php'; require_once 'inc/db.php';

$slug = $_GET['slug'] ?? '';
$room = fetchOne("
  SELECT r.*, h.name AS hotel_name 
  FROM rooms r 
  JOIN hotels h ON r.hotel_id=h.id 
  WHERE r.slug=?", [$slug]
);

if (!$room) { echo "<p>Room not found.</p>"; include 'inc/footer.php'; exit; }

$amen = fetchAll("
  SELECT a.* 
  FROM room_amenities ra 
  JOIN amenities a ON ra.amenity_id=a.id 
  WHERE ra.room_id=?", [$room['id']]
);

// Map slug to image file
$imgMap = [
  'business-king' => '/assets/images/rooms/businessking.jpg',
  'penthouse-suite' => '/assets/images/rooms/penthouse.jpg',
  'family-room' => '/assets/images/rooms/family.jpg',
  'queen-suite' => '/assets/images/rooms/queen.jpg'
];
$img = $imgMap[$room['slug']] ?? '/assets/images/storefront.jpg';
?>
<h1><?php echo htmlspecialchars($room['name']); ?> — <?php echo htmlspecialchars($room['hotel_name']); ?></h1>
<img src="<?php echo APP_URL . $img; ?>" alt="<?php echo htmlspecialchars($room['name']); ?>">
<p><?php echo nl2br(htmlspecialchars($room['description'])); ?></p>
<p><strong>Price:</strong> $<?php echo number_format($room['price_per_night'],2); ?>/night</p>
<p><strong>Amenities:</strong> 
  <?php echo implode(', ', array_map(fn($a)=>$a['name'], $amen)); ?>
</p>
<a href="<?php echo APP_URL; ?>/booking.php?room_id=<?php echo (int)$room['id']; ?>">Book now</a>
<?php include 'inc/footer.php'; ?>
