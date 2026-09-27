<?php
session_start();
require_once 'db.php';

// Fitur Bonus: Search via GET menggunakan PDO Prepared Statement
$q = trim($_GET['q'] ?? '');
if ($q !== '') {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE :q OR category LIKE :q ORDER BY id DESC");
    $stmt->execute(['q' => "%$q%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
}
$products = $stmt->fetchAll();

// Penanganan CSRF Token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pace & Pulse - Running Club Catalog</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <header>
        <div>
            <h1>Pace & Pulse</h1>
            <p>Running Club & Activewear Management</p>
        </div>
        <a href="create.php" class="btn">+ Tambah Produk</a>
    </header>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_GET['msg'], ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <!-- Form Pencarian (Bonus) -->
    <form method="GET" action="index.php" class="search-form">
        <input type="text" name="q" value="<?= htmlspecialchars($q, ENT_QUOTES, 'UTF-8') ?>" placeholder="Cari nama atau kategori produk activewear...">
        <button type="submit" class="btn">Cari</button>
    </form>

    <div class="card-grid">
        <?php if (count($products) > 0): ?>
            <?php foreach ($products as $product): ?>
                <div class="card">
                    <div>
                        <!-- Syarat Output: Safe Escaping (Mencegah XSS) -->
                        <div class="card-title"><?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?></div>
                        <span class="badge"><?= htmlspecialchars($product['category'], ENT_QUOTES, 'UTF-8') ?></span>
                        <div class="card-body">
                            <p><strong>Harga:</strong> Rp <?= number_format($product['price'], 0, ',', '.') ?></p>
                            <p><strong>Stok:</strong> <?= (int)$product['stock'] ?> pcs</p>
                        </div>
                    </div>
                    <div class="card-actions">
                        <a href="edit.php?id=<?= $product['id'] ?>" class="btn btn-warning">Edit</a>
                        
                        <!-- Form Delete dengan POST + CSRF Protection -->
                        <form action="delete.php" method="POST" onsubmit="return confirm('Yakin hapus produk ini?');" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $product['id'] ?>">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Tidak ada produk ditemukan.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
