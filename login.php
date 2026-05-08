<?php
session_start();
include 'koneksi.php';

if(isset($_POST['login'])){

    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $query = mysqli_query($conn,
    "SELECT * FROM users
    WHERE email='$email'
    AND password='$password'");

    $cek = mysqli_num_rows($query);

    if($cek > 0){

        $data = mysqli_fetch_assoc($query);

        $_SESSION['user'] = $data['username'];

        header("Location: dashboard.php");
        exit;

    } else {
        $error = "Email atau Password Salah!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Toko Telur</title>

    <style>

        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body{
            font-family: Arial;
            background: linear-gradient(120deg,#f6d365,#fda085);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-box{
            background: white;
            width: 350px;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
        }

        h2{
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        input{
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button{
            width: 100%;
            padding: 12px;
            border: none;
            background: #ff7e5f;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover{
            background: #eb5e3b;
        }

        .error{
            background: #ffdddd;
            color: red;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            text-align: center;
        }

        p{
            text-align: center;
            margin-top: 15px;
        }

        a{
            text-decoration: none;
            color: #ff7e5f;
        }

    </style>

</head>
<body>

<div class="login-box">

    <h2>Login Toko Telur</h2>

    <?php if(isset($error)){ ?>
        <div class="error">
            <?php echo $error; ?>
        </div>
    <?php } ?>

    <form method="POST">

        <input type="email"
        name="email"
        placeholder="Masukkan Email"
        required>

        <input type="password"
        name="password"
        placeholder="Masukkan Password"
        required>

        <button type="submit" name="login">
            Login
        </button>

    </form>

    <p>
        Belum punya akun?
        <a href="register.php">Register</a>
    </p>

</div>

</body>
</html>