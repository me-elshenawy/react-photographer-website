<?php
session_start();
require_once 'config/db.php';

// إذا كانت العربة فارغة، أعد توجيه المستخدم إلى صفحة العربة
if (empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit;
}

// نفس منطق حساب الإجماليات الموجود في cart.php
$cart_items_details = [];
$subtotal = 0;
$item_count = 0;

$product_ids = array_keys($_SESSION['cart']);
$placeholders = implode(',', array_fill(0, count($product_ids), '?'));
$stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
$stmt->execute($product_ids);
$products_from_db = $stmt->fetchAll(PDO::FETCH_ASSOC);

$products_by_id = [];
foreach ($products_from_db as $product) {
    $products_by_id[$product['id']] = $product;
}

foreach ($_SESSION['cart'] as $product_id => $item) {
    if (isset($products_by_id[$product_id])) {
        $product = $products_by_id[$product_id];
        $quantity = $item['quantity'];
        $item_total = $product['price'] * $quantity;
        $subtotal += $item_total;
        $item_count += $quantity;
        $cart_items_details[] = ['name' => $product['name'], 'quantity' => $quantity, 'total' => $item_total];
    }
}

$shipping = ($subtotal >= 200) ? 0 : 5.99;
$tax = $subtotal * 0.08;
$total = $subtotal + $shipping + $tax;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Amazon - Checkout</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <?php include 'includes/header.php'; ?>

  <main class="container my-4">
    <div class="row g-5">
      <!-- Order Summary -->
      <div class="col-md-5 col-lg-4 order-md-last">
        <h4 class="d-flex justify-content-between align-items-center mb-3">
          <span class="text-primary">Your cart</span>
          <span class="badge bg-primary rounded-pill"><?= $item_count ?></span>
        </h4>
        <ul class="list-group mb-3">
          <?php foreach ($cart_items_details as $item): ?>
            <li class="list-group-item d-flex justify-content-between lh-sm">
              <div>
                <h6 class="my-0"><?= htmlspecialchars($item['name']) ?></h6>
                <small class="text-muted">Quantity: <?= $item['quantity'] ?></small>
              </div>
              <span class="text-muted">$<?= number_format($item['total'], 2) ?></span>
            </li>
          <?php endforeach; ?>
          <li class="list-group-item d-flex justify-content-between">
            <span>Subtotal</span>
            <strong>$<?= number_format($subtotal, 2) ?></strong>
          </li>
          <li class="list-group-item d-flex justify-content-between">
            <span>Shipping</span>
            <strong><?= $shipping == 0 ? 'Free' : '$' . number_format($shipping, 2) ?></strong>
          </li>
           <li class="list-group-item d-flex justify-content-between">
            <span>Tax (8%)</span>
            <strong>$<?= number_format($tax, 2) ?></strong>
          </li>
          <li class="list-group-item d-flex justify-content-between bg-light">
            <span class="fw-bold">Total (USD)</span>
            <strong class="fw-bold">$<?= number_format($total, 2) ?></strong>
          </li>
        </ul>
      </div>

      <!-- Shipping Information Form -->
      <div class="col-md-7 col-lg-8">
        <h4 class="mb-3">Shipping address</h4>
        <!-- النموذج يرسل البيانات إلى place_order.php -->
        <form action="place_order.php" method="POST">
          <div class="row g-3">
            <div class="col-12">
              <label for="fullName" class="form-label">Full Name</label>
              <input type="text" id="fullName" name="full_name" class="form-control" required>
            </div>
            <div class="col-12">
              <label for="address" class="form-label">Address</label>
              <input type="text" id="address" name="address" class="form-control" placeholder="1234 Main St" required>
            </div>
            <div class="col-12">
              <label for="phone" class="form-label">Phone</label>
              <input type="tel" id="phone" name="phone" class="form-control" placeholder="+201234567890" required>
            </div>
          </div>
          <hr class="my-4">
          <button type="submit" class="w-100 btn btn-primary btn-lg">Place Order</button>
        </form>
      </div>
    </div>
  </main>

  <?php include 'includes/footer.php'; ?>

  <script src="assets/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/main.js"></script>
</body>
</html>