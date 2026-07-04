<?php
// ====================================================================
// KONEKSI DATABASE MENGGUNAKAN PDO (PHP DATA OBJECTS)
// ====================================================================
// File       : config/database.php
// Kelas      : Database
// Deskripsi  : Menangani pembuatan koneksi aman ke database MySQL
//              menggunakan driver PDO dengan prepared statement support.
// ====================================================================

class Database {
    // Atribut untuk kredensial database
    private $host = 'localhost';
    private $db_name = 'db_uas_251011700828';
    private $username = 'root';
    private $password = ''; // Kosongkan jika menggunakan XAMPP bawaan standar, sesuaikan jika memakai Laragon/MAMP
    private $conn;

    /**
     * Mengambil instansi koneksi database PDO
     * @return PDO|null
     */
    public function getConnection() {
        $this->conn = null;

        try {
            // Melakukan koneksi dengan DNS MySQL dan pengaturan Charset UTF-8
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4";
            
            // Konfigurasi opsi PDO untuk keamanan dan penanganan error
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Melempar exception jika ada query/koneksi error
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Hasil fetch dibaca sebagai array asosiatif secara default
                PDO::ATTR_EMULATE_PREPARES   => false,                  // Menonaktifkan emulasi prepared statement untuk keamanan ekstra SQL Injection
            ];

            // Instansiasi objek PDO
            $this->conn = new PDO($dsn, $this->username, $this->password, $options);
            
        } catch (PDOException $exception) {
            // Jika koneksi gagal, hentikan proses dan tampilkan pesan kesalahan yang ramah
            die("Koneksi Database Gagal: " . $exception->getMessage());
        }

        return $this->conn;
    }
}