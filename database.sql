-- ====================================================================
-- DATABASE: db_toko_sepatu
-- SKEMA SERTIFIKASI: Junior Web Developer (SKKNI No. 282 Tahun 2016)
-- KEMENTERIAN KETENAGAKERJAAN REPUBLIK INDONESIA
-- ====================================================================

-- 1. Buat Database jika belum ada
CREATE DATABASE IF NOT EXISTS `db_toko_sepatu` 
DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE `db_toko_sepatu`;

-- 2. Buat Tabel `sepatu`
-- Menyimpan informasi katalog dan inventaris produk sepatu
DROP TABLE IF EXISTS `sepatu`;

CREATE TABLE `sepatu` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `kode_produk` VARCHAR(20) NOT NULL UNIQUE,
    `nama_sepatu` VARCHAR(100) NOT NULL,
    `kategori` VARCHAR(50) NOT NULL,
    `ukuran` VARCHAR(50) NOT NULL,
    `harga` DECIMAL(12, 2) NOT NULL,
    `stok` INT NOT NULL DEFAULT 0,
    `deskripsi` TEXT NULL,
    `gambar` VARCHAR(255) DEFAULT 'default-shoe.png',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Data Awal (Dummy/Seed Data) untuk Uji Coba Asesmen
INSERT INTO `sepatu` (`kode_produk`, `nama_sepatu`, `kategori`, `ukuran`, `harga`, `stok`, `deskripsi`, `gambar`) VALUES
('SPT-001', 'Nike Air Jordan 1 Low', 'Sneakers', '40, 41, 42, 43', 1899000.00, 15, 'Sepatu kasual bergaya retro klasik dengan kenyamanan maksimal untuk pemakaian sehari-hari.', 'default-shoe.png'),
('SPT-002', 'Adidas Ultraboost Light', 'Running', '39, 40, 41, 42, 43', 2799000.00, 8, 'Sepatu lari berbobot ringan dengan bantalan Boost mutakhir untuk performa tinggi.', 'default-shoe.png'),
('SPT-003', 'Ventela Public Low Black Natural', 'Sneakers', '38, 39, 40, 41, 42', 299000.00, 25, 'Sneakers lokal buatan anak bangsa berbahan kanvas tebal 12oz dengan teknologi insole ultralite foam.', 'default-shoe.png'),
('SPT-004', 'Compass Gazelle Hi Black White', 'Casual', '40, 41, 42', 438000.00, 4, 'Sepatu kanvas berdesain siluet klasik dengan aksen gazelle stripe khas Compass.', 'default-shoe.png'),
('SPT-005', 'Puma Future Z 1.4 FG', 'Olahraga', '41, 42, 43, 44', 1599000.00, 0, 'Sepatu sepakbola profesional dengan kontrol presisi tinggi di lapangan rumput.', 'default-shoe.png');

