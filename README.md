# 👟 Sistem Inventaris & Katalog Toko Sepatu (ShoeVault)

> **Proyek Uji Kompetensi Skema: Junior Web Developer**  
> Mengacu pada **SKKNI No. 282 Tahun 2016** & Standar Pelatihan Vokasi Kementerian Ketenagakerjaan R.I.

---

## 📌 1. Deskripsi Proyek
**ShoeVault** adalah aplikasi berbasis web yang dirancang untuk mengelola inventaris, katalog produk, dan ketersediaan stok sepatu secara terstruktur, aman, dan responsif. Aplikasi ini mengimplementasikan fungsi CRUD (*Create, Read, Update, Delete*) lengkap dengan manajemen berkas media (*file upload*), filter kategori, pencarian cerdas, serta penanganan validasi.

---

## 🏆 2. Pemetaan Pemenuhan Unit Kompetensi SKKNI

| No | Kode Unit | Judul Unit Kompetensi | Bukti Implementasi pada Proyek |
|---|---|---|---|
| 1 | `J.620100.005.02` | **Mengimplementasikan User Interface** | Layout modern responsif menggunakan **Bootstrap 5.3**, form input interaktif dengan validasi visual, badge status stok, modal konfirmasi hapus data, dan live image preview. |
| 2 | `J.620100.010.01` | **Menerapkan Perintah Eksekusi Bahasa Pemrograman** | Menjalankan aplikasi pada lingkungan server lokal (Apache & PHP 8.x di Laragon/XAMPP), eksekusi skrip PHP melalui peramban web (*browser*) dan CLI. |
| 3 | `J.620100.015.01` | **Menyusun Fungsi, File, atau Sumber Daya Pemrograman dalam Organisasi yang Rapi** | Struktur folder modular terpisah (`config/`, `includes/`, `assets/css/`, `assets/js/`, `assets/uploads/`), penamaan identifier deskriptif, dokumentasi komentar PHPDoc, dan file `README.md`. |
| 4 | `J.620100.016.01` | **Menulis Kode dengan Prinsip Sesuai Guidelines dan Best Practices** | Mengikuti standar konvensi PSR-12, sanitasi input XSS (`htmlspecialchars`), proteksi SQL Injection dengan **PDO Prepared Statements**, dan pemisahan logika tampilan (*Separation of Concerns*). |
| 5 | `J.620100.017.02` | **Mengimplementasikan Pemrograman Terstruktur** | Penggunaan variabel bertipe data (string, integer, float, array, boolean), kontrol alur percabangan (`if-else`), perulangan (`foreach`), fungsi/prosedur modular (`functions.php`), manipulasi array asosiatif, pengolahan berkas/media (*file upload* & `unlink`), dan error handling `try-catch`. |
| 6 | `J.620100.019.02` | **Menggunakan Library atau Komponen Pre-existing** | Integrasi framework CSS & JS **Bootstrap 5.3**, icon kit **Bootstrap Icons**, konfigurasi dependensi paket via **Composer** (`composer.json`), dengan kepatuhan lisensi **MIT License**. |

---

## 📁 3. Struktur Direktori Proyek

```text
SKKNI/
├── assets/
│   ├── css/
│   │   └── style.css            # Kustomisasi gaya tampilan UI (Unit 1.1)
│   ├── js/
│   │   └── script.js            # Skrip validasi formulir, image preview, & modal (Unit 1.1)
│   └── uploads/
│       └── default-shoe.png     # Tempat penyimpanan berkas gambar sepatu yang diunggah
├── config/
│   └── database.php             # Konfigurasi koneksi database PDO aman (Unit 1.3 & 1.5)
├── includes/
│   ├── header.php               # Template navigasi atas & flash notification
│   ├── footer.php               # Template navigasi bawah, modal hapus, & skrip
│   └── functions.php            # Kumpulan modul fungsi logika CRUD terstruktur (Unit 1.5)
├── composer.json                # Bukti pengelolaan library pre-existing / Composer (Unit 1.6)
├── database.sql                 # Skrip skema database MySQL & data awal (seed)
├── index.php                    # Dashboard: Tabel & Katalog Produk Sepatu (READ)
├── create.php                   # Form & Proses Tambah Sepatu Baru (CREATE)
├── edit.php                     # Form & Proses Edit Data Sepatu (UPDATE)
├── delete.php                   # Skrip Proses Hapus Sepatu & Hapus File Fisik (DELETE)
├── detail.php                   # Halaman Rincian Spesifikasi Produk Sepatu
└── README.md                    # Dokumentasi lengkap sistem & panduan asesmen
```

