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
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Pencatatan Barang Masuk</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Montserrat:wght@500&display=swap" rel="stylesheet">
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
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
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="page-title" style="margin-top: 30px; margin-bottom: 26px;">
                            Pencatatan Barang Masuk
                        </h1>

                    <div class="card mb-4" style="margin-bottom: 24px;">
                        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <!-- KIRI -->
                            <div class="d-flex align-items-center gap-2">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal">
                                    +Tambah Barang
                                </button>
                                <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#kalkulatorModal">
                                    Kalkulator
                                </button>
                            </div>
                            <form method="GET" class="mb-0">
                                <input type="date" name="tanggal" class="form-control" value="<?= isset($_GET['tanggal']) ? $_GET['tanggal'] : '' ?>" onchange="this.form.submit()">
                            </form>
                        </div>
                    </div>
                    <!-- Tombol barang masuk -->
                    <div class="modal fade" id="modalmasuk">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Barang Masuk</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <!--MODAL TOMBOL BARANG_MASUK -->
                                <form method="post">
                                    <div class="modal-body">
                                        <!-- MODAL TOMBOL BARANG_MASUK: AUTO['TANGGAL'] DAN SELECT/KETIK['NAMA BARANG'] -->
                                        <label for="tanggal">Tanggal Masuk</label>
                                        <input type="date" name="tanggal" id="tanggal" class="form-control" value="<?= date('Y-m-d') ?>" required>
                                        <br>

                                        <input list="baranglist" name="barangnya" id="inputBarang" class="form-control" placeholder="Nama Barang" style="text-transform: capitalize;" required>
                                        <datalist id="baranglist">
                                            <?php
                                            $ambilsemuadatanya = mysqli_query($conn, "SELECT * FROM stokbarang");
                                            $data_idbarang = [];
                                            while ($fetcharray = mysqli_fetch_array($ambilsemuadatanya)) {
                                                $namabarang = $fetcharray['namabarang'];
                                                $idbarang = $fetcharray['idbarang'];

                                                echo "<option value=\"$namabarang\">";

                                                // Buat array mapping nama => idbarang
                                                $data_idbarang[$namabarang] = $idbarang;
                                            }
                                            ?>
                                        </datalist>
                                        <br>
                                        <input type="text" name="modalawal_display" id="modalawal_display" class="form-control format-rupiah" placeholder="Modal Awal (Rp)" required>
                                        <input type="hidden" name="modalawal" id="modalawal">
                                        <br>
                                        <input type="number" name="jumlahbeli" class="form-control" placeholder="Jumlah Pembelian" required>
                                        <br>
                                        <button type="submit" class="btn btn-primary" name="barangmasuk">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="datatablesSimple">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Nama Barang</th>
                                    <th>Kategori</th>
                                    <th>Harga Pembelian</th>
                                    <th>Jumlah Pembelian</th>
                                    <th>Total Pembelian</th>
                                    <th>Modal Satuan</th>
                                    <th>Ubah Modal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $whereClause = "";
                                if (isset($_GET['tanggal']) && $_GET['tanggal'] !== '') {
                                    $tanggal = $_GET['tanggal'];
                                    $whereClause .= " AND DATE(masuk.tanggal) = '$tanggal'";
                                }
                                $ambilsemuadatastok = mysqli_query($conn, "
                                    SELECT * FROM masuk 
                                    JOIN stokbarang ON masuk.idbarang = stokbarang.idbarang
                                    WHERE 1=1 $whereClause
                                    ORDER BY masuk.idmasuk DESC
                                ");
                                while($data = mysqli_fetch_array($ambilsemuadatastok)){
                                    $idb = $data['idbarang'];
                                    $idm = $data['idmasuk'];
                                    $tanggal = date('Y-m-d', strtotime($data['tanggal']));
                                    $namabarang = $data['namabarang'];
                                    $stokbaru = $data['stok'];
                                    $keterangan = $data['keterangan'];
                                    $modalbarang = $data['modalawal'];
                                    $jumlahbeli = $data['jumlahbeli'];
                                    $hargasatuan = $data['hargasatuan'];
                                ?>
                                <tr data-tanggal="<?=$tanggal;?>">
                                    <td><?=$tanggal;?></td>
                                    <td><?=$namabarang;?></td>
                                    <td><?=$keterangan;?></td>
                                    <td><?='Rp' . number_format($modalbarang, 0, ',', '.');?></td>
                                    <td><?=$jumlahbeli;?></td>
                                    <td><?=$stokbaru;?></td>
                                    <td><?='Rp' . number_format($hargasatuan, 0, ',', '.');?></td>
                                    <td>
                                        <button type="button" class="btn btn-outline-primary btn-sm-edit" data-bs-toggle="modal" data-bs-target="#edit<?=$idm?>">
                                            Edit
                                        </button>

                                        <input type="hidden" name="idbarangyangdihapus" value="<?=$idb;?>">

                                        <button type="button" class="btn btn-outline-danger btn-sm-edit" data-bs-toggle="modal" data-bs-target="#delete<?=$idm;?>">
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                                <!-- Modal Edit -->
                                <div class="modal fade" id="edit<?=$idm;?>" tabindex="-1" aria-labelledby="editModalLabel<?=$idm;?>" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content rounded-4 shadow">

                                            <form method="post">
                                                <div class="modal-header bg-primary text-white rounded-top-4">
                                                <h5 class="modal-title" id="editModalLabel<?=$idm;?>">Edit Modal & Jumlah Beli</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>

                                                <div class="modal-body px-4 py-3">
                                                <input type="hidden" name="idbarang" value="<?=$idb;?>">
                                                <input type="hidden" name="idmasuk" value="<?=$idm;?>">

                                                <!-- Modal Awal (dengan Format Rupiah) -->
                                                <div class="mb-3">
                                                    <label for="modalawal_display<?=$idm;?>" class="form-label">Modal Pembelian (Rp)</label>
                                                    <input type="text" name="modalawal_display" id="modalawal_display<?=$idm;?>" 
                                                        value="Rp<?=number_format($modalbarang, 0, ',', '.');?>"
                                                        class="form-control format-rupiah" required>
                                                    <input type="hidden" name="modalawal" id="modalawal<?=$idm;?>" value="<?=$modalbarang;?>">
                                                </div>

                                                <!-- Jumlah Pembelian -->
                                                <div class="mb-3">
                                                    <label for="jumlahbeli<?=$idm;?>" class="form-label">Jumlah Pembelian</label>
                                                    <input type="number" name="jumlahbeli" id="jumlahbeli<?=$idm;?>" value="<?=$jumlahbeli;?>" 
                                                        class="form-control" required>
                                                </div>
                                                </div>

                                                <div class="modal-footer px-4 py-2">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary" name="editbarangmasuk">Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Delete -->
                                <div class="modal fade" id="delete<?=$idm;?>">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="post">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Hapus Barang Masuk</h4>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>

                                                <div class="modal-body">
                                                    Apakah kamu yakin ingin menghapus data ini?<br><strong><?=$namabarang;?></strong>
                                                    <input type="hidden" name="idmasuk" value="<?=$idm;?>">
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-danger" name="hapusbarangmasuk">Hapus</button>
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                } 
                                ?>
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
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>
        <!-- js format rupiah -->
        <script>
            document.querySelectorAll('.format-rupiah').forEach(function(input) {
                input.addEventListener('input', function(e) {
                    const hiddenId = this.id.replace('_display', '');
                    const hiddenInput = document.getElementById(hiddenId);

                    // Ambil angka mentah
                    let angka = this.value.replace(/[^,\d]/g, '').toString();
                    let clean = angka.replace(/\./g, '').replace(',', '');

                    // Simpan ke hidden input
                    hiddenInput.value = clean;

                    // Format tampilan
                    let split = angka.split(',');
                    let sisa = split[0].length % 3;
                    let rupiah = split[0].substr(0, sisa);
                    let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                    if (ribuan) {
                        let separator = sisa ? '.' : '';
                        rupiah += separator + ribuan.join('.');
                    }

                    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
                    this.value = 'Rp' + rupiah;
                });
            });
        </script>

        <!-- SCRIPT TOMBOL BARANG MASUK: SHORTCUT KEYBOARD -->
        <script>
            document.addEventListener('keydown', function(event) {
                // Jika menekan tombol "=" saja dan tidak sedang mengetik di input/textarea
                if (event.key === 'Enter' &&
                    document.activeElement.tagName !== 'INPUT' &&
                    document.activeElement.tagName !== 'TEXTAREA') {

                    event.preventDefault(); // Mencegah fungsi default tombol
                    const modalElement = document.getElementById('modalmasuk');
                    const modal = new bootstrap.Modal(modalElement);
                    modal.show();
                }
            });
        </script>

        <!-- SCRIPT TOMBOL BARANG MASUK: KURSOR_AUTO('TANGGAL')-->
        <script>
            const modalMasuk = document.getElementById('modalmasuk');
            modalMasuk.addEventListener('shown.bs.modal', function () {
                document.getElementById('tanggal').focus();
            });
        </script>

        <!-- Modal Kalkulator -->
        <div class="modal fade" id="kalkulatorModal" tabindex="-1" aria-labelledby="kalkulatorModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                
                <div class="modal-header">
                    <h5 class="modal-title" id="kalkulatorModalLabel">Kalkulator</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <form id="formKalkulator">
                    <input type="text" id="display" class="form-control text-end mb-3" disabled>

                    <div class="row g-2">
                        <!-- Angka -->
                        <div class="col-3"><button type="button" class="btn btn-light w-100" onclick="append('7')">7</button></div>
                        <div class="col-3"><button type="button" class="btn btn-light w-100" onclick="append('8')">8</button></div>
                        <div class="col-3"><button type="button" class="btn btn-light w-100" onclick="append('9')">9</button></div>
                        <div class="col-3"><button type="button" class="btn btn-warning w-100" onclick="append('/')">÷</button></div>

                        <div class="col-3"><button type="button" class="btn btn-light w-100" onclick="append('4')">4</button></div>
                        <div class="col-3"><button type="button" class="btn btn-light w-100" onclick="append('5')">5</button></div>
                        <div class="col-3"><button type="button" class="btn btn-light w-100" onclick="append('6')">6</button></div>
                        <div class="col-3"><button type="button" class="btn btn-warning w-100" onclick="append('*')">×</button></div>

                        <div class="col-3"><button type="button" class="btn btn-light w-100" onclick="append('1')">1</button></div>
                        <div class="col-3"><button type="button" class="btn btn-light w-100" onclick="append('2')">2</button></div>
                        <div class="col-3"><button type="button" class="btn btn-light w-100" onclick="append('3')">3</button></div>
                        <div class="col-3"><button type="button" class="btn btn-warning w-100" onclick="append('-')">−</button></div>

                        <div class="col-3"><button type="button" class="btn btn-danger w-100" onclick="clearDisplay()">C</button></div>
                        <div class="col-3"><button type="button" class="btn btn-light w-100" onclick="append('0')">0</button></div>
                        <div class="col-3"><button type="button" class="btn btn-success w-100" onclick="calculate()">=</button></div>
                        <div class="col-3"><button type="button" class="btn btn-warning w-100" onclick="append('+')">+</button></div>
                    </div>
                    </form>
                </div>

                </div>
            </div>
        </div>

        <!--- SCRIPT TOMBOL KALKULATOR -->
        <script>
            function append(value) {
                document.getElementById('display').value += value;
            }

            function clearDisplay() {
                document.getElementById('display').value = '';
            }

            function calculate() {
                try {
                let result = eval(document.getElementById('display').value);
                document.getElementById('display').value = result;
                } catch {
                document.getElementById('display').value = 'Error';
                }
            }
        </script>

        <!-- SCRIPT TOMBOL PILIH TANGGAL -->
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const filterInput = document.getElementById("filterTanggal");

                filterInput.addEventListener("input", function () {
                    const selectedDate = this.value.trim();
                    const rows = document.querySelectorAll("#datatablesSimple tbody tr");

                    rows.forEach(row => {
                        const rowDate = row.getAttribute("data-tanggal")?.trim();
                        if (!selectedDate || rowDate === selectedDate) {
                            row.style.display = "";
                        } else {
                            row.style.display = "none";
                        }
                    });
                });
            });
        </script>




    </body>
</html>
