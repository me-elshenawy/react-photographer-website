<?php

session_start();

require_once 'config/db.php';

$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$products_per_page = 6;
$offset = ($page - 1) * $products_per_page;

// متغيرات الفلترة من الـ URL
$selected_brands = isset($_GET['brands']) && is_array($_GET['brands']) ? $_GET['brands'] : [];
$min_price = isset($_GET['min_price']) && is_numeric($_GET['min_price']) ? (float)$_GET['min_price'] : null;
$max_price = isset($_GET['max_price']) && is_numeric($_GET['max_price']) ? (float)$_GET['max_price'] : null;
$sort_option = $_GET['sort'] ?? 'relevant';

// --- 2. بناء استعلام SQL الديناميكي والآمن ---

$where_clauses = [];
$params = [];

if (!empty($selected_brands)) {
    $brand_placeholders = implode(',', array_fill(0, count($selected_brands), '?'));
    $where_clauses[] = "brand IN ($brand_placeholders)";
    $params = array_merge($params, $selected_brands);
}
if ($min_price !== null && $min_price !== '') {
    $where_clauses[] = "price >= ?";
    $params[] = $min_price;
}
if ($max_price !== null && $max_price !== '') {
    $where_clauses[] = "price <= ?";
    $params[] = $max_price;
}

$where_sql = !empty($where_clauses) ? " WHERE " . implode(" AND ", $where_clauses) : '';

$order_sql = " ORDER BY ";
switch ($sort_option) {
    case 'price_asc':
        $order_sql .= "price ASC";
        break;
    case 'price_desc':
        $order_sql .= "price DESC";
        break;
    default:
        $order_sql .= "created_at DESC";
        break;
}

