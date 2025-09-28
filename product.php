<?php
session_start();

require_once 'config/db.php';

// --- الخطوة 1: التحقق من صحة معرف المنتج (ID) من الرابط ---

// تحقق مما إذا كان 'id' موجودًا في رابط الـ URL وأنه ليس فارغًا وأنه يحتوي على أرقام فقط.
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    // إذا لم يتحقق الشرط، أوقف تنفيذ السكربت واعرض رسالة خطأ.
    // في تطبيق حقيقي، قد ترغب في التوجيه إلى صفحة خطأ 404.
    http_response_code(400); // Bad Request
    die('خطأ: معرّف المنتج غير صالح.');
}

// تحويل الـ ID إلى نوع integer (عدد صحيح) لمزيد من الأمان.
$product_id = intval($_GET['id']);


// --- الخطوة 2: جلب بيانات المنتج من قاعدة البيانات ---

try {
    // إعداد استعلام SQL مُعدّ (prepared statement) لتجنب حقن SQL.
    // نحن نستخدم '?' كـ placeholder لمعرّف المنتج.
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    
    // تنفيذ الاستعلام، وتمرير معرف المنتج في مصفوفة.
    $stmt->execute([$product_id]);
    
    // جلب نتيجة الاستعلام كمصفوفة ترابطية (key-value pairs).
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    // التحقق مما إذا تم العثور على المنتج في قاعدة البيانات.
    if (!$product) {
        // إذا كانت النتيجة false (لم يتم العثور على المنتج)، أوقف التنفيذ.
        http_response_code(404); // Not Found
        die('لم يتم العثور على المنتج.');
    }
} catch (PDOException $e) {
    // في حالة حدوث أي خطأ أثناء الاتصال أو تنفيذ الاستعلام، اعرض رسالة خطأ.
    http_response_code(500); // Internal Server Error
    die("فشل الاستعلام من قاعدة البيانات: " . $e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- عنوان الصفحة الآن ديناميكي، يعرض اسم المنتج -->
    <title><?= htmlspecialchars($product['name']) ?> - Amazon</title>
    <!-- CSS Files -->
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="./assets/css/style.css" />
</head>
<body>
    <!-- تضمين الهيدر الديناميكي -->
    <?php include 'includes/header.php'; ?>

    <main>
    <!-- Product Details Section -->
    <!-- مهم: إضافة "data-product-id" هنا. هذا يسمح لـ script.js بمعرفة ID المنتج الحالي -->
    <div class="container my-4" data-product-id="<?= $product['id'] ?>">
      <div class="row g-4">
        <!-- قسم صور المنتج -->
        <div class="col-lg-6">
          <div class="main-image mb-3">
            <img
              src="<?= htmlspecialchars($product['image_url']) ?>"
              alt="<?= htmlspecialchars($product['name']) ?>"
              id="mainProductImage"
              class="img-fluid rounded shadow"
            />
          </div>
          <div class="d-flex justify-content-center gap-2">
            <!-- الصور المصغرة (Thumbnails) -->
            <img
              src="<?= htmlspecialchars($product['image_url']) ?>"
              alt="Thumbnail 1"
              class="thumbnail active"
              onclick="changeMainImage(this)"
            />
            <!-- ملاحظة: الصور التالية ثابتة لأغراض العرض. في نظام متقدم، يمكن جلبها من جدول منفصل في قاعدة البيانات -->
            <img
              src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=100&h=100&fit=crop"
              alt="Thumbnail 2"
              class="thumbnail"
              onclick="changeMainImage(this)"
            />
          </div>
        </div>

        <!-- قسم معلومات المنتج -->
        <div class="col-lg-6">
          <!-- اسم المنتج الديناميكي -->
          <h1 class="display-5 fw-bold mb-3" id="productTitle">
            <?= htmlspecialchars($product['name']) ?>
          </h1>

          <!-- تقييم المنتج (ثابت حاليًا) -->
          <div class="d-flex align-items-center mb-3">
            <div class="text-warning me-2">
              <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i>
            </div>
            <span class="text-muted">(1,234 reviews)</span>
          </div>

          <!-- سعر المنتج الديناميكي -->
          <div class="d-flex align-items-center mb-4">
            <span class="display-6 fw-bold text-primary me-3" id="productPrice">$<?= number_format($product['price'], 2) ?></span>
            <?php if (!empty($product['old_price']) && $product['old_price'] > $product['price']): ?>
                <span class="text-decoration-line-through text-muted me-3">$<?= number_format($product['old_price'], 2) ?></span>
                <span class="badge bg-danger">Save <?= round((($product['old_price'] - $product['price']) / $product['old_price']) * 100) ?>%</span>
            <?php endif; ?>
          </div>

          <!-- وصف المنتج الديناميكي -->
          <div class="card mb-4">
            <div class="card-body">
              <h5 class="card-title">Product Description</h5>
              <p class="card-text" id="productDescription">
                <?= nl2br(htmlspecialchars($product['description'])) // nl2br لتحويل أسطر النص الجديدة إلى <br> ?>
              </p>
            </div>
          </div>

          <!-- خيارات الكمية واللون -->
          <div class="row mb-4">
            <div class="col-md-6 mb-3">
              <label for="quantity" class="form-label fw-bold">Quantity:</label>
              <div class="input-group" style="max-width: 150px;">
                <button class="btn btn-outline-secondary" type="button" onclick="decreaseQuantity()">-</button>
                <input type="number" class="form-control text-center" id="quantity" value="1" min="1" max="10">
                <button class="btn btn-outline-secondary" type="button" onclick="increaseQuantity()">+</button>
              </div>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold">Color:</label>
              <div class="d-flex gap-2">
                <div class="color-option active" data-color="black" style="background-color: #000"></div>
                <div class="color-option" data-color="white" style="background-color: #fff; border: 1px solid #ddd"></div>
              </div>
            </div>
          </div>

          <!-- أزرار الإضافة للشراء -->
          <div class="d-grid gap-2 d-md-flex mb-4">
            <button class="btn btn-primary btn-lg flex-fill me-md-2" onclick="addToCart()">
              <i class="fas fa-shopping-cart me-2"></i> Add to Cart
            </button>
            <button class="btn btn-success btn-lg flex-fill" onclick="buyNow()">
              <i class="fas fa-bolt me-2"></i> Buy Now
            </button>
          </div>

          <!-- حالة المخزون الديناميكية -->
          <div class="alert alert-info">
            <i class="fas fa-box-open me-2"></i>
            <span>
                <?php 
                    if ($product['stock_quantity'] > 10) {
                        echo "In Stock";
                    } elseif ($product['stock_quantity'] > 0) {
                        echo "Low Stock: Only " . $product['stock_quantity'] . " items left!";
                    } else {
                        echo "Out of Stock";
                    }
                ?>
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- نافذة Modal "تمت الإضافة بنجاح" (لا تحتاج لتغيير) -->
    <div class="modal fade" id="successModal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header"><h5 class="modal-title"><i class="fas fa-check-circle text-success me-2"></i> Added to Cart!</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
          <div class="modal-body"><p>Product has been successfully added to your cart.</p></div>
          <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Continue Shopping</button><button type="button" class="btn btn-primary" onclick="goToCart()">View Cart</button></div>
        </div>
      </div>
    </div>
    </main>

    <!-- تضمين الفوتر الديناميكي -->
    <?php include 'includes/footer.php'; ?>
    
    <!-- JS Files -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>