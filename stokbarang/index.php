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
        <title>Manajemen Barang</title>
        <!-- Bootstrap 5 JavaScript Bundle (wajib) -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Montserrat:wght@500&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
        <link href="css/styles.css" rel="stylesheet" />
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
                            Manajemen Barang
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

                            <!-- KANAN -->
                            <div class="d-flex align-items-center gap-2">
                                <form method="GET" class="mb-0">
                                    <select id="filterKeterangan" name="keterangan" class="form-select" onchange="this.form.submit()">
                                    <option value="">-- Semua Kategori --</option>
                                    <?php
                                    $result_ket = mysqli_query($conn, "SELECT DISTINCT keterangan FROM stokbarang ORDER BY keterangan ASC");
                                    while ($row = mysqli_fetch_assoc($result_ket)) {
                                        $ket = $row['keterangan'];
                                        $selected = (isset($_GET['keterangan']) && $_GET['keterangan'] == $ket) ? 'selected' : '';
                                        echo "<option value=\"$ket\" $selected>$ket</option>";
                                    }
                                    ?>
                                    </select>
                                </form>
                            </div>

                        </div>
                    </div>



                    <!-- MODAL TOMBOL TAMBAH BARANG -->
                    <div class="modal fade" id="myModal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <!-- MODAL TOMBOL TAMBAH BARANG : JUDUL FORM  -->
                                <div class="modal-header">
                                <h4 class="modal-title">+Tambah Barang</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <!-- MODAL TOMBOL TAMBAHB BARANG : ISI FORM  -->
                                <form method="post">
                                <div class="modal-body">
                                <input type="text" id="namabarang" name="namabarang" placeholder="Nama Barang" class="form-control" style="text-transform: capitalize;" required>
                                <small id="alert-duplikat" style="color: red; display: none;">Nama barang sudah ada!</small>
                                <?php
                                $daftar_nama_barang = [];
                                $ambil_nama = mysqli_query($conn, "SELECT namabarang FROM stokbarang");
                                while ($row = mysqli_fetch_assoc($ambil_nama)) {
                                    $daftar_nama_barang[] = strtolower($row['namabarang']);
                                }
                                ?>
                                <br>
                                <input type="text" name="keterangan" placeholder="Keterangan" class="form-control" style="text-transform: capitalize;" required>
                                <br>
                                <input type="text" id="hargajual" name="hargajual" placeholder="Harga Jual" class="form-control" oninput="formatRupiah(this)" required>
                                <br>
                                <button type="submit" class="btn btn-primary" name="addnewbarang">Tambahkan</button>
                                </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="datatablesSimple">
                            <thead>
                                <tr>
                                    <th>Nama Barang</th>
                                    <th>Kategori</th>
                                    <th>Modal/Pcs</th>
                                    <th>Harga Jual</th>
                                    <th>Keuntungan</th>
                                    <th>Edit Barang</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $filter = isset($_GET['keterangan']) ? $_GET['keterangan'] : '';
                                if ($filter) {
                                    $ambilsemuadatastok = mysqli_query($conn, "SELECT * FROM stokbarang WHERE keterangan = '$filter' ORDER BY namabarang ASC");
                                } else {
                                    $ambilsemuadatastok = mysqli_query($conn, "SELECT * FROM stokbarang ORDER BY namabarang DESC");
                                }
                                while($data=mysqli_fetch_array($ambilsemuadatastok)){
                                    $idb = $data['idbarang'];
                                    $namabarang = $data['namabarang'];
                                    $keterangan = $data['keterangan'];
                                    $modalbarang = $data['modalawal'];
                                    $jumlahpembelian = $data['jumlahbeli'];
                                    $hargasatuan =$data['hargasatuan'];
                                    $hargajual= $data['hargajual'];
                                    $keuntungan = $data['keuntungan'];
                                    $keuntungan = ($hargajual != 0 && $hargasatuan != 0) ? $hargajual - $hargasatuan : 0;
                                    $keuntungan_persen = ($hargasatuan > 0) ? round(($keuntungan / $hargasatuan) * 100, 1) : 0;                            
                                ?>
                                <tr>
                                    <td><?=$namabarang;?></td>
                                    <td><?= $keterangan;?></td>
                                    <td><?='Rp' . number_format($hargasatuan, 0, ',', '.');?></td>
                                    <td><?='Rp' . number_format($hargajual, 0, ',', '.');?></td>
                                    <td><?='Rp' . number_format($keuntungan, 0, ',', '.');?> &nbsp;&nbsp;&nbsp; (<?= $keuntungan_persen ?>%)</td>
                                    <td>
                                        <button type="button" class="btn btn-outline-primary btn-sm-edit" data-bs-toggle="modal" data-bs-target="#edit<?=$idb;?>">
                                            Edit
                                        </button>

                                        <input type="hidden" name="idbarangyangdihapus" value="<?=$idb;?>">

                                        <button type="button" class="btn btn-outline-danger btn-sm-edit px-2" data-bs-toggle="modal" data-bs-target="#delete<?=$idb;?>">
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                                <!-- Modal Edit -->
                                <div class="modal fade" id="edit<?=$idb;?>">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Edit Barang</h4>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form method="post">
                                                <div class="modal-body">
                                                    <input type="hidden" name="idb" value="<?=$idb;?>">
                                                    <input type="text" name="namabarang" value="<?=$namabarang;?>" class="form-control"><br>
                                                    <input type="text" name="keterangan" value="<?=$keterangan;?>" class="form-control"><br>
                                                    <input type="text" id="hargasatuan<?=$idb;?>" name="hargasatuan" placeholder="Modal/Pcs" class="form-control" oninput="formatRupiah(this)" required><br>
                                                    <input type="text" id="hargajual<?=$idb;?>" name="hargajual" placeholder="Harga Jual" class="form-control" oninput="formatRupiah(this)" required><br>
                                                    <button type="submit" class="btn btn-primary" name="updatebarang">Edit</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div> 

                                <!-- MODAL TOMBOL HAPUS -->
                                <div class="modal fade" id="delete<?=$idb;?>">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <!-- MODAL TOMBOL HAPUS: JUDUL-->
                                            <div class="modal-header">
                                                <h4 class="modal-title">Hapus Barang</h4>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <!-- MODAL TOMBOL HAPUS: FORM KONFIRMASI -->
                                            <form method="post">
                                                <div class="modal-body">
                                                Apakah anda yakin ingin menghapus <?=$namabarang;?>?
                                                <input type="hidden" name="idb" value="<?=$idb;?>"> <br> <br>
                                                <button type="submit" class="btn btn-danger" name="hapusbarang">Hapus</button>
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
        <!-- SCRIPT TOMBOL +TAMBAH BARANG: KURSOR_AUTO('NAMA BARANG')-->
        <script>
            const modalTambah = document.getElementById('myModal');
            modalTambah.addEventListener('shown.bs.modal', function () {
                document.getElementById('namabarang').focus();
            });
        </script>

        <!-- SCRIPT TOMBOL +TAMBAH BARANG: FORMAT_RUPIAH('HARGA JUAL')-->
        <script>
            function formatRupiah(input) {
                let value = input.value.replace(/[^,\d]/g, '').toString();
                let split = value.split(',');
                let sisa = split[0].length % 3;
                let rupiah = split[0].substr(0, sisa);
                let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                if (ribuan) {
                    let separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }

                rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
                input.value = 'Rp ' + rupiah;
            }
        </script>
 
        <!--SCRIPT TOMBOL +TAMBAH BARANG: NAMA_BARANG TIDAK TERDUPLIKAT -->
        <script>
            const existingNames = <?= json_encode($daftar_nama_barang); ?>;
            const namaInput = document.getElementById('namabarang');
            const alertText = document.getElementById('alert-duplikat');
            namaInput.addEventListener('input', function () {
                const inputVal = this.value.trim().toLowerCase();

                if (existingNames.includes(inputVal)) {
                alertText.style.display = 'block';
                this.setCustomValidity('Nama barang sudah ada');
                } else {
                alertText.style.display = 'none';
                this.setCustomValidity('');
                }
            });
        </script>

        <!-- SCRIPT TOMBOL +TAMBAH BARANG: SHORTCUT TOMBOL +TAMBAH_BARANG -->
        <script>
            document.addEventListener('keydown', function(event) {
                // Jika menekan tombol "=" saja dan tidak sedang mengetik di input/textarea
                if (event.key === 'Enter' &&
                    document.activeElement.tagName !== 'INPUT' &&
                    document.activeElement.tagName !== 'TEXTAREA') {

                    event.preventDefault(); // Mencegah fungsi default tombol
                    const modalElement = document.getElementById('myModal');
                    const modal = new bootstrap.Modal(modalElement);
                    modal.show();
                }
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

    </body>
</html>
