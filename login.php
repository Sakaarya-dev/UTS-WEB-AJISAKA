<?php
session_start();
include 'koneksi.php';

if(isset($_SESSION['user'])){
    header("Location: dashboard.php");
    exit;
}

$error = '';

if(isset($_POST['login'])){
    // trim() berfungsi menghapus spasi tak sengaja di awal/akhir inputan
    $username = trim(mysqli_real_escape_string($conn, $_POST['username']));
    $password_input = $_POST['password']; 
    
    // Kita siapkan versi MD5-nya juga
    $password_md5 = md5($password_input); 

    // Jalankan query pencarian berdasarkan username ATAU email
    $query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' OR email='$username'");
    
    if($query && mysqli_num_rows($query) >= 1){
        $data = mysqli_fetch_assoc($query);
        
        // Pengecekan Ganda: COCOKKAN dengan password biasa ATAU password MD5
        if($password_input === $data['password'] || $password_md5 === $data['password']){
            $_SESSION['id'] = $data['id']; // Ditambahkan agar fitur riwayat.php bisa berfungsi
            $_SESSION['user'] = $data['username'];
            $_SESSION['role'] = $data['role']; 
            header("Location: dashboard.php");
            exit;
        } else {
            $error = 'ACCESS DENIED: Password yang Anda masukkan salah!';
        }
    } else {
        $error = 'ACCESS DENIED: Akun (Username/Email) tidak ditemukan di database!';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Saka Poultry</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.2/dist/minty/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            background: url('bg_ayam.png') no-repeat center center fixed;
            background-size: cover;
        }
        .login-card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .login-card .card-header {
            background-color: transparent;
            border-bottom: none;
            padding-top: 2rem;
            padding-bottom: 0;
            text-align: center;
        }
        .logo-icon {
            font-size: 3rem;
            color: #0d6efd;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body class="d-flex align-items-center py-4 min-vh-100">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card login-card">
                <div class="card-header">
                    <i class="fa-solid fa-wheat-awn logo-icon"></i>
                    <h3 class="mb-0 fw-bold">Masuk</h3>
                    <p class="text-muted">Saka Poultry</p>
                </div>
                <div class="card-body p-4">
                    
                    <?php if(!empty($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fa-solid fa-circle-exclamation me-1"></i> <?php echo htmlspecialchars($error); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Username atau Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="fa-solid fa-user text-muted"></i></span>
                                <input type="text" name="username" class="form-control" placeholder="Masukkan Username / Email" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-muted small fw-bold">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="fa-solid fa-lock text-muted"></i></span>
                                <input type="password" name="password" class="form-control" placeholder="Masukkan Password" required>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" name="login" class="btn btn-primary py-2 fw-bold">
                                <i class="fa-solid fa-right-to-bracket me-1"></i> Login Sekarang
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-4 pt-2 border-top">
                        <p class="mb-0 text-muted small">Belum Punya Akun? <a href="register.php" class="text-primary text-decoration-none fw-bold">Register</a></p>
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
