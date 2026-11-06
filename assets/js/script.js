/**
 * Custom JavaScript - Toko Sepatu (SKKNI JWD)
 * Unit: J.620100.005.02 (User Interface) & J.620100.016.01 (Guidelines)
 */

document.addEventListener('DOMContentLoaded', function () {
    // 1. Bootstrap Form Validation
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });

    // 2. Live Preview Gambar saat File Diunggah
    const inputGambar = document.getElementById('gambar');
    const imgPreview = document.getElementById('img-preview');

    if (inputGambar && imgPreview) {
        inputGambar.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                // Validasi ukuran sisi klien (maks 2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('Peringatan: Ukuran berkas gambar melebihi batas maksimal 2 MB!');
                    this.value = '';
                    imgPreview.style.display = 'none';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function (e) {
                    imgPreview.src = e.target.result;
                    imgPreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                imgPreview.style.display = 'none';
            }
        });
    }

    // 3. Konfirmasi Hapus Data Menggunakan Modal Dinamis
    const deleteModalEl = document.getElementById('deleteConfirmModal');
    if (deleteModalEl) {
        deleteModalEl.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const shoeId = button.getAttribute('data-id');
            const shoeName = button.getAttribute('data-nama');

            const modalItemName = deleteModalEl.querySelector('#delete-item-name');
            const confirmBtn = deleteModalEl.querySelector('#confirm-delete-btn');

            if (modalItemName) modalItemName.textContent = shoeName;
            if (confirmBtn) confirmBtn.href = 'delete.php?id=' + encodeURIComponent(shoeId);
        });
    }

    // 4. Auto-dismiss Alert Notifikasi setelah 4 detik
    const alerts = document.querySelectorAll('.alert-dismissible');
    alerts.forEach(function (alert) {
        setTimeout(function () {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            if (bsAlert) {
                bsAlert.close();
            }
        }, 4000);
    });
});

