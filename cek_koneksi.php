<?php

$conn = mysqli_connect("localhost", "root", "", "db_toko_telur");

if($conn){
    echo "Koneksi database berhasil";
}else{
    echo "Koneksi database gagal";
}

?>