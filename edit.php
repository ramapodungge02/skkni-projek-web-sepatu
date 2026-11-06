<?php
/**
 * Halaman Ubah / Edit Data Sepatu (UPDATE)
 * Unit Kompetensi:
 * - J.620100.005.02 (User Interface Form & Feedback)
 * - J.620100.016.01 (Guidelines & Sanitasi Input)
 * - J.620100.017.02 (Pemrograman Terstruktur: Pengolahan Array, Form POST, Update DB)
 */

$pageTitle = 'Edit Data Sepatu - Toko Sepatu';
require_once __DIR__ . '/includes/header.php';

// Ambil parameter ID dari query string
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$sepatu = getSepatuById($id);

// Jika ID tidak ditemukan, kembalikan ke beranda
if (!$sepatu) {
    $_SESSION['flash'] = [
        'type'    => 'danger',
        'message' => 'Data sepatu yang ingin Anda ubah tidak ditemukan dalam database.',
    ];
    header('Location: index.php');
    exit;
}

$errors = [];

// Tangani pengiriman formulir perubahan (Method POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = ubahSepatu($id, $_POST, $_FILES['gambar'] ?? null);

    if ($result['success']) {
        $_SESSION['flash'] = [
            'type'    => 'success',
            'message' => $result['message'],
        ];
        header('Location: index.php');
        exit;
    } else {
        $errors[] = $result['message'];
    }
}

$listKategori = ['Sneakers', 'Running', 'Casual', 'Olahraga', 'Formal'];
$listUkuran   = ['37', '38', '39', '40', '41', '42', '43', '44', '45'];

// Pisahkan string ukuran yang tersimpan di DB menjadi array
$currentSizes = array_map('trim', explode(',', $sepatu['ukuran']));

