<?php
// ====================================================================
// VIEW DASHBOARD UTAMA
// ====================================================================
// File       : app/views/dashboard/index.php
// Deskripsi  : Menampilkan ringkasan statistik aset secara minimalis 
//              dan manis dengan tema visual Strawberry Matcha.
// ====================================================================
?>

<div class="container-fluid p-0">
    <!-- Row Sapaan Pengguna Aktif -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="p-4 rounded-4 shadow-sm border border-2 d-flex align-items-center justify-content-between" 
                 style="background: linear-gradient(135deg, #FCE4EC 0%, #E8F5E9 100%); border-color: var(--matcha-medium) !important;">
                <div>
                    <h3 class="fw-bold text-dark mb-1">
                        Halo, <span style="color: var(--strawberry-dark);"><?= htmlspecialchars($_SESSION['nama_lengkap']); ?></span>! 👋
                    </h3>
                    <p class="text-secondary m-0 small">
                        Selamat datang kembali di Sistem Informasi Aset Barang. Mari kelola inventaris hari ini dengan mudah.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Row Card Statistik Minimalis -->
    <div class="row g-4 mb-4">
        
        <!-- Card 1: Total Aset Barang (Strawberry Pink Theme) -->
        <div class="col-12 col-md-4">
            <div class="card h-100 border-0 rounded-4 shadow-sm p-4 text-center transition-card" 
                 style="background-color: var(--strawberry-light); border-bottom: 5px solid var(--strawberry-medium) !important;">
                <div class="d-flex justify-content-center align-items-center mb-3">
                    <div class="rounded-circle p-3 bg-white shadow-sm d-flex justify-content-center align-items-center" 
                         style="width: 60px; height: 60px;">
                        <i class="bi bi-box-seam-fill fs-3" style="color: var(--strawberry-dark);"></i>
                    </div>
                </div>
                <h6 class="text-secondary fw-semibold mb-1 uppercase tracking-wider small">Total Jenis Aset</h6>
                <h2 class="display-5 fw-bold m-0" style="color: var(--strawberry-dark);">
                    <?= htmlspecialchars($stats['total_jenis']); ?>
                </h2>
                <p class="text-muted extra-small mt-2 mb-0" style="font-size: 0.75rem;">
                    Total keseluruhan unit: <b><?= htmlspecialchars($stats['total_qty']); ?></b> barang
                </p>
            </div>
        </div>

        <!-- Card 2: Kondisi Baik (Matcha Green Theme) -->
        <div class="col-12 col-md-4">
            <div class="card h-100 border-0 rounded-4 shadow-sm p-4 text-center transition-card" 
                 style="background-color: var(--matcha-light); border-bottom: 5px solid var(--matcha-medium) !important;">
                <div class="d-flex justify-content-center align-items-center mb-3">
                    <div class="rounded-circle p-3 bg-white shadow-sm d-flex justify-content-center align-items-center" 
                         style="width: 60px; height: 60px;">
                        <i class="bi bi-shield-fill-check fs-3" style="color: var(--matcha-dark);"></i>
                    </div>
                </div>
                <h6 class="text-secondary fw-semibold mb-1 uppercase tracking-wider small">Aset Kondisi Baik</h6>
                <h2 class="display-5 fw-bold m-0" style="color: var(--matcha-dark);">
                    <?= htmlspecialchars($stats['baik_qty']); ?>
                </h2>
                <p class="text-muted extra-small mt-2 mb-0" style="font-size: 0.75rem;">
                    Aset dalam keadaan prima & siap pakai
                </p>
            </div>
        </div>

        <!-- Card 3: Kondisi Rusak (Subtle Pastel Soft Red Theme) -->
        <div class="col-12 col-md-4">
            <div class="card h-100 border-0 rounded-4 shadow-sm p-4 text-center transition-card" 
                 style="background-color: #FFEBEE; border-bottom: 5px solid #EF9A9A !important;">
                <div class="d-flex justify-content-center align-items-center mb-3">
                    <div class="rounded-circle p-3 bg-white shadow-sm d-flex justify-content-center align-items-center" 
                         style="width: 60px; height: 60px;">
                        <i class="bi bi-exclamation-triangle-fill fs-3" style="color: #C62828;"></i>
                    </div>
                </div>
                <h6 class="text-secondary fw-semibold mb-1 uppercase tracking-wider small">Aset Kondisi Rusak</h6>
                <h2 class="display-5 fw-bold m-0" style="color: #C62828;">
                    <?= htmlspecialchars($stats['rusak_qty']); ?>
                </h2>
                <p class="text-muted extra-small mt-2 mb-0" style="font-size: 0.75rem;">
                    Memerlukan perbaikan / pemeliharaan segera
                </p>
            </div>
        </div>

    </div>

    <!-- Row Petunjuk Penggunaan -->
    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card border-0 rounded-4 shadow-sm p-4 bg-white">
                <h5 class="fw-bold text-dark mb-3">
                    <i class="bi bi-info-circle text-success me-2"></i>Petunjuk Singkat Aplikasi
                </h5>
                <ul class="text-secondary mb-0 ps-3 small" style="line-height: 1.8;">
                    <li>Gunakan menu navigasi di bilah samping (sidebar) untuk mengakses seluruh fitur.</li>
                    <li>Pada menu <b>Kelola Aset</b>, Anda dapat melakukan manipulasi data secara menyeluruh seperti menambah, memperbarui, mencari, dan menghapus data aset.</li>
                    <li>Anda juga dapat langsung mengekspor laporan inventaris ke format <b>PDF (FPDF)</b> atau <b>Excel</b> secara instan melalui tombol aksi yang telah kami sediakan di atas tabel kelola data.</li>
                </ul>
            </div>
        </div>
        <div class="col-12 col-lg-4 mt-4 mt-lg-0">
            <div class="card border-0 rounded-4 shadow-sm p-4 bg-white text-center">
                <h6 class="text-secondary fw-semibold mb-2">Pemeriksa Sistem Aktif</h6>
                <div class="my-2">
                    <span class="badge rounded-pill px-3 py-2 text-success" style="background-color: var(--matcha-light);">
                        <i class="bi bi-patch-check-fill me-1"></i> Terverifikasi Keaslian
                    </span>
                </div>
                <p class="text-dark fw-bold mb-1 mt-2"><?= htmlspecialchars($_SESSION['nama_lengkap']); ?></p>
                <p class="text-secondary m-0 small">NIM. 251011700828</p>
            </div>
        </div>
    </div>
</div>

<style>
    /* Transisi visual halus pada card dashboard */
    .transition-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .transition-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05) !important;
    }
</style>