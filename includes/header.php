<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// حساب العدد الإجمالي للمنتجات في عربة التسوق
$cart_item_count = 0;
if (!empty($_SESSION['cart'])) {
    $cart_item_count = array_sum(array_column($_SESSION['cart'], 'quantity'));
}
?>
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold fs-3 text-warning" href="index.php">Amazon</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="category.php">Category</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="product.php?id=1">Product Page</a>
        </li>
      </ul>
      <div class="d-flex flex-grow-1 justify-content-center px-4">
        <div class="input-group" style="max-width: 500px;">
          <input type="text" class="form-control" placeholder="Search products...">
          <button class="btn btn-warning" type="button">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
      <div class="nav-actions">
        <a href="cart.php" class="btn btn-outline-light position-relative">
          <i class="fas fa-shopping-cart"></i>
          <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="cartCount">
            <?= $cart_item_count ?>
          </span>
        </a>
      </div>
    </div>
  </div>
</nav>