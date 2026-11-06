<?php
/**
 * Konfigurasi Koneksi Database
 * Unit Kompetensi: J.620100.015.01 & J.620100.017.02
 * 
 * Menggunakan PDO (PHP Data Objects) untuk mendukung keamanan
 * query berbasis Prepared Statements guna mencegah serangan SQL Injection.
 */

// Pengaturan kredensial database
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'db_toko_sepatu');
define('DB_CHARSET', 'utf8mb4');

/**
 * Mendapatkan instance koneksi PDO ke database
 *
 * @return PDO Objek PDO yang siap digunakan
 */
function getConnection(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Lempar Exception jika terjadi error query
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Hasil query berupa array asosiatif
            PDO::ATTR_EMULATE_PREPARES   => false,                  // Native prepared statements demi keamanan optimal
        ];

        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // Tampilkan pesan error ramah pengguna tanpa membocorkan kredensial sistem (Standar UU ITE & Best Practices)
            die("
                <div style='font-family:sans-serif; margin: 40px auto; max-width: 600px; padding: 20px; border: 1px solid #f5c2c7; background-color: #f8d7da; color: #842029; border-radius: 8px;'>
                    <h3 style='margin-top:0;'>⚠️ Gagal Terhubung ke Database MySQL</h3>
                    <p>Pastikan layanan MySQL di Laragon / XAMPP sudah berjalan (aktif).</p>
                    <p>Dan pastikan database <strong>db_toko_sepatu</strong> sudah di-import melalui file <code>database.sql</code>.</p>
                    <small>Pesan Error Teknis: " . htmlspecialchars($e->getMessage()) . "</small>
                </div>
            ");
        }
    }

    return $pdo;
}

