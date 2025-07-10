<?php
session_start();
//membuat koneksi ke database
$conn = mysqli_connect("localhost","root","","stokbarang");

if(isset($_POST['addnewbarang'])){
    $namabarang = ucwords(strtolower($_POST['namabarang']));
    $keterangan = ucwords(strtolower($_POST['keterangan']));

    // Bersihkan format rupiah
    $hargajual = $_POST['hargajual'];
    $hargajual = preg_replace('/[^\d]/', '', $hargajual); // Buang Rp, titik, dll

    $addtotable = mysqli_query($conn,"insert into stokbarang (namabarang, keterangan, hargajual) VALUES('$namabarang','$keterangan','$hargajual')");
    
    if($addtotable){
        header('location:index.php');
    } else {
        echo 'Gagal menambahkan data';
        header('location:index.php');
    }
}

if (isset($_POST['barangmasuk'])) {
    $tanggal = $_POST['tanggal'];
    $namabarang = ucwords(strtolower($_POST['barangnya']));
    $caridata = mysqli_query($conn, "SELECT * FROM stokbarang WHERE namabarang = '$namabarang'");
    $databarang = mysqli_fetch_assoc($caridata);

    if (!$databarang) {
        echo 'Barang tidak ditemukan di stokbarang!';
        exit;
    }

    $idbarang = $databarang['idbarang'];
    $stoklama = $databarang['stok'];
    $keterangan = $databarang['keterangan'];
    $jumlahbeli = $_POST['jumlahbeli'];
    $modalawal = $_POST['modalawal'];
    $hargasatuan = ($jumlahbeli != 0) ? $modalawal / $jumlahbeli : 0;

    $caridata = mysqli_query($conn, "SELECT * FROM stokbarang WHERE idbarang = '$idbarang'");
    $databarang = mysqli_fetch_assoc($caridata);
    $stoklama = $databarang['stok'];
    $stokbaru = $stoklama + $jumlahbeli;
    $keterangan = $databarang['keterangan'];

    $insert = mysqli_query($conn, "INSERT INTO masuk (idbarang, jumlahbeli, modalawal, keterangan, tanggal) VALUES ('$idbarang', '$jumlahbeli', '$modalawal', '$keterangan', '$tanggal')");

    // Update stok
    $updatestok = mysqli_query($conn, "UPDATE stokbarang SET 
        stok = '$stokbaru',
        modalawal = '$modalawal',
        jumlahbeli = '$jumlahbeli',
        hargasatuan = '$hargasatuan'
        WHERE idbarang = '$idbarang'");

    if ($insert && $updatestok) {
        header ('location:masuk.php');
    } else {
        echo 'Gagal input barang masuk';
    }
}

// Edit data barang masuk
if (isset($_POST['editbarangmasuk'])) {
    $idbarang = $_POST['idbarang'];
    $idmasuk = $_POST['idmasuk'];
    $modalawal = $_POST['modalawal'];
    $jumlahbeli = $_POST['jumlahbeli'];

    // Hitung ulang harga satuan
    $hargasatuan = ($jumlahbeli != 0) ? $modalawal / $jumlahbeli : 0;

    // Update ke tabel 'masuk'
    $updatemasuk = mysqli_query($conn, "UPDATE masuk SET 
        modalawal = '$modalawal',
        jumlahbeli = '$jumlahbeli'
        WHERE idmasuk = '$idmasuk'");

    // Update juga ke stokbarang
    $updatestok = mysqli_query($conn, "UPDATE stokbarang SET 
        modalawal = '$modalawal',
        jumlahbeli = '$jumlahbeli',
        hargasatuan = '$hargasatuan'
        WHERE idbarang = '$idbarang'");

    if ($updatemasuk && $updatestok) {
        header('Location: masuk.php');
        exit;
    } else {
        echo 'Gagal update data barang masuk';
    }
}


if (isset($_POST['hapusbarangmasuk'])) {
    $idmasuk = $_POST['idmasuk'];

    // Ambil data dulu sebelum dihapus
    $get = mysqli_query($conn, "SELECT * FROM masuk WHERE idmasuk='$idmasuk'");
    $data = mysqli_fetch_array($get);

    $idbarang = $data['idbarang'];
    $qty = $data['jumlahbeli']; // perbaikan dari 'qty'
    $modalawal = $data['modalawal'];
    $jumlahbeli = $data['jumlahbeli'];

    // Ambil data stokbarang
    $getstok = mysqli_query($conn, "SELECT stok, jumlahbeli, modalawal FROM stokbarang WHERE idbarang='$idbarang'");
    $datastok = mysqli_fetch_array($getstok);

    $stoklama = $datastok['stok'];
    $jumlahbeli_sekarang = $datastok['jumlahbeli'];
    $modalawal_sekarang = $datastok['modalawal'];

    $stokbaru = $stoklama - $qty;
    $jumlahbeli_baru = $jumlahbeli_sekarang - $jumlahbeli;
    $modalawal_baru = $modalawal_sekarang - $modalawal;

    if ($stokbaru < 0) $stokbaru = 0;
    if ($jumlahbeli_baru < 0) $jumlahbeli_baru = 0;
    if ($modalawal_baru < 0) $modalawal_baru = 0;

    $hargasatuan_baru = ($jumlahbeli_baru != 0) ? $modalawal_baru / $jumlahbeli_baru : 0;

    $updatestok = mysqli_query($conn, "UPDATE stokbarang SET 
        stok='$stokbaru',
        jumlahbeli='$jumlahbeli_baru',
        modalawal='$modalawal_baru',
        hargasatuan='$hargasatuan_baru'
        WHERE idbarang='$idbarang'");

    // Hapus dari tabel masuk
    $hapus = mysqli_query($conn, "DELETE FROM masuk WHERE idmasuk='$idmasuk'");

    if ($hapus && $updatestok) {
        header('Location: masuk.php');
        exit;
    } else {
        echo 'Gagal menghapus data barang masuk';
    }
}


//edit namabarang dan keterangan di stok barang
if(isset($_POST['updatebarang'])){
    $idb = $_POST['idb'];
    $namabarang = $_POST['namabarang'];
    $keterangan = $_POST['keterangan'];
    $hargasatuan = $_POST['hargasatuan'];
    $hargajual = $_POST['hargajual'];
    $hargajual = preg_replace('/[^\d]/', '', $hargajual);
    $hargasatuan = preg_replace('/[^\d]/', '', $hargasatuan);


    $update = mysqli_query($conn,"update stokbarang set namabarang='$namabarang',keterangan='$keterangan',hargasatuan='$hargasatuan',hargajual='$hargajual' where idbarang='$idb'");
    if($update){
        header('location.index.php');
    } else {
        echo 'gagal';
        header('location.index.php');
    }
}

//hapus barang di stok barang
if(isset($_POST['hapusbarang'])){
    $idb = $_POST['idb'];

    $hapus = mysqli_query($conn,"delete from stokbarang where idbarang='$idb'");
    if($hapus){
        header('location.index.php');
    } else {
        echo 'gagal';
        header('location.index.php');
    }
}



?>