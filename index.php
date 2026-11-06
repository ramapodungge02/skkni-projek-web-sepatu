<?php
/**
 * Halaman Utama: Daftar & Inventaris Sepatu (READ)
 * Unit Kompetensi:
 * - J.620100.005.02 (Implementasi User Interface)
 * - J.620100.016.01 (Coding Guidelines)
 * - J.620100.017.02 (Pemrograman Terstruktur: Looping, Array, Percabangan)
 */

$pageTitle = 'Inventaris Sepatu - Beranda';
require_once __DIR__ . '/includes/header.php';

// Ambil parameter filter & pencarian (Sanitasi input)
$keyword  = isset($_GET['q']) ? sanitizeInput($_GET['q']) : null;
$kategori = isset($_GET['kategori']) ? sanitizeInput($_GET['kategori']) : null;

// Eksekusi fungsi untuk mengambil data sepatu
$daftarSepatu = getAllSepatu($keyword, $kategori);

// Hitung statistik untuk dashboard ringkas (Pemrograman Terstruktur: Array & Looping)
$totalProduk  = count($daftarSepatu);
$totalStok    = 0;
$stokMenipis  = 0;

foreach ($daftarSepatu as $item) {
    $totalStok += $item['stok'];
    if ($item['stok'] < 5) {
        $stokMenipis++;
    }
}

// Daftar kategori tetap untuk filter
$listKategori = ['Sneakers', 'Running', 'Casual', 'Olahraga', 'Formal'];
?>

<div class="row align-items-center mb-4">
    <div class="col-md-7">
        <h2 class="fw-bold mb-1">
            <i class="bi bi-collection-fill text-primary me-2"></i>Katalog & Inventaris Sepatu
        </h2>
        <p class="text-muted mb-0">Kelola master data produk sepatu, stok ketersediaan, dan harga katalog.</p>
    </div>
    <div class="col-md-5 text-md-end mt-3 mt-md-0">
        <a href="create.php" class="btn btn-primary px-3 shadow-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah Sepatu Baru
        </a>
    </div>
</div>

<!-- Kartu Ringkasan Statistik (Structured Array Processing) -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-4">
        <div class="card stats-card shadow-sm p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small text-uppercase fw-bold">Total Varian Sepatu</span>
                    <h3 class="fw-bold mb-0 mt-1"><?= $totalProduk ?> <small class="fs-6 fw-normal text-muted">Item</small></h3>
                </div>
                <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-circle">
                    <i class="bi bi-box-seam fs-3"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="card stats-card shadow-sm p-3 border-start-success" style="border-left-color: #198754 !important;">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small text-uppercase fw-bold">Total Kuantitas Stok</span>
                    <h3 class="fw-bold mb-0 mt-1"><?= $totalStok ?> <small class="fs-6 fw-normal text-muted">Pasang</small></h3>
                </div>
                <div class="bg-success bg-opacity-10 text-success p-3 rounded-circle">
                    <i class="bi bi-layers-fill fs-3"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="card stats-card shadow-sm p-3 border-start-warning" style="border-left-color: #ffc107 !important;">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small text-uppercase fw-bold">Perlu Restok (&lt; 5)</span>
                    <h3 class="fw-bold mb-0 mt-1"><?= $stokMenipis ?> <small class="fs-6 fw-normal text-muted">Item</small></h3>
                </div>
                <div class="bg-warning bg-opacity-10 text-warning p-3 rounded-circle">
                    <i class="bi bi-exclamation-triangle-fill fs-3"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter dan Pencarian Bar -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form action="index.php" method="GET" class="row g-2 align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" name="q" class="form-control border-start-0" placeholder="Cari nama sepatu, kode, atau deskripsi..." value="<?= htmlspecialchars($keyword ?? '') ?>">
                </div>
            </div>
            <div class="col-md-3">
                <select name="kategori" class="form-select">
                    <option value="">Semua Kategori</option>
                    <?php foreach ($listKategori as $kat): ?>
                        <option value="<?= $kat ?>" <?= ($kategori === $kat) ? 'selected' : '' ?>>
                            <?= $kat ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-dark flex-grow-1">
                    <i class="bi bi-funnel me-1"></i> Terapkan Filter
                </button>
                <?php if (!empty($keyword) || !empty($kategori)): ?>
                    <a href="index.php" class="btn btn-outline-secondary" title="Reset Filter">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Daftar Sepatu (READ) -->
