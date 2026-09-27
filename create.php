<?php
session_start();
require_once 'db.php';

$errors = [];
$name = $category = $price = $stock = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $price    = $_POST['price'] ?? '';
    $stock    = $_POST['stock'] ?? '';

    // Validasi Sesuai Ketentuan Dosen
    if (strlen($name) < 3) {
        $errors[] = "Nama produk harus minimal 3 karakter.";
    }
    if (!is_numeric($price) || $price <= 0) {
        $errors[] = "Harga harus berupa angka lebih besar dari 0 (tidak boleh 0 atau negatif).";
    }
    if (!is_numeric($stock) || $stock < 0) {
        $errors[] = "Stok harus berupa angka dan tidak boleh negatif.";
    }

    // Validasi Nama Unik
    if (empty($errors)) {
        $stmtCheck = $pdo->prepare("SELECT id FROM products WHERE name = :name");
        $stmtCheck->execute(['name' => $name]);
        if ($stmtCheck->fetch()) {
            $errors[] = "Nama produk sudah ada, harap gunakan nama unik.";
        }
    }

    // Jalankan INSERT jika validasi lolos
    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO products (name, category, price, stock) VALUES (:name, :category, :price, :stock)");
        $stmt->execute([
            'name'     => $name,
            'category' => $category,
            'price'    => $price,
            'stock'    => $stock
        ]);

        // PRG Pattern (Redirect setelah POST)
        header("Location: index.php?msg=Produk+berhasil+ditambahkan");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Produk Activewear</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <h2>Tambah Produk Activewear</h2>
    
    <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="create.php">
        <div class="form-group">
            <label>Nama Produk:</label>
            <input type="text" name="name" value="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>" required>
        </div>
        <div class="form-group">
            <label>Kategori:</label>
            <select name="category" required>
                <option value="Apparel" <?= $category === 'Apparel' ? 'selected' : '' ?>>Apparel</option>
                <option value="Footwear" <?= $category === 'Footwear' ? 'selected' : '' ?>>Footwear</option>
                <option value="Accessories" <?= $category === 'Accessories' ? 'selected' : '' ?>>Accessories</option>
                <option value="Gear" <?= $category === 'Gear' ? 'selected' : '' ?>>Gear</option>
            </select>
        </div>
        <div class="form-group">
            <label>Harga (Rp):</label>
            <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($price, ENT_QUOTES, 'UTF-8') ?>" required>
        </div>
        <div class="form-group">
            <label>Stok:</label>
            <input type="number" name="stock" value="<?= htmlspecialchars($stock, ENT_QUOTES, 'UTF-8') ?>" required>
        </div>
        <button type="submit" class="btn">Simpan Produk</button>
        <a href="index.php" class="btn btn-warning">Batal</a>
    </form>
</div>
</body>
</html>
