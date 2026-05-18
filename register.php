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
<html>
<head>
    <title>Register - Toko Telur</title>

    <style>

        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body{
            font-family: Arial, sans-serif;
            background: linear-gradient(120deg, #f6d365, #fda085);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .box{
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

        .error{
            background: #ffdddd;
            color: red;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            text-align: center;
        }

        input{
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button{
            width: 100%;
            padding: 12px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover{
            background: #218838;
        }

        .login{
            margin-top: 15px;
            text-align: center;
        }

        .login a{
            text-decoration: none;
            color: #ff7e5f;
            font-weight: bold;
        }

    </style>

</head>
<body>

<div class="box">

    <h2>Register Toko Telur</h2>

    <?php if(isset($error)){ ?>

        <div class="error">
            <?php echo $error; ?>
        </div>

    <?php } ?>

    <form method="POST">

        <input type="text"
        name="username"
        placeholder="Username"
        required>

        <input type="email"
        name="email"
        placeholder="Email"
        required>

        <input type="password"
        name="password"
        placeholder="Password"
        required>

        <button type="submit"
        name="register">
            Register
        </button>

    </form>

    <div class="login">

        Sudah punya akun?

        <a href="login.php">
            Login
        </a>

    </div>

</div>

</body>
</html>