<?php
session_start();

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

$query = mysqli_query($conn, "SELECT * FROM produk");
?>

<!DOCTYPE html>
<html lang="id">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Data Produk</title>

    <!-- FONT AWESOME -->
    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- GOOGLE FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
    rel="stylesheet">

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            font-family:'Poppins',sans-serif;

            background:
            linear-gradient(
            rgba(0,0,0,0.6),
            rgba(0,0,0,0.6)),

            url('https://images.unsplash.com/photo-1548550023-2bdb3c5beed7');

            background-size:cover;
            background-position:center;
            background-attachment:fixed;

            min-height:100vh;
            color:white;

            transition:0.4s;
            animation:fadeIn 1s ease;
        }

        /* DARK MODE */

        body.dark{
            background:#111;
            background-image:none;
            color:white;
        }

        body.dark table{
            background:#1b1b1b;
        }

        body.dark th{
            background:#333;
        }

        body.dark td{
            background:#222;
        }

        body.dark tr:hover{
            background:#2c2c2c;
        }

        body.dark .container{
            background:rgba(20,20,20,0.9);
        }

        /* CONTAINER */

        .container{
            width:90%;
            max-width:1300px;
            margin:40px auto;

            background:rgba(255,255,255,0.08);

            backdrop-filter:blur(12px);

            padding:30px;
            border-radius:25px;

            box-shadow:
            0 10px 30px rgba(0,0,0,0.3);

            animation:fadeUp 1s ease;
        }

        /* HEADER */

        .header{
            display:flex;
            justify-content:space-between;
            align-items:center;
            flex-wrap:wrap;
            gap:15px;

            margin-bottom:30px;
        }

        .header h1{
            font-size:35px;

            animation:slideLeft 1s ease;
        }

        .menu-btn{
            display:flex;
            gap:10px;
            flex-wrap:wrap;
        }

        /* BUTTON */

        .btn{
            text-decoration:none;

            background:
            linear-gradient(45deg,#6a994e,#386641);

            color:white;

            padding:12px 22px;
            border-radius:12px;

            font-weight:600;

            transition:0.3s;

            border:none;
            cursor:pointer;

            box-shadow:
            0 0 10px rgba(106,153,78,0.5);
        }

        .btn:hover{
            transform:translateY(-5px) scale(1.03);

            box-shadow:
            0 0 20px rgba(106,153,78,0.9);
        }

        /* TABLE */

        table{
            width:100%;
            border-collapse:collapse;

            background:rgba(255,255,255,0.12);

            backdrop-filter:blur(10px);

            border-radius:20px;
            overflow:hidden;

            box-shadow:
            0 10px 25px rgba(0,0,0,0.25);
        }

        th{
            background:rgba(34,70,40,0.9);

            padding:18px;
            font-size:16px;
        }

        td{
            padding:18px;
            text-align:center;

            border-bottom:
            1px solid rgba(255,255,255,0.1);

            transition:0.3s;
        }

        tr{
            transition:0.3s;
        }

        tr:hover{
            background:rgba(255,255,255,0.08);

            transform:scale(1.01);
        }

        /* GAMBAR */

        .gambar{
            width:80px;
            height:80px;

            object-fit:cover;

            border-radius:15px;

            border:2px solid rgba(255,255,255,0.2);

            transition:0.4s;
        }

        .gambar:hover{
            transform:scale(1.15) rotate(2deg);

            box-shadow:
            0 0 20px rgba(255,255,255,0.5);
        }

        /* AKSI */

        .aksi{
            display:flex;
            justify-content:center;
            flex-wrap:wrap;
            gap:8px;
        }

        .aksi a{
            text-decoration:none;
            color:white;

            padding:8px 14px;
            border-radius:10px;

            font-size:14px;
            transition:0.3s;
        }

        .aksi a:hover{
            transform:scale(1.08);
        }

        .edit{
            background:#fca311;
        }

        .hapus{
            background:#e63946;
        }

        .beli{
            background:#2a9d8f;
        }

        /* STATUS STOK */

        .stok-habis{
            background:#ff4d4d;
            color:white;
            padding:6px 10px;
            border-radius:8px;
            font-size:13px;
            font-weight:bold;

            animation:pulse 1s infinite;
        }

        .stok-menipis{
            background:#ff9800;
            color:white;
            padding:6px 10px;
            border-radius:8px;
            font-size:13px;
            font-weight:bold;
        }

        .stok-aman{
            background:#4CAF50;
            color:white;
            padding:6px 10px;
            border-radius:8px;
            font-size:13px;
            font-weight:bold;
        }

        /* KEMBALI */

        .kembali{
            display:inline-block;

            margin-top:25px;

            text-decoration:none;
            color:white;

            background:#8b5e3c;

            padding:12px 20px;
            border-radius:10px;

            transition:0.3s;
        }

        .kembali:hover{
            transform:translateY(-4px);

            box-shadow:
            0 0 15px rgba(255,255,255,0.3);
        }

        /* ANIMASI */

        @keyframes fadeIn{
            from{
                opacity:0;
            }
            to{
                opacity:1;
            }
        }

        @keyframes fadeUp{
            from{
                opacity:0;
                transform:translateY(30px);
            }
            to{
                opacity:1;
                transform:translateY(0);
            }
        }

        @keyframes slideLeft{
            from{
                opacity:0;
                transform:translateX(-50px);
            }
            to{
                opacity:1;
                transform:translateX(0);
            }
        }

        @keyframes pulse{
            0%{
                box-shadow:0 0 0 rgba(255,0,0,0.7);
            }
            50%{
                box-shadow:0 0 15px rgba(255,0,0,1);
            }
            100%{
                box-shadow:0 0 0 rgba(255,0,0,0.7);
            }
        }

        /* RESPONSIVE */

        @media(max-width:768px){

            .header{
                flex-direction:column;
                align-items:flex-start;
            }

            .header h1{
                font-size:28px;
            }

            table{
                font-size:13px;
            }

            td,th{
                padding:10px;
            }

            .gambar{
                width:60px;
                height:60px;
            }

        }

    </style>

</head>
<body>

<div class="container">

    <div class="header">

        <h1>
            <i class="fa-solid fa-egg"></i>
            Data Produk
        </h1>

        <div class="menu-btn">

            <?php if($_SESSION['role'] == 'admin'){ ?>

                <a href="tambah.php" class="btn">
                    + Tambah Produk
                </a>

            <?php } ?>

            <button class="btn" onclick="darkMode()">
                🌙 Dark Mode
            </button>

        </div>

    </div>

    <table>

        <tr>
            <th>No</th>
            <th>Gambar</th>
            <th>Nama Produk</th>
            <th>Jenis</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>

        <?php
        $no = 1;

        while($data = mysqli_fetch_assoc($query)){
        ?>

        <tr>

            <td><?php echo $no++; ?></td>

            <td>

                <?php if($data['gambar'] != ""){ ?>

                    <img
                    src="gambar_produk/<?php echo $data['gambar']; ?>"
                    class="gambar">

                <?php } else { ?>

                    Tidak ada gambar

                <?php } ?>

            </td>

            <td>
                <?php echo $data['nama_telur']; ?>
            </td>

            <td>
                <?php echo $data['jenis_telur']; ?>
            </td>

            <td>
                Rp <?php echo number_format($data['harga']); ?>
            </td>

            <td>
                <?php echo $data['stok']; ?>
            </td>

            <td>

                <?php
                if($data['stok'] <= 0){
                    echo "<span class='stok-habis'>Stok Habis</span>";
                }elseif($data['stok'] <= 5){
                    echo "<span class='stok-menipis'>Stok Menipis</span>";
                }else{
                    echo "<span class='stok-aman'>Stok Aman</span>";
                }
                ?>

            </td>

            <td>

                <div class="aksi">

                    <?php if($_SESSION['role'] == 'admin'){ ?>

                        <a href="edit.php?id=<?php echo $data['id']; ?>"
                        class="edit">
                            Edit
                        </a>

                        <a href="hapus.php?id=<?php echo $data['id']; ?>"
                        class="hapus"
                        onclick="return confirm('Yakin ingin hapus?')">
                            Hapus
                        </a>

                    <?php } else { ?>

                        <?php if($data['stok'] > 0){ ?>

                            <a href="beli.php?id=<?php echo $data['id']; ?>"
                            class="beli">
                                Beli
                            </a>

                        <?php } else { ?>

                            <span style="color:red;font-weight:bold;">
                                Tidak Tersedia
                            </span>

                        <?php } ?>

                    <?php } ?>

                </div>

            </td>

        </tr>

        <?php } ?>

    </table>

    <a href="dashboard.php" class="kembali">
        ← Kembali ke Dashboard
    </a>

</div>

<script>

function darkMode(){
    document.body.classList.toggle('dark');
}

</script>

</body>
</html>