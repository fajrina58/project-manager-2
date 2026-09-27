<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $csrf = $_POST['csrf_token'] ?? '';

    // Proteksi Keamanan CSRF
    if (empty($csrf) || !hash_equals($_SESSION['csrf_token'] ?? '', $csrf)) {
        die("Validasi CSRF Token Gagal.");
    }

    if ($id) {
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}

header("Location: index.php?msg=Produk+berhasil+dihapus");
exit;
?>
