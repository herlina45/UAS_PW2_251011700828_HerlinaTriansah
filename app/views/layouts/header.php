<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? htmlspecialchars($page_title) : 'Sistem Informasi Manajemen Barang - SIMBAR'; ?></title>
    
    <!-- Google Font Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    
    <style>
        :root {
            --matcha-dark: #2E7D32;
            --matcha-medium: #81C784;
            --matcha-light: #E8F5E9;
            --strawberry-dark: #C2185B;
            --strawberry-medium: #F06292;
            --strawberry-light: #FCE4EC;
            --bg-soft-grey: #FAFAFA;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-soft-grey);
            color: #333333;
            overflow-x: hidden;
        }

        /* Layout Grid Sidebar dan Konten */
        .dashboard-wrapper {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        /* Sidebar Styling */
        .sidebar {
            width: 280px;
            background-color: var(--matcha-light);
            border-right: 2px solid var(--matcha-medium);
            padding: 2rem 1.25rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .sidebar-brand {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .brand-icon {
            font-size: 2.5rem;
            color: var(--strawberry-dark);
            display: inline-block;
            margin-bottom: 0.5rem;
        }

        .brand-text {
            color: var(--strawberry-dark);
            font-weight: 700;
            font-size: 1.2rem;
            letter-spacing: -0.5px;
            margin: 0;
        }

        .brand-desc {
            color: var(--matcha-dark);
            font-size: 0.8rem;
            font-weight: 500;
            margin: 0;
        }

        /* Menu List */
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
            flex-grow: 1;
        }

        .sidebar-item {
            margin-bottom: 0.75rem;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 0.85rem 1.25rem;
            color: var(--matcha-dark);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            border-radius: 12px;
            transition: all 0.2s ease;
        }

        .sidebar-link i {
            font-size: 1.2rem;
            margin-right: 0.85rem;
        }

        /* Link Hover & Active (Strawberry Matcha Blend) */
        .sidebar-link:hover {
            background-color: var(--matcha-medium);
            color: #FFFFFF !important;
        }

        .sidebar-link.active {
            background-color: var(--strawberry-light);
            color: var(--strawberry-dark) !important;
            font-weight: 600;
            box-shadow: 0 4px 10px rgba(194, 24, 91, 0.08);
        }

        /* Admin Info Section */
        .admin-info {
            background-color: #FFFFFF;
            border-radius: 16px;
            padding: 1rem;
            border: 1px solid var(--matcha-medium);
            margin-top: 2rem;
            box-shadow: 0 4px 12px rgba(46, 125, 50, 0.03);
        }

        .admin-avatar {
            font-size: 1.8rem;
            color: var(--strawberry-dark);
            background-color: var(--strawberry-light);
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        /* Main Content Styling */
        .main-content {
            flex-grow: 1;
            padding: 2.5rem;
            overflow-y: auto;
            max-width: calc(100% - 280px);
        }

        /* Header Seluler (Mobile Header Navbar) */
        .mobile-header {
            display: none;
            background-color: var(--matcha-light);
            border-bottom: 2px solid var(--matcha-medium);
            padding: 0.85rem 1.25rem;
            align-items: center;
            justify-content: space-between;
        }

        /* Penyesuaian Responsif Layar Kecil */
        @media (max-width: 991.98px) {
            .dashboard-wrapper {
                flex-direction: column;
            }
            .sidebar {
                width: 100%;
                display: none; /* Disembunyikan, dikendalikan via JS offcanvas/collapse jika mau, atau simpelnya stack layout */
                border-right: none;
                border-bottom: 2px solid var(--matcha-medium);
            }
            .sidebar.show {
                display: flex;
            }
            .main-content {
                max-width: 100%;
                padding: 1.5rem;
            }
            .mobile-header {
                display: flex;
            }
        }
    </style>
</head>
<body>

    <!-- Header Responsif untuk Tampilan Seluler (Mobile) -->
    <header class="mobile-header">
        <div class="d-flex align-items-center">
            <i class="bi bi-box-seam text-danger me-2" style="font-size: 1.5rem;"></i>
            <span class="fw-bold text-success" style="font-size: 0.95rem;">SIMBAR</span>
        </div>
        <button class="btn btn-outline-success btn-sm border-2 rounded-3" type="button" onclick="toggleMobileSidebar()">
            <i class="bi bi-list fs-5"></i>
        </button>
    </header>

    <div class="dashboard-wrapper">
        <!-- Bilah Samping (Sidebar Navigasi) -->
        <aside class="sidebar" id="sidebarContainer">
            <div>
                <!-- Brand Aplikasi -->
                <div class="sidebar-brand">
                    <div class="brand-icon">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <h2 class="brand-text">SIMBAR</h2>
                    <p class="brand-desc">Sistem Informasi Aset Barang</p>
                </div>

                <!-- Menu Navigasi Utama -->
                <nav>
                    <ul class="sidebar-menu">
                        <!-- Menu Dashboard -->
                        <li class="sidebar-item">
                            <a href="index.php?page=dashboard" class="sidebar-link <?= (!isset($_GET['page']) || $_GET['page'] === 'dashboard') ? 'active' : ''; ?>">
                                <i class="bi bi-grid-1x2-fill"></i> Dashboard
                            </a>
                        </li>
                        <!-- Menu Kelola Aset Barang -->
                        <li class="sidebar-item">
                            <a href="index.php?page=aset" class="sidebar-link <?= (isset($_GET['page']) && $_GET['page'] === 'aset' && (!isset($_GET['action']) || ($_GET['action'] !== 'create' && $_GET['action'] !== 'edit'))) ? 'active' : ''; ?>">
                                <i class="bi bi-box-fill"></i> Kelola Aset
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>

            <!-- Info Profil Admin & Tombol Keluar -->
            <div>
                <div class="admin-info d-flex align-items-center mb-3">
                    <div class="admin-avatar me-2 shadow-sm">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <div class="overflow-hidden">
                        <h6 class="m-0 fw-bold text-dark text-truncate small"><?= htmlspecialchars($_SESSION['nama_lengkap']); ?></h6>
                        <span class="text-secondary" style="font-size: 0.75rem;">Administrator</span>
                    </div>
                </div>

                <!-- Tombol Logout Keluar -->
                <a href="index.php?page=auth&action=logout" class="btn btn-outline-danger w-100 rounded-3 py-2 text-start fw-semibold small" onclick="return confirm('Apakah Anda yakin ingin keluar dari sistem?');">
                    <i class="bi bi-box-arrow-left me-2"></i> Keluar Sistem
                </a>
            </div>
        </aside>

        <!-- Area Konten Utama Halaman -->
        <main class="main-content">