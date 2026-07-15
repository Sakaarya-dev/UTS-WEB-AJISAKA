<?php
session_start();

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

$kategori_filter = isset($_GET['kategori']) ? $_GET['kategori'] : '';
if($kategori_filter != ''){
    $kategori_sql = mysqli_real_escape_string($conn, $kategori_filter);
    $query = mysqli_query($conn, "SELECT * FROM produk WHERE jenis_telur='$kategori_sql' ORDER BY id DESC");
} else {
    $query = mysqli_query($conn, "SELECT * FROM produk ORDER BY id DESC");
}

$q_kat = mysqli_query($conn, "SELECT DISTINCT jenis_telur FROM produk");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Produk - Saka Poultry</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.2/dist/minty/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            background-color: #f4f6f9;
        }
        .gambar-frame {
            width: 75px;
            height: 75px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #dee2e6;
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
            <?php if($_SESSION['role'] == 'admin'){ ?>
                <a href="tambah.php" class="btn btn-success btn-sm fw-bold">
                    <i class="fa-solid fa-plus-square"></i> Tambah Produk
                </a>
            <?php } ?>
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

    <div class="d-flex justify-content-between align-items-center mb-4">
        <form method="GET" class="d-flex gap-2">
            <select name="kategori" class="form-select" onchange="this.form.submit()">
                <option value="">Semua Kategori</option>
                <?php while($kat = mysqli_fetch_assoc($q_kat)): ?>
                    <option value="<?php echo htmlspecialchars($kat['jenis_telur']); ?>" <?php if($kategori_filter == $kat['jenis_telur']) echo 'selected'; ?>><?php echo htmlspecialchars($kat['jenis_telur']); ?></option>
                <?php endwhile; ?>
            </select>
        </form>
        <?php if($_SESSION['role'] == 'user'){ ?>
            <a href="keranjang.php" class="btn btn-warning fw-bold shadow-sm">
                <i class="fa-solid fa-cart-shopping"></i> Keranjang Saya
            </a>
        <?php } ?>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0 fw-bold text-primary"><i class="fa-solid fa-file-export me-2"></i>Export Data</h6>
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
                    <h6 class="mb-0 fw-bold text-warning"><i class="fa-solid fa-file-import me-2"></i>Import CSV</h6>
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

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th width="12%" class="text-center">Gambar</th>
                            <th>Nama Produk</th>
                            <th>Jenis</th>
                            <th>Harga</th>
                            <th class="text-center">Stok</th>
                            <th class="text-center">Status</th>
                            <th width="18%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        while($data = mysqli_fetch_assoc($query)){
                        ?>
                        <tr>
                            <td class="text-center fw-bold"><?php echo $no++; ?></td>
                            <td class="text-center">
                                <?php if($data['gambar'] != ""){ ?>
                                    <img src="gambar_produk/<?php echo $data['gambar']; ?>" class="gambar-frame">
                                <?php } else { ?>
                                    <span class="badge bg-secondary">NO IMAGE</span>
                                <?php } ?>
                            </td>
                            <td class="fw-bold"><?php echo htmlspecialchars($data['nama_telur']); ?></td>
                            <td class="text-muted"><?php echo htmlspecialchars($data['jenis_telur']); ?></td>
                            <td class="fw-bold text-success">Rp <?php echo number_format($data['harga']); ?></td>
                            <td class="text-center">
                                <span class="fs-5 fw-bold"><?php echo $data['stok']; ?></span>
                                <span class="text-muted small">btr</span>
                            </td>
                            <td class="text-center">
                                <?php
                                if($data['stok'] <= 0){
                                    echo "<span class='badge bg-danger'>HABIS</span>";
                                }elseif($data['stok'] <= 5){
                                    echo "<span class='badge bg-warning text-dark'>MENIPIS</span>";
                                }else {
                                    echo "<span class='badge bg-success'>AMAN</span>";
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    <?php if($_SESSION['role'] == 'admin'){ ?>
                                        <a href="edit.php?id=<?php echo $data['id']; ?>" class="btn btn-warning btn-sm fw-bold"><i class="fa-solid fa-edit"></i> Edit</a>
                                        <a href="hapus.php?id=<?php echo $data['id']; ?>" class="btn btn-danger btn-sm fw-bold" onclick="return confirm('Yakin ingin menghapus data logistik ini?')"><i class="fa-solid fa-trash"></i> Hapus</a>
                                    <?php } else { ?>
                                        <?php if($data['stok'] > 0){ ?>
                                            <a href="keranjang_tambah.php?id=<?php echo $data['id']; ?>" class="btn btn-primary btn-sm fw-bold w-100"><i class="fa-solid fa-cart-plus"></i> Tambah Keranjang</a>
                                        <?php } else { ?>
                                            <span class="text-danger fw-bold small">OUT OF STOCK</span>
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <a href="dashboard.php" class="text-decoration-none text-muted fw-bold">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>

</div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
