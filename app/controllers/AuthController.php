<?php
// ====================================================================
// CONTROLLER OTENTIKASI (LOGIN, REGISTER, & LOGOUT)
// ====================================================================
// File       : app/controllers/AuthController.php
// Kelas      : AuthController
// Deskripsi  : Mengendalikan logika autentikasi administrator sistem.
// ====================================================================

class AuthController {
    // Menyimpan instansi model user
    private $userModel;

    /**
     * Konstruktor kelas AuthController
     * @param PDO $db Objek koneksi database PDO
     */
    public function __construct($db) {
        // Melakukan instansiasi UserModel dengan mengoper koneksi database
        require_once 'app/models/UserModel.php';
        $this->userModel = new UserModel($db);
    }

    /**
     * Menangani proses login admin
     */
    public function login() {
        // Jika user sudah login, arahkan langsung ke halaman dashboard
        if (isset($_SESSION['user_id'])) {
            header("Location: index.php?page=dashboard");
            exit();
        }

        // Memproses request POST dari form login
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username']);
            $password = $_POST['password'];

            // Validasi input kosong
            if (empty($username) || empty($password)) {
                $_SESSION['error_msg'] = "Username dan Password wajib diisi!";
                header("Location: index.php?page=auth&action=login");
                exit();
            }

            try {
                // Mencari user berdasarkan username di database
                $user = $this->userModel->getByUsername($username);

                // Memverifikasi apakah user ditemukan dan password-nya cocok
                if ($user && password_verify($password, $user['password'])) {
                    // Menyimpan data kredensial penting ke dalam Session
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
                    
                    // Flash message sukses login
                    $_SESSION['success_msg'] = "Selamat datang kembali, " . $user['nama_lengkap'] . "!";
                    
                    // Redirect ke dashboard utama
                    header("Location: index.php?page=dashboard");
                    exit();
                } else {
                    // Jika login gagal (username tidak terdaftar atau password salah)
                    $_SESSION['error_msg'] = "Username atau Password yang Anda masukkan salah!";
                    header("Location: index.php?page=auth&action=login");
                    exit();
                }
            } catch (Exception $e) {
                $_SESSION['error_msg'] = "Terjadi kesalahan sistem: " . $e->getMessage();
                header("Location: index.php?page=auth&action=login");
                exit();
            }
        }

        // Jika request berupa GET, render (tampilkan) file view login
        require_once 'app/views/auth/login.php';
    }

    /**
     * Menangani proses registrasi akun admin baru
     */
    public function register() {
        // Jika user sudah login, batasi hak akses dan arahkan langsung ke dashboard
        if (isset($_SESSION['user_id'])) {
            header("Location: index.php?page=dashboard");
            exit();
        }

        // Memproses request POST dari form registrasi
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama_lengkap = trim($_POST['nama_lengkap']);
            $username = trim($_POST['username']);
            $password = $_POST['password'];
            $konfirmasi_password = $_POST['konfirmasi_password'];

            // 1. Validasi input wajib terisi semua
            if (empty($nama_lengkap) || empty($username) || empty($password) || empty($konfirmasi_password)) {
                $_SESSION['error_msg'] = "Seluruh kolom formulir pendaftaran wajib diisi!";
                header("Location: index.php?page=auth&action=register");
                exit();
            }

            // 2. Validasi kesesuaian isi password dan konfirmasi password
            if ($password !== $konfirmasi_password) {
                $_SESSION['error_msg'] = "Konfirmasi password tidak cocok dengan password utama!";
                header("Location: index.php?page=auth&action=register");
                exit();
            }

            try {
                // 3. Memastikan username belum pernah digunakan oleh admin lain
                $existing_user = $this->userModel->getByUsername($username);
                if ($existing_user) {
                    $_SESSION['error_msg'] = "Username '" . htmlspecialchars($username) . "' sudah terdaftar. Silakan gunakan username lain!";
                    header("Location: index.php?page=auth&action=register");
                    exit();
                }

                // 4. Mendaftarkan user ke database melalui UserModel
                $result = $this->userModel->register($username, $password, $nama_lengkap);

                if ($result) {
                    $_SESSION['success_msg'] = "Pendaftaran berhasil! Silakan login menggunakan akun baru Anda.";
                    header("Location: index.php?page=auth&action=login");
                    exit();
                } else {
                    $_SESSION['error_msg'] = "Gagal mendaftarkan akun. Silakan coba beberapa saat lagi.";
                    header("Location: index.php?page=auth&action=register");
                    exit();
                }
            } catch (Exception $e) {
                $_SESSION['error_msg'] = "Gagal mendaftarkan user: " . $e->getMessage();
                header("Location: index.php?page=auth&action=register");
                exit();
            }
        }

        // Jika request berupa GET, tampilkan halaman view register
        require_once 'app/views/auth/register.php';
    }

    /**
     * Menghancurkan session dan mengeluarkan admin dari sistem
     */
    public function logout() {
        // Membersihkan seluruh data array $_SESSION
        $_SESSION = array();

        // Jika session menggunakan cookie, hapus cookie session tersebut
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // Menghancurkan session di server secara total
        session_destroy();

        // Memulai session baru hanya untuk mengirimkan pesan penutup sukses logout
        session_start();
        $_SESSION['success_msg'] = "Anda berhasil keluar dari sistem. Terima kasih!";
        
        // Mengalihkan pengguna kembali ke form login
        header("Location: index.php?page=auth&action=login");
        exit();
    }
}