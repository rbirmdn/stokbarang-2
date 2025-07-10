<?php
require 'function.php';
require 'cek.php';
?>
<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title>Export Tabel</title>
        <!-- Bootstrap 5 JavaScript Bundle (wajib) -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Montserrat:wght@500&display=swap" rel="stylesheet">
        <link href="css/styles.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <!-- Navbar Brand-->
            <h2 class="navbar-brand ps-3">Toko Rusmarita</h2>
            <!-- Sidebar Toggle-->
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Menu</div>
                                <a class="nav-link" href="charts.html">
                                    <div class="sb-nav-link-icon-grafik me-2"><i class="bi bi-graph-up-arrow"></i></div>
                                    Grafik Data
                                </a>
                                <a class="nav-link" href="index.php">
                                    <div class="sb-nav-link-icon-margin me-2"><i class="bi bi-cash-stack"></i></div>
                                    Margin Barang
                                </a>
                                <a class="nav-link" href="masuk.php">
                                    <div class="sb-nav-link-icon-masuk me-2"><i class="bi bi-calendar3"></i></div>
                                    Barang Masuk
                                </a>
                                <a class="nav-link" href="keluar.php">
                                    <div class="sb-nav-link-icon-pdf me-2"><i class="bi bi-filetype-pdf"></i></div>
                                    Export Tabel
                                </a>
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <a class="btn btn-danger btn-sm" href="logout.php">
                            <i class="bi bi-person-fill"></i>Logout</a>
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main class="container-fluid px-4">
                    <h1 class="page-title" style="margin-top: 30px; margin-bottom: 26px;">Export Tabel Barang</h1>

                    <div class="card mb-4">
                        <div class="card-header">  
                            <form id="exportForm">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="filterKeterangan" class="form-label">Pilih Kategori (bisa lebih dari satu)</label>
                                        <select id="filterKeterangan" class="form-select" multiple required>
                                            <?php
                                            $query = mysqli_query($conn, "SELECT DISTINCT keterangan FROM stokbarang ORDER BY keterangan ASC");
                                            while ($row = mysqli_fetch_assoc($query)) {
                                                $keterangan = htmlspecialchars($row['keterangan']);
                                                echo "<option value=\"$keterangan\">$keterangan</option>";
                                            }
                                            ?>
                                        </select>
                                        <small class="text-muted">* Gunakan Ctrl untuk memilih lebih dari satu</small>
                                    </div>
                                    <div class="col-md-12">
                                        <button type="button" style="margin-top: 30px; margin-bottom: 26px;" class="btn btn-primary w-100" onclick="exportToPDF()">Export ke PDF</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div id="pdf-content" class="card p-3" style="display:none;">
                        <h4 class="mb-3 text-center">Daftar Barang</h4>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Barang</th>
                                    <th>Harga Jual</th>
                                </tr>
                            </thead>
                            <tbody id="pdf-tbody">
                                <!-- Data akan diisi dari JS -->
                            </tbody>
                        </table>
                    </div>
                </main>
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Toko Rusmarita 2025</div>
                            <div>
                                <a href="#">Kebijakan Privasi</a>
                                &middot;
                                <a href="#">Syarat &amp; Ketentuan</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <!-- Script -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
        <script>
            function exportToPDF() {
                const select = document.getElementById('filterKeterangan');
                const selectedOptions = Array.from(select.selectedOptions).map(opt => opt.value);

                if (selectedOptions.length === 0) {
                    alert('Pilih minimal satu keterangan!');
                    return;
                }

                const params = new URLSearchParams();
                selectedOptions.forEach(k => params.append('keterangan[]', k));

                fetch(`get-data.php?${params.toString()}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.length === 0) {
                            alert('Tidak ada data ditemukan.');
                            return;
                        }

                        const tbody = document.getElementById('pdf-tbody');
                        tbody.innerHTML = '';

                        data.forEach((item, index) => {
                            tbody.innerHTML += `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${item.namabarang}</td>
                                    <td>Rp ${Number(item.hargajual).toLocaleString('id-ID')}</td>
                                </tr>`;
                        });

                        document.getElementById('pdf-content').style.display = 'block';

                        html2pdf().from(document.getElementById('pdf-content')).set({
                            filename: 'Data-Barang.pdf',
                            html2canvas: { scale: 2 },
                            jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
                        }).save();
                    });
            }
        </script>

    </body>
</html>
