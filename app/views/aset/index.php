<?php
// ====================================================================
// VIEW DAFTAR KELOLA ASET BARANG
// ====================================================================
// File       : app/views/aset/index.php
// Deskripsi  : Menampilkan data inventaris dalam bentuk tabel interaktif,
//              lengkap dengan pencarian, aksi CRUD, dan ekspor laporan.
// ====================================================================
?>

<div class="container-fluid p-0">
    
    <!-- Bagian Alert Feedback Sukses / Error -->
    <?php if (isset($_SESSION['success_msg'])): ?>
        <div class="alert alert-success alert-custom alert-dismissible fade show border-0 shadow-sm p-3 mb-4" 
             style="background-color: var(--matcha-light); color: var(--matcha-dark); border-radius: 12px;" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-check-circle-fill fs-5 me-2"></i>
                <div>
                    <b>Berhasil!</b> <?= htmlspecialchars($_SESSION['success_msg']); ?>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['success_msg']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_msg'])): ?>
        <div class="alert alert-danger alert-custom alert-dismissible fade show border-0 shadow-sm p-3 mb-4" 
             style="background-color: #FFEBEE; color: #C62828; border-radius: 12px;" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
                <div>
                    <b>Gagal!</b> <?= htmlspecialchars($_SESSION['error_msg']); ?>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['error_msg']); ?>
    <?php endif; ?>

    <!-- Judul Halaman & Panel Pencarian -->
    <div class="card border-0 rounded-4 shadow-sm p-4 mb-4 bg-white">
        <div class="row align-items-center g-3">
            <div class="col-12 col-md-6 text-center text-md-start">
                <h4 class="fw-bold text-dark m-0">
                    <i class="bi bi-box-fill text-success me-2" style="color: var(--matcha-dark) !important;"></i>Kelola Aset Inventaris
                </h4>
                <p class="text-secondary m-0 small mt-1">
                    Kelola data, unggah foto kondisi fisik, dan unduh laporan secara instan.
                </p>
            </div>
            
            <div class="col-12 col-md-6">
                <!-- Form Pencarian Real-time -->
                <form action="index.php" method="GET" autocomplete="off" class="d-flex justify-content-md-end">
                    <input type="hidden" name="page" value="aset">
                    <div class="input-group" style="max-width: 360px; width: 100%;">
                        <input type="text" name="search" class="form-control bg-light border-end-0 border-2" 
                               style="border-color: #E0E0E0; border-radius: 12px 0 0 12px; font-size: 0.9rem;"
                               placeholder="Cari kode, nama, atau kategori..." 
                               value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                        <button class="btn btn-outline-success border-start-0 border-2 px-3" type="submit" 
                                style="border-color: #E0E0E0; border-radius: 0 12px 12px 0; color: var(--matcha-dark);">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Toolbar Aksi Utama (Tambah & Ekspor) -->
    <div class="row g-3 mb-4">
        <!-- Tombol Tambah Data -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <a href="index.php?page=aset&action=create" class="btn btn-matcha w-100 py-2.5 rounded-3 shadow-sm text-center d-flex align-items-center justify-content-center gap-2">
                <i class="bi bi-plus-circle-fill"></i> Tambah Aset Baru
            </a>
        </div>
        
        <!-- Tombol Export PDF -->
        <div class="col-6 col-sm-3 col-md-4 col-lg-2">
            <a href="index.php?page=aset&action=export_pdf<?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?>" 
               target="_blank" class="btn btn-strawberry w-100 py-2.5 rounded-3 shadow-sm text-center d-flex align-items-center justify-content-center gap-2">
                <i class="bi bi-file-earmark-pdf-fill"></i> Export PDF
            </a>
        </div>

        <!-- Tombol Export Excel -->
        <div class="col-6 col-sm-3 col-md-4 col-lg-2">
            <a href="index.php?page=aset&action=export_excel<?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?>" 
               class="btn btn-outline-success w-100 py-2.5 rounded-3 shadow-sm text-center d-flex align-items-center justify-content-center gap-2"
               style="color: var(--matcha-dark); border-color: var(--matcha-medium); background-color: #FFFFFF;">
                <i class="bi bi-file-earmark-excel-fill text-success"></i> Export Excel
            </a>
        </div>
    </div>

    <!-- Card Penampung Tabel -->
    <div class="card border-0 rounded-4 shadow-sm overflow-hidden bg-white mb-4">
        <div class="table-responsive">
            <table class="table align-middle m-0" id="assetTable">
                <!-- Header Tabel Bertema Matcha Green -->
                <thead style="background-color: var(--matcha-light); color: var(--matcha-dark);">
                    <tr>
                        <th class="py-3 px-4 text-center" style="width: 60px;">No</th>
                        <th class="py-3 text-center" style="width: 90px;">Fisik</th>
                        <th class="py-3 px-3">Kode Aset</th>
                        <th class="py-3 px-3">Nama Barang / Aset</th>
                        <th class="py-3 px-3">Kategori</th>
                        <th class="py-3 px-3 text-center" style="width: 100px;">Jumlah</th>
                        <th class="py-3 px-3 text-center" style="width: 110px;">Kondisi</th>
                        <th class="py-3 px-3 text-center" style="width: 140px;">Tgl Perolehan</th>
                        <th class="py-3 px-4 text-center" style="width: 130px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($aset_list)): ?>
                        <!-- Keadaan Jika Data Kosong / Tidak Ditemukan -->
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <div class="py-4">
                                    <span class="fs-1">📦</span>
                                    <h5 class="fw-bold text-secondary mt-3">Tidak Ada Data Aset</h5>
                                    <p class="text-muted extra-small mb-3">Silakan tambahkan data baru atau sesuaikan kata kunci pencarian Anda.</p>
                                    <a href="index.php?page=aset&action=create" class="btn btn-matcha btn-sm px-4 rounded-pill">
                                        <i class="bi bi-plus"></i> Tambah Data Pertama
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <!-- Loop Iterasi Data Aset -->
                        <?php 
                        $no = 1; 
                        foreach ($aset_list as $row): 
                        ?>
                            <tr class="transition-row">
                                <td class="text-center fw-semibold text-secondary px-4"><?= $no++; ?></td>
                                
                                <!-- Render Thumbnail Foto dengan fallback placeholder -->
                                <td class="text-center py-2">
                                    <?php if (!empty($row['foto']) && file_exists('uploads/' . $row['foto'])): ?>
                                        <img src="uploads/<?= htmlspecialchars($row['foto']); ?>" 
                                             alt="Foto <?= htmlspecialchars($row['nama_barang']); ?>" 
                                             class="rounded-circle object-fit-cover shadow-sm border" 
                                             style="width: 48px; height: 48px; border-color: var(--matcha-medium) !important;">
                                    <?php else: ?>
                                        <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center border" 
                                             style="width: 48px; height: 48px; border-color: var(--matcha-medium) !important;">
                                            <i class="bi bi-box-seam text-secondary fs-5"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                
                                <!-- Kode Aset (Highlight soft style) -->
                                <td class="px-3">
                                    <span class="badge rounded-pill px-3 py-1.5 fw-semibold text-dark border"
                                          style="background-color: var(--bg-soft-grey); font-size: 0.8rem; border-color: #E0E0E0 !important;">
                                        <?= htmlspecialchars($row['kode_aset']); ?>
                                    </span>
                                </td>
                                
                                <!-- Nama Barang -->
                                <td class="px-3 fw-semibold text-dark text-wrap" style="max-width: 250px;">
                                    <?= htmlspecialchars($row['nama_barang']); ?>
                                </td>
                                
                                <!-- Kategori -->
                                <td class="px-3 text-secondary small">
                                    <?= htmlspecialchars($row['kategori']); ?>
                                </td>
                                
                                <!-- Jumlah Unit -->
                                <td class="px-3 text-center fw-bold text-dark">
                                    <?= htmlspecialchars($row['jumlah']); ?>
                                </td>
                                
                                <!-- Badge Kondisi Barang -->
                                <td class="px-3 text-center">
                                    <?php if ($row['kondisi'] === 'Baik'): ?>
                                        <span class="badge rounded-pill px-3 py-1.5" 
                                              style="background-color: var(--matcha-light); color: var(--matcha-dark); font-size: 0.75rem;">
                                            <i class="bi bi-check-circle-fill me-1"></i> Baik
                                        </span>
                                    <?php else: ?>
                                        <span class="badge rounded-pill px-3 py-1.5" 
                                              style="background-color: #FFEBEE; color: #C62828; font-size: 0.75rem;">
                                            <i class="bi bi-exclamation-triangle-fill me-1"></i> Rusak
                                        </span>
                                    <?php endif; ?>
                                </td>
                                
                                <!-- Tanggal Perolehan -->
                                <td class="px-3 text-center text-secondary small">
                                    <?= date('d-m-Y', strtotime($row['tgl_perolehan'])); ?>
                                </td>
                                
                                <!-- Tombol Aksi (Ubah & Hapus) -->
                                <td class="px-4 text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <!-- Tombol Edit -->
                                        <a href="index.php?page=aset&action=edit&id=<?= $row['id']; ?>" 
                                           class="btn btn-sm btn-outline-success border-2 rounded-3 d-flex align-items-center justify-content-center"
                                           style="width: 34px; height: 34px; color: var(--matcha-dark); border-color: var(--matcha-medium);"
                                           title="Ubah Data">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                        
                                        <!-- Tombol Trigger Modal Hapus (Bukan confirm JS) -->
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-danger border-2 rounded-3 d-flex align-items-center justify-content-center"
                                                style="width: 34px; height: 34px;"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteConfirmModal" 
                                                data-id="<?= $row['id']; ?>" 
                                                data-nama="<?= htmlspecialchars($row['nama_barang']); ?>"
                                                title="Hapus Data">
                                            <i class="bi bi-trash-fill"></i>
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
</div>

