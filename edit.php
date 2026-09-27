<?php
session_start();
require_once 'db.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php");
    exit;
}

// Fetch data awal berdasarkan ID
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
$stmt->execute(['id' => $id]);
$product = $stmt->fetch();

if (!$product) {
    header("Location: index.php");
    exit;
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $price    = $_POST['price'] ?? '';
    $stock    = $_POST['stock'] ?? '';

    // Validasi Input
    if (strlen($name) < 3) {
        $errors[] = "Nama produk minimal 3 karakter.";
    }
    if (!is_numeric($price) || $price <= 0) {
        $errors[] = "Harga harus angka > 0.";
    }
    if (!is_numeric($stock) || $stock < 0) {
        $errors[] = "Stok tidak boleh negatif.";
    }

    // Validasi Nama Unik (jika diubah)
    if (empty($errors)) {
        $stmtCheck = $pdo->prepare("SELECT id FROM products WHERE name = :name AND id != :id");
        $stmtCheck->execute(['name' => $name, 'id' => $id]);
        if ($stmtCheck->fetch()) {
            $errors[] = "Nama produk sudah digunakan oleh produk lain.";
        }
    }

    if (empty($errors)) {
        $stmtUpdate = $pdo->prepare("UPDATE products SET name = :name, category = :category, price = :price, stock = :stock WHERE id = :id");
        $stmtUpdate->execute([
            'name'     => $name,
            'category' => $category,
            'price'    => $price,
            'stock'    => $stock,
            'id'       => $id
        ]);

        header("Location: index.php?msg=Produk+berhasil+diperbarui");
        exit;
    }
} else {
    $name     = $product['name'];
    $category = $product['category'];
    $price    = $product['price'];
    $stock    = $product['stock'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Produk Activewear</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <h2>Edit Produk</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="edit.php?id=<?= $id ?>">
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
        <button type="submit" class="btn">Update Produk</button>
        <a href="index.php" class="btn btn-warning">Batal</a>
    </form>
</div>
</body>
</html>
