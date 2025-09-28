<?php
// بدء الجلسة
session_start();

// تضمين ملف الاتصال
require_once 'config/db.php';

// متغير لتخزين منتجات العروض
$deals = [];

try {
    // جلب 6 منتجات عليها خصم بشكل عشوائي من قاعدة البيانات
    $stmt = $pdo->query("
        SELECT * 
        FROM products 
        WHERE old_price IS NOT NULL AND old_price > price 
        ORDER BY RAND() 
        LIMIT 6
    ");
    $deals = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // يمكن ترك هذا القسم فارغًا بأمان إذا فشل الاتصال
    // ستظهر رسالة "لا توجد عروض" في قسم العروض
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Amazon - Home</title>
    <!-- CSS Files -->
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="./assets/css/style.css" />
    <link rel="stylesheet" href="./assets/css/home.css" />
</head>
<body>
    <!-- تضمين الهيدر الديناميكي -->
    <?php include 'includes/header.php'; ?>

    <main>

    <!-- Banner Carousel (محتوى ثابت) -->
    <div id="mainCarousel" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-inner">
        <div class="carousel-item active"><img src="https://m.media-amazon.com/images/I/715mmvJOYfL._SX3000_.jpg" class="d-block w-100" alt="Banner 1"/></div>
        <div class="carousel-item"><img src="https://m.media-amazon.com/images/I/61Z4W0jra4L._SX3000_.jpg" class="d-block w-100" alt="Banner 2"/></div>
        <div class="carousel-item"><img src="https://m.media-amazon.com/images/I/612-T5YABuL._SX3000_.jpg" class="d-block w-100" alt="Banner 3"/></div>
        <div class="carousel-item"><img src="https://m.media-amazon.com/images/I/61SZFIlHkTL._SX3000_.jpg" class="d-block w-100" alt="Banner 4"/></div>
        <div class="carousel-item"><img src="https://m.media-amazon.com/images/I/71DQMiLiGZL._SX3000_.jpg" class="d-block w-100" alt="Banner 5"/></div>
        <div class="carousel-item"><img src="https://m.media-amazon.com/images/I/71m5s4DCkfL._SX3000_.jpg" class="d-block w-100" alt="Banner 6"/></div>
        <div class="carousel-item"><img src="https://m.media-amazon.com/images/I/61-f8nyehEL._SX3000_.jpg" class="d-block w-100" alt="Banner 7"/></div>
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span></button>
      <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next"><span class="carousel-control-next-icon"></span></button>

      <!-- Cards Overlay (تم استعادة جميع البطاقات الثابتة مع تصحيح الروابط) -->
      <div class="cards-overlay container">
        <div class="row g-3 g-sm-4">
          <div class="col-6 col-md-4 col-lg-3"><div class="promo-card"><h5>Enjoy Prime on us</h5><img src="https://images-eu.ssl-images-amazon.com/images/G/42/outbound/ghoneimh/Q4/WFS24/Fuse/2409DR25_Acquisition_EG_DC_CIB_MC_758x608_EN_1x_v2._SY304_CB540423503_.jpg" alt="Prime" class="img-fluid"/><a href="category.php">Redeem now</a></div></div>
          <div class="col-6 col-md-4 col-lg-3"><div class="promo-card"><h5>New deals everyday</h5><img src="https://images-eu.ssl-images-amazon.com/images/G/42/Homepage/2025/BAU/Q2/Dealsforyou/2505GB004_EG_GW_DC_Dealsforyou_758x608_EN_1X._SY304_CB793959620_.jpg" alt="Deals" class="img-fluid"/><a href="category.php">Shop now</a></div></div>
          <div class="col-6 col-md-4 col-lg-3"><div class="promo-card"><h5>Electronics Fest</h5><img src="https://m.media-amazon.com/images/I/41M795r5WfL._SR480,440_.jpg" alt="Electronics" class="img-fluid"/><a href="category.php">See more</a></div></div>
          <div class="col-6 col-md-4 col-lg-3"><div class="promo-card"><h5>Fashion Deals</h5><img src="https://images-eu.ssl-images-amazon.com/images/G/42/Egypt-hq/2025/img/Consumer_Electronics/lastchance/2504GB025_EG_Xcat_DC_758x608_Lastchancedeals_EN_1x._SY304_CB795161141_.jpg" alt="Fashion" class="img-fluid"/><a href="category.php">See more</a></div></div>
          <div class="col-6 col-md-4 col-lg-3"><div class="promo-card"><h5>Recommended Products</h5><img src="https://images-eu.ssl-images-amazon.com/images/G/42/consumables/2024/DI/2409DR27_5_EG_Di_Import_GW_DC_GlobalBrands_379x304_1X._SY304_CB541512851_.jpg" alt="Recommended" class="img-fluid"/><a href="category.php">See more</a></div></div>
          <div class="col-6 col-md-4 col-lg-3"><div class="promo-card"><h5>Best Sellers</h5><img src="https://images-eu.ssl-images-amazon.com/images/G/42/consumables/2024/2407GB040_EG_CL_Amazon_Basket_CC_DC_758x608_EN_1x._SY304_CB566105094_.jpg" alt="Best Sellers" class="img-fluid"/><a href="category.php">See more</a></div></div>
          <div class="col-6 col-md-4 col-lg-3"><div class="promo-card"><h5>Global brands</h5><img src="https://images-eu.ssl-images-amazon.com/images/G/42/Gift_Cards/Evergreen/EG_Evergreen_CC_Aug25_GW_379x304._SY304_CB804939677_.png" alt="Global Brands" class="img-fluid"/><a href="category.php">See more</a></div></div>
          <div class="col-6 col-md-4 col-lg-3"><div class="promo-card"><h5>Beauty Week | Up to 35% off</h5><img src="https://m.media-amazon.com/images/I/41cq96sxNML.AC_SX250.jpg" alt="Beauty" class="img-fluid"/><a href="category.php">See more</a></div></div>
        </div>
      </div>
    </div>

    <!-- Budget Zone Section (تم استعادة الشريحتين وتصحيح الروابط) -->
    <div class="container my-5">
      <div class="card shadow-sm border-0 p-3 rounded-3 position-relative">
        <h4 class="mb-4">Budget Zone</h4>
        <div id="budgetCarousel" class="carousel slide" data-bs-ride="carousel">
          <div class="carousel-inner">
            <!-- Slide 1 -->
            <div class="carousel-item active">
              <div class="row justify-content-center">
                <div class="col-3 col-lg-2 mb-3"><div class="circle-card"><a href="category.php?max_price=50"><img src="https://images-eu.ssl-images-amazon.com/images/G/42/Homepage/2024/Dec/BudgetStore/2410DR46_Budget_store_AE_xsite_Bubbler_Below49_400x400_EN._CB538123149_.jpg" alt="Item 1"/></a><p>Under $50</p></div></div>
                <div class="col-3 col-lg-2 mb-3"><div class="circle-card"><a href="category.php?max_price=100"><img src="https://images-eu.ssl-images-amazon.com/images/G/42/Homepage/2024/Dec/BudgetStore/2410DR46_Budget_store_AE_xsite_Bubbler_Below99_400x400_EN._CB538123149_.jpg" alt="Item 2"/></a><p>Under $100</p></div></div>
                <div class="col-3 col-lg-2 mb-3"><div class="circle-card"><a href="category.php?max_price=200"><img src="https://images-eu.ssl-images-amazon.com/images/G/42/Homepage/2024/Dec/BudgetStore/2410DR46_Budget_store_AE_xsite_Bubbler_Beauty_400x400_EN._CB538290247_.jpg" alt="Item 3"/></a><p>Under $200</p></div></div>
                <div class="col-3 col-lg-2 mb-3"><div class="circle-card"><a href="category.php?max_price=500"><img src="https://images-eu.ssl-images-amazon.com/images/G/42/Homepage/2024/Dec/BudgetStore/2410DR46_Budget_store_AE_xsite_Bubbler_Computer_Accessories_400x400_EN._CB538123149_.jpg" alt="Item 4"/></a><p>Under $500</p></div></div>
                <div class="col-3 col-lg-2 mb-3 d-none d-lg-block"><div class="circle-card"><a href="category.php?max_price=160"><img src="https://images-eu.ssl-images-amazon.com/images/G/42/Homepage/2024/Dec/BudgetStore/2410DR46_Budget_store_AE_xsite_Bubbler_Supermarket_400x400_EN._CB538123149_.jpg" alt="Item 5"/></a><p>Under $160</p></div></div>
                <div class="col-3 col-lg-2 mb-3 d-none d-lg-block"><div class="circle-card"><a href="category.php?max_price=400"><img src="https://images-eu.ssl-images-amazon.com/images/G/42/Homepage/2024/Dec/BudgetStore/2410DR46_Budget_store_AE_xsite_Bubbler_Electronics_400x400_EN._CB538123149_.jpg" alt="Item 6"/></a><p>Under $400</p></div></div>
              </div>
            </div>
            <!-- Slide 2 -->
            <div class="carousel-item">
              <div class="row justify-content-center">
                <div class="col-3 col-lg-2 mb-3"><div class="circle-card"><a href="category.php?max_price=10"><img src="https://images-eu.ssl-images-amazon.com/images/G/42/Homepage/2024/Dec/BudgetStore/2410DR46_Budget_store_AE_xsite_Bubbler_Kitchen_400x400_EN._CB538123149_.jpg" alt="Item 1"/></a><p>Under $10</p></div></div>
                <div class="col-3 col-lg-2 mb-3"><div class="circle-card"><a href="category.php?max_price=20"><img src="https://images-eu.ssl-images-amazon.com/images/G/42/Homepage/2024/Dec/BudgetStore/2410DR46_Budget_store_AE_xsite_Bubbler_Home_400x400_EN._CB538123149_.jpg" alt="Item 2"/></a><p>Under $20</p></div></div>
                <div class="col-3 col-lg-2 mb-3"><div class="circle-card"><a href="category.php?max_price=30"><img src="https://images-eu.ssl-images-amazon.com/images/G/42/Homepage/2024/Dec/BudgetStore/2410DR46_Budget_store_AE_xsite_Bubbler_Bath_Body_400x400_EN._CB538290247_.jpg" alt="Item 3"/></a><p>Under $30</p></div></div>
                <div class="col-3 col-lg-2 mb-3"><div class="circle-card"><a href="category.php?max_price=40"><img src="https://images-eu.ssl-images-amazon.com/images/G/42/Homepage/2024/Dec/BudgetStore/2410DR46_Budget_store_AE_xsite_Bubbler_ShopLocal_400x400_EN._CB538123149_.jpg" alt="Item 4"/></a><p>Under $40</p></div></div>
                <div class="col-3 col-lg-2 mb-3 d-none d-lg-block"><div class="circle-card"><a href="category.php?max_price=50"><img src="https://images-eu.ssl-images-amazon.com/images/G/42/Homepage/2024/Dec/BudgetStore/2410DR46_Budget_store_AE_xsite_Bubbler_NewIn_400x400_EN._CB538123149_.jpg" alt="Item 5"/></a><p>Under $50</p></div></div>
                <div class="col-3 col-lg-2 mb-3 d-none d-lg-block"><div class="circle-card"><a href="category.php?max_price=100"><img src="https://images-eu.ssl-images-amazon.com/images/G/42/Homepage/2024/Dec/BudgetStore/2410DR46_Budget_store_AE_xsite_Bubbler_Fashion_400x400_EN._CB538123149_.jpg" alt="Item 6"/></a><p>Under $100</p></div></div>
              </div>
            </div>
          </div>
          <button class="carousel-control-prev custom-control" type="button" data-bs-target="#budgetCarousel" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span></button>
          <button class="carousel-control-next custom-control" type="button" data-bs-target="#budgetCarousel" data-bs-slide="next"><span class="carousel-control-next-icon"></span></button>
        </div>
      </div>
    </div>

    <!-- Deals Section (هذا الجزء ديناميكي بالكامل) -->
    <div class="container my-5">
      <div class="deals-box p-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="m-0">✨ Today's Deals ✨</h5>
          <a href="category.php" class="fw-bold text-decoration-none">Shop all deals</a>
        </div>
        <div class="row g-3">
          <?php if (empty($deals)): ?>
            <p class="text-white text-center">No special deals available right now. Check back later!</p>
          <?php else: ?>
            <?php foreach ($deals as $product): ?>
              <div class="col-6 col-md-4 col-lg-2">
                <div class="deal-card">
                  <a href="product.php?id=<?= $product['id'] ?>">
                    <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>"/>
                  </a>
                  <div class="deal-info">
                    <span class="badge bg-danger"><?= round((($product['old_price'] - $product['price']) / $product['old_price']) * 100) ?>% off</span>
                    <span class="text-danger small">Limited time deal</span>
                    <p class="price">EGP <?= number_format($product['price'], 2) ?> <span class="old-price">EGP <?= number_format($product['old_price'], 2) ?></span></p>
                    <p class="product-name text-truncate"><?= htmlspecialchars($product['name']) ?></p>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>
    </main>

    <!-- تضمين الفوتر -->
    <?php include 'includes/footer.php'; ?>
    
    <!-- JS Files -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>