# Pace & Pulse - Running Club & Activewear Management System

Proyek web manajemen produk sederhana menggunakan PHP PDO dan MySQL.

## Cara Menjalankan

1. Ekstrak/salin folder `Praktikum3_NIM_Nama` ke dalam folder web server Anda (misal: `htdocs` di XAMPP).
2. Buat database di MySQL/phpMyAdmin dan import berkas `database/store_db.sql`.
3. Sesuaikan konfigurasi koneksi database di `db.php` jika diperlukan (host, username, password).
4. Buka peramban (browser) dan akses `http://localhost/Praktikum3_NIM_Nama/index.php`.

## Fitur Keamanan & Implementasi
- **Keamanan Query**: Semua query SQL menggunakan PDO Prepared Statements untuk mencegah SQL Injection.
- **Keamanan Output**: Penggunaan `htmlspecialchars()` pada variabel untuk menangkal XSS Injection.
- **Proteksi Delete**: Menggunakan metode POST dan validasi Token CSRF.
- **Anti-Duplikasi Data**: Penerapan Pola PRG (Post-Redirect-Get) saat submit form.
- **UI Responsif**: Menggunakan Flexbox & Grid CSS.
