<?php
session_start();
include 'koneksi.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}

$id = $_SESSION['id'];

$query = mysqli_query($conn,
"SELECT * FROM users WHERE id='$id'");

$data = mysqli_fetch_assoc($query);

if(isset($_POST['submit'])){

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password_lama = $_POST['password_lama'];
    $password_baru = $_POST['password_baru'];
    $konfirmasi_password = $_POST['konfirmasi_password'];

    // FOTO
    $foto = $data['foto'];

    if($_FILES['foto']['name'] != ""){
        $folder = "foto_profil/";
        if(!is_dir($folder)){ mkdir($folder, 0777, true); }
        $nama_file = time().'_'.$_FILES['foto']['name'];
        $tmp = $_FILES['foto']['tmp_name'];
        move_uploaded_file($tmp, $folder.$nama_file);
        $foto = $nama_file;
    }

    $error_msg = '';

    // jika ada input password
    if($password_lama != "" || $password_baru != ""){
        if(md5($password_lama) != $data['password']){
            $error_msg = "Password lama yang Anda masukkan salah!";
        } elseif($password_baru != $konfirmasi_password){
            $error_msg = "Konfirmasi password baru tidak cocok!";
        } elseif(strlen($password_baru) < 6){
            $error_msg = "Password baru minimal 6 karakter!";
        } else {
            $password_md5 = md5($password_baru);
            mysqli_query($conn, "UPDATE users SET username='$username', password='$password_md5', foto='$foto' WHERE id='$id'");
            $_SESSION['user'] = $username;
            echo "<script>alert('Profil dan Password berhasil diupdate!'); window.location='dashboard.php';</script>";
            exit;
        }
    } else {
        mysqli_query($conn, "UPDATE users SET username='$username', foto='$foto' WHERE id='$id'");
        $_SESSION['user'] = $username;
        echo "<script>alert('Profil berhasil diupdate!'); window.location='dashboard.php';</script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil - Saka Poultry</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.2/dist/minty/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { background-color: #f4f6f9; }
        .profil-img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid #fff;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="py-5">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h5 class="mb-0 fw-bold"><i class="fa-solid fa-user-edit me-2"></i>Edit Profil</h5>
                </div>
                <div class="card-body p-4">
                    
                    <div class="text-center mb-4">
                        <?php if($data['foto'] != ""){ ?>
                            <img src="foto_profil/<?php echo htmlspecialchars($data['foto']); ?>" class="profil-img mb-2">
                        <?php } else { ?>
                            <div class="d-inline-flex justify-content-center align-items-center bg-secondary text-white rounded-circle mb-2" style="width: 120px; height: 120px; font-size: 3rem;">
                                <i class="fa-solid fa-user"></i>
                            </div>
                        <?php } ?>
                    </div>

                    <?php if(!empty($error_msg)): ?>
                        <div class="alert alert-danger"><i class="fa-solid fa-circle-exclamation me-1"></i> <?php echo $error_msg; ?></div>
                    <?php endif; ?>

                    <form method="POST" enctype="multipart/form-data">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Username</label>
                            <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($data['username']); ?>" required>
                        </div>

                        <hr class="my-4">
                        <h6 class="fw-bold mb-3">Keamanan Akun (Ganti Password)</h6>
                        
                        <div class="mb-3">
                            <label class="form-label text-muted small">Password Lama</label>
                            <input type="password" name="password_lama" class="form-control" placeholder="Kosongkan jika tidak ingin ganti">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">Password Baru</label>
                            <input type="password" name="password_baru" class="form-control" placeholder="Password Baru">
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-muted small">Konfirmasi Password Baru</label>
                            <input type="password" name="konfirmasi_password" class="form-control" placeholder="Ulangi Password Baru">
                        </div>

                        <hr class="my-4">

                        <div class="mb-4">
                            <label class="form-label fw-bold text-muted">Foto Profil</label>
                            <input type="file" name="foto" class="form-control">
                        </div>

                        <button type="submit" name="submit" class="btn btn-success w-100 fw-bold py-2">
                            <i class="fa-solid fa-save me-1"></i> Simpan Profil
                        </button>

                    </form>
                    
                    <div class="text-center mt-4">
                        <a href="dashboard.php" class="text-decoration-none text-muted fw-bold">
                            <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Dashboard
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
