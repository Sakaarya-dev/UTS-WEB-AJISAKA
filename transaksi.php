<?php
session_start();

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}

if($_SESSION['role'] != 'admin'){
    header("Location: dashboard.php");
    exit;
}

include 'koneksi.php';

/* =========================
   AMBIL DATA TRANSAKSI
========================= */

$query = mysqli_query($conn,
"SELECT * FROM pembelian
ORDER BY tanggal DESC");

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Transaksi - Saka Poultry</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.2/dist/minty/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { background-color: #f4f6f9; }
    </style>
</head>
<body>

<div class="container py-5">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-cart-shopping text-primary me-2"></i>Data Transaksi</h3>
        <a href="dashboard.php" class="btn btn-secondary fw-bold">
            <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Dashboard
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th class="text-center">Jumlah</th>
                            <th>Total Harga</th>
                            <th>Tanggal</th>
                            <th class="text-center">Rating</th>
                            <th>Ulasan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        if(mysqli_num_rows($query) > 0){
                            while($data = mysqli_fetch_assoc($query)){
                        ?>
                        <tr>
                            <td class="text-center fw-bold"><?php echo $no++; ?></td>
                            <td class="fw-bold"><?php echo htmlspecialchars($data['nama_produk']); ?></td>
                            <td class="text-success">Rp <?php echo number_format($data['harga']); ?></td>
                            <td class="text-center fw-bold"><?php echo $data['jumlah']; ?></td>
                            <td class="fw-bold text-primary">Rp <?php echo number_format($data['total_harga']); ?></td>
                            <td class="text-muted small">
                                <?php
                                date_default_timezone_set('Asia/Jakarta');
                                if($data['tanggal'] != "" && $data['tanggal'] != "0000-00-00 00:00:00"){
                                    echo date('d M Y - H:i', strtotime($data['tanggal']));
                                } else {
                                    echo "-";
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <?php if($data['rating']) { ?>
                                    <span class="badge bg-warning text-dark"><i class="fa-solid fa-star"></i> <?php echo $data['rating']; ?></span>
                                <?php } else { echo "-"; } ?>
                            </td>
                            <td class="text-muted small"><?php echo $data['ulasan'] ? htmlspecialchars($data['ulasan']) : '-'; ?></td>
                        </tr>
                        <?php
                            }
                        } else {
                        ?>
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">Belum ada transaksi</td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
