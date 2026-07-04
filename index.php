<?php
// ====================================================================
// FRONT CONTROLLER & ROUTER UTAMA
// ====================================================================
// File       : index.php
// Deskripsi  : Menangani semua request aplikasi, inisialisasi session,
//              koneksi database global, dan routing controller.
// ====================================================================

// 1. Memulai session global untuk autentikasi dan flash message
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Memuat berkas konfigurasi database
require_once 'config/database.php';

try {
    // Membangun instansi koneksi database menggunakan PDO
    $database = new Database();
    $db = $database->getConnection();
} catch (Exception $e) {
    // Menampilkan pesan kesalahan jika database gagal terkoneksi
    die("Gagal memuat sistem koneksi database: " . $e->getMessage());
}

// 3. Menangkap parameter routing dari URL
$page = isset($_GET['page']) ? trim($_GET['page']) : 'dashboard';
$action = isset($_GET['action']) ? trim($_GET['action']) : 'index';

// 4. Jalur Routing Engine (Front Controller Switch Case)
switch ($page) {
    case 'auth':
        // Memuat controller autentikasi
        require_once 'app/controllers/AuthController.php';
        $controller = new AuthController($db);

        if ($action === 'login') {
            $controller->login();
        } elseif ($action === 'register') {
            $controller->register();
        } elseif ($action === 'logout') {
            $controller->logout();
        } else {
            // Default jika action auth tidak valid, arahkan ke login
            header("Location: index.php?page=auth&action=login");
            exit();
        }
        break;

    case 'dashboard':
        // Memuat controller dashboard statistik
        require_once 'app/controllers/DashboardController.php';
        $controller = new DashboardController($db);
        $controller->index();
        break;

    case 'aset':
        // Memuat controller manajemen aset barang
        require_once 'app/controllers/AsetController.php';
        $controller = new AsetController($db);

        // Routing sub-aksi (CRUD, Upload, & Export)
        if ($action === 'create') {
            $controller->create();
        } elseif ($action === 'store') {
            $controller->store();
        } elseif ($action === 'edit') {
            $controller->edit();
        } elseif ($action === 'update') {
            $controller->update();
        } elseif ($action === 'delete') {
            $controller->delete();
        } elseif ($action === 'export_pdf') {
            $controller->exportPdf();
        } elseif ($action === 'export_excel') {
            $controller->exportExcel();
        } else {
            // Default memuat tabel index utama aset
            $controller->index();
        }
        break;

    default:
        // Jika parameter page tidak dikenali, arahkan otomatis ke dashboard
        header("Location: index.php?page=dashboard");
        exit();
        break;
}