<!-- MODAL KONFIRMASI HAPUS (Mematuhi Aturan Tanpa confirm() JS) -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow" style="overflow: hidden;">
            <!-- Modal Header Accent Pink -->
            <div class="modal-header border-0 p-4 pb-2" style="background-color: #FFEBEE;">
                <h5 class="modal-title fw-bold text-danger d-flex align-items-center" id="deleteModalLabel">
                    <i class="bi bi-exclamation-triangle-fill fs-4 me-2"></i> Konfirmasi Hapus Data
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Modal Body -->
            <div class="modal-body p-4 text-center">
                <p class="text-secondary m-0">Apakah Anda benar-benar yakin ingin menghapus data aset berikut?</p>
                <div class="my-3 p-3 bg-light rounded-3 border">
                    <h6 class="fw-bold text-dark m-0" id="deleteTargetName">-</h6>
                </div>
                <p class="text-muted extra-small m-0" style="font-size: 0.75rem;">
                    *Tindakan ini tidak dapat dibatalkan. Berkas gambar fisik di server juga akan dihapus permanen.
                </p>
            </div>
            <!-- Modal Footer -->
            <div class="modal-footer border-0 p-4 pt-2 d-flex justify-content-center gap-3">
                <button type="button" class="btn btn-light border-2 rounded-pill px-4 text-secondary py-2" data-bs-dismiss="modal" style="font-weight: 500;">
                    Batal
                </button>
                <a href="#" id="deleteConfirmBtn" class="btn btn-danger rounded-pill px-4 py-2" style="font-weight: 600;">
                    Ya, Hapus Permanen
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Mendapatkan elemen modal konfirmasi hapus
        const deleteModal = document.getElementById('deleteConfirmModal');
        
        if (deleteModal) {
            deleteModal.addEventListener('show.bs.modal', function(event) {
                // Tombol trigger yang ditekan
                const button = event.relatedTarget;
                
                // Ekstrak atribut data dari tombol
                const id = button.getAttribute('data-id');
                const nama = button.getAttribute('data-nama');
                
                // Cari elemen internal modal yang akan diupdate secara dinamis
                const deleteTargetName = deleteModal.querySelector('#deleteTargetName');
                const deleteConfirmBtn = deleteModal.querySelector('#deleteConfirmBtn');
                
                // Update teks nama target dan URL aksi hapus di tombol konfirmasi
                deleteTargetName.textContent = nama;
                deleteConfirmBtn.setAttribute('href', 'index.php?page=aset&action=delete&id=' + id);
            });
        }
    });
</script>

<style>
    /* Tombol Kustom Berwarna Hijau Matcha */
    .btn-matcha {
        background-color: var(--matcha-dark) !important;
        border: none;
        color: #FFFFFF !important;
        font-weight: 600;
        transition: all 0.25s ease;
    }
    .btn-matcha:hover {
        background-color: #246227 !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(46, 125, 50, 0.2);
    }

    /* Tombol Kustom Berwarna Pink Strawberry */
    .btn-strawberry {
        background-color: var(--strawberry-medium) !important;
        border: none;
        color: #FFFFFF !important;
        font-weight: 600;
        transition: all 0.25s ease;
    }
    .btn-strawberry:hover {
        background-color: #E91E63 !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(240, 98, 146, 0.2);
    }

    /* Efek transisi baris tabel saat di-hover */
    .transition-row {
        transition: background-color 0.2s ease;
    }
    .transition-row:hover {
        background-color: var(--bg-soft-grey);
    }
</style>