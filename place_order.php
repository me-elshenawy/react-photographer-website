<?php
session_start();
require_once 'config/db.php';

// تحقق من أن الطلب هو POST وأن العربة ليست فارغة
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_SESSION['cart'])) {
    header('Location: index.php');
    exit;
}

// 1. جمع بيانات العميل من النموذج والتحقق منها
$customer_name = trim($_POST['full_name'] ?? '');
$customer_address = trim($_POST['address'] ?? '');
$customer_phone = trim($_POST['phone'] ?? '');

if (empty($customer_name) || empty($customer_address) || empty($customer_phone)) {
    // يمكنك هنا تخزين رسالة خطأ في الجلسة وعرضها في صفحة الدفع
    die('Please fill out all required fields.');
}

// 2. إعادة حساب الإجمالي في السيرفر (مهم جدًا للأمان)
$subtotal = 0;
$product_ids = array_keys($_SESSION['cart']);
$placeholders = implode(',', array_fill(0, count($product_ids), '?'));
$stmt = $pdo->prepare("SELECT id, price FROM products WHERE id IN ($placeholders)");
$stmt->execute($product_ids);
$products_from_db = $stmt->fetchAll(PDO::FETCH_KEY_PAIR); // [id => price]

foreach ($_SESSION['cart'] as $product_id => $item) {
    if (isset($products_from_db[$product_id])) {
        $subtotal += $products_from_db[$product_id] * $item['quantity'];
    }
}
$shipping = ($subtotal > 25) ? 0 : 5.99;
$tax = $subtotal * 0.08;
$total_amount = $subtotal + $shipping + $tax;

// 3. بدء Transaction (لضمان حفظ جميع البيانات معًا أو لا شيء)
$pdo->beginTransaction();

try {
    // 4. إدراج الطلب الأساسي في جدول `orders`
    $order_sql = "INSERT INTO orders (customer_name, customer_address, customer_phone, total_amount) VALUES (?, ?, ?, ?)";
    $order_stmt = $pdo->prepare($order_sql);
    $order_stmt->execute([$customer_name, $customer_address, $customer_phone, $total_amount]);
    
    // الحصول على ID الطلب الجديد الذي تم إنشاؤه
    $order_id = $pdo->lastInsertId();

    // 5. إدراج المنتجات في جدول `order_items`
    $item_sql = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
    $item_stmt = $pdo->prepare($item_sql);

    foreach ($_SESSION['cart'] as $product_id => $item) {
        if (isset($products_from_db[$product_id])) {
            $item_stmt->execute([
                $order_id,
                $product_id,
                $item['quantity'],
                $products_from_db[$product_id] // سعر المنتج وقت الشراء
            ]);
        }
    }

    // 6. إذا نجحت كل العمليات، قم بتأكيد التغييرات (Commit)
    $pdo->commit();

    // 7. إفراغ العربة وتخزين ID الطلب للـ success page
    unset($_SESSION['cart']);
    $_SESSION['last_order_id'] = $order_id;
    
    // 8. إعادة التوجيه إلى صفحة النجاح
    header('Location: order_success.php');
    exit;

} catch (Exception $e) {
    // 9. في حالة حدوث أي خطأ، تراجع عن كل التغييرات (Rollback)
    $pdo->rollBack();
    // عرض رسالة خطأ عامة
    die("An error occurred while placing your order. Please try again. Error: " . $e->getMessage());
}
?>