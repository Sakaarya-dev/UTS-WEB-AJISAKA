<?php
session_start();

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

/* =========================
   AMBIL DATA PRODUK
========================= */

$id = $_GET['id'];

$query = mysqli_query($conn,
"SELECT * FROM produk WHERE id='$id'");

$data = mysqli_fetch_assoc($query);

/* =========================
   UPDATE PRODUK
========================= */

if(isset($_POST['update'])){

    $nama     = mysqli_real_escape_string($conn, $_POST['nama_telur']);
    $jenis    = mysqli_real_escape_string($conn, $_POST['jenis_telur']);
    $harga    = mysqli_real_escape_string($conn, $_POST['harga']);
    $harga_modal = mysqli_real_escape_string($conn, $_POST['harga_modal']);
    $stok     = mysqli_real_escape_string($conn, $_POST['stok']);
    $supplier = mysqli_real_escape_string($conn, $_POST['supplier']);

    $gambar_lama = $data['gambar'];

    /* =========================
       UPLOAD GAMBAR BARU
    ========================= */

    $folder = "gambar_produk/";

    $gambar = $_FILES['gambar']['name'];
    $tmp    = $_FILES['gambar']['tmp_name'];

    if($gambar != ""){

        $ext = strtolower(pathinfo($gambar, PATHINFO_EXTENSION));

        $format_valid = ['jpg','jpeg','png','webp'];

        if(in_array($ext, $format_valid)){

            $nama_gambar = time().'_'.$gambar;

            move_uploaded_file(
                $tmp,
                $folder.$nama_gambar
            );

            // hapus gambar lama
            if($gambar_lama != "" &&
               file_exists($folder.$gambar_lama)){

                unlink($folder.$gambar_lama);
            }

        }else{

            echo "
            <script>
                alert('Format gambar tidak valid!');
                window.location='edit.php?id=$id';
            </script>
            ";
            exit;
        }

    }else{

        // kalau tidak upload gambar baru
        $nama_gambar = $gambar_lama;
    }

    /* =========================
       UPDATE DATABASE
    ========================= */

    $update = mysqli_query($conn,
    "UPDATE produk SET

    nama_telur='$nama',
    jenis_telur='$jenis',
    harga='$harga',
    harga_modal='$harga_modal',
    stok='$stok',
    supplier='$supplier',
    gambar='$nama_gambar'

    WHERE id='$id'
    ");

    if($update){

        echo "
        <script>
            alert('Produk berhasil diupdate!');
            window.location='data.php';
        </script>
        ";

    }else{

        echo "
        <script>
            alert('Gagal update produk!');
        </script>
        ";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - Saka Poultry</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.2/dist/minty/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { background-color: #f4f6f9; }
        .gambar-preview {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 10px;
            border: 3px solid #dee2e6;
        }
    </style>
</head>
<body class="py-5">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-warning text-dark text-center py-3">
                    <h4 class="mb-0 fw-bold"><i class="fa-solid fa-pen-to-square me-2"></i>Edit Produk Telur</h4>
                </div>
                <div class="card-body p-4">
                    <form method="POST" enctype="multipart/form-data">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Nama Telur</label>
                            <input type="text" name="nama_telur" class="form-control" value="<?php echo htmlspecialchars($data['nama_telur']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Jenis Telur</label>
                            <input type="text" name="jenis_telur" class="form-control" value="<?php echo htmlspecialchars($data['jenis_telur']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Harga Jual</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="harga" class="form-control" value="<?php echo htmlspecialchars($data['harga']); ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Harga Modal (Beli)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="harga_modal" class="form-control" value="<?php echo htmlspecialchars($data['harga_modal'] ?? 0); ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Stok</label>
                            <input type="number" name="stok" class="form-control" value="<?php echo htmlspecialchars($data['stok']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Supplier</label>
                            <input type="text" name="supplier" class="form-control" value="<?php echo htmlspecialchars($data['supplier']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Gambar Saat Ini</label>
                            <div>
                                <?php if($data['gambar'] != ""){ ?>
                                    <img src="gambar_produk/<?php echo htmlspecialchars($data['gambar']); ?>" class="gambar-preview mb-2">
                                <?php } else { ?>
                                    <p class="text-muted small">Tidak ada gambar</p>
                                <?php } ?>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-muted">Upload Gambar Baru (Opsional)</label>
                            <input type="file" name="gambar" accept="image/*" class="form-control">
                        </div>

                        <button type="submit" name="update" class="btn btn-warning w-100 fw-bold py-2">
                            <i class="fa-solid fa-save me-1"></i> Update Data
                        </button>
                    </form>
                    
                    <div class="text-center mt-4">
                        <a href="data.php" class="text-decoration-none text-muted">
                            <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Data Produk
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
