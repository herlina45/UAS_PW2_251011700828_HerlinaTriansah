<?php
// ====================================================================
// VIEW FORM TAMBAH DATA ASET BARANG BARU
// ====================================================================
// File       : app/views/aset/create.php
// Deskripsi  : Menampilkan form entri data baru beserta preview foto fisik.
// ====================================================================
?>

<div class="container-fluid p-0">
    <!-- Judul & Navigasi Kembali -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold text-dark m-0">
                <i class="bi bi-plus-circle-fill text-success me-2" style="color: var(--matcha-dark) !important;"></i>Tambah Aset Baru
            </h4>
            <p class="text-secondary m-0 small mt-1">Lengkapi data di bawah ini untuk menambahkan aset ke dalam inventaris.</p>
        </div>
        <a href="index.php?page=aset" class="btn btn-outline-secondary border-2 rounded-3 px-3 py-2 d-flex align-items-center gap-2 btn-back" style="font-size: 0.9rem; font-weight: 500;">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar
        </a>
    </div>

    <!-- Form Utama -->
    <form action="index.php?page=aset&action=store" method="POST" enctype="multipart/form-data" autocomplete="off">
        <div class="row g-4">
            
            <!-- Kolom Kiri: Unggah Foto Fisik & Preview Instan -->
            <div class="col-12 col-lg-4">
                <div class="card border-0 rounded-4 shadow-sm p-4 text-center bg-white h-100">
                    <h6 class="fw-bold text-dark mb-3 text-start">Foto Kondisi Fisik</h6>
                    
                    <!-- Area Preview Gambar -->
                    <div class="preview-container d-flex align-items-center justify-content-center mb-4 position-relative border border-2 border-dashed rounded-4 p-3 bg-light overflow-hidden" 
                         style="height: 240px; border-color: var(--matcha-medium) !important;">
                        
                        <!-- Placeholder Sebelum Diunggah -->
                        <div id="uploadPlaceholder" class="text-center">
                            <i class="bi bi-cloud-arrow-up-fill text-success fs-1 mb-2" style="color: var(--matcha-dark) !important;"></i>
                            <p class="text-secondary small m-0">Belum ada foto terpilih</p>
                            <span class="text-muted extra-small" style="font-size: 0.75rem;">Format: JPG, JPEG, PNG (Maks 2MB)</span>
                        </div>
                        
                        <!-- Elemen Image Preview -->
                        <img id="imagePreview" src="#" alt="Pratinjau Foto" class="img-fluid rounded-3 object-fit-cover d-none w-100 h-100">
                    </div>
                    
                    <!-- Input File Tersembunyi tapi di-Style Cantik -->
                    <label for="fotoInput" class="btn btn-outline-success border-2 rounded-pill w-100 py-2 fw-semibold btn-upload" style="color: var(--matcha-dark); border-color: var(--matcha-medium);">
                        <i class="bi bi-camera me-1"></i> Pilih Foto Berkas
                    </label>
                    <input type="file" id="fotoInput" name="foto" class="d-none" accept="image/png, image/jpeg, image/jpg" onchange="previewSelectedImage(event)">
                </div>
            </div>

            <!-- Kolom Kanan: Detail Informasi Barang -->
            <div class="col-12 col-lg-8">
                <div class="card border-0 rounded-4 shadow-sm p-4 bg-white">
                    <h6 class="fw-bold text-dark mb-4 pb-2 border-bottom">Detail Informasi Aset</h6>
                    
                    <div class="row g-3">
                        <!-- Kode Aset -->
                        <div class="col-12 col-md-6">
                            <label for="kode_aset" class="form-label small text-secondary fw-medium">Kode Aset / Inventaris</label>
                            <input type="text" class="form-control" id="kode_aset" name="kode_aset" 
                                   placeholder="Contoh: AST-XXXXXXXX" required style="border-radius: 12px; border-width: 1.5px;">
                        </div>

                        <!-- Kategori Aset -->
                        <div class="col-12 col-md-6">
                            <label for="kategori" class="form-label small text-secondary fw-medium">Kategori</label>
                            <select class="form-select" id="kategori" name="kategori" required style="border-radius: 12px; border-width: 1.5px;">
                                <option value="" disabled selected>-- Pilih Kategori --</option>
                                <option value="Elektronik">Elektronik</option>
                                <option value="Mebel">Mebel</option>
                                <option value="Kendaraan">Kendaraan</option>
                                <option value="Peralatan Kantor">Peralatan Kantor</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>

                        <!-- Nama Barang -->
                        <div class="col-12">
                            <label for="nama_barang" class="form-label small text-secondary fw-medium">Nama Barang / Aset</label>
                            <input type="text" class="form-control" id="nama_barang" name="nama_barang" 
                                   placeholder="Masukkan nama lengkap barang..." required style="border-radius: 12px; border-width: 1.5px;">
                        </div>

                        <!-- Jumlah Unit -->
                        <div class="col-12 col-md-6">
                            <label for="jumlah" class="form-label small text-secondary fw-medium">Jumlah Unit (Qty)</label>
                            <input type="number" class="form-control" id="jumlah" name="jumlah" 
                                   min="1" placeholder="Min. 1" required style="border-radius: 12px; border-width: 1.5px;">
                        </div>

                        <!-- Tanggal Perolehan -->
                        <div class="col-12 col-md-6">
                            <label for="tgl_perolehan" class="form-label small text-secondary fw-medium">Tanggal Perolehan</label>
                            <input type="date" class="form-control" id="tgl_perolehan" name="tgl_perolehan" 
                                   required style="border-radius: 12px; border-width: 1.5px;">
                        </div>

                        <!-- Kondisi Fisik Aset (Radio modern) -->
                        <div class="col-12 mt-4">
                            <label class="form-label small text-secondary fw-medium d-block mb-3">Kondisi Fisik Saat Ini</label>
                            <div class="d-flex gap-4">
                                <!-- Kondisi Baik -->
                                <div class="form-check custom-radio-wrapper">
                                    <input class="form-check-input d-none" type="radio" name="kondisi" id="kondisiBaik" value="Baik" checked>
                                    <label class="form-check-label custom-radio-label rounded-4 px-4 py-3 d-flex align-items-center gap-2" for="kondisiBaik">
                                        <i class="bi bi-check-circle-fill fs-5 text-success"></i>
                                        <div>
                                            <span class="d-block fw-bold text-dark">Baik</span>
                                            <span class="text-muted extra-small" style="font-size: 0.75rem;">Siap beroperasi</span>
                                        </div>
                                    </label>
                                </div>
                                <!-- Kondisi Rusak -->
                                <div class="form-check custom-radio-wrapper">
                                    <input class="form-check-input d-none" type="radio" name="kondisi" id="kondisiRusak" value="Rusak">
                                    <label class="form-check-label custom-radio-label rounded-4 px-4 py-3 d-flex align-items-center gap-2" for="kondisiRusak">
                                        <i class="bi bi-exclamation-triangle-fill fs-5 text-danger"></i>
                                        <div>
                                            <span class="d-block fw-bold text-dark">Rusak</span>
                                            <span class="text-muted extra-small" style="font-size: 0.75rem;">Butuh perbaikan</span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Garis Batas & Tombol Form -->
                    <div class="mt-5 pt-3 border-top d-flex justify-content-end gap-3">
                        <button type="reset" class="btn btn-light border-2 rounded-pill px-4 text-secondary py-2" onclick="resetFormPreview()" style="font-weight: 500;">
                            Atur Ulang
                        </button>
                        <button type="submit" class="btn btn-matcha rounded-pill px-5 py-2">
                            <i class="bi bi-save me-1"></i> Simpan Data Baru
                        </button>
                    </div>

                </div>
            </div>

        </div>
    </form>
