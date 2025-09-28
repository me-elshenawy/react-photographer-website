<?php
session_start();

if (!isset($_SESSION['last_order_id'])) {
    header('Location: index.php');
    exit;
}

$last_order_id = $_SESSION['last_order_id'];

unset($_SESSION['last_order_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmed - Amazon</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container text-center my-5">
        <div class="py-5">
            <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" fill="currentColor" class="bi bi-check-circle-fill text-success mb-4" viewBox="0 0 16 16">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
            </svg>
            <h1 class="display-5 fw-bold">Thank You!</h1>
            <p class="fs-4">Your order has been placed successfully.</p>
            <p class="lead text-muted">Your Order ID is: <strong>#<?= htmlspecialchars($last_order_id) ?></strong></p>
            <hr>
            <p>You will receive an email confirmation shortly.</p>
            <a href="index.php" class="btn btn-primary btn-lg mt-3">Continue Shopping</a>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>