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

    $username = mysqli_real_escape_string(
        $conn,
        $_POST['username']
    );

    $password = $_POST['password'];

    // FOTO
    $foto = $data['foto'];

    if($_FILES['foto']['name'] != ""){

        $folder = "foto_profil/";

        if(!is_dir($folder)){
            mkdir($folder, 0777, true);
        }

        $nama_file =
        time().'_'.$_FILES['foto']['name'];

        $tmp = $_FILES['foto']['tmp_name'];

        move_uploaded_file(
            $tmp,
            $folder.$nama_file
        );

        $foto = $nama_file;
    }

    // jika password diisi
    if($password != ""){

        $password = md5($password);

        mysqli_query($conn,
        "UPDATE users SET

        username='$username',
        password='$password',
        foto='$foto'

        WHERE id='$id'");
    }else{

        mysqli_query($conn,
        "UPDATE users SET

        username='$username',
        foto='$foto'

        WHERE id='$id'");
    }

    $_SESSION['user'] = $username;

    echo "
    <script>
        alert('Profil berhasil diupdate!');
        window.location='dashboard.php';
    </script>
    ";
}
?>

<!DOCTYPE html>
<html>
<head>

    <title>Edit Profil</title>

    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            font-family:Arial;

            background:
            linear-gradient(
            rgba(0,0,0,0.5),
            rgba(0,0,0,0.5)),

            url('https://images.unsplash.com/photo-1548550023-2bdb3c5beed7');

            background-size:cover;
            background-position:center;

            min-height:100vh;

            display:flex;
            justify-content:center;
            align-items:center;
        }

        .box{
            width:400px;

            background:rgba(255,255,255,0.1);

            backdrop-filter:blur(10px);

            padding:30px;

            border-radius:20px;

            color:white;
        }

        h2{
            text-align:center;
            margin-bottom:20px;
        }

        input{
            width:100%;

            padding:12px;

            margin-bottom:15px;

            border:none;
            border-radius:10px;
        }

        button{
            width:100%;

            padding:12px;

            border:none;
            border-radius:10px;

            background:#4CAF50;
            color:white;

            cursor:pointer;
        }

        img{
            width:100px;
            height:100px;

            border-radius:50%;

            object-fit:cover;

            display:block;
            margin:auto;

            margin-bottom:15px;
        }

    </style>

</head>
<body>

<div class="box">

    <h2>
        <i class="fa-solid fa-user"></i>
        Edit Profil
    </h2>

    <?php if($data['foto'] != ""){ ?>

        <img src="foto_profil/<?php echo $data['foto']; ?>">

    <?php } ?>

    <form method="POST"
    enctype="multipart/form-data">

        <input type="text"
        name="username"

        value="<?php echo $data['username']; ?>"
        required>

        <input type="password"
        name="password"

        placeholder="Password baru">

        <input type="file"
        name="foto">

        <button type="submit"
        name="submit">

            Simpan Profil

        </button>

    </form>

</div>

</body>
</html>