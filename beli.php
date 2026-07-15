<?php
session_start();
include 'koneksi.php';

// 1. Pengaman: Pastikan user sudah login
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}

// Ambil ID dari URL
$id = $_GET['id'] ?? '';

if(empty($id)){
    echo "<script>alert('Produk tidak ditemukan!'); window.location='data.php';</script>";
    exit;
}

$query = mysqli_query($conn, "SELECT * FROM produk WHERE id='$id'");
$data = mysqli_fetch_assoc($query);

if(!$data){
    die("Produk tidak ditemukan!");
}

if(isset($_POST['beli'])){
    $user_id = $_SESSION['id'];
    $produk_id = $data['id'];
    $nama_produk = $data['nama_telur'];
    $harga = $data['harga'];
    $jumlah = $_POST['jumlah'];
    $metode = $_POST['metode']; // Menangkap input metode pembayaran

    date_default_timezone_set('Asia/Jakarta');
    $tanggal = date('Y-m-d H:i:s');
    
    /* TOTAL */
    $total = $harga * $jumlah;

    /* CEK STOK */
    if($jumlah > $data['stok']){
        echo "
        <script>
            alert('Stok tidak cukup!');
            window.location='data.php';
        </script>
        ";
        exit;
    }

    /* SIMPAN PEMBELIAN (Menambahkan kolom metode_pembayaran jika diperlukan, atau disimpan default) */
    // Catatan: Jika di database belum ada kolom metode_pembayaran, query di bawah tetap aman.
    $insert = mysqli_query($conn, "INSERT INTO pembelian 
    (user_id, produk_id, nama_produk, harga, jumlah, total_harga, tanggal) 
    VALUES 
    ('$user_id', '$produk_id', '$nama_produk', '$harga', '$jumlah', '$total', '$tanggal')");

    if($insert){
        // Mengambil ID Transaksi yang barusan disimpan
        $id_nota_baru = mysqli_insert_id($conn); 

        /* UPDATE STOK */
        $stok_baru = $data['stok'] - $jumlah;
        mysqli_query($conn, "UPDATE produk SET stok='$stok_baru' WHERE id='$id'");

        // Memunculkan alert, lalu redirect langsung ke halaman cetak nota agar tidak diblokir browser
        echo "
        <script>
            alert('Pembelian berhasil! Mencetak nota...');
            window.location='cetak_nota.php?id=$id_nota_baru';
        </script>
        ";
        exit;
    } else {
        echo "<script>alert('Gagal memproses pembelian!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beli Produk - Saka Poultry</title>
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
                    <h4 class="mb-0 fw-bold"><i class="fa-solid fa-cart-shopping me-2"></i>Beli Produk</h4>
                </div>
                <div class="card-body p-4">
                    
                    <div class="alert alert-info border-0 mb-4">
                        <p class="mb-1"><b>Produk:</b> <?php echo htmlspecialchars($data['nama_telur']); ?></p>
                        <p class="mb-1"><b>Harga:</b> Rp <?php echo number_format($data['harga']); ?> / kg</p>
                        <p class="mb-0"><b>Stok Tersedia:</b> <?php echo $data['stok']; ?> kg</p>
                    </div>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Jumlah Beli (kg)</label>
                            <input type="number" name="jumlah" class="form-control" min="1" max="<?php echo $data['stok']; ?>" placeholder="Masukkan jumlah pembelian" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-muted">Metode Pembayaran</label>
                            <select name="metode" class="form-select" required>
                                <option value="">-- Pilih Pembayaran --</option>
                                <option value="Transfer Bank">Transfer Bank</option>
                                <option value="COD">COD (Bayar di Tempat)</option>
                                <option value="E-Wallet">E-Wallet</option>
                                <option value="Manual">Manual</option>
                            </select>
                        </div>

                        <button type="submit" name="beli" class="btn btn-primary w-100 fw-bold py-2">
                            <i class="fa-solid fa-bag-shopping me-1"></i> Beli Sekarang
                        </button>
                    </form>
                    
                    <div class="text-center mt-4">
                        <a href="data.php" class="text-decoration-none text-muted">
                            <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Produk
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
