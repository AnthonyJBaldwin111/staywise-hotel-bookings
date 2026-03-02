// booking.php
<?php include 'inc/header.php'; require_once 'inc/db.php';
$room_id = (int)($_GET['room_id'] ?? 0);
$room = fetchOne("SELECT r.*, h.name AS hotel_name FROM rooms r JOIN hotels h ON r.hotel_id=h.id WHERE r.id=?", [$room_id]);
if (!$room) { echo "<p>Room not found.</p>"; include 'inc/footer.php'; exit; }

if ($_SERVER['REQUEST_METHOD']==='POST') {
  $check_in = $_POST['check_in']; $check_out = $_POST['check_out'];
  $nights = (new DateTime($check_in))->diff(new DateTime($check_out))->days;
  $total = $nights * (float)$room['price_per_night'];
  $user = ['id'=>2, 'name'=>'Jane Guest']; // demo: attach a real logged-in user in production
  $booking_id = insert('bookings', [
    'user_id'=>$user['id'],'room_id'=>$room['id'],'check_in'=>$check_in,'check_out'=>$check_out,
    'nights'=>$nights,'status'=>'confirmed','total'=>$total
  ]);
  insert('payments', [
    'booking_id'=>$booking_id,'method'=>'card','amount'=>$total,'status'=>'captured','transaction_ref'=>'TX'.time()
  ]);
  echo "<p>Booking confirmed! ID #$booking_id — Total: $".number_format($total,2)."</p>";
}
?>
<h1>Book: <?php echo htmlspecialchars($room['name']); ?> — <?php echo htmlspecialchars($room['hotel_name']); ?></h1>
<form method="post">
  <label><strong>Check-in:</strong> <input type="date" name="check_in" required></label>
  <label><strong>Check-out:</strong> <input type="date" name="check_out" required></label>
  <button>Confirm booking</button>
</form>
<?php include 'inc/footer.php'; ?>
