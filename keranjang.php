<?php
session_start();
if(!isset($_SESSION['user'])){ header("Location: login.php"); exit; }
include 'koneksi.php';

$user_id = $_SESSION['id'];

// Proses ubah jumlah
if(isset($_POST['update_qty'])){
    $keranjang_id = $_POST['keranjang_id'];
    $jumlah = $_POST['jumlah'];
    if($jumlah > 0){
        mysqli_query($conn, "UPDATE keranjang SET jumlah='$jumlah' WHERE id='$keranjang_id' AND user_id='$user_id'");
    } else {
        mysqli_query($conn, "DELETE FROM keranjang WHERE id='$keranjang_id' AND user_id='$user_id'");
    }
    header("Location: keranjang.php");
    exit;
}

// Proses hapus item
if(isset($_GET['hapus'])){
    $keranjang_id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM keranjang WHERE id='$keranjang_id' AND user_id='$user_id'");
    header("Location: keranjang.php");
    exit;
}

// Ambil isi keranjang
$query = mysqli_query($conn, "SELECT k.*, p.nama_telur, p.harga, p.gambar, p.stok FROM keranjang k JOIN produk p ON k.produk_id = p.id WHERE k.user_id='$user_id'");
$total_belanja = 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - Saka Poultry</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.2/dist/minty/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { background-color: #f4f6f9; }
        .gambar-keranjang { width: 80px; height: 80px; object-fit: cover; border-radius: 8px; }
    </style>
</head>
<body class="py-5">

<div class="container">
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-warning text-dark py-3">
            <h4 class="mb-0 fw-bold"><i class="fa-solid fa-cart-shopping me-2"></i>Keranjang Belanja</h4>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Produk</th>
                            <th>Harga</th>
                            <th width="15%">Jumlah</th>
                            <th>Subtotal</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if(mysqli_num_rows($query) > 0):
                            while($row = mysqli_fetch_assoc($query)): 
                                $subtotal = $row['harga'] * $row['jumlah'];
                                $total_belanja += $subtotal;
                        ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <?php $img = !empty($row['gambar']) ? 'gambar_produk/'.$row['gambar'] : 'https://via.placeholder.com/80'; ?>
                                        <img src="<?php echo $img; ?>" class="gambar-keranjang">
                                        <div>
                                            <h6 class="fw-bold mb-0"><?php echo htmlspecialchars($row['nama_telur']); ?></h6>
                                            <small class="text-muted">Stok: <?php echo $row['stok']; ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>Rp <?php echo number_format($row['harga']); ?></td>
                                <td>
                                    <form method="POST" class="d-flex gap-1">
                                        <input type="hidden" name="keranjang_id" value="<?php echo $row['id']; ?>">
                                        <input type="number" name="jumlah" class="form-control form-control-sm" value="<?php echo $row['jumlah']; ?>" min="1" max="<?php echo $row['stok']; ?>">
                                        <button type="submit" name="update_qty" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-sync"></i></button>
                                    </form>
                                </td>
                                <td class="fw-bold text-success">Rp <?php echo number_format($subtotal); ?></td>
                                <td class="text-center">
                                    <a href="keranjang.php?hapus=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php 
                            endwhile;
                        else:
                        ?>
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <h5 class="text-muted">Keranjang masih kosong.</h5>
                                    <a href="data.php" class="btn btn-primary mt-2">Belanja Sekarang</a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php if($total_belanja > 0): ?>
        <div class="card-footer bg-white py-4">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0 fw-bold">Total: <span class="text-success">Rp <?php echo number_format($total_belanja); ?></span></h4>
                <a href="checkout.php" class="btn btn-success btn-lg fw-bold px-5">Checkout <i class="fa-solid fa-arrow-right ms-2"></i></a>
            </div>
        </div>
        <?php endif; ?>
    </div>
    
    <a href="data.php" class="text-decoration-none text-muted fw-bold"><i class="fa-solid fa-arrow-left me-1"></i> Lanjut Belanja</a>
</div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
