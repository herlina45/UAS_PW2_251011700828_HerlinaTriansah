<?php
// ====================================================================
// CONTROLLER KELOLA ASET BARANG (CRUD & EXPORT LAPORAN)
// ====================================================================
// File       : app/controllers/AsetController.php
// Kelas      : AsetController
// Deskripsi  : Mengatur seluruh manipulasi aset barang, unggah berkas,
//              serta cetak laporan PDF dan Excel secara dinamis.
// ====================================================================

class AsetController {
    private $db;
    private $asetModel;

    /**
     * Konstruktor kelas AsetController
     * @param PDO $db Objek koneksi database PDO
     */
    public function __construct($db) {
        $this->db = $db;
        // Instansiasi model AsetModel untuk interaksi database
        require_once 'app/models/AsetModel.php';
        $this->asetModel = new AsetModel($this->db);
    }

    /**
     * Mengamankan akses halaman (Hanya admin login yang boleh masuk)
     */
    private function checkAuth() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error_msg'] = "Silakan login terlebih dahulu untuk mengakses fitur kelola aset!";
            header("Location: index.php?page=auth&action=login");
            exit();
        }
    }

    /**
     * Menampilkan daftar seluruh aset barang (Read & Search)
     */
    public function index() {
        $this->checkAuth();

        // Mengambil keyword pencarian dari parameter GET jika ada
        $keyword = isset($_GET['search']) ? trim($_GET['search']) : null;

        try {
            // Mengambil data dari model (terfilter jika keyword terisi)
            $aset_list = $this->asetModel->getAll($keyword);
        } catch (Exception $e) {
            $aset_list = [];
            $_SESSION['error_msg'] = "Gagal memuat data aset: " . $e->getMessage();
        }

        $page_title = "Kelola Aset Barang - SIMBAR";
        
        // Merender view halaman utama daftar aset
        require_once 'app/views/layouts/header.php';
        require_once 'app/views/aset/index.php';
        require_once 'app/views/layouts/footer.php';
    }

    /**
     * Menampilkan formulir tambah aset baru
     */
    public function create() {
        $this->checkAuth();

        $page_title = "Tambah Aset Barang Baru";

        // Merender view form input
        require_once 'app/views/layouts/header.php';
        require_once 'app/views/aset/create.php';
        require_once 'app/views/layouts/footer.php';
    }

    /**
     * Memproses penyimpanan data aset baru (Validasi, Upload, & Insert)
     */
    public function store() {
        $this->checkAuth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $kode_aset = trim($_POST['kode_aset']);
            $nama_barang = trim($_POST['nama_barang']);
            $kategori = $_POST['kategori'];
            $jumlah = $_POST['jumlah'];
            $kondisi = $_POST['kondisi'];
            $tgl_perolehan = $_POST['tgl_perolehan'];

            // 1. Validasi Input Dasar
            if (empty($kode_aset) || empty($nama_barang) || empty($kategori) || empty($jumlah) || empty($kondisi) || empty($tgl_perolehan)) {
                $_SESSION['error_msg'] = "Semua kolom input formulir wajib diisi!";
                header("Location: index.php?page=aset&action=create");
                exit();
            }

            // 2. Logika Penanganan Upload Foto Fisik Barang
            $foto_name = null;
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {
                $file = $_FILES['foto'];
                $allowed_extensions = ['jpg', 'jpeg', 'png'];
                $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $file_size = $file['size'];

                // Validasi Ekstensi File
                if (!in_array($file_ext, $allowed_extensions)) {
                    $_SESSION['error_msg'] = "Ekstensi file tidak valid! Gunakan hanya file JPG, JPEG, atau PNG.";
                    header("Location: index.php?page=aset&action=create");
                    exit();
                }

                // Validasi Ukuran File (Maksimal 2MB)
                if ($file_size > 2 * 1024 * 1024) {
                    $_SESSION['error_msg'] = "Ukuran file terlalu besar! Maksimal ukuran file adalah 2 MB.";
                    header("Location: index.php?page=aset&action=create");
                    exit();
                }

                // Pastikan folder uploads tersedia
                if (!is_dir('uploads')) {
                    mkdir('uploads', 0755, true);
                }

                // Generate nama file unik untuk menghindari tabrakan data di server
                $foto_name = time() . '_' . bin2hex(random_bytes(4)) . '.' . $file_ext;
                $target_path = 'uploads/' . $foto_name;

                // Memindahkan file dari folder sementara ke folder uploads
                if (!move_uploaded_file($file['tmp_name'], $target_path)) {
                    $_SESSION['error_msg'] = "Gagal mengunggah berkas foto ke server!";
                    header("Location: index.php?page=aset&action=create");
                    exit();
                }
            }

            // Menyusun array asosiatif data input
            $data = [
                'kode_aset' => $kode_aset,
                'nama_barang' => $nama_barang,
                'kategori' => $kategori,
                'jumlah' => $jumlah,
                'kondisi' => $kondisi,
                'foto' => $foto_name,
                'tgl_perolehan' => $tgl_perolehan
            ];

            try {
                // Menyimpan ke database via model
                $result = $this->asetModel->insert($data);

                if ($result) {
                    $_SESSION['success_msg'] = "Aset baru berhasil ditambahkan!";
                    header("Location: index.php?page=aset");
                    exit();
                } else {
                    $_SESSION['error_msg'] = "Gagal menyimpan data aset ke database.";
                    header("Location: index.php?page=aset&action=create");
                    exit();
                }
            } catch (Exception $e) {
                // Jika terjadi duplikasi kode aset unik atau kendala SQL lainnya
                $_SESSION['error_msg'] = "Kesalahan Sistem: " . $e->getMessage();
                header("Location: index.php?page=aset&action=create");
                exit();
            }
        }
    }

    /**
     * Menampilkan formulir edit aset berdasarkan ID
     */
    public function edit() {
        $this->checkAuth();

        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        try {
            $aset = $this->asetModel->getById($id);
            if (!$aset) {
                $_SESSION['error_msg'] = "Data aset tidak ditemukan atau sudah dihapus!";
                header("Location: index.php?page=aset");
                exit();
            }
        } catch (Exception $e) {
            $_SESSION['error_msg'] = "Error mengambil data: " . $e->getMessage();
            header("Location: index.php?page=aset");
            exit();
        }

        $page_title = "Ubah Data Aset - " . htmlspecialchars($aset['nama_barang']);

        // Merender view form edit
        require_once 'app/views/layouts/header.php';
        require_once 'app/views/aset/edit.php';
        require_once 'app/views/layouts/footer.php';
    }

    /**
     * Memproses pembaruan data aset di database (Update)
     */
    public function update() {
        $this->checkAuth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id']);
            $kode_aset = trim($_POST['kode_aset']);
            $nama_barang = trim($_POST['nama_barang']);
            $kategori = $_POST['kategori'];
            $jumlah = $_POST['jumlah'];
            $kondisi = $_POST['kondisi'];
            $tgl_perolehan = $_POST['tgl_perolehan'];

            // 1. Validasi Input Dasar
            if (empty($kode_aset) || empty($nama_barang) || empty($kategori) || empty($jumlah) || empty($kondisi) || empty($tgl_perolehan)) {
                $_SESSION['error_msg'] = "Semua kolom input wajib diisi!";
                header("Location: index.php?page=aset&action=edit&id=" . $id);
                exit();
            }

            try {
                // Ambil data aset lama untuk mengecek keberadaan foto lama
                $old_aset = $this->asetModel->getById($id);
                if (!$old_aset) {
                    $_SESSION['error_msg'] = "Data aset tidak valid!";
                    header("Location: index.php?page=aset");
                    exit();
                }

                $foto_name = $old_aset['foto']; // Default menggunakan foto lama

                // 2. Mengecek apakah admin mengunggah berkas foto baru
                if (isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {
                    $file = $_FILES['foto'];
                    $allowed_extensions = ['jpg', 'jpeg', 'png'];
                    $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                    $file_size = $file['size'];

                    // Validasi Ekstensi
                    if (!in_array($file_ext, $allowed_extensions)) {
                        $_SESSION['error_msg'] = "Ubah Gagal! Format file foto harus berupa JPG, JPEG, atau PNG.";
                        header("Location: index.php?page=aset&action=edit&id=" . $id);
                        exit();
                    }

                    // Validasi Ukuran (Maksimal 2MB)
                    if ($file_size > 2 * 1024 * 1024) {
                        $_SESSION['error_msg'] = "Ubah Gagal! Ukuran file foto maksimal adalah 2 MB.";
                        header("Location: index.php?page=aset&action=edit&id=" . $id);
                        exit();
                    }

                    // Hapus file foto fisik yang lama dari server jika ada dan bukan file kosong
                    if (!empty($old_aset['foto']) && file_exists('uploads/' . $old_aset['foto'])) {
                        unlink('uploads/' . $old_aset['foto']);
                    }

                    // Upload file foto baru
                    $foto_name = time() . '_' . bin2hex(random_bytes(4)) . '.' . $file_ext;
                    move_uploaded_file($file['tmp_name'], 'uploads/' . $foto_name);
                }

                $data = [
                    'kode_aset' => $kode_aset,
                    'nama_barang' => $nama_barang,
                    'kategori' => $kategori,
                    'jumlah' => $jumlah,
                    'kondisi' => $kondisi,
                    'foto' => $foto_name,
                    'tgl_perolehan' => $tgl_perolehan
                ];

                $result = $this->asetModel->update($id, $data);

                if ($result) {
                    $_SESSION['success_msg'] = "Data aset berhasil diperbarui!";
                    header("Location: index.php?page=aset");
                    exit();
                } else {
                    $_SESSION['error_msg'] = "Tidak ada perubahan data yang disimpan.";
                    header("Location: index.php?page=aset&action=edit&id=" . $id);
                    exit();
                }
            } catch (Exception $e) {
                $_SESSION['error_msg'] = "Gagal memperbarui data: " . $e->getMessage();
                header("Location: index.php?page=aset&action=edit&id=" . $id);
                exit();
            }
        }
    }

    /**
     * Menghapus data aset secara permanen dari DB dan server (Delete)
     */
    public function delete() {
        $this->checkAuth();

        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

        try {
            $aset = $this->asetModel->getById($id);
            if ($aset) {
                // 1. Hapus berkas fisik gambar di server jika ada
                if (!empty($aset['foto']) && file_exists('uploads/' . $aset['foto'])) {
                    unlink('uploads/' . $aset['foto']);
                }

                // 2. Hapus baris data dari database
                $this->asetModel->delete($id);
                $_SESSION['success_msg'] = "Data aset beserta file foto berhasil dihapus permanen!";
            } else {
                $_SESSION['error_msg'] = "Aset tidak ditemukan atau sudah dihapus terlebih dahulu!";
            }
        } catch (Exception $e) {
            $_SESSION['error_msg'] = "Gagal menghapus aset: " . $e->getMessage();
        }

        header("Location: index.php?page=aset");
        exit();
    }

    /**
     * Mengekspor data aset ke dalam format PDF (FPDF)
     */
    public function exportPdf() {
        $this->checkAuth();

        // Ambil data aktif sesuai parameter pencarian jika ada
        $keyword = isset($_GET['search']) ? trim($_GET['search']) : null;
        
        try {
            $aset_list = $this->asetModel->getAll($keyword);
        } catch (Exception $e) {
            die("Gagal mengekstrak data untuk PDF: " . $e->getMessage());
        }

        // Menyertakan library FPDF secara aman
        if (!file_exists('libs/fpdf/fpdf.php')) {
            die("Library FPDF tidak ditemukan! Silakan letakkan berkas fpdf.php di folder libs/fpdf/.");
        }
        require_once 'libs/fpdf/fpdf.php';

        // Desain Custom PDF Laporan dengan Orientasi Landscape agar rapi
        $pdf = new FPDF('L', 'mm', 'A4');
        $pdf->AddPage();
        
        // --- HEADER LAPORAN (Strawberry Matcha Pink Theme) ---
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->SetTextColor(194, 24, 91); // Pastel Strawberry Pink
        $pdf->Cell(0, 10, 'LAPORAN DATA ASET BARANG', 0, 1, 'C');
        
        $pdf->SetFont('Arial', 'I', 11);
        $pdf->SetTextColor(46, 125, 50); // Matcha Green
        $pdf->Cell(0, 8, 'Sistem Informasi Aset - Universitas Pamulang', 0, 1, 'C');
        
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetTextColor(100, 100, 100);
        $pdf->Cell(0, 6, 'Dicetak oleh: Herlina Triansah (NIM: 251011700828) | Tanggal: ' . date('d-m-Y H:i'), 0, 1, 'C');
        $pdf->Ln(5);

        // Garis batas pemisah header
        $pdf->SetLineWidth(0.8);
        $pdf->SetDrawColor(194, 24, 91);
        $pdf->Line(10, 38, 287, 38);
        $pdf->Ln(5);

        // --- TABEL DATA ---
        // Header Kolom Tabel (Warna Latar Matcha Green)
        $pdf->SetFillColor(232, 245, 233); // #E8F5E9 Matcha Green Light
        $pdf->SetDrawColor(194, 24, 91);   // Pink Border
        $pdf->SetLineWidth(0.3);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetTextColor(46, 125, 50);   // Dark Green text

        // Lebar Kolom total 277 mm (A4 Landscape lebar area cetak ~277 mm)
        $pdf->Cell(10, 10, 'No', 1, 0, 'C', true);
        $pdf->Cell(45, 10, 'Kode Aset', 1, 0, 'C', true);
        $pdf->Cell(92, 10, 'Nama Barang / Aset', 1, 0, 'L', true);
        $pdf->Cell(45, 10, 'Kategori', 1, 0, 'C', true);
        $pdf->Cell(20, 10, 'Jumlah', 1, 0, 'C', true);
        $pdf->Cell(25, 10, 'Kondisi', 1, 0, 'C', true);
        $pdf->Cell(40, 10, 'Tgl Perolehan', 1, 1, 'C', true);

        // Data Row Tabel
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetTextColor(50, 50, 50); // Soft grey/black text
        
        $no = 1;
        foreach ($aset_list as $row) {
            // Zebra striping effect (ganti warna baris selang-seling agar estetis)
            $fill = ($no % 2 === 0) ? true : false;
            $pdf->SetFillColor(252, 228, 236); // #FCE4EC Strawberry Pink Light
            
            $pdf->Cell(10, 8, $no++, 1, 0, 'C', $fill);
            $pdf->Cell(45, 8, $row['kode_aset'], 1, 0, 'C', $fill);
            $pdf->Cell(92, 8, ' ' . $row['nama_barang'], 1, 0, 'L', $fill);
            $pdf->Cell(45, 8, $row['kategori'], 1, 0, 'C', $fill);
            $pdf->Cell(20, 8, $row['jumlah'], 1, 0, 'C', $fill);
            $pdf->Cell(25, 8, $row['kondisi'], 1, 0, 'C', $fill);
            $pdf->Cell(40, 8, date('d-m-Y', strtotime($row['tgl_perolehan'])), 1, 1, 'C', $fill);
        }

        // Tanda Tangan Penerima / Pemeriksa di kanan bawah
        $pdf->Ln(10);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(210);
        $pdf->Cell(67, 5, 'Tangerang Selatan, ' . date('d F Y'), 0, 1, 'C');
        $pdf->Cell(210);
        $pdf->Cell(67, 5, 'Pemeriksa Sistem,', 0, 1, 'C');
        $pdf->Ln(12);
        $pdf->SetFont('Arial', 'BU', 10);
        $pdf->Cell(210);
        $pdf->Cell(67, 5, 'Herlina Triansah', 0, 1, 'C');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(210);
        $pdf->Cell(67, 5, 'NIM. 251011700828', 0, 1, 'C');

        // Melakukan output stream PDF langsung diunduh
        $pdf->Output('I', 'Laporan_Aset_Barang_Herlina.pdf');
        exit();
    }

    /**
     * Mengekspor data aset ke dalam format Microsoft Excel (.xls)
     */
    public function exportExcel() {
        $this->checkAuth();

        // Ambil data aktif sesuai parameter pencarian jika ada
        $keyword = isset($_GET['search']) ? trim($_GET['search']) : null;
        
        try {
            $aset_list = $this->asetModel->getAll($keyword);
        } catch (Exception $e) {
            die("Gagal mengekstrak data untuk Excel: " . $e->getMessage());
        }

        // Konfigurasi Header agar browser mengenali file yang diunduh sebagai Microsoft Excel
        header("Content-Type: application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=Laporan_Aset_Barang_Herlina.xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        // Merender isi dokumen dalam bentuk HTML Table standard yang otomatis terkonversi di Excel
        ?>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <h3>LAPORAN DATA ASET BARANG</h3>
        <p>Sistem Informasi Aset - Universitas Pamulang</p>
        <p>Pemeriksa: <b>Herlina Triansah (NIM: 251011700828)</b> | Tanggal Cetak: <b><?= date('d-m-Y H:i') ?></b></p>
        
        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr style="background-color: #E8F5E9; color: #2E7D32; font-weight: bold;">
                    <th>No</th>
                    <th>Kode Aset</th>
                    <th>Nama Barang / Aset</th>
                    <th>Kategori</th>
                    <th>Jumlah</th>
                    <th>Kondisi</th>
                    <th>Tanggal Perolehan</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                foreach ($aset_list as $row): 
                    $bgColor = ($no % 2 === 0) ? '#FCE4EC' : '#FFFFFF';
                ?>
                    <tr style="background-color: <?= $bgColor ?>;">
                        <td align="center"><?= $no++ ?></td>
                        <!-- Menggunakan mso-number-format agar Excel mengenali sebagai text tanpa memunculkan tanda petik fisik -->
                        <td align="center" style="mso-number-format:'\@';"><?= htmlspecialchars($row['kode_aset']) ?></td>
                        <td><?= htmlspecialchars($row['nama_barang']) ?></td>
                        <td><?= htmlspecialchars($row['kategori']) ?></td>
                        <td align="center"><?= htmlspecialchars($row['jumlah']) ?></td>
                        <td align="center"><?= htmlspecialchars($row['kondisi']) ?></td>
                        <td align="center"><?= date('d-m-Y', strtotime($row['tgl_perolehan'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php
        exit();
    }
}