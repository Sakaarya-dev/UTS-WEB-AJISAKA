<?php
session_start();
include 'koneksi.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['id'];

// Cek apakah user yang login memiliki role admin
// Catatan: Sesuaikan nama session 'role' atau 'level' dengan yang ada di proses login-mu
$is_admin = isset($_SESSION['role']) && $_SESSION['role'] == 'admin'; 

// Jika dia admin, tampilkan SEMUA data pembelian di toko untuk laporan.
// Jika dia pembeli biasa, hanya tampilkan data pembelian milik dia sendiri.
if ($is_admin) {
    $query = mysqli_query($conn, "SELECT pembelian.*, users.username FROM pembelian LEFT JOIN users ON pembelian.user_id = users.id ORDER BY tanggal DESC");
} else {
    $query = mysqli_query($conn, "SELECT * FROM pembelian WHERE user_id='$user_id' ORDER BY tanggal DESC");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pembelian - Saka Poultry</title>
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
        <h3 class="fw-bold mb-0 text-dark">
            <i class="fa-solid fa-clock-rotate-left text-primary me-2"></i>
            Riwayat Pembelian <?php echo $is_admin ? '(Admin Mode)' : ''; ?>
        </h3>
        <a href="dashboard.php" class="btn btn-secondary fw-bold">
            <i class="fa-solid fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <?php if ($is_admin) { ?>
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0 fw-bold text-primary"><i class="fa-solid fa-download me-2"></i>Admin Ekspor Laporan</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="ekspor.php?jenis=excel" class="btn btn-success fw-bold"><i class="fa-solid fa-file-excel me-1"></i> Excel</a>
                        <a href="ekspor.php?jenis=word" class="btn btn-primary fw-bold"><i class="fa-solid fa-file-word me-1"></i> Word</a>
                        <a href="ekspor.php?jenis=pdf" target="_blank" class="btn btn-danger fw-bold"><i class="fa-solid fa-file-pdf me-1"></i> PDF</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0 fw-bold text-warning"><i class="fa-solid fa-upload me-2"></i>Admin Impor (CSV)</h6>
                </div>
                <div class="card-body">
                    <form action="impor.php" method="POST" enctype="multipart/form-data" class="d-flex gap-2">
                        <input type="file" name="file_csv" accept=".csv" class="form-control" required>
                        <button type="submit" name="import" class="btn btn-warning fw-bold text-nowrap">
                            <i class="fa-solid fa-upload"></i> Upload
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <?php if($is_admin) { echo "<th>Pembeli</th>"; } ?>
                            <th>Produk</th>
                            <th>Harga</th>
                            <th class="text-center">Jumlah</th>
                            <th>Total</th>
                            <th>Tanggal</th>
                            <th class="text-center">Rating</th>
                            <th>Ulasan</th>
                            <th class="text-center">Aksi</th>
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
                            <?php if($is_admin) { ?>
                                <td class="fw-bold text-primary"><?php echo htmlspecialchars($data['username'] ?? 'User Dihapus'); ?></td>
                            <?php } ?>
                            <td class="fw-bold"><?php echo htmlspecialchars($data['nama_produk']); ?></td>
                            <td class="text-success">Rp <?php echo number_format($data['harga']); ?></td>
                            <td class="text-center fw-bold"><?php echo $data['jumlah']; ?></td>
                            <td class="fw-bold text-primary">Rp <?php echo number_format($data['total_harga']); ?></td>
                            <td class="text-muted small">
                                <?php
                                if($data['tanggal'] == "" || $data['tanggal'] == "0000-00-00 00:00:00"){
                                    echo "-";
                                }else{
                                    date_default_timezone_set('Asia/Jakarta');
                                    echo date('d M Y - H:i', strtotime($data['tanggal']));
                                }
                                ?>
                            </td>

                            <?php if(empty($data['rating'])){ ?>
                                <td colspan="2">
                                    <?php if($is_admin) { ?>
                                        <span class="text-muted small fst-italic">Belum diulas pembeli</span>
                                    <?php } else { ?>
                                        <form method="POST" action="rating.php" class="d-flex gap-2 align-items-center">
                                            <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
                                            <select name="rating" class="form-select form-select-sm" style="width: auto;" required>
                                                <option value="">Rating</option>
                                                <option value="1">⭐ 1</option>
                                                <option value="2">⭐⭐ 2</option>
                                                <option value="3">⭐⭐⭐ 3</option>
                                                <option value="4">⭐⭐⭐⭐ 4</option>
                                                <option value="5">⭐⭐⭐⭐⭐ 5</option>
                                            </select>
                                            <input type="text" name="ulasan" class="form-control form-control-sm" placeholder="Tulis ulasan..." required>
                                            <button type="submit" class="btn btn-success btn-sm fw-bold">Kirim</button>
                                        </form>
                                    <?php } ?>
                                </td>
                            <?php } else { ?>
                                <td class="text-center"><span class="badge bg-warning text-dark"><i class="fa-solid fa-star"></i> <?php echo $data['rating']; ?></span></td>
                                <td class="text-muted small"><?php echo htmlspecialchars($data['ulasan']); ?></td>
                            <?php } ?>

                            <td class="text-center">
                                <a href="cetak_nota.php?id=<?php echo $data['id']; ?>" target="_blank" class="btn btn-info btn-sm text-white fw-bold">
                                    <i class="fa-solid fa-print"></i> Cetak
                                </a>
                            </td>
                        </tr>
                        <?php 
                            } 
                        } else { 
                        ?>
                        <tr>
                            <td colspan="<?php echo $is_admin ? '10' : '9'; ?>" class="text-center py-4 text-muted">Belum ada pembelian</td>
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
