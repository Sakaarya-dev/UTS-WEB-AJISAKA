<?php
// Pastikan tidak ada baris kosong atau spasi di paling atas sebelum kode <?php
session_start();

// Hapus semua data session secara total
$_SESSION = array();
session_unset();
session_destroy();

// Bersihkan cache header agar halaman admin tidak bisa di-back setelah logout
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Paksa pindah ke halaman login gahar menggunakan PHP & JavaScript (Double Protection)
if (!headers_sent()) {
    header("Location: login.php");
    exit;
} else {
    echo '<script type="text/javascript">window.location.href="login.php";</script>';
    exit;
}
?>
