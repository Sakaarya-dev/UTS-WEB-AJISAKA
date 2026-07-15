<?php
session_start();
include 'koneksi.php';

// Fetch products for showcase
$query_produk = mysqli_query($conn, "SELECT * FROM produk WHERE stok > 0 LIMIT 6");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saka Poultry - Segar & Berkualitas</title>
    <!-- Bootstrap 5 CSS (Minty Theme) -->
    <link href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.2/dist/minty/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .hero {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('bg_ayam.png') no-repeat center center;
            background-size: cover;
            color: white;
            padding: 100px 0;
            text-align: center;
        }
        .hero h1 {
            font-size: 3.5rem;
            font-weight: bold;
            color: #ffffff;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.6);
        }
        .feature-box {
            padding: 30px;
            text-align: center;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s ease;
        }
        .feature-box:hover {
            transform: translateY(-10px);
        }
        .feature-icon {
            font-size: 3rem;
            color: #20c997;
            margin-bottom: 15px;
        }
        .product-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            overflow: hidden;
            transition: transform 0.3s ease;
        }
        .product-card:hover {
            transform: scale(1.03);
        }
        .product-img {
            height: 200px;
            object-fit: cover;
        }
    </style>
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">
            <i class="fa-solid fa-egg text-warning"></i> Saka Poultry
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto gap-2">
                <?php if(isset($_SESSION['user'])): ?>
                    <li class="nav-item">
                        <a class="btn btn-outline-light fw-bold" href="dashboard.php">Dashboard</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link fw-bold" href="login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-warning fw-bold text-dark" href="register.php">Daftar Akun</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <h1 class="mb-4">Telur Segar Langsung dari Peternakan</h1>
        <p class="lead mb-5">Menyediakan berbagai jenis telur berkualitas tinggi, bebas bakteri, dan kaya nutrisi untuk kebutuhan keluarga dan bisnis Anda.</p>
        <a href="#produk" class="btn btn-success btn-lg fw-bold px-5 py-3 rounded-pill">Lihat Produk Kami</a>
    </div>
</section>

<!-- Keunggulan Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Mengapa Memilih Kami?</h2>
            <p class="text-muted">Kualitas terbaik yang menjamin kepuasan Anda</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-box h-100">
                    <i class="fa-solid fa-leaf feature-icon"></i>
                    <h5 class="fw-bold">100% Organik & Segar</h5>
                    <p class="text-muted">Telur dipanen setiap pagi dari peternakan kami sendiri, memastikan kesegaran maksimal.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-box h-100">
                    <i class="fa-solid fa-shield-virus feature-icon"></i>
                    <h5 class="fw-bold">Bebas Bakteri</h5>
                    <p class="text-muted">Melewati proses sterilisasi modern (UV) sehingga aman dikonsumsi oleh keluarga Anda.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-box h-100">
                    <i class="fa-solid fa-truck-fast feature-icon"></i>
                    <h5 class="fw-bold">Pengiriman Cepat</h5>
                    <p class="text-muted">Layanan antar langsung ke rumah Anda di hari yang sama untuk menjaga kualitas.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Produk Section -->
<section id="produk" class="py-5 bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Katalog Produk</h2>
            <p class="text-muted">Pilih telur terbaik sesuai kebutuhan Anda</p>
        </div>
        <div class="row g-4">
            <?php 
            if(mysqli_num_rows($query_produk) > 0): 
                while($row = mysqli_fetch_assoc($query_produk)): 
                    $img_src = !empty($row['gambar']) ? 'gambar_produk/' . $row['gambar'] : 'https://via.placeholder.com/300x200?text=No+Image';
            ?>
                <div class="col-md-4">
                    <div class="card product-card h-100">
                        <img src="<?php echo $img_src; ?>" class="card-img-top product-img" alt="<?php echo htmlspecialchars($row['nama_telur']); ?>">
                        <div class="card-body">
                            <h5 class="card-title fw-bold text-primary mb-1"><?php echo htmlspecialchars($row['nama_telur']); ?></h5>
                            <span class="badge bg-info mb-2"><?php echo htmlspecialchars($row['jenis_telur']); ?></span>
                            <h4 class="text-success fw-bold">Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></h4>
                        </div>
                        <div class="card-footer bg-white border-0 pb-3">
                            <a href="login.php" class="btn btn-primary w-100 fw-bold">
                                <i class="fa-solid fa-cart-shopping me-1"></i> Beli Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            <?php 
                endwhile;
            else: 
            ?>
                <div class="col-12 text-center py-5">
                    <p class="text-muted">Belum ada produk yang tersedia.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="bg-dark text-white py-4 text-center">
    <div class="container">
        <p class="mb-0">&copy; <?php echo date('Y'); ?> Saka Poultry. Hak Cipta Dilindungi.</p>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
