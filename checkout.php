<?php
session_start();
if(!isset($_SESSION['user'])){ header("Location: login.php"); exit; }
include 'koneksi.php';

$user_id = $_SESSION['id'];

// Check cart
$query = mysqli_query($conn, "SELECT k.*, p.nama_telur, p.harga, p.harga_modal, p.stok FROM keranjang k JOIN produk p ON k.produk_id = p.id WHERE k.user_id='$user_id'");

if(mysqli_num_rows($query) == 0){
    echo "<script>alert('Keranjang kosong!'); window.location='data.php';</script>";
    exit;
}

if(isset($_POST['proses_checkout'])){
    date_default_timezone_set('Asia/Jakarta');
    $tanggal = date('Y-m-d H:i:s');
    $semua_berhasil = true;
    
    while($row = mysqli_fetch_assoc($query)){
        $produk_id = $row['produk_id'];
        $nama_produk = $row['nama_telur'];
        $harga = $row['harga'];
        $harga_modal = $row['harga_modal'] ?? 0;
        $jumlah = $row['jumlah'];
        $total_harga = $harga * $jumlah;
        
        // Cek stok lagi
        if($jumlah > $row['stok']){
            $semua_berhasil = false;
            echo "<script>alert('Stok $nama_produk tidak cukup!'); window.location='keranjang.php';</script>";
            exit;
        }
        
        $insert = mysqli_query($conn, "INSERT INTO pembelian 
        (user_id, produk_id, nama_produk, harga, harga_modal, jumlah, total_harga, tanggal) 
        VALUES 
        ('$user_id', '$produk_id', '$nama_produk', '$harga', '$harga_modal', '$jumlah', '$total_harga', '$tanggal')");
        
        if($insert){
            $stok_baru = $row['stok'] - $jumlah;
            mysqli_query($conn, "UPDATE produk SET stok='$stok_baru' WHERE id='$produk_id'");
        } else {
            $semua_berhasil = false;
        }
    }
    
    if($semua_berhasil){
        mysqli_query($conn, "DELETE FROM keranjang WHERE user_id='$user_id'");
        echo "<script>alert('Checkout berhasil! Terima kasih telah berbelanja.'); window.location='riwayat.php';</script>";
        exit;
    } else {
        echo "<script>alert('Gagal memproses checkout!'); window.location='keranjang.php';</script>";
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Saka Poultry</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.2/dist/minty/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style> body { background-color: #f4f6f9; } </style>
</head>
<body class="py-5">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-success text-white py-3">
                    <h4 class="mb-0 fw-bold"><i class="fa-solid fa-check-circle me-2"></i>Konfirmasi Checkout</h4>
                </div>
                <div class="card-body p-4">
                    <h5 class="fw-bold border-bottom pb-2 mb-3">Ringkasan Pesanan</h5>
                    <div class="table-responsive mb-4">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th>Qty</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $total_semua = 0;
                                mysqli_data_seek($query, 0); // reset pointer
                                while($row = mysqli_fetch_assoc($query)): 
                                    $sub = $row['harga'] * $row['jumlah'];
                                    $total_semua += $sub;
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['nama_telur']); ?></td>
                                    <td>Rp <?php echo number_format($row['harga']); ?></td>
                                    <td><?php echo $row['jumlah']; ?></td>
                                    <td class="text-end fw-bold">Rp <?php echo number_format($sub); ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-end fs-5">Total Pembayaran:</th>
                                    <th class="text-end fs-5 text-success">Rp <?php echo number_format($total_semua); ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <form method="POST">
                        <div class="mb-4">
                            <label class="form-label fw-bold text-muted">Metode Pembayaran</label>
                            <select name="metode" class="form-select" required>
                                <option value="">-- Pilih Pembayaran --</option>
                                <option value="Transfer Bank">Transfer Bank</option>
                                <option value="COD">COD (Bayar di Tempat)</option>
                                <option value="E-Wallet">E-Wallet</option>
                            </select>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="keranjang.php" class="btn btn-outline-secondary fw-bold"><i class="fa-solid fa-arrow-left me-1"></i> Kembali</a>
                            <button type="submit" name="proses_checkout" class="btn btn-success fw-bold px-4"><i class="fa-solid fa-money-bill-wave me-1"></i> Proses Pesanan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
