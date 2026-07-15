<?php
session_start();

// Jika session user belum ada, tendang kembali ke login.php
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

// Mengambil total produk untuk counter statistik
$query_produk = mysqli_query($conn, "SELECT COUNT(*) as total FROM produk");
$total_produk = 0;
if($query_produk) {
    $data_produk = mysqli_fetch_assoc($query_produk);
    $total_produk = $data_produk['total'] ?? 0;
}

// Mengambil total transaksi dari tabel pembelian
$query_transaksi = mysqli_query($conn, "SELECT COUNT(*) as total FROM pembelian");
$total_transaksi = 0;
if($query_transaksi) {
    $data_transaksi = mysqli_fetch_assoc($query_transaksi);
    $total_transaksi = $data_transaksi['total'] ?? 0;
}

// Data untuk Chart.js
$role_user = isset($_SESSION['role']) ? $_SESSION['role'] : 'admin';
$user_id = $_SESSION['id'] ?? 0;

$chart_labels = [];
$chart_data = [];

if($role_user == 'admin') {
    // Admin: Profit per tanggal (7 hari terakhir)
    $q_chart = mysqli_query($conn, "SELECT DATE(tanggal) as tgl, SUM(total_harga - (harga_modal * jumlah)) as profit FROM pembelian WHERE tanggal IS NOT NULL AND tanggal != '0000-00-00 00:00:00' GROUP BY DATE(tanggal) ORDER BY DATE(tanggal) ASC LIMIT 7");
    if($q_chart) {
        while($row = mysqli_fetch_assoc($q_chart)){
            $chart_labels[] = date('d M', strtotime($row['tgl']));
            $chart_data[] = $row['profit'];
        }
    }
} else {
    // User: Total pengeluaran per tanggal (7 hari terakhir)
    $q_chart = mysqli_query($conn, "SELECT DATE(tanggal) as tgl, SUM(total_harga) as total FROM pembelian WHERE user_id = '$user_id' AND tanggal IS NOT NULL AND tanggal != '0000-00-00 00:00:00' GROUP BY DATE(tanggal) ORDER BY DATE(tanggal) ASC LIMIT 7");
    if($q_chart) {
        while($row = mysqli_fetch_assoc($q_chart)){
            $chart_labels[] = date('d M', strtotime($row['tgl']));
            $chart_data[] = $row['total'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Saka Poultry</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.2/dist/minty/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            background-color: #f4f6f9;
        }
        .icon-wrapper {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        .card-menu:hover {
            transform: translateY(-5px);
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
            transition: all .3s ease;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="#">
            <i class="fa-solid fa-egg text-warning"></i> Saka Poultry
        </a>
        <div class="d-flex ms-auto gap-2">
            <a href="profil.php" class="btn btn-outline-info btn-sm fw-bold">
                <i class="fa-solid fa-user-edit"></i> Profil
            </a>
            <a href="logout.php" class="btn btn-outline-danger btn-sm fw-bold">
                <i class="fa-solid fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>
</nav>

<div class="container pb-5">
    
    <?php if($role_user == 'admin'): 
        $cek_stok = mysqli_query($conn, "SELECT nama_telur, stok FROM produk WHERE stok < 10");
        if(mysqli_num_rows($cek_stok) > 0):
    ?>
        <div class="alert alert-warning alert-dismissible fade show shadow-sm" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i><strong>Peringatan Stok Menipis!</strong>
            Produk berikut stoknya hampir habis (kurang dari 10): 
            <?php 
                $stok_habis = [];
                while($s = mysqli_fetch_assoc($cek_stok)){
                    $stok_habis[] = "<strong>" . htmlspecialchars($s['nama_telur']) . "</strong> (" . $s['stok'] . ")";
                }
                echo implode(", ", $stok_habis);
            ?>. Segera lakukan restock.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php 
        endif; 
    endif; ?>
    <div class="card border-0 shadow-sm mb-4 bg-primary text-white">
        <div class="card-body p-4">
            <h3 class="fw-bold mb-2">Selamat Datang, <?php echo htmlspecialchars($_SESSION['user']); ?>!</h3>
            <p class="mb-0 text-white-50">Sistem Manajemen Hub Pusat: Logistik Stok, Pemantauan Transaksi, dan Rekapitulasi Data Operasional.</p>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center justify-content-between p-4">
                    <div>
                        <h2 class="fw-bold text-primary mb-1"><?php echo $total_produk; ?></h2>
                        <p class="text-muted fw-bold small text-uppercase mb-0">Total Produk Telur</p>
                    </div>
                    <i class="fa-solid fa-box-open fa-3x text-primary opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center justify-content-between p-4">
                    <div>
                        <h2 class="fw-bold text-success mb-1"><?php echo $total_transaksi; ?></h2>
                        <p class="text-muted fw-bold small text-uppercase mb-0">Total Transaksi</p>
                    </div>
                    <i class="fa-solid fa-receipt fa-3x text-success opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik Statistik (Chart.js) -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 fw-bold text-dark">
                        <i class="fa-solid fa-chart-area me-2 <?php echo $role_user == 'admin' ? 'text-primary' : 'text-warning'; ?>"></i> 
                        <?php echo $role_user == 'admin' ? 'Statistik Keuntungan Bersih (7 Hari Terakhir)' : 'Statistik Pengeluaran Belanja (7 Hari Terakhir)'; ?>
                    </h6>
                </div>
                <div class="card-body">
                    <?php if (count($chart_labels) > 0) { ?>
                        <canvas id="mainChart" height="80"></canvas>
                    <?php } else { ?>
                        <div class="text-center text-muted py-5">
                            <i class="fa-solid fa-chart-bar fa-3x mb-3 opacity-25"></i>
                            <p class="mb-0 fw-bold">Belum ada data transaksi yang cukup untuk menampilkan grafik.</p>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <?php 
        // 🔥 SISTEM ANTIBLANK: Jika session role tidak ada/kosong, paksa default sebagai admin
        $role_user = isset($_SESSION['role']) ? $_SESSION['role'] : 'admin';

        if($role_user == 'admin'){ 
        ?>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 text-center p-4 card-menu">
                    <div class="card-body">
                        <i class="fa-solid fa-square-plus text-primary icon-wrapper"></i>
                        <h5 class="fw-bold">Tambah Produk</h5>
                        <p class="text-muted small mb-4">Menambahkan varian logistik telur baru ke dalam database gudang pusat.</p>
                        <a href="tambah.php" class="btn btn-primary w-100 fw-bold">Tambah Produk</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 text-center p-4 card-menu">
                    <div class="card-body">
                        <i class="fa-solid fa-table-list text-info icon-wrapper"></i>
                        <h5 class="fw-bold">Data Produk</h5>
                        <p class="text-muted small mb-4">Kelola seluruh stok barang gudang, sistem ekspor laporan, dan impor massal.</p>
                        <a href="data.php" class="btn btn-info text-white w-100 fw-bold">Lihat Produk</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 text-center p-4 card-menu">
                    <div class="card-body">
                        <i class="fa-solid fa-chart-line text-success icon-wrapper"></i>
                        <h5 class="fw-bold">Semua Transaksi</h5>
                        <p class="text-muted small mb-4">Memonitor jalannya alur logistik masuk-keluar serta invoice pembeli secara global.</p>
                        <a href="transaksi.php" class="btn btn-success w-100 fw-bold">Lihat Transaksi</a>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100 text-center p-4 card-menu">
                    <div class="card-body">
                        <i class="fa-solid fa-basket-shopping text-warning icon-wrapper"></i>
                        <h5 class="fw-bold">Beli Produk</h5>
                        <p class="text-muted small mb-4">Jelajahi pilihan pasokan telur segar terbaik hari ini dan lakukan pesanan instan.</p>
                        <a href="data.php" class="btn btn-warning text-dark w-100 fw-bold">Beli Produk</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100 text-center p-4 card-menu">
                    <div class="card-body">
                        <i class="fa-solid fa-clock-rotate-left text-secondary icon-wrapper"></i>
                        <h5 class="fw-bold">Riwayat Pembelian</h5>
                        <p class="text-muted small mb-4">Cek arsip nota pembayaran, histori transaksi, dan status logistik pesananmu.</p>
                        <a href="riwayat.php" class="btn btn-secondary w-100 fw-bold">Lihat Riwayat</a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>

</div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
<?php if (count($chart_labels) > 0) { ?>
    const ctx = document.getElementById('mainChart').getContext('2d');
    const chartLabels = <?php echo json_encode($chart_labels); ?>;
    const chartData = <?php echo json_encode($chart_data); ?>;
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartLabels,
            datasets: [{
                label: '<?php echo $role_user == "admin" ? "Keuntungan Bersih" : "Pengeluaran"; ?>',
                data: chartData,
                borderColor: '<?php echo $role_user == "admin" ? "#0d6efd" : "#ffc107"; ?>',
                backgroundColor: '<?php echo $role_user == "admin" ? "rgba(13, 110, 253, 0.2)" : "rgba(255, 193, 7, 0.2)"; ?>',
                borderWidth: 2,
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
<?php } ?>
</script>
</body>
</html>
