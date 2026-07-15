<?php
session_start();
include 'koneksi.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}

$jenis = $_GET['jenis'] ?? '';

// Ambil data produk
$query = mysqli_query($conn, "SELECT * FROM produk ORDER BY id DESC");

// Atur Header berdasarkan jenis ekspor
if ($jenis == 'excel') {
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=Laporan_Stok_Produk.xls");
} elseif ($jenis == 'word') {
    header("Content-type: application/vnd::ms-word");
    header("Content-Disposition: attachment; filename=Laporan_Stok_Produk.doc");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Produk & Stok</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        /* Desain Dasar Global */
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            color: #333; 
            background: #fff; 
            margin: 30px;
            line-height: 1.6;
        }
        
        /* Kop Laporan / Header */
        .report-header {
            border-bottom: 3px double #224628;
            padding-bottom: 20px;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .shop-title h2 {
            color: #224628;
            margin: 0 0 5px 0;
            font-size: 26px;
            letter-spacing: 0.5px;
        }
        .shop-title p {
            margin: 0;
            color: #666;
            font-size: 14px;
        }
        .report-meta {
            text-align: right;
            font-size: 13px;
            color: #555;
        }

        /* Desain Tabel Modern */
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        th { 
            background-color: #224628 !important; 
            color: white !important;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 13px;
            letter-spacing: 0.5px;
            padding: 14px 10px;
        }
        td { 
            padding: 12px 10px; 
            text-align: center; 
            font-size: 14px;
            border-bottom: 1px solid #e0e0e0;
        }
        /* Efek baris abu-abu bergantian */
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .text-left { 
            text-align: left; 
            font-weight: 500;
        }
        
        /* Badge Status Stok */
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            color: #fff;
        }
        .bg-aman { background-color: #2e7d32; }
        .bg-menipis { background-color: #ef6c00; }
        .bg-habis { background-color: #c62828; }

        /* Panel Tombol Cetak Melayang */
        .no-print { 
            margin-bottom: 25px; 
            text-align: center; 
            background: #f5f5f5;
            padding: 15px;
            border-radius: 12px;
            border: 1px solid #e0e0e0;
        }
        .btn-print {
            padding: 10px 24px; 
            background: #c62828; 
            color: white; 
            border: none; 
            border-radius: 8px; 
            cursor: pointer; 
            font-weight: bold;
            box-shadow: 0 4px 6px rgba(198,40,40,0.2);
            transition: 0.2s;
        }
        .btn-print:hover { background: #b71c1c; transform: translateY(-1px); }
        .btn-close {
            padding: 10px 24px; 
            background: #424242; 
            color: white; 
            border: none; 
            border-radius: 8px; 
            cursor: pointer; 
            margin-left: 10px;
            transition: 0.2s;
        }
        .btn-close:hover { background: #212121; }

        /* Sembunyikan panel tombol saat proses cetak/simpan PDF */
        @media print { 
            .no-print { display: none !important; } 
            body { margin: 10px; }
        }
    </style>
</head>
<body>

    <?php if ($jenis == 'pdf') { ?>
        <div class="no-print">
            <button onclick="window.print()" class="btn-print"><i class="fa-solid fa-print"></i> Cetak Dokumen / Simpan PDF</button>
            <button onclick="window.close()" class="btn-close">Tutup</button>
        </div>
    <?php } ?>

    <div class="report-header">
        <div class="shop-title">
            <h2><i class="fa-solid fa-egg" style="color: #fca311;"></i> SAKA POULTRY</h2>
            <p>Sistem Manajemen Stok & Inventory Gudang Modern</p>
        </div>
        <div class="report-meta">
            <strong>Dokumen:</strong> Laporan Data Produk & Stok<br>
            <strong>Oleh Admin:</strong> <?php echo htmlspecialchars($_SESSION['user']); ?><br>
            <strong>Waktu Unduh:</strong> <?php echo date('d-m-Y H:i'); ?> WIB
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="35%">Nama Produk Telur</th>
                <th width="20%">Jenis Telur</th>
                <th width="20%">Harga Jual</th>
                <th width="20%">Status & Sisa Stok</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            while($data = mysqli_fetch_assoc($query)){ 
                // Menentukan badge warna status stok
                if($data['stok'] <= 0) {
                    $status_class = 'bg-habis';
                    $status_text = 'Habis';
                } elseif($data['stok'] <= 5) {
                    $status_class = 'bg-menipis';
                    $status_text = 'Menipis';
                } else {
                    $status_class = 'bg-aman';
                    $status_text = 'Aman';
                }
            ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td class="text-left"><?php echo htmlspecialchars($data['nama_telur']); ?></td>
                <td><?php echo htmlspecialchars($data['jenis_telur']); ?></td>
                <td>Rp <?php echo number_format($data['harga']); ?></td>
                <td>
                    <strong><?php echo $data['stok']; ?></strong> btr/kg 
                    <span class="badge <?php echo $status_class; ?>" style="margin-left: 5px;">
                        <?php echo $status_text; ?>
                    </span>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

    <?php if ($jenis == 'pdf') { ?>
        <script>
            window.onload = function() { 
                // Sedikit delay agar rendering CSS selesai sempurna sebelum dialog print muncul
                setTimeout(function() { window.print(); }, 300); 
            }
        </script>
    <?php } ?>

</body>
</html>
