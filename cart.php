<?php
session_start();

require_once 'config/db.php';

$cart_items_details = [];
$subtotal = 0;
$item_count = 0;

try {
    if (!empty($_SESSION['cart'])) {
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
                
                $cart_items_details[] = [
                    'id' => $product['id'],
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'image_url' => $product['image_url'],
                    'quantity' => $quantity,
                    'total' => $item_total
                ];
            }
        }
    }

    // الخطوة 6: حساب بقية تفاصيل ملخص الطلب
    $shipping = ($subtotal > 200 && $subtotal >= 0) ? 0 : 5.99;
    $tax = $subtotal * 0.08; // 8% ضريبة
    $total = $subtotal + $shipping + $tax;

} catch (PDOException $e) {
    // في حالة حدوث خطأ في قاعدة البيانات، يتم عرض رسالة خطأ واضحة
    die("Error fetching cart data: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Amazon</title>
    <!-- CSS Files -->
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="./assets/css/style.css">
</head>
<body>
    <!-- تضمين الهيدر الديناميكي -->
    <?php include 'includes/header.php'; ?>

    <main>
    <div class="container my-4">
        <div class="text-center mb-4">
            <h1 class="display-4 fw-bold">Shopping Cart</h1>
            <p class="lead text-muted">Review your items before checkout</p>
        </div>

        <div class="row g-4">
            <!-- قسم عرض عناصر العربة -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Items in your cart</h5>
                        <button class="btn btn-outline-danger btn-sm" onclick="clearCart()">
                            <i class="fas fa-trash me-1"></i> Clear Cart
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div id="cartItems">
                            <?php if (empty($cart_items_details)): ?>
                                <!-- رسالة العربة الفارغة -->
                                <div class="text-center py-5" id="emptyCart">
                                    <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                                    <h3 class="text-muted">Your cart is empty</h3>
                                    <p class="text-muted">Add some products to get started!</p>
                                    <a href="category.php" class="btn btn-primary">Continue Shopping</a>
                                </div>
                            <?php else: ?>
                                <!-- عرض المنتجات الموجودة في العربة -->
                                <?php foreach ($cart_items_details as $item): ?>
                                    <div class="cart-item" data-item-id="<?= $item['id'] ?>">
                                        <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="cart-item-image">
                                        <div class="cart-item-info">
                                            <h6 class="mb-1"><?= htmlspecialchars($item['name']) ?></h6>
                                            <small class="text-muted">Unit Price: $<?= number_format($item['price'], 2) ?></small>
                                        </div>
                                        <div class="cart-item-quantity">
                                            <button class="btn btn-sm btn-outline-secondary" onclick="cartManager.updateQuantity(<?= $item['id'] ?>, <?= $item['quantity'] - 1 ?>)">-</button>
                                            <input type="number" class="form-control form-control-sm text-center" value="<?= $item['quantity'] ?>" min="1" max="10" 
                                                   onchange="cartManager.updateQuantity(<?= $item['id'] ?>, parseInt(this.value))" style="width: 60px;">
                                            <button class="btn btn-sm btn-outline-secondary" onclick="cartManager.updateQuantity(<?= $item['id'] ?>, <?= $item['quantity'] + 1 ?>)">+</button>
                                        </div>
                                        <div class="cart-item-price">$<?= number_format($item['total'], 2) ?></div>
                                        <div class="cart-item-actions">
                                            <button class="btn btn-sm btn-outline-danger delete-btn" onclick="openDeleteModal(<?= $item['id'] ?>)" title="Remove Item">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- قسم ملخص الطلب -->
            <div class="col-lg-4">
                <div class="card sticky-top" style="top: 100px;">
                    <div class="card-header"><h5 class="mb-0">Order Summary</h5></div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal (<span id="itemCount"><?= $item_count ?></span> items):</span>
                            <span id="subtotal">$<?= number_format($subtotal, 2) ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping:</span>
                            <span id="shipping"><?= $shipping == 0 ? 'Free' : '$' . number_format($shipping, 2) ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax (8%):</span>
                            <span id="tax">$<?= number_format($tax, 2) ?></span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold fs-5">
                            <span>Total:</span>
                            <span id="total">$<?= number_format($total, 2) ?></span>
                        </div>
                        <div class="mt-3">
                            <a href="checkout.php" class="btn btn-success w-100 mb-3 <?= empty($cart_items_details) ? 'disabled' : '' ?>">
                                <i class="fas fa-lock me-2"></i> Proceed to Checkout
                            </a>
                            <div class="text-center"><small class="text-success"><i class="fas fa-shield-alt me-1"></i> Secure checkout</small></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals (لا تحتاج لتغيير) -->
    <div class="modal fade" id="deleteModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5 class="modal-title"><i class="fas fa-exclamation-triangle text-warning me-2"></i> Remove Item</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><p>Are you sure you want to remove this item from your cart?</p></div><div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="button" class="btn btn-danger" onclick="confirmDelete()">Remove Item</button></div></div></div></div>
    <div class="modal fade" id="clearCartModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5 class="modal-title"><i class="fas fa-exclamation-triangle text-warning me-2"></i> Clear Cart</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><p>Are you sure you want to remove all items from your cart?</p></div><div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="button" class="btn btn-danger" onclick="confirmClearCart()">Clear Cart</button></div></div></div></div>

    </main>

    <!-- تضمين الفوتر -->
    <?php include 'includes/footer.php'; ?>

    <!-- JS Files -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>