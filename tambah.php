<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

/* =========================
   CEK ROLE ADMIN
========================= */

if($_SESSION['role'] != 'admin'){
    header("Location: dashboard.php");
    exit;
}

/* =========================
   TAMBAH PRODUK
========================= */

if(isset($_POST['submit'])){

    $nama  = mysqli_real_escape_string($conn, $_POST['nama_telur']);
    $jenis = mysqli_real_escape_string($conn, $_POST['jenis_telur']);
    $harga = mysqli_real_escape_string($conn, $_POST['harga']);
    $harga_modal = mysqli_real_escape_string($conn, $_POST['harga_modal']);
    $stok  = mysqli_real_escape_string($conn, $_POST['stok']);
    $supplier = mysqli_real_escape_string($conn, $_POST['supplier']);

    /* =========================
       UPLOAD GAMBAR
    ========================= */

    $folder = "gambar_produk/";

    if(!is_dir($folder)){
        mkdir($folder, 0777, true);
    }

    $gambar = $_FILES['gambar']['name'];
    $tmp    = $_FILES['gambar']['tmp_name'];

    $nama_gambar = "";

    if($gambar != ""){

        $ext = strtolower(pathinfo($gambar, PATHINFO_EXTENSION));

        $format_valid = ['jpg','jpeg','png','webp'];

        if(in_array($ext, $format_valid)){

            $nama_gambar = time() . "_" . $gambar;

            move_uploaded_file(
                $tmp,
                $folder . $nama_gambar
            );

        } else {

            echo "
            <script>
                alert('Format gambar harus JPG, JPEG, PNG, atau WEBP!');
                window.location='tambah.php';
            </script>
            ";
            exit;
        }
    }

    /* =========================
       SIMPAN DATABASE
    ========================= */

    $query = mysqli_query($conn,
    "INSERT INTO produk
    (nama_telur, jenis_telur, harga, harga_modal, stok, supplier, gambar)
    VALUES
    ('$nama','$jenis','$harga','$harga_modal','$stok','$supplier','$nama_gambar')");

    if($query){

        echo "
        <script>
            alert('Produk berhasil ditambahkan!');
            window.location='data.php';
        </script>
        ";

    } else {

        echo mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk - Saka Poultry</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.2/dist/minty/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { background-color: #f4f6f9; }
    </style>
</head>
<body class="py-5">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h4 class="mb-0 fw-bold"><i class="fa-solid fa-square-plus me-2"></i>Tambah Produk</h4>
                </div>
                <div class="card-body p-4">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Nama Telur</label>
                            <input type="text" name="nama_telur" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Jenis Telur</label>
                            <input type="text" name="jenis_telur" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Harga Jual</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="harga" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Harga Modal (Beli)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="harga_modal" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Stok</label>
                            <input type="number" name="stok" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Supplier</label>
                            <input type="text" name="supplier" class="form-control" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-muted">Upload Gambar</label>
                            <input type="file" name="gambar" accept="image/*" class="form-control" required>
                        </div>

                        <button type="submit" name="submit" class="btn btn-success w-100 fw-bold py-2">
                            <i class="fa-solid fa-save me-1"></i> Simpan Produk
                        </button>
                    </form>
                    
                    <div class="text-center mt-4">
                        <a href="dashboard.php" class="text-decoration-none text-muted">
                            <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
