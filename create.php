<?php
/**
 * Halaman Tambah Sepatu Baru (CREATE)
 * Unit Kompetensi:
 * - J.620100.005.02 (User Interface Form & Feedback)
 * - J.620100.016.01 (Guidelines & Sanitasi Input)
 * - J.620100.017.02 (Pemrograman Terstruktur: Pengolahan Array, Form POST, File Upload)
 */

$pageTitle = 'Tambah Sepatu Baru - Toko Sepatu';
require_once __DIR__ . '/includes/header.php';

$errors = [];
$autoKode = generateKodeProduk();

// Tangani pengiriman formulir (Method POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Panggil fungsi tambah data dari functions.php
    $result = tambahSepatu($_POST, $_FILES['gambar'] ?? null);

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
?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <!-- Breadcrumb Navigasi -->
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Inventaris</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tambah Sepatu</li>
            </ol>
        </nav>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="bi bi-plus-circle me-2"></i>Formulir Tambah Data Sepatu
                </h5>
                <small class="opacity-75">Isi seluruh informasi spesifikasi produk dengan lengkap dan valid.</small>
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

                <form action="create.php" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <div class="row g-3">
                        <!-- Kode Produk -->
                        <div class="col-md-4">
                            <label for="kode_produk" class="form-label fw-semibold">Kode Produk <span class="text-danger">*</span></label>
                            <input type="text" class="form-control font-monospace" id="kode_produk" name="kode_produk" 
                                   value="<?= htmlspecialchars($_POST['kode_produk'] ?? $autoKode) ?>" required>
                            <div class="invalid-feedback">Kode produk tidak boleh kosong.</div>
                            <small class="text-muted">Kode digenerate otomatis.</small>
                        </div>

                        <!-- Nama Sepatu -->
                        <div class="col-md-8">
                            <label for="nama_sepatu" class="form-label fw-semibold">Nama Sepatu <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama_sepatu" name="nama_sepatu" 
                                   placeholder="Contoh: Nike Air Jordan Retro High" 
                                   value="<?= htmlspecialchars($_POST['nama_sepatu'] ?? '') ?>" required>
                            <div class="invalid-feedback">Silakan masukkan nama produk sepatu.</div>
                        </div>

                        <!-- Kategori -->
                        <div class="col-md-6">
                            <label for="kategori" class="form-label fw-semibold">Kategori Sepatu <span class="text-danger">*</span></label>
                            <select class="form-select" id="kategori" name="kategori" required>
                                <option value="" disabled <?= empty($_POST['kategori']) ? 'selected' : '' ?>>Pilih kategori...</option>
                                <?php foreach ($listKategori as $kat): ?>
                                    <option value="<?= $kat ?>" <?= (($_POST['kategori'] ?? '') === $kat) ? 'selected' : '' ?>>
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
                                       placeholder="Contoh: 750000" 
                                       value="<?= htmlspecialchars($_POST['harga'] ?? '') ?>" required>
                                <div class="invalid-feedback">Masukkan nominal harga yang valid (min. Rp 1.000).</div>
                            </div>
                        </div>

                        <!-- Pilihan Ukuran Tersedia (Array Processing) -->
                        <div class="col-12">
                            <label class="form-label fw-semibold d-block">Pilihan Ukuran (Size Tersedia) <span class="text-danger">*</span></label>
                            <div class="d-flex flex-wrap gap-3 p-3 bg-light rounded border">
                                <?php 
                                $selectedSizes = $_POST['ukuran'] ?? ['39', '40', '41', '42'];
                                if (!is_array($selectedSizes)) {
                                    $selectedSizes = array_map('trim', explode(',', $selectedSizes));
                                }
                                foreach ($listUkuran as $size): 
                                    $isChecked = in_array($size, $selectedSizes, true);
                                ?>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="ukuran[]" id="size_<?= $size ?>" value="<?= $size ?>" <?= $isChecked ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="size_<?= $size ?>">EU <?= $size ?></label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <small class="text-muted">Centang ukuran yang tersedia dalam stok.</small>
                        </div>

                        <!-- Jumlah Stok Awal -->
                        <div class="col-md-4">
                            <label for="stok" class="form-label fw-semibold">Stok Awal (Pasang) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="stok" name="stok" min="0" 
                                   value="<?= htmlspecialchars($_POST['stok'] ?? '10') ?>" required>
                            <div class="invalid-feedback">Masukkan jumlah stok (minimal 0).</div>
                        </div>

                        <!-- Upload Foto Sepatu (File I/O) -->
                        <div class="col-md-8">
                            <label for="gambar" class="form-label fw-semibold">Unggah Foto Sepatu</label>
                            <input type="file" class="form-control" id="gambar" name="gambar" accept="image/png, image/jpeg, image/jpg, image/webp">
                            <small class="text-muted d-block">Format: JPG, PNG, WEBP. Ukuran file maksimal 2 MB.</small>
                            <!-- Live Preview Gambar (JavaScript) -->
                            <img id="img-preview" src="#" alt="Preview Gambar" class="img-preview mt-2">
                        </div>

                        <!-- Deskripsi Produk -->
                        <div class="col-12">
                            <label for="deskripsi" class="form-label fw-semibold">Deskripsi & Spesifikasi Produk</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" 
                                      placeholder="Tuliskan material bahan, keunggulan, atau catatan produk..."><?= htmlspecialchars($_POST['deskripsi'] ?? '') ?></textarea>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="col-12 pt-3 border-top d-flex gap-2 justify-content-end">
                            <a href="index.php" class="btn btn-secondary px-4">
                                <i class="bi bi-arrow-left me-1"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-save me-1"></i> Simpan Sepatu
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

