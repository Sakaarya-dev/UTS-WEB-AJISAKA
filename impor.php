<?php
session_start();
include 'koneksi.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}

if(isset($_POST['import'])){
    $file = $_FILES['file_csv']['tmp_name'];
    
    if(empty($file)){
        echo "<script>alert('Pilih file CSV terlebih dahulu!'); window.history.back();</script>";
        exit;
    }

    // Buka file CSV
    $handle = fopen($file, "r");
    
    // Lewati baris pertama (header kolom Excel)
    fgets($handle); 
    
    $sukses = 0;

    // Lakukan perulangan untuk membaca tiap baris data CSV produk
    while(($filesop = fgetcsv($handle, 1000, ",")) !== false){
        // PERBAIKAN: Susunan kolom disesuaikan dengan data produk toko telurmu
        // Kolom 0 = nama_produk, Kolom 1 = harga, Kolom 2 = stok
        $nama_produk = mysqli_real_escape_string($conn, $filesop[0]);
        $harga       = intval($filesop[1]);
        $stok        = intval($filesop[2]);
        
        // Validasi agar baris kosong di Excel tidak ikut terinput
        if(!empty($nama_produk)) {
            // PERBAIKAN: Query diubah untuk menyimpan langsung ke tabel 'produk'
            $sql = mysqli_query($conn, "INSERT INTO produk (nama_produk, harga, stok) VALUES ('$nama_produk', '$harga', '$stok')");
            
            if($sql){
                $sukses++;
            }
        }
    }

    fclose($handle);

    // Diarahkan kembali ke halaman pengelolaan produk (ganti nama filenya jika bukan produk.php)
    if($sukses > 0){
        echo "<script>alert('Berhasil mengimpor $sukses data produk telur baru!'); window.location='produk.php';</script>";
    } else {
        echo "<script>alert('Gagal mengimpor data! Cek kembali format kolom file CSV Anda.'); window.location='produk.php';</script>";
    }
}
?>
