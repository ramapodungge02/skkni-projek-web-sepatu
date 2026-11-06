        </div>
    </main>

    <!-- Modal Konfirmasi Hapus Data (Re-usable Modal) -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteModalLabel">
                        <i class="bi bi-trash3-fill me-2"></i>Konfirmasi Hapus Data
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-4 text-center">
                    <i class="bi bi-exclamation-triangle text-warning display-4 mb-3 d-block"></i>
                    <p class="mb-1">Apakah Anda yakin ingin menghapus data sepatu berikut?</p>
                    <h5 class="text-danger fw-bold" id="delete-item-name">-</h5>
                    <small class="text-muted">Tindakan ini tidak dapat dibatalkan dan file gambar akan dihapus dari server.</small>
                </div>
                <div class="modal-footer bg-light justify-content-center">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Batal
                    </button>
                    <a href="#" id="confirm-delete-btn" class="btn btn-danger px-4">
                        <i class="bi bi-trash3 me-1"></i>Ya, Hapus Sekarang
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="py-4 border-top">
        <div class="container text-center text-md-between d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
            <div>
                <span class="fw-semibold text-white">ShoeVault</span> &copy; <?= date('Y') ?> - Sistem Inventaris & Katalog Sepatu
            </div>
            <div class="small">
                <span class="badge bg-dark border border-secondary text-light">Skema: Junior Web Developer</span>
                <span class="ms-1 text-muted">SKKNI No. 282 Tahun 2016</span>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 Bundle JS (Pre-existing Library - Unit J.620100.019.02) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
    <!-- Custom JavaScript -->
    <script src="assets/js/script.js"></script>
</body>
</html>