</div>

<script>
    /**
     * Menangani preview instan file gambar saat dipilih
     */
    function previewSelectedImage(event) {
        const fileInput = event.target;
        const placeholder = document.getElementById('uploadPlaceholder');
        const preview = document.getElementById('imagePreview');

        if (fileInput.files && fileInput.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('d-none');
                placeholder.classList.add('d-none');
            }

            reader.readAsDataURL(fileInput.files[0]);
        }
    }

    /**
     * Mengatur ulang visual upload area saat tombol reset ditekan
     */
    function resetFormPreview() {
        document.getElementById('imagePreview').classList.add('d-none');
        document.getElementById('uploadPlaceholder').classList.remove('d-none');
    }
</script>

<style>
    /* Desain Input & Dropdown */
    .form-control:focus, .form-select:focus {
        border-color: var(--matcha-medium) !important;
        box-shadow: 0 0 0 0.25rem rgba(129, 199, 132, 0.15) !important;
    }

    /* Efek Tombol Kembali */
    .btn-back:hover {
        background-color: var(--bg-soft-grey);
        color: #333333 !important;
    }

    /* Upload button hover */
    .btn-upload:hover {
        background-color: var(--matcha-light) !important;
        color: var(--matcha-dark) !important;
        transform: translateY(-1px);
    }

    /* Custom Radio Buttons Modern bertema Pastel */
    .custom-radio-wrapper {
        padding-left: 0;
        cursor: pointer;
    }
    .custom-radio-label {
        border: 2px solid #E0E0E0;
        background-color: #FFFFFF;
        transition: all 0.25s ease;
        display: inline-block;
        min-width: 200px;
        cursor: pointer;
    }
    .custom-radio-label:hover {
        border-color: var(--matcha-medium);
        background-color: var(--bg-soft-grey);
    }
    
    /* Keadaan Saat Radio Terpilih (Checked) */
    .form-check-input:checked + .custom-radio-label {
        border-color: var(--matcha-medium) !important;
        background-color: var(--matcha-light) !important;
        box-shadow: 0 4px 10px rgba(46, 125, 50, 0.08);
    }

    /* Penyelarasan khusus radio 'Rusak' saat terpilih */
    .form-check-input:checked + #kondisiRusak + .custom-radio-label {
        border-color: #EF9A9A !important;
        background-color: #FFEBEE !important;
    }
</style>