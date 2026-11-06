<?php
/**
 * Halaman Proses Hapus Sepatu (DELETE)
 * Unit Kompetensi:
 * - J.620100.016.01 (Coding Guidelines & Error Handling)
 * - J.620100.017.02 (Pemrograman Terstruktur: Delete DB & Hapus File Gambar Fisik)
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/includes/functions.php';

// Validasi parameter ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    $_SESSION['flash'] = [
        'type'    => 'danger',
        'message' => 'Permintaan tidak valid: ID sepatu tidak disertakan.',
    ];
    header('Location: index.php');
    exit;
}

// Eksekusi fungsi hapus
$result = hapusSepatu($id);

$_SESSION['flash'] = [
    'type'    => $result['success'] ? 'success' : 'danger',
    'message' => $result['message'],
];

// Alihkan kembali ke halaman utama
header('Location: index.php');
exit;

