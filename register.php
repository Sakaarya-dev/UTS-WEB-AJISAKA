<?php
include 'koneksi.php';

if(isset($_POST['register'])){

    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $cek = mysqli_query($conn,
    "SELECT * FROM users WHERE email='$email'");

    if(mysqli_num_rows($cek) > 0){

        $error = "Email sudah digunakan";

    } else {

        mysqli_query($conn,
        "INSERT INTO users
        (username, email, password)
        VALUES
        ('$username', '$email', '$password')");

        header("Location: login.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Saka Poultry</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.2/dist/minty/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            background: url('bg_ayam.png') no-repeat center center fixed;
            background-size: cover;
        }
        .register-card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .register-card .card-header {
            background-color: transparent;
            border-bottom: none;
            padding-top: 2rem;
            padding-bottom: 0;
            text-align: center;
        }
        .logo-icon {
            font-size: 3rem;
            color: #198754;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body class="d-flex align-items-center py-4 min-vh-100">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card register-card">
                <div class="card-header">
                    <i class="fa-solid fa-user-plus logo-icon"></i>
                    <h3 class="mb-0 fw-bold">Daftar Akun</h3>
                    <p class="text-muted">Saka Poultry</p>
                </div>
                <div class="card-body p-4">
                    
                    <?php if(isset($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fa-solid fa-circle-exclamation me-1"></i> <?php echo htmlspecialchars($error); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Username</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="fa-solid fa-user text-muted"></i></span>
                                <input type="text" name="username" class="form-control" placeholder="Username" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="fa-solid fa-envelope text-muted"></i></span>
                                <input type="email" name="email" class="form-control" placeholder="Email aktif" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-muted small fw-bold">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="fa-solid fa-lock text-muted"></i></span>
                                <input type="password" name="password" class="form-control" placeholder="Password" required>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" name="register" class="btn btn-success py-2 fw-bold">
                                <i class="fa-solid fa-user-check me-1"></i> Daftar Sekarang
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-4 pt-2 border-top">
                        <p class="mb-0 text-muted small">Sudah punya akun? <a href="login.php" class="text-success text-decoration-none fw-bold">Login</a></p>
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