// Jalur berkas gambar sepatu
$imgPath = 'assets/uploads/' . htmlspecialchars($sepatu['gambar']);
if (!file_exists(__DIR__ . '/' . $imgPath) || empty($sepatu['gambar'])) {
    $imgPath = 'assets/uploads/default-shoe.png';
}
?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <!-- Breadcrumb Navigasi -->
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Inventaris</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit: <?= htmlspecialchars($sepatu['nama_sepatu']) ?></li>
            </ol>
        </nav>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-warning bg-opacity-25 py-3 border-bottom border-warning-subtle">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-0 fw-bold text-dark">
                            <i class="bi bi-pencil-square me-2 text-warning"></i>Edit Data Sepatu
                        </h5>
                        <small class="text-muted">Perbarui rincian produk, harga, stok, atau perbarui foto produk.</small>
                    </div>
                    <span class="badge bg-dark font-monospace"><?= htmlspecialchars($sepatu['kode_produk']) ?></span>
                </div>
            </div>

            <div class="card-body p-4">
                <!-- Tampilkan pesan error jika ada -->
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0 ps-3">
                            <?php foreach ($errors as $err): ?>
                                <li><?= htmlspecialchars($err) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <form action="edit.php?id=<?= $id ?>" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <div class="row g-3">
                        <!-- Kode Produk (Readonly untuk menjaga integritas data) -->
                        <div class="col-md-4">
                            <label for="kode_produk" class="form-label fw-semibold">Kode Produk</label>
                            <input type="text" class="form-control font-monospace bg-light" id="kode_produk" name="kode_produk" 
                                   value="<?= htmlspecialchars($sepatu['kode_produk']) ?>" readonly>
                            <small class="text-muted">Kode produk tidak dapat diubah.</small>
                        </div>

                        <!-- Nama Sepatu -->
                        <div class="col-md-8">
                            <label for="nama_sepatu" class="form-label fw-semibold">Nama Sepatu <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama_sepatu" name="nama_sepatu" 
                                   value="<?= htmlspecialchars($_POST['nama_sepatu'] ?? $sepatu['nama_sepatu']) ?>" required>
                            <div class="invalid-feedback">Silakan masukkan nama produk sepatu.</div>
                        </div>

                        <!-- Kategori -->
                        <div class="col-md-6">
                            <label for="kategori" class="form-label fw-semibold">Kategori Sepatu <span class="text-danger">*</span></label>
                            <select class="form-select" id="kategori" name="kategori" required>
                                <?php 
                                $selectedCategory = $_POST['kategori'] ?? $sepatu['kategori'];
                                foreach ($listKategori as $kat): 
                                ?>
                                    <option value="<?= $kat ?>" <?= ($selectedCategory === $kat) ? 'selected' : '' ?>>
                                        <?= $kat ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Pilih salah satu kategori.</div>
                        </div>

                        <!-- Harga Satuan -->
                        <div class="col-md-6">
                            <label for="harga" class="form-label fw-semibold">Harga Satuan (Rp) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control" id="harga" name="harga" min="1000" step="500" 
                                       value="<?= htmlspecialchars($_POST['harga'] ?? (int)$sepatu['harga']) ?>" required>
                                <div class="invalid-feedback">Masukkan nominal harga yang valid.</div>
                            </div>
                        </div>

                        <!-- Pilihan Ukuran Tersedia (Array Processing) -->
                        <div class="col-12">
                            <label class="form-label fw-semibold d-block">Pilihan Ukuran (Size Tersedia) <span class="text-danger">*</span></label>
                            <div class="d-flex flex-wrap gap-3 p-3 bg-light rounded border">
                                <?php 
                                $activeSizes = isset($_POST['ukuran']) 
                                    ? (is_array($_POST['ukuran']) ? $_POST['ukuran'] : explode(',', $_POST['ukuran'])) 
                                    : $currentSizes;

                                foreach ($listUkuran as $size): 
                                    $isChecked = in_array($size, $activeSizes, true);
                                ?>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="ukuran[]" id="size_<?= $size ?>" value="<?= $size ?>" <?= $isChecked ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="size_<?= $size ?>">EU <?= $size ?></label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Jumlah Stok -->
                        <div class="col-md-4">
                            <label for="stok" class="form-label fw-semibold">Kuantitas Stok <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="stok" name="stok" min="0" 
                                   value="<?= htmlspecialchars($_POST['stok'] ?? $sepatu['stok']) ?>" required>
                            <div class="invalid-feedback">Masukkan jumlah stok (minimal 0).</div>
                        </div>

                        <!-- Upload Foto Sepatu Pengganti -->
                        <div class="col-md-8">
                            <label for="gambar" class="form-label fw-semibold">Ganti Foto Sepatu</label>
                            <input type="file" class="form-control" id="gambar" name="gambar" accept="image/png, image/jpeg, image/jpg, image/webp">
                            <small class="text-muted d-block">Biarkan kosong jika tidak ingin mengganti gambar saat ini.</small>
                            
                            <div class="d-flex align-items-center gap-3 mt-2">
                                <div>
                                    <small class="text-muted d-block mb-1">Foto Saat Ini:</small>
                                    <img src="<?= $imgPath ?>" alt="Foto Sekarang" class="shoe-thumbnail">
                                </div>
                                <div id="preview-container">
                                    <img id="img-preview" src="#" alt="Pratinjau Baru" class="img-preview" style="max-height: 80px;">
                                </div>
                            </div>
                        </div>

                        <!-- Deskripsi Produk -->
                        <div class="col-12">
                            <label for="deskripsi" class="form-label fw-semibold">Deskripsi & Spesifikasi Produk</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"><?= htmlspecialchars($_POST['deskripsi'] ?? $sepatu['deskripsi'] ?? '') ?></textarea>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="col-12 pt-3 border-top d-flex gap-2 justify-content-end">
                            <a href="index.php" class="btn btn-secondary px-4">
                                <i class="bi bi-x-circle me-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-warning px-4 text-dark fw-semibold">
                                <i class="bi bi-check2-circle me-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

