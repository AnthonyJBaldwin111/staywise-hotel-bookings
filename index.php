<?php include 'inc/header.php'; require_once 'inc/db.php';

$hotels = fetchAll("SELECT * FROM hotels ORDER BY created_at DESC LIMIT 4");

// Map hotel slugs to image files
$hotelImgMap = [
  'downtown-suites' => '/assets/images/hotels/downtown.jpg',
  'airport-inn' => '/assets/images/hotels/airport.jpg',
  'code-blooded-suites' => '/assets/images/hotels/codeblooded.jpg',
  'java-inn' => '/assets/images/hotels/java.jpg'
];
?>
<h1>Welcome to StayWise</h1>
<p>Find the perfect stay, from downtown suites to airport inns.</p>
<div class="grid">
  <?php foreach ($hotels as $h): ?>
    <?php $img = $hotelImgMap[$h['slug']] ?? '/assets/images/storefront.jpg'; ?>
    <div class="card">
      <img src="<?php echo APP_URL . $img; ?>" alt="<?php echo htmlspecialchars($h['name']); ?>">
      <h3><?php echo htmlspecialchars($h['name']); ?></h3>
      <p><?php echo htmlspecialchars($h['city'].', '.$h['country']); ?></p>
      <a href="<?php echo APP_URL; ?>/hotels.php?slug=<?php echo urlencode($h['slug']); ?>">Explore</a>
    </div>
  <?php endforeach; ?>
</div>
<?php include 'inc/footer.php'; ?>
