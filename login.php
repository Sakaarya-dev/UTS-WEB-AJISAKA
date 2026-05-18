<?php
session_start();
include 'koneksi.php';

if(isset($_POST['login'])){

    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $query = mysqli_query(
        $conn,
        "SELECT * FROM users
        WHERE email='$email'
        AND password='$password'"
    );

    $cek = mysqli_num_rows($query);

    if($cek > 0){

        $data = mysqli_fetch_assoc($query);

        $_SESSION['user'] = $data['username'];
        $_SESSION['id'] = $data['id'];
        $_SESSION['role'] = $data['role'];
        
        header("Location: dashboard.php");
        exit;

    }else{

        $error = "Email atau Password Salah!";

    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>

    <meta charset="UTF-8">
    <meta name="viewport"
    content="width=device-width, initial-scale=1.0">

    <title>Login Telur</title>
    
    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
    rel="stylesheet">

    <style>

        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body{
            font-family: 'Poppins', sans-serif;

            height: 100vh;

            display: flex;
            justify-content: center;
            align-items: center;

            background:
            linear-gradient(
            rgba(0,0,0,0.5),
            rgba(0,0,0,0.5)),

            url('https://images.unsplash.com/photo-1548550023-2bdb3c5beed7');

            background-size: cover;
            background-position: center;

            overflow: hidden;
        }



        .circle{
            position: absolute;

            border-radius: 50%;

            background:
            rgba(255,255,255,0.1);

            animation: float 6s infinite ease-in-out;
        }

        .circle:nth-child(1){
            width: 200px;
            height: 200px;

            top: 10%;
            left: 10%;
        }

        .circle:nth-child(2){
            width: 300px;
            height: 300px;

            bottom: 10%;
            right: 10%;

            animation-delay: 2s;
        }

        .circle:nth-child(3){
            width: 150px;
            height: 150px;

            bottom: 20%;
            left: 35%;

            animation-delay: 4s;
        }

        @keyframes float{

            0%,100%{
                transform: translateY(0);
            }

            50%{
                transform: translateY(-20px);
            }

        }

        /* =========================
           LOGIN BOX
        ========================= */

        .login-box{
            position: relative;
            z-index: 2;

            width: 400px;

            padding: 40px;

            background:
            rgba(255,255,255,0.12);

            backdrop-filter: blur(15px);

            border:
            1px solid rgba(255,255,255,0.2);

            border-radius: 25px;

            box-shadow:
            0 15px 35px rgba(0,0,0,0.3);

            animation: fadeUp 1s ease;
        }

        @keyframes fadeUp{

            from{
                opacity: 0;
                transform: translateY(30px);
            }

            to{
                opacity: 1;
                transform: translateY(0);
            }

        }

        
        .logo{
            text-align: center;
            margin-bottom: 20px;
        }

        .logo i{
            font-size: 70px;
            color: #ffd166;

            text-shadow:
            0 0 20px rgba(255,209,102,0.7);
        }

        h2{
            text-align: center;

            color: white;

            margin-bottom: 30px;

            font-size: 30px;
        }

        
        .error{
            background:
            rgba(255,0,0,0.2);

            color: white;

            padding: 12px;

            border-radius: 12px;

            text-align: center;

            margin-bottom: 20px;
        }


        .input-box{
            position: relative;
            margin-bottom: 20px;
        }

        .input-box i{
            position: absolute;

            left: 15px;
            top: 15px;

            color: #666;
        }

        .input-box input{
            width: 100%;

            padding: 14px 14px 14px 45px;

            border: none;
            outline: none;

            border-radius: 12px;

            background:
            rgba(255,255,255,0.9);

            font-size: 15px;
        }

        .input-box input:focus{
            box-shadow:
            0 0 10px rgba(255,255,255,0.8);
        }

       
        button{
            width: 100%;

            padding: 14px;

            border: none;

            border-radius: 12px;

            background:
            linear-gradient(45deg,#6a994e,#386641);

            color: white;

            font-size: 17px;
            font-weight: 600;

            cursor: pointer;

            transition: 0.3s;
        }

        button:hover{
            transform: translateY(-3px);

            box-shadow:
            0 10px 20px rgba(0,0,0,0.3);
        }

        
        .register{
            text-align: center;

            margin-top: 20px;

            color: white;
        }

        .register a{
            color: #ffd166;

            text-decoration: none;

            font-weight: bold;
        }

        .register a:hover{
            text-decoration: underline;
        }

        /* =========================
           RESPONSIVE
        ========================= */

        @media(max-width:500px){

            .login-box{
                width: 90%;
                padding: 30px;
            }

            h2{
                font-size: 24px;
            }

        }

    </style>

</head>
<body>


<div class="circle"></div>
<div class="circle"></div>
<div class="circle"></div>



<div class="login-box">

    <div class="logo">

        <i class="fa-solid fa-wheat-awn"></i>

    </div>

    <h2>Login</h2>

    <?php if(isset($error)){ ?>

        <div class="error">
            <?php echo $error; ?>
        </div>

    <?php } ?>

    <form method="POST">

        <div class="input-box">

            <i class="fa-solid fa-envelope"></i>

            <input
            type="email"
            name="email"
            placeholder="Masukkan Email"
            required>

        </div>

        <div class="input-box">

            <i class="fa-solid fa-lock"></i>

            <input
            type="password"
            name="password"
            placeholder="Masukkan Password"
            required>

        </div>

        <button type="submit" name="login">

            <i class="fa-solid fa-right-to-bracket"></i>
            Login Sekarang

        </button>

    </form>

    <div class="register">

        Ora Ndue Akun?

        <a href="register.php">
            Register
        </a>

    </div>

</div>

</body>
</html>