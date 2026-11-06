<?php
/**
 * Halaman Rincian Produk Sepatu (DETAIL)
 * Unit Kompetensi:
 * - J.620100.005.02 (Implementasi User Interface)
 * - J.620100.017.02 (Pemrograman Terstruktur: Array Association & Tampilan Data)
 */

$pageTitle = 'Rincian Produk Sepatu - Toko Sepatu';
require_once __DIR__ . '/includes/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$sepatu = getSepatuById($id);

if (!$sepatu) {
    $_SESSION['flash'] = [
        'type'    => 'danger',
        'message' => 'Data sepatu tidak ditemukan.',
    ];
    header('Location: index.php');
    exit;
}

$sizes = array_map('trim', explode(',', $sepatu['ukuran']));
$imgPath = 'assets/uploads/' . htmlspecialchars($sepatu['gambar']);
if (!file_exists(__DIR__ . '/' . $imgPath) || empty($sepatu['gambar'])) {
    $imgPath = 'assets/uploads/default-shoe.png';
}
?>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <!-- Breadcrumb Navigasi -->
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Inventaris</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($sepatu['nama_sepatu']) ?></li>
            </ol>
        </nav>

        <div class="card shadow-sm border-0 overflow-hidden">
            <div class="card-body p-0">
                <div class="row g-0">
                    <!-- Kolom Gambar Produk -->
                    <div class="col-md-5 bg-light d-flex align-items-center justify-content-center p-4 border-end">
                        <img src="<?= $imgPath ?>" alt="<?= htmlspecialchars($sepatu['nama_sepatu']) ?>" class="shoe-detail-img img-fluid shadow-sm">
                    </div>

                    <!-- Kolom Spesifikasi & Informasi -->
                    <div class="col-md-7 p-4 p-lg-5">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 fs-6">
                                <?= htmlspecialchars($sepatu['kategori']) ?>
                            </span>
                            <span class="badge bg-light text-dark border font-monospace px-3 py-2">
                                <?= htmlspecialchars($sepatu['kode_produk']) ?>
                            </span>
                        </div>

                        <h2 class="fw-bold mb-2"><?= htmlspecialchars($sepatu['nama_sepatu']) ?></h2>

                        <div class="my-3">
                            <span class="text-muted small d-block">Harga Resmi:</span>
                            <h3 class="fw-bold text-primary mb-0"><?= formatRupiah((float)$sepatu['harga']) ?></h3>
                        </div>

                        <hr class="my-4">

                        <!-- Status Stok -->
                        <div class="mb-3">
                            <label class="fw-semibold text-muted small text-uppercase d-block mb-1">Status Ketersediaan</label>
                            <div>
                                <?= getStatusStokBadge((int)$sepatu['stok']) ?>
                                <span class="ms-2 text-secondary small">(Stok fisik: <?= (int)$sepatu['stok'] ?> pasang)</span>
                            </div>
                        </div>

                        <!-- Ukuran Tersedia -->
                        <div class="mb-3">
                            <label class="fw-semibold text-muted small text-uppercase d-block mb-2">Pilihan Ukuran Tersedia</label>
                            <div class="d-flex flex-wrap gap-2">
                                <?php foreach ($sizes as $s): ?>
                                    <span class="badge bg-secondary-subtle text-secondary-emphasis border px-3 py-2 fw-semibold">
                                        EU <?= htmlspecialchars($s) ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-4">
                            <label class="fw-semibold text-muted small text-uppercase d-block mb-1">Deskripsi Produk</label>
                            <p class="text-muted mb-0">
                                <?= nl2br(htmlspecialchars($sepatu['deskripsi'] ?: 'Tidak ada deskripsi rinci untuk produk ini.')) ?>
                            </p>
                        </div>

                        <!-- Informasi Tanggal & Metadata -->
                        <div class="p-3 bg-light rounded border mb-4 small text-muted">
                            <div class="row">
                                <div class="col-6">
                                    <i class="bi bi-calendar-plus me-1"></i> Ditambahkan: <br>
                                    <span class="text-dark fw-semibold"><?= date('d F Y, H:i', strtotime($sepatu['created_at'])) ?></span>
                                </div>
                                <div class="col-6">
                                    <i class="bi bi-clock-history me-1"></i> Terakhir Diubah: <br>
                                    <span class="text-dark fw-semibold"><?= date('d F Y, H:i', strtotime($sepatu['updated_at'])) ?></span>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="d-flex flex-wrap gap-2">
                            <a href="index.php" class="btn btn-outline-secondary px-3">
                                <i class="bi bi-arrow-left me-1"></i> Kembali
                            </a>
                            <a href="edit.php?id=<?= $sepatu['id'] ?>" class="btn btn-warning px-3 text-dark fw-semibold">
                                <i class="bi bi-pencil-square me-1"></i> Ubah Data
                            </a>
                            <button type="button" 
                                    class="btn btn-outline-danger px-3" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteConfirmModal"
                                    data-id="<?= $sepatu['id'] ?>"
                                    data-nama="<?= htmlspecialchars($sepatu['nama_sepatu']) ?>">
                                <i class="bi bi-trash3 me-1"></i> Hapus
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