try {
    // --- 3. تنفيذ الاستعلامات ---

    // استعلام لحساب العدد الإجمالي للمنتجات (مع الفلاتر)
    $count_stmt = $pdo->prepare("SELECT COUNT(id) FROM products" . $where_sql);
    $count_stmt->execute($params);
    $total_products = $count_stmt->fetchColumn();
    $total_pages = ceil($total_products / $products_per_page);

    $sql = "SELECT * FROM products" . $where_sql . $order_sql . " LIMIT ? OFFSET ?";
    $products_stmt = $pdo->prepare($sql);

    // ربط متغيرات الفلاتر (WHERE clause)
    $param_index = 1;
    foreach ($params as $value) {
        $products_stmt->bindValue($param_index, $value);
        $param_index++;
    }

    // ربط متغيرات LIMIT و OFFSET وتحديد نوعها كـ Integer
    $products_stmt->bindValue($param_index, $products_per_page, PDO::PARAM_INT);
    $param_index++;
    $products_stmt->bindValue($param_index, $offset, PDO::PARAM_INT);

    // تنفيذ الاستعلام بعد ربط كل المتغيرات
    $products_stmt->execute();
    $products = $products_stmt->fetchAll(PDO::FETCH_ASSOC);

    $brands_stmt = $pdo->query("SELECT DISTINCT brand FROM products WHERE brand IS NOT NULL AND brand != '' ORDER BY brand ASC");
    $brands = $brands_stmt->fetchAll(PDO::FETCH_COLUMN);

} catch (PDOException $e) {
    die("Database query failed: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Category - Laptops</title>
  <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="./assets/css/style.css">
  <link rel="stylesheet" href="./assets/css/category-style.css"> 
</head>
<body>

  <?php include 'includes/header.php'; ?>

  <main class="container my-4">
    <div class="control-bar d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4 flex-row-reverse">
      <div class="small text-muted">Showing <b><?= count($products) ?></b> of <b><?= $total_products ?></b> results</div>
      
      <form action="category.php" method="GET" class="d-flex align-items-center">
        <?php foreach ($selected_brands as $brand): ?>
            <input type="hidden" name="brands[]" value="<?= htmlspecialchars($brand) ?>">
        <?php endforeach; ?>
        <input type="hidden" name="min_price" value="<?= htmlspecialchars($min_price ?? '') ?>">
        <input type="hidden" name="max_price" value="<?= htmlspecialchars($max_price ?? '') ?>">

        <label class="small me-2" for="sort-select">Sort by</label>
        <select id="sort-select" name="sort" class="form-select form-select-sm d-inline-block me-1" style="width:auto;" onchange="this.form.submit()">
          <option value="relevant" <?= $sort_option == 'relevant' ? 'selected' : '' ?>>Most Relevant</option>
          <option value="price_asc" <?= $sort_option == 'price_asc' ? 'selected' : '' ?>>Price: Low to High</option>
          <option value="price_desc" <?= $sort_option == 'price_desc' ? 'selected' : '' ?>>Price: High to Low</option>
        </select>
      </form>
    </div>

    <div class="row g-4">
      <!-- Sidebar with Filters -->
      <div class="col-12 col-lg-3">
        <button class="btn btn-outline-primary w-100 d-lg-none mb-2" type="button" data-bs-toggle="collapse" data-bs-target="#filterBox">Show Filters</button>
        <div id="filterBox" class="collapse d-lg-block">
          <form action="category.php" method="GET">
            <div class="sidebar-box">
              <h6 class="mb-3 text-secondary">Filter Results</h6>
              
              <div class="mb-4">
                <label class="form-label fw-semibold">Price</label>
                <div class="row g-2 mb-2">
                  <div class="col-6"><input type="number" name="min_price" class="form-control form-control-sm" placeholder="Min" value="<?= htmlspecialchars($min_price ?? '') ?>"></div>
                  <div class="col-6"><input type="number" name="max_price" class="form-control form-control-sm" placeholder="Max" value="<?= htmlspecialchars($max_price ?? '') ?>"></div>
                </div>
              </div>
              
              <div class="mb-4">
                <label class="form-label fw-semibold">Brand</label>
                <?php foreach ($brands as $brand): ?>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="brands[]" value="<?= htmlspecialchars($brand) ?>" id="brand_<?= htmlspecialchars($brand) ?>" <?= in_array($brand, $selected_brands) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="brand_<?= htmlspecialchars($brand) ?>"><?= htmlspecialchars($brand) ?></label>
                  </div>
                <?php endforeach; ?>
              </div>
              
              <button type="submit" class="btn btn-primary w-100 btn-sm">Apply Filters</button>
            </div>
          </form>
        </div>
      </div>

      <!-- Products Grid -->
      <div class="col-12 col-lg-9">
        <div class="row g-3">
          <?php if (empty($products)): ?>
            <div class="col-12"><div class="alert alert-warning text-center">No products match your filters.</div></div>
          <?php else: ?>
            <?php foreach ($products as $product): ?>
              <div class="col-12 col-md-6 col-lg-4 d-flex align-items-stretch">
                <div class="product-card w-100 d-flex flex-column">
                  <a href="product.php?id=<?= $product['id'] ?>"><img src="<?= htmlspecialchars($product['image_url']) ?>" class="product-img" alt="<?= htmlspecialchars($product['name']) ?>"></a>
                  <h6 class="mt-2 mb-1"><?= htmlspecialchars($product['name']) ?></h6>
                  <small class="text-muted d-block mb-2"><?= htmlspecialchars($product['brand'] ?? '') ?></small>
                  <div class="mb-2"><span class="price">EGP <?= number_format($product['price'], 2) ?></span>
                    <?php if (!empty($product['old_price'])): ?><span class="old">EGP <?= number_format($product['old_price'], 2) ?></span><?php endif; ?>
                  </div>
                  <div class="d-flex gap-2 mt-auto">
                    <button class="btn btn-primary btn-sm" onclick="cartManager.addToCart({id: <?= $product['id'] ?>, quantity: 1})">Add to Cart</button>
                    <a href="product.php?id=<?= $product['id'] ?>" class="btn btn-outline-secondary btn-sm">Details</a>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>

        <!-- Dynamic Pagination -->
        <nav class="mt-4">
          <ul class="pagination justify-content-center">
            <?php
            $query_params = $_GET;
            if ($page > 1) {
                $query_params['page'] = $page - 1;
                echo '<li class="page-item"><a class="page-link" href="?' . http_build_query($query_params) . '">Prev</a></li>';
            } else {
                echo '<li class="page-item disabled"><span class="page-link">Prev</span></li>';
            }
            for ($i = 1; $i <= $total_pages; $i++) {
                $query_params['page'] = $i;
                $active_class = ($i == $page) ? 'active' : '';
                echo '<li class="page-item ' . $active_class . '"><a class="page-link" href="?' . http_build_query($query_params) . '">' . $i . '</a></li>';
            }
            if ($page < $total_pages) {
                $query_params['page'] = $page + 1;
                echo '<li class="page-item"><a class="page-link" href="?' . http_build_query($query_params) . '">Next</a></li>';
            } else {
                echo '<li class="page-item disabled"><span class="page-link">Next</span></li>';
            }
            ?>
          </ul>
        </nav>
      </div>
    </div>
  </main>

  <?php include 'includes/footer.php'; ?>

  <script src="assets/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/script.js"></script>
  <script src="assets/js/main.js"></script>
</body>
</html>