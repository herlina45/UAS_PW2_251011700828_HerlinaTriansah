🍵 SIMBAR - Sistem Informasi Manajemen Barang 🍓

Sistem Informasi Manajemen Barang (SIMBAR) adalah aplikasi berbasis web PHP Native dengan arsitektur MVC (Model-View-Controller) dan koneksi database berbasis PDO (PHP Data Objects). Aplikasi ini dirancang sebagai solusi inventarisasi barang yang aman, responsif, dan estetik menggunakan perpaduan visual pastel Strawberry Matcha (merah muda lembut dan hijau matcha yang sejuk).
Proyek ini dibangun sebagai tugas untuk Ujian Akhir Semester (UAS) Genap - Mata Kuliah Pemrograman Web II.

👩‍💻 Identitas Mahasiswa

Nama Lengkap: Herlina Triansah
NIM: 251011700828
Kelas: 02SIFE008
Program Studi: Sistem Informasi
Universitas: Universitas Pamulang (UNPAM)
Dosen Pengampu: Samso Supriyatna, S.Kom., M.Kom.

🚀 Fitur Utama Sistem

- Autentikasi Aman: Sistem Login & Register Admin menggunakan enkripsi satu arah berbasis Bcrypt murni (password_hash).
- Session Security Protection: Melindungi halaman dashboard dan kelola aset dari akses ilegal sebelum login.
- Pencarian Real-Time (Search Engine): Filter data dinamis menggunakan native prepared statements (LIKE query) yang kebal dari SQL Injection.
- CRUD Aset Terintegrasi: Manajemen data aset barang, lengkap dengan unggah berkas foto kondisi fisik (< 2MB, format JPG/JPEG/PNG) dengan nama berkas acak yang aman.
- Dashboard Statistik Minimalis: Ringkasan jumlah data aset, jumlah kondisi baik, dan jumlah kondisi rusak secara instan.
- Ekspor PDF Dinamis: Mengunduh dokumen laporan portabel secara offline menggunakan library FPDF (v1.86) yang dilengkapi tanda tangan pemeriksa sistem.
- Ekspor Excel Bersih: Mengunduh lembar sebar .xls menggunakan trik format mso-number-format untuk menjaga kerapian kode aset tanpa tanda petik fisik.
- Interaktivitas Bebas confirm(): Menggunakan Bootstrap 5 Modals kustom yang cantik untuk konfirmasi penghapusan data.

🛠️ Tech Stack & Dependensi

Core Language: PHP 8.3.10 / PHP Native (OOP & MVC)
Database Driver: MySQL (PDO Connection with Prepared Statements)
UI Framework: Bootstrap 5.3.3 & Bootstrap Icons (via CDN)
Typography: Poppins (Google Fonts)
Third-party Libs: FPDF v1.86 (diletakkan di folder libs/fpdf/)

💾 Akun Administrator Bawaan (Default Credentials)
Untuk keperluan pengujian oleh dosen penguji, sistem telah diisi data akun admin bawaan melalui seeder database:
Username: herlina45
Password: admin123

⚙️ Langkah Instalasi Lokal
Clone Repositori:
- git clone https://github.com/herlina45/UAS_PW2_251011700828_HerlinaTriansah.git
- Pindahkan ke Direktori Web Server:
- Pindahkan folder hasil klon ke folder htdocs/ (XAMPP) atau www/ (Laragon). Pastikan nama folder proyek adalah uas_251011700828_aset_barang.

Import Database:
- Buka phpMyAdmin, buat database baru bernama db_uas_251011700828.
- Import berkas SQL database.sql yang berada di dalam folder proyek ke database tersebut.

Konfigurasi Database:
Sesuaikan konfigurasi database (host, user, password) pada berkas config/database.php jika diperlukan.

Akses Aplikasi:
Buka peramban browser Anda, lalu akses alamat:
http://localhost/uas_251011700828_aset_barang/
