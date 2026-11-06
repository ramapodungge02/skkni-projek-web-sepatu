<?php
/**
 * Template Header & Navigasi
 * Unit Kompetensi: J.620100.005.02 (User Interface) & J.620100.015.01 (Organisasi File)
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/functions.php';

$pageTitle = $pageTitle ?? 'Sistem Inventaris Sepatu - SKKNI JWD';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    
    <!-- Bootstrap 5 CSS (Pre-existing Library - Unit J.620100.019.02) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <!-- Navigasi Utama -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="index.php">
                <i class="bi bi-box-seam text-warning fs-4"></i>
                <span>SHOE<span class="text-warning">VAULT</span></span>
                <span class="badge bg-secondary ms-2 small d-none d-sm-inline-block">SKKNI JWD</span>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-nav-item">
                        <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : '' ?>" href="index.php">
                            <i class="bi bi-speedometer2 me-1"></i> Data Inventaris
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'create.php') ? 'active' : '' ?>" href="create.php">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Sepatu Baru
                        </a>
                    </li>
                </ul>

                <!-- Form Pencarian Cepat di Header -->
                <form class="d-flex" action="index.php" method="GET" role="search">
                    <div class="input-group">
                        <input class="form-control form-control-sm" type="search" name="q" placeholder="Cari kode/nama..." value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>" aria-label="Search">
                        <button class="btn btn-sm btn-outline-light" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </nav>

    <!-- Main Content Wrapper -->
    <main class="py-4">
        <div class="container">
            <!-- Flash Notification System -->
            <?php if (isset($_SESSION['flash'])): ?>
                <div class="alert alert-<?= htmlspecialchars($_SESSION['flash']['type']) ?> alert-dismissible fade show shadow-sm" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bi <?= $_SESSION['flash']['type'] === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill' ?> fs-4 me-2"></i>
                        <div>
                            <?= htmlspecialchars($_SESSION['flash']['message']) ?>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['flash']); ?>
            <?php endif; ?>

