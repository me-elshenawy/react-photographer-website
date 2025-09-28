<?php
session_start();
header('Content-Type: application/json');

// تهيئة العربة إذا لم تكن موجودة
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// دالة مساعدة لحساب العدد الإجمالي للعناصر في العربة
function getCartCount() {
    if (empty($_SESSION['cart'])) {
        return 0;
    }
    return array_sum(array_column($_SESSION['cart'], 'quantity'));
}

// التأكد من أن الطلب هو من نوع POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

// استلام البيانات من طلب AJAX
$action = $_POST['action'] ?? '';
$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;

// استخدام switch لتوجيه الطلب بناءً على 'action'
switch ($action) {
    case 'add':
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
        if ($product_id > 0 && $quantity > 0) {
            if (isset($_SESSION['cart'][$product_id])) {
                $_SESSION['cart'][$product_id]['quantity'] += $quantity;
            } else {
                $_SESSION['cart'][$product_id] = ['quantity' => $quantity];
            }
            echo json_encode(['success' => true, 'message' => 'Product added!', 'cart_count' => getCartCount()]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid product or quantity.']);
        }
        break;

    // --- الجزء الجديد والمهم ---
    case 'update':
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
        // تأكد من أن المنتج موجود في العربة وأن الكمية صالحة
        if ($product_id > 0 && isset($_SESSION['cart'][$product_id]) && $quantity > 0) {
            $_SESSION['cart'][$product_id]['quantity'] = $quantity;
            echo json_encode(['success' => true, 'message' => 'Cart updated!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Item not in cart or invalid quantity.']);
        }
        break;

    case 'remove':
        if ($product_id > 0 && isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]); // حذف المنتج من مصفوفة الجلسة
            echo json_encode(['success' => true, 'message' => 'Item removed!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Item not in cart.']);
        }
        break;

    case 'clear':
        $_SESSION['cart'] = []; // إفراغ العربة
        echo json_encode(['success' => true, 'message' => 'Cart cleared!']);
        break;
    // --- نهاية الجزء الجديد ---

    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action specified.']);
        break;
}
?>