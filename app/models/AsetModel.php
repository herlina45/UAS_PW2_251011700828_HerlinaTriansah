<?php
// ====================================================================
// MODEL ASET BARANG (CRUD & STATISTIK DATA)
// ====================================================================
// File       : app/models/AsetModel.php
// Kelas      : AsetModel
// Deskripsi  : Menangani manipulasi dan query data pada tabel `aset_barang`.
// ====================================================================

class AsetModel {
    // Menyimpan instansi koneksi database PDO
    private $db;
    // Nama tabel yang dikelola oleh model ini
    private $table_name = "aset_barang";

    /**
     * Konstruktor kelas AsetModel
     * @param PDO $db Objek koneksi database PDO
     */
    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Mengambil seluruh data aset atau memfilter berdasarkan keyword pencarian
     * @param string|null $keyword Kata kunci pencarian (nama barang, kategori, atau kode aset)
     * @return array Array berisi daftar data aset barang
     */
    public function getAll($keyword = null) {
        // Query dasar untuk mengambil semua data diurutkan dari yang terbaru diinput
        $query = "SELECT id, kode_aset, nama_barang, kategori, jumlah, kondisi, foto, tgl_perolehan, created_at 
                  FROM " . $this->table_name;

        // Jika keyword pencarian dikirimkan, tambahkan kondisi filter LIKE
        if (!empty($keyword)) {
            $query .= " WHERE nama_barang LIKE :keyword 
                        OR kategori LIKE :keyword 
                        OR kode_aset LIKE :keyword";
        }
        
        $query .= " ORDER BY created_at DESC";

        try {
            $stmt = $this->db->prepare($query);

            if (!empty($keyword)) {
                // Menambahkan tanda wildcard (%) untuk pencarian parsial
                $search_term = "%" . $keyword . "%";
                $stmt->bindParam(':keyword', $search_term);
            }

            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $exception) {
            throw new Exception("Gagal mengambil data aset: " . $exception->getMessage());
        }
    }

    /**
     * Mengambil detail satu data aset berdasarkan ID primary key
     * @param int $id ID unik data aset
     * @return array|false Mengembalikan array data aset jika ada, atau false jika tidak ditemukan
     */
    public function getById($id) {
        $query = "SELECT id, kode_aset, nama_barang, kategori, jumlah, kondisi, foto, tgl_perolehan, created_at 
                  FROM " . $this->table_name . " 
                  WHERE id = :id 
                  LIMIT 0,1";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch();
        } catch (PDOException $exception) {
            throw new Exception("Gagal mengambil detail aset: " . $exception->getMessage());
        }
    }

    /**
     * Menyimpan data aset baru ke dalam tabel aset_barang
     * @param array $data Array asosiatif yang berisi data input form
     * @return bool True jika berhasil, False jika gagal
     */
    public function insert($data) {
        $query = "INSERT INTO " . $this->table_name . " 
                  (kode_aset, nama_barang, kategori, jumlah, kondisi, foto, tgl_perolehan, created_at) 
                  VALUES (:kode_aset, :nama_barang, :kategori, :jumlah, :kondisi, :foto, :tgl_perolehan, CURRENT_TIMESTAMP)";

        try {
            $stmt = $this->db->prepare($query);

            // Sanitasi input form agar terhindar dari celah XSS
            $kode_aset = htmlspecialchars(strip_tags($data['kode_aset']));
            $nama_barang = htmlspecialchars(strip_tags($data['nama_barang']));
            $kategori = htmlspecialchars(strip_tags($data['kategori']));
            $jumlah = intval($data['jumlah']);
            $kondisi = htmlspecialchars(strip_tags($data['kondisi']));
            $foto = htmlspecialchars(strip_tags($data['foto']));
            $tgl_perolehan = htmlspecialchars(strip_tags($data['tgl_perolehan']));

            // Mengikat parameter query dengan nilai yang sudah disanitasi
            $stmt->bindParam(':kode_aset', $kode_aset);
            $stmt->bindParam(':nama_barang', $nama_barang);
            $stmt->bindParam(':kategori', $kategori);
            $stmt->bindParam(':jumlah', $jumlah, PDO::PARAM_INT);
            $stmt->bindParam(':kondisi', $kondisi);
            $stmt->bindParam(':foto', $foto);
            $stmt->bindParam(':tgl_perolehan', $tgl_perolehan);

            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $exception) {
            throw new Exception("Gagal menyimpan aset baru: " . $exception->getMessage());
        }
    }

    /**
     * Memperbarui data aset yang sudah ada berdasarkan ID primary key
     * @param int $id ID unik data aset yang akan diubah
     * @param array $data Array asosiatif berisi data input form baru
     * @return bool True jika berhasil, False jika gagal
     */
    public function update($id, $data) {
        $query = "UPDATE " . $this->table_name . " 
                  SET kode_aset = :kode_aset, 
                      nama_barang = :nama_barang, 
                      kategori = :kategori, 
                      jumlah = :jumlah, 
                      kondisi = :kondisi, 
                      foto = :foto, 
                      tgl_perolehan = :tgl_perolehan 
                  WHERE id = :id";

        try {
            $stmt = $this->db->prepare($query);

            // Sanitasi input form
            $kode_aset = htmlspecialchars(strip_tags($data['kode_aset']));
            $nama_barang = htmlspecialchars(strip_tags($data['nama_barang']));
            $kategori = htmlspecialchars(strip_tags($data['kategori']));
            $jumlah = intval($data['jumlah']);
            $kondisi = htmlspecialchars(strip_tags($data['kondisi']));
            $foto = htmlspecialchars(strip_tags($data['foto']));
            $tgl_perolehan = htmlspecialchars(strip_tags($data['tgl_perolehan']));

            // Mengikat parameter query
            $stmt->bindParam(':kode_aset', $kode_aset);
            $stmt->bindParam(':nama_barang', $nama_barang);
            $stmt->bindParam(':kategori', $kategori);
            $stmt->bindParam(':jumlah', $jumlah, PDO::PARAM_INT);
            $stmt->bindParam(':kondisi', $kondisi);
            $stmt->bindParam(':foto', $foto);
            $stmt->bindParam(':tgl_perolehan', $tgl_perolehan);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $exception) {
            throw new Exception("Gagal memperbarui aset: " . $exception->getMessage());
        }
    }

    /**
     * Menghapus baris data aset berdasarkan ID
     * @param int $id ID data yang akan dihapus
     * @return bool True jika berhasil dihapus, False jika gagal
     */
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $exception) {
            throw new Exception("Gagal menghapus aset: " . $exception->getMessage());
        }
    }

    /**
     * Mengambil rangkuman statistik data aset (Total Data Aset, Jumlah Baik, Jumlah Rusak)
     * @return array Array berisi total_jenis, total_qty, baik_qty, dan rusak_qty
     */
    public function getStatsCount() {
        // Query agregasi efisien menggunakan SUM & CASE WHEN dalam satu langkah query saja
        $query = "SELECT 
                    COUNT(id) as total_jenis,
                    IFNULL(SUM(jumlah), 0) as total_qty,
                    IFNULL(SUM(CASE WHEN kondisi = 'Baik' THEN jumlah ELSE 0 END), 0) as baik_qty,
                    IFNULL(SUM(CASE WHEN kondisi = 'Rusak' THEN jumlah ELSE 0 END), 0) as rusak_qty
                  FROM " . $this->table_name;

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $exception) {
            throw new Exception("Gagal menghitung statistik aset: " . $exception->getMessage());
        }
    }
}