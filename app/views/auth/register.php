<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Akun - SIMBAR</title>
    
    <!-- Google Font Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #FCE4EC 0%, #E8F5E9 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
        }

        .register-card {
            background-color: #FFFFFF;
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(194, 24, 91, 0.05);
            overflow: hidden;
            max-width: 460px;
            width: 100%;
        }

        .card-header-accent {
            background-color: #FCE4EC;
            border-bottom: 2px solid #E8F5E9;
            padding: 2rem 2rem 1.2rem 2rem;
            text-align: center;
        }

        .brand-title {
            color: #C2185B; /* Strawberry Pink */
            font-weight: 700;
            font-size: 1.4rem;
            margin-bottom: 0.2rem;
            letter-spacing: -0.5px;
        }

        .brand-subtitle {
            color: #2E7D32; /* Matcha Green */
            font-size: 0.85rem;
            font-weight: 500;
        }

        .form-control {
            border-radius: 12px;
            padding: 0.7rem 1rem;
            border: 1.5px solid #E0E0E0;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: #81C784; /* Matcha Green Focus */
            box-shadow: 0 0 0 0.25rem rgba(129, 199, 132, 0.15);
        }

        .btn-matcha {
            background-color: #81C784; /* Matcha Green Button */
            color: #FFFFFF;
            border: none;
            border-radius: 12px;
            padding: 0.8rem;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }

        .btn-matcha:hover {
            background-color: #66BB6A;
            transform: translateY(-1px);
        }

        .btn-matcha:active {
            transform: translateY(1px);
        }

        .login-link {
            color: #C2185B;
            text-decoration: none;
            font-weight: 600;
        }

        .login-link:hover {
            text-decoration: underline;
            color: #AD1457;
        }

        .alert-custom {
            border-radius: 12px;
            font-size: 0.85rem;
            border: none;
        }
    </style>
</head>
<body>

    <div class="register-card p-0 mx-3">
        <!-- Bagian Atas Card (Header) -->
        <div class="card-header-accent">
            <div class="mb-2">
                <i class="bi bi-person-plus" style="font-size: 2.5rem; color: #C2185B;"></i>
            </div>
            <h1 class="brand-title">Registrasi Admin</h1>
            <p class="brand-subtitle m-0">Sistem Informasi Manajemen Barang (SIMBAR)</p>
        </div>

        <!-- Bagian Form Utama (Body) -->
        <div class="p-4 pt-3">
            
            <?php if (isset($_SESSION['error_msg'])): ?>
                <div class="alert alert-danger alert-custom alert-dismissible fade show mb-3" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <?= htmlspecialchars($_SESSION['error_msg']); ?>
                    <button type="button" class="btn-close" data-bs-alert aria-label="Close" onclick="this.parentElement.style.display='none';"></button>
                </div>
                <?php unset($_SESSION['error_msg']); ?>
            <?php endif; ?>

            <form action="index.php?page=auth&action=register" method="POST" autocomplete="off">
                
                <!-- Input Nama Lengkap -->
                <div class="mb-3">
                    <label for="nama_lengkap" class="form-label small text-secondary fw-medium">Nama Lengkap</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0" style="border-radius: 12px 0 0 12px; border: 1.5px solid #E0E0E0;">
                            <i class="bi bi-card-text text-secondary"></i>
                        </span>
                        <input type="text" class="form-control border-start-0" id="nama_lengkap" name="nama_lengkap" placeholder="Masukkan nama lengkap" style="border-radius: 0 12px 12px 0;" required>
                    </div>
                </div>

                <!-- Input Username -->
                <div class="mb-3">
                    <label for="username" class="form-label small text-secondary fw-medium">Username</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0" style="border-radius: 12px 0 0 12px; border: 1.5px solid #E0E0E0;">
                            <i class="bi bi-person text-secondary"></i>
                        </span>
                        <input type="text" class="form-control border-start-0" id="username" name="username" placeholder="Buat username baru" style="border-radius: 0 12px 12px 0;" required>
                    </div>
                </div>

                <!-- Input Password -->
                <div class="mb-3">
                    <label for="password" class="form-label small text-secondary fw-medium">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0" style="border-radius: 12px 0 0 12px; border: 1.5px solid #E0E0E0;">
                            <i class="bi bi-lock text-secondary"></i>
                        </span>
                        <input type="password" class="form-control border-start-0" id="password" name="password" placeholder="Buat password aman" style="border-radius: 0 12px 12px 0;" required>
                    </div>
                </div>

                <!-- Input Konfirmasi Password -->
                <div class="mb-4">
                    <label for="konfirmasi_password" class="form-label small text-secondary fw-medium">Konfirmasi Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0" style="border-radius: 12px 0 0 12px; border: 1.5px solid #E0E0E0;">
                            <i class="bi bi-shield-lock text-secondary"></i>
                        </span>
                        <input type="password" class="form-control border-start-0" id="konfirmasi_password" name="konfirmasi_password" placeholder="Ketik ulang password" style="border-radius: 0 12px 12px 0;" required>
                    </div>
                </div>

                <!-- Tombol Submit Register -->
                <button type="submit" class="btn btn-matcha w-100 mb-3 shadow-sm">
                    <i class="bi bi-person-check me-2"></i> Daftar Akun Baru
                </button>
            </form>

            <!-- Tautan Kembali ke Login -->
            <div class="text-center mt-2">
                <p class="small text-secondary mb-1">Sudah memiliki akun admin?</p>
                <a href="index.php?page=auth&action=login" class="login-link small"><i class="bi bi-arrow-left"></i> Kembali ke Login</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>