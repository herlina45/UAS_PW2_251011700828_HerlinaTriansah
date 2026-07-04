<?php
// ====================================================================
// CONTROLLER DASHBOARD (HALAMAN UTAMA STATISTIK)
// ====================================================================
// File       : app/controllers/DashboardController.php
// Kelas      : DashboardController
// Deskripsi  : Mengelola halaman dashboard dan ringkasan statistik aset.
// ====================================================================

class DashboardController {
    private $db;
    private $asetModel;

    /**
     * Konstruktor kelas DashboardController
     * @param PDO $db Objek koneksi database PDO
     */
    public function __construct($db) {
        $this->db = $db;
        // Melakukan instansiasi AsetModel untuk mengambil statistik data
        require_once 'app/models/AsetModel.php';
        $this->asetModel = new AsetModel($this->db);
    }

    /**
     * Menampilkan halaman dashboard utama
     */
    public function index() {
        // Proteksi Halaman: Pastikan admin sudah login, jika belum arahkan ke login
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error_msg'] = "Silakan login terlebih dahulu untuk mengakses sistem!";
            header("Location: index.php?page=auth&action=login");
            exit();
        }

        try {
            // Mengambil ringkasan statistik dari database
            $stats = $this->asetModel->getStatsCount();
        } catch (Exception $e) {
            // Jika terjadi kegagalan pengambilan data, siapkan statistik bernilai nol
            $stats = [
                'total_jenis' => 0,
                'total_qty' => 0,
                'baik_qty' => 0,
                'rusak_qty' => 0
            ];
            $_SESSION['error_msg'] = "Gagal memuat statistik sistem: " . $e->getMessage();
        }

        // Menentukan judul halaman dinamis untuk header
        $page_title = "Dashboard - Sistem Informasi Aset Barang";

        // Merender view dashboard dengan menyertakan layouts header dan footer
        require_once 'app/views/layouts/header.php';
        require_once 'app/views/dashboard/index.php';
        require_once 'app/views/layouts/footer.php';
    }
}