</main>
    </div> <!-- Akhir dari .dashboard-wrapper -->

    <!-- Bagian Footer Statis Aplikasi -->
    <footer class="text-center py-3 bg-white border-top mt-auto" style="border-color: var(--matcha-medium) !important;">
        <div class="container-fluid">
            <p class="m-0 text-secondary small">
                &copy; <?= date('Y'); ?> <b>SIMBAR</b> - Dikembangkan oleh 
                <span class="text-danger fw-semibold">Herlina Triansah</span> (NIM: 251011700828)
            </p>
            <p class="m-0 text-muted extra-small" style="font-size: 0.7rem;">
                Ujian Akhir Semester Genap | Pemrograman Web II | Universitas Pamulang
            </p>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle CDN dengan Popper.js terintegrasi -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Skrip Interaktivitas Kustom -->
    <script>
        /**
         * Fungsi untuk menampilkan dan menyembunyikan sidebar di tampilan layar seluler (Mobile)
         */
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('sidebarContainer');
            if (sidebar) {
                sidebar.classList.toggle('show');
            }
        }

        // Menunggu seluruh halaman selesai dimuat
        document.addEventListener("DOMContentLoaded", function() {
            // Mengambil semua elemen alert bootstrap yang ada di halaman
            const alerts = document.querySelectorAll('.alert-custom');
            
            alerts.forEach(function(alert) {
                // Sembunyikan alert secara halus setelah 4 detik (4000 milidetik)
                setTimeout(function() {
                    alert.style.transition = "opacity 0.5s ease";
                    alert.style.opacity = "0";
                    setTimeout(function() {
                        alert.style.display = "none";
                    }, 500);
                }, 4000);
            });
        });
    </script>
</body>
</html>