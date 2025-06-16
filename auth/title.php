<?php
// Dapatkan halaman saat ini
$current_page = basename($_SERVER['PHP_SELF']);

// Tentukan judul berdasarkan halaman
switch ($current_page) {
    case 'index.php':
        $title = 'Beranda';
        break;
    case 'kandidat.php':
        $title = 'Kelola Kandiat';
        break;
    case 'voting.php':
        $title = 'Voting';
        break;
    case 'pengguna.php':
        $title = 'Kelola Pengguna';
        break;
    default:
        $title = 'My Website';
        break;
}
