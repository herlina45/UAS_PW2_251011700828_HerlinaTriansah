<?php
// ====================================================================
// MODEL USER (OTENTIKASI & REGISTRASI ADMIN)
// ====================================================================
// File       : app/models/UserModel.php
// Kelas      : UserModel
// Deskripsi  : Menangani manipulasi dan query data pada tabel `users`.
// ====================================================================

class UserModel {
    // Menyimpan instansi koneksi database PDO
    private $db;
    // Nama tabel yang dikelola
    private $table_name = "users";

    /**
     * Konstruktor kelas UserModel
     * @param PDO $db Objek koneksi database PDO
     */
    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Mencari dan mengambil data user berdasarkan username (untuk Login)
     * @param string $username
     * @return array|false Mengembalikan array data user jika ditemukan, atau false jika tidak ditemukan
     */
    public function getByUsername($username) {
        // Query menggunakan Prepared Statement untuk keamanan dari SQL Injection
        $query = "SELECT id, username, password, nama_lengkap, created_at 
                  FROM " . $this->table_name . " 
                  WHERE username = :username 
                  LIMIT 0,1";

        try {
            // Mempersiapkan query
            $stmt = $this->db->prepare($query);

            // Sanitasi input demi keamanan tambahan
            $username = htmlspecialchars(strip_tags($username));

            // Mengikat parameter query
            $stmt->bindParam(':username', $username);

            // Mengeksekusi query
            $stmt->execute();

            // Mengambil hasil fetch baris pertama sebagai array asosiatif
            return $stmt->fetch();
        } catch (PDOException $exception) {
            // Melempar error jika terjadi masalah pada query
            throw new Exception("Gagal mencari user: " . $exception->getMessage());
        }
    }

    /**
     * Mendaftarkan akun admin baru ke dalam tabel `users`
     * @param string $username
     * @param string $password
     * @param string $nama_lengkap
     * @return bool True jika pendaftaran berhasil, False jika gagal
     */
    public function register($username, $password, $nama_lengkap) {
        // Query insert menggunakan Prepared Statement
        $query = "INSERT INTO " . $this->table_name . " 
                  (username, password, nama_lengkap, created_at) 
                  VALUES (:username, :password, :nama_lengkap, CURRENT_TIMESTAMP)";

        try {
            // Mempersiapkan query insert
            $stmt = $this->db->prepare($query);

            // Melakukan sanitasi input form
            $username = htmlspecialchars(strip_tags($username));
            $nama_lengkap = htmlspecialchars(strip_tags($nama_lengkap));

            // Melakukan enkripsi password yang aman (Bcrypt) sebelum disimpan
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Mengikat parameter query dengan nilai input
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $hashed_password);
            $stmt->bindParam(':nama_lengkap', $nama_lengkap);

            // Mengeksekusi query insert ke database
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $exception) {
            // Melempar error jika pendaftaran gagal akibat username duplikat atau kendala sistem lainnya
            throw new Exception("Gagal mendaftarkan user baru: " . $exception->getMessage());
        }
    }
}