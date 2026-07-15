<?php
session_start();
if(!isset($_SESSION['user'])){ header("Location: login.php"); exit; }
include 'koneksi.php';

$id_produk = $_GET['id'];
$user_id = $_SESSION['id'];

// Check if already in cart
$cek = mysqli_query($conn, "SELECT * FROM keranjang WHERE user_id='$user_id' AND produk_id='$id_produk'");
if(mysqli_num_rows($cek) > 0){
    // Update quantity
    mysqli_query($conn, "UPDATE keranjang SET jumlah=jumlah+1 WHERE user_id='$user_id' AND produk_id='$id_produk'");
} else {
    // Insert new
    mysqli_query($conn, "INSERT INTO keranjang (user_id, produk_id, jumlah) VALUES ('$user_id', '$id_produk', 1)");
}

header("Location: data.php");
exit;
?>