<div class="card shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">
            <i class="bi bi-table me-2 text-primary"></i>Tabel Master Data Produk
        </h5>
        <span class="badge bg-light text-dark border">
            Menampilkan <?= count($daftarSepatu) ?> Data
        </span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th class="text-center" style="width: 50px;">No</th>
                    <th style="width: 80px;">Foto</th>
                    <th>Kode</th>
                    <th>Nama Sepatu</th>
                    <th>Kategori</th>
                    <th>Ukuran</th>
                    <th>Harga Satuan</th>
                    <th class="text-center">Status Stok</th>
                    <th class="text-center" style="width: 170px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($daftarSepatu)): ?>
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                            <h6 class="fw-semibold">Tidak ada data sepatu yang ditemukan</h6>
                            <p class="small mb-3">Silakan sesuaikan kata kunci pencarian atau tambah produk baru.</p>
                            <a href="create.php" class="btn btn-sm btn-primary">
                                <i class="bi bi-plus-lg me-1"></i> Tambah Sepatu Baru
                            </a>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php 
                    $no = 1; 
                    foreach ($daftarSepatu as $sepatu): 
                        $imgPath = 'assets/uploads/' . htmlspecialchars($sepatu['gambar']);
                        if (!file_exists(__DIR__ . '/' . $imgPath) || empty($sepatu['gambar'])) {
                            $imgPath = 'assets/uploads/default-shoe.png';
                        }
                    ?>
                        <tr>
                            <td class="text-center fw-semibold text-muted"><?= $no++ ?></td>
                            <td>
                                <img src="<?= $imgPath ?>" alt="<?= htmlspecialchars($sepatu['nama_sepatu']) ?>" class="shoe-thumbnail">
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border font-monospace">
                                    <?= htmlspecialchars($sepatu['kode_produk']) ?>
                                </span>
                            </td>
                            <td>
                                <a href="detail.php?id=<?= $sepatu['id'] ?>" class="text-decoration-none fw-bold text-dark d-block">
                                    <?= htmlspecialchars($sepatu['nama_sepatu']) ?>
                                </a>
                                <small class="text-muted d-block text-truncate" style="max-width: 250px;">
                                    <?= htmlspecialchars($sepatu['deskripsi'] ?? '-') ?>
                                </small>
                            </td>
                            <td>
                                <span class="badge bg-info bg-opacity-10 text-info-emphasis border border-info-subtle">
                                    <?= htmlspecialchars($sepatu['kategori']) ?>
                                </span>
                            </td>
                            <td>
                                <span class="small text-secondary"><?= htmlspecialchars($sepatu['ukuran']) ?></span>
                            </td>
                            <td class="fw-bold text-primary">
                                <?= formatRupiah((float)$sepatu['harga']) ?>
                            </td>
                            <td class="text-center">
                                <?= getStatusStokBadge((int)$sepatu['stok']) ?>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="detail.php?id=<?= $sepatu['id'] ?>" class="btn btn-outline-secondary" title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="edit.php?id=<?= $sepatu['id'] ?>" class="btn btn-outline-warning text-dark" title="Edit Sepatu">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-outline-danger" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteConfirmModal"
                                            data-id="<?= $sepatu['id'] ?>"
                                            data-nama="<?= htmlspecialchars($sepatu['nama_sepatu']) ?>"
                                            title="Hapus Sepatu">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

