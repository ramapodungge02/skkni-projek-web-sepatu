<?php
/**
 * Modul Fungsi & Logika Pemrograman Terstruktur
 * Unit Kompetensi: 
 * - J.620100.015.01 (Menyusun fungsi dan identifier)
 * - J.620100.016.01 (Coding guidelines & best practices)
 * - J.620100.017.02 (Pemrograman terstruktur: branching, looping, fungsi, array, file I/O)
 */

require_once __DIR__ . '/../config/database.php';

// Direktori penyimpanan gambar upload
define('UPLOAD_DIR', __DIR__ . '/../assets/uploads/');
define('MAX_FILE_SIZE', 2 * 1024 * 1024); // Maksimal 2 Megabyte

/**
 * 1. FUNGSI SANITASI & VALIDASI DATA (Best Practices Keamanan)
 * Mencegah Cross-Site Scripting (XSS) dan karakter berbahaya
 *
 * @param string $data Teks input mentah
 * @return string Teks yang sudah dibersihkan
 */
function sanitizeInput(string $data): string
{
    $data = trim($data);
    $data = stripslashes($data);
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

/**
 * 2. FUNGSI FORMAT MATA UANG RUPIAH
 * Mengubah tipe data numerik (float/int) menjadi string format Rupiah
 *
 * @param float|int $angka
 * @return string Contoh: Rp 1.899.000
 */
function formatRupiah(float|int $angka): string
{
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

/**
 * 3. FUNGSI BADGE STATUS STOK (Percabangan Terstruktur if-else / switch)
 * Menghasilkan komponen HTML label status ketersediaan produk
 *
 * @param int $stok Jumlah stok sepatu
 * @return string Elemen HTML badge
 */
function getStatusStokBadge(int $stok): string
{
    if ($stok <= 0) {
        return '<span class="badge bg-danger"><i class="bi bi-x-circle-fill me-1"></i>Habis</span>';
    } elseif ($stok < 5) {
        return '<span class="badge bg-warning text-dark"><i class="bi bi-exclamation-triangle-fill me-1"></i>Tersisa ' . $stok . '</span>';
    } else {
        return '<span class="badge bg-success"><i class="bi bi-check-circle-fill me-1"></i>Tersedia (' . $stok . ')</span>';
    }
}

/**
 * 4. FUNGSI GENERATE KODE PRODUK OTOMATIS
 * Menghasilkan kode unik seperti SPT-001, SPT-002, dst.
 *
 * @return string Kode produk berikutnya
 */
function generateKodeProduk(): string
{
    $pdo = getConnection();
    $stmt = $pdo->query("SELECT MAX(id) AS max_id FROM `sepatu`");
    $row = $stmt->fetch();
    $nextId = ($row['max_id'] ?? 0) + 1;
    return 'SPT-' . str_pad((string)$nextId, 3, '0', STR_PAD_LEFT);
}

/**
 * 5. FUNGSI READ: Mengambil seluruh data sepatu (dengan filter dan pencarian)
 * Menerapkan Prepared Statement dan pengembalian Array Data
 *
 * @param string|null $keyword Kata kunci pencarian nama atau kode
 * @param string|null $kategori Filter kategori sepatu
 * @return array Sekumpulan data array asosiatif sepatu
 */
function getAllSepatu(?string $keyword = null, ?string $kategori = null): array
{
    $pdo = getConnection();
    $sql = "SELECT * FROM `sepatu` WHERE 1=1";
    $params = [];

    // Filter Kata Kunci
    if (!empty($keyword)) {
        $sql .= " AND (nama_sepatu LIKE :keyword OR kode_produk LIKE :keyword OR deskripsi LIKE :keyword)";
        $params[':keyword'] = '%' . $keyword . '%';
    }

    // Filter Kategori
    if (!empty($kategori)) {
        $sql .= " AND kategori = :kategori";
        $params[':kategori'] = $kategori;
    }

    $sql .= " ORDER BY id DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

/**
 * 6. FUNGSI READ BY ID: Mengambil 1 data sepatu spesifik berdasarkan ID
 *
 * @param int $id ID Sepatu
 * @return array|null Data sepatu atau null jika tidak ditemukan
 */
function getSepatuById(int $id): ?array
{
    $pdo = getConnection();
    $stmt = $pdo->prepare("SELECT * FROM `sepatu` WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $id]);
    $result = $stmt->fetch();
    return $result ?: null;
}

/**
 * 7. FUNGSI FILE I/O: Upload Berkas Gambar (Akses Media Penyimpanan)
 * Memeriksa tipe MIME, ekstensi, dan ukuran file gambar
 *
 * @param array $file Array $_FILES['gambar']
 * @return array ['success' => bool, 'filename' => string, 'error' => string|null]
 */
function uploadGambar(array $file): array
{
    // Jika tidak ada file yang diunggah
    if (!isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return ['success' => true, 'filename' => 'default-shoe.png', 'error' => null];
    }

    // Cek error bawaan PHP
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'filename' => '', 'error' => 'Gagal mengunggah gambar (Kode error: ' . $file['error'] . ')'];
    }

    // Validasi Ukuran File (maks 2MB)
    if ($file['size'] > MAX_FILE_SIZE) {
        return ['success' => false, 'filename' => '', 'error' => 'Ukuran file gambar maksimal 2 Megabyte.'];
    }

    // Validasi Ekstensi dan MIME Type
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
    $fileInfo = pathinfo($file['name']);
    $extension = strtolower($fileInfo['extension'] ?? '');

    if (!in_array($extension, $allowedExtensions, true)) {
        return ['success' => false, 'filename' => '', 'error' => 'Format file tidak didukung! Gunakan format JPG, JPEG, PNG, atau WEBP.'];
    }

    // Pastikan folder uploads ada
    if (!is_dir(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0755, true);
    }

    // Generate nama file unik agar tidak menimpa file lain
    $newFileName = 'shoe_' . uniqid('', true) . '.' . $extension;
    $destination = UPLOAD_DIR . $newFileName;

    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return ['success' => true, 'filename' => $newFileName, 'error' => null];
    }

    return ['success' => false, 'filename' => '', 'error' => 'Gagal memindahkan file ke direktori tujuan.'];
}

/**
 * 8. FUNGSI FILE I/O: Menghapus file gambar fisik dari media penyimpanan
 *
 * @param string $filename Nama file yang akan dihapus
 * @return void
 */
function hapusFileGambar(string $filename): void
{
    if (!empty($filename) && $filename !== 'default-shoe.png') {
        $filePath = UPLOAD_DIR . $filename;
        if (file_exists($filePath)) {
            @unlink($filePath);
        }
    }
}

/**
 * 9. FUNGSI CREATE: Menambah data sepatu baru ke database
 *
 * @param array $data Input form ($_POST)
 * @param array|null $file File gambar ($_FILES['gambar'])
 * @return array ['success' => bool, 'message' => string]
 */
function tambahSepatu(array $data, ?array $file = null): array
{
    $pdo = getConnection();

    // Sanitasi input
    $kodeProduk = sanitizeInput($data['kode_produk'] ?? generateKodeProduk());
    $namaSepatu = sanitizeInput($data['nama_sepatu'] ?? '');
    $kategori   = sanitizeInput($data['kategori'] ?? '');
    $ukuran     = is_array($data['ukuran'] ?? null) 
                    ? implode(', ', array_map('sanitizeInput', $data['ukuran'])) 
                    : sanitizeInput($data['ukuran'] ?? '');
    $harga      = (float)($data['harga'] ?? 0);
    $stok       = (int)($data['stok'] ?? 0);
    $deskripsi  = sanitizeInput($data['deskripsi'] ?? '');

    // Validasi form
    if (empty($namaSepatu) || empty($kategori) || empty($ukuran) || $harga <= 0) {
        return ['success' => false, 'message' => 'Silakan isi seluruh bidang wajib dengan benar.'];
    }

    // Proses upload gambar jika ada
    $gambar = 'default-shoe.png';
    if ($file !== null && isset($file['name']) && !empty($file['name'])) {
        $uploadResult = uploadGambar($file);
        if (!$uploadResult['success']) {
            return ['success' => false, 'message' => $uploadResult['error']];
        }
        $gambar = $uploadResult['filename'];
    }

    try {
        $stmt = $pdo->prepare("
            INSERT INTO `sepatu` (`kode_produk`, `nama_sepatu`, `kategori`, `ukuran`, `harga`, `stok`, `deskripsi`, `gambar`)
            VALUES (:kode_produk, :nama_sepatu, :kategori, :ukuran, :harga, :stok, :deskripsi, :gambar)
        ");

        $stmt->execute([
            ':kode_produk' => $kodeProduk,
            ':nama_sepatu' => $namaSepatu,
            ':kategori'    => $kategori,
            ':ukuran'      => $ukuran,
            ':harga'       => $harga,
            ':stok'        => $stok,
            ':deskripsi'   => $deskripsi,
            ':gambar'      => $gambar,
        ]);

        return ['success' => true, 'message' => "Sepatu '{$namaSepatu}' berhasil ditambahkan ke inventaris!"];
    } catch (PDOException $e) {
        // Hapus file gambar jika query gagal
        hapusFileGambar($gambar);
        return ['success' => false, 'message' => 'Terjadi kesalahan database: ' . $e->getMessage()];
    }
}

/**
 * 10. FUNGSI UPDATE: Memperbarui data sepatu
 *
 * @param int $id ID Sepatu
 * @param array $data Input form ($_POST)
 * @param array|null $file File gambar baru jika ada
 * @return array ['success' => bool, 'message' => string]
 */
function ubahSepatu(int $id, array $data, ?array $file = null): array
{
    $pdo = getConnection();
    $existing = getSepatuById($id);

    if (!$existing) {
        return ['success' => false, 'message' => 'Data sepatu tidak ditemukan.'];
    }

    // Sanitasi input
    $namaSepatu = sanitizeInput($data['nama_sepatu'] ?? '');
    $kategori   = sanitizeInput($data['kategori'] ?? '');
    $ukuran     = is_array($data['ukuran'] ?? null) 
                    ? implode(', ', array_map('sanitizeInput', $data['ukuran'])) 
                    : sanitizeInput($data['ukuran'] ?? '');
    $harga      = (float)($data['harga'] ?? 0);
    $stok       = (int)($data['stok'] ?? 0);
    $deskripsi  = sanitizeInput($data['deskripsi'] ?? '');

    if (empty($namaSepatu) || empty($kategori) || empty($ukuran) || $harga <= 0) {
        return ['success' => false, 'message' => 'Silakan isi seluruh bidang wajib dengan benar.'];
    }

    $gambar = $existing['gambar'];

    // Cek apakah ada upload gambar baru
    if ($file !== null && isset($file['name']) && !empty($file['name'])) {
        $uploadResult = uploadGambar($file);
        if (!$uploadResult['success']) {
            return ['success' => false, 'message' => $uploadResult['error']];
        }
        // Hapus gambar lama jika bukan default
        hapusFileGambar($existing['gambar']);
        $gambar = $uploadResult['filename'];
    }

    try {
        $stmt = $pdo->prepare("
            UPDATE `sepatu` 
            SET `nama_sepatu` = :nama_sepatu,
                `kategori`    = :kategori,
                `ukuran`      = :ukuran,
                `harga`       = :harga,
                `stok`        = :stok,
                `deskripsi`   = :deskripsi,
                `gambar`      = :gambar
            WHERE `id` = :id
        ");

        $stmt->execute([
            ':nama_sepatu' => $namaSepatu,
            ':kategori'    => $kategori,
            ':ukuran'      => $ukuran,
            ':harga'       => $harga,
            ':stok'        => $stok,
            ':deskripsi'   => $deskripsi,
            ':gambar'      => $gambar,
            ':id'          => $id,
        ]);

        return ['success' => true, 'message' => "Data sepatu '{$namaSepatu}' berhasil diperbarui!"];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Terjadi kesalahan database: ' . $e->getMessage()];
    }
}

/**
 * 11. FUNGSI DELETE: Menghapus data sepatu beserta berkas gambarnya
 *
 * @param int $id ID Sepatu
 * @return array ['success' => bool, 'message' => string]
 */
function hapusSepatu(int $id): array
{
    $pdo = getConnection();
    $existing = getSepatuById($id);

    if (!$existing) {
        return ['success' => false, 'message' => 'Data sepatu tidak ditemukan.'];
    }

    try {
        $stmt = $pdo->prepare("DELETE FROM `sepatu` WHERE `id` = :id");
        $stmt->execute([':id' => $id]);

        // Hapus gambar dari penyimpanan jika ada
        hapusFileGambar($existing['gambar']);

        return ['success' => true, 'message' => "Sepatu '{$existing['nama_sepatu']}' berhasil dihapus dari sistem."];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Gagal menghapus data: ' . $e->getMessage()];
    }
}