---

## ⚙️ 4. Petunjuk Instalasi & Menjalankan Aplikasi

### A. Prasyarat Lingkungan
* Web Server: **Apache** (via Laragon / XAMPP)
* Interpreter: **PHP 8.0** atau yang lebih baru (ekstensi `pdo_mysql`, `gd` aktif)
* Database Server: **MySQL 5.7+** / **MariaDB 10.4+** / **MySQL 8.x**
* Peramban Web: Google Chrome, Mozilla Firefox, atau Microsoft Edge

### B. Langkah Instalasi Database
1. Buka aplikasi **Laragon** lalu klik tombol **"Start All"**.
2. Buka database management tool favorit Anda (phpMyAdmin / HeidiSQL / MySQL CLI).
3. Import berkas `database.sql` yang berada di direktori root proyek ini:
   * Melalui HeidiSQL: Menu `File` -> `Load SQL File...` -> pilih `database.sql` -> tekan tombol `Execute` (F9).
   * Atau via MySQL CLI:
     ```bash
     mysql -u root -p < database.sql
     ```
   *(Database `db_toko_sepatu` dan tabel `sepatu` beserta data contoh akan otomatis dibuat).*

### C. Menjalankan Aplikasi
1. Buka web browser.
2. Akses alamat URL lokal:
   ```text
   http://localhost/SKKNI/
   ```
   *(atau `http://skkni.test` jika menggunakan fitur Auto Virtual Hosts Laragon).*

---

## 🔒 5. Kepatuhan Regulasi & Aspek Hukum (UU ITE & KIP)

Sesuai materi silabus pelatihan halaman 11–12 mengenai regulasi teknologi informasi di Indonesia:

1. **Undang-Undang Informasi dan Transaksi Elektronik (UU ITE No. 11/2008 jo. UU No. 1/2024)**:
   * **Integritas & Kerahasiaan Data (Pasal 30 & 32)**: Aplikasi menerapkan sanitasi karakter pada seluruh masukan formulir dan menggunakan *Prepared Statements* untuk mencegah injeksi kode (*SQL Injection* dan *XSS*). Akses pengubahan atau penghapusan data dibatasi melalui parameter ID terverifikasi.
   * **Keamanan Berkas**: Berkas gambar yang diunggah divalidasi berdasarkan *MIME type* dan ekstensi yang diizinkan untuk mencegah pengunggahan skrip berbahaya (*Arbitrary File Upload*).
2. **Undang-Undang Keterbukaan Informasi Publik (UU KIP No. 14/2008)**:
   * **Akses Informasi Publik Berkala**: Data katalog produk sepatu, harga satuan, dan ketersediaan stok disajikan secara transparan dan terbuka kepada publik/pengguna.
   * **Perlindungan Informasi Dikecualikan**: Konfigurasi koneksi internal database (`config/database.php`) tidak membeberkan kredensial rahasia server ke tampilan publik saat terjadi kegagalan sistem (*custom error handler*).

---

## 📄 6. Lisensi & Hak Cipta Komponen Pre-Existing
* Bootstrap 5.3 Framework - Dirilis di bawah lisensi **MIT License** (The MIT License).
* Bootstrap Icons - Dirilis di bawah lisensi **MIT License**.
* Kode Proyek - Dibuat untuk pemenuhan Asesmen Uji Kompetensi Junior Web Developer.

