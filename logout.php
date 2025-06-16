<?php
session_start();

// Hapus semua session
session_unset();
session_destroy();

// Arahkan kembali ke halaman login (atau halaman utama)
header("Location: login.php");
exit;
