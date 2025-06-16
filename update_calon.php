<?php
require_once 'auth/config.php';

$id_calon = $_POST['id_calon'];
$no_calon = $_POST['no_calon'];
$nama_calon = $_POST['nama_calon'];
$visi = $_POST['visi'];
$misi = $_POST['misi'];

// Ambil foto lama
$query = "SELECT foto FROM calon WHERE id_calon = '$id_calon'";
$result = $pdo->query($query);
$row = $result->fetch();
$foto_lama = $row['foto'];

$foto_baru = $foto_lama;

if ($_FILES['foto']['error'] === 0) {
    $nama_file = $_FILES['foto']['name'];
    $tmp_file = $_FILES['foto']['tmp_name'];

    $tujuan = 'images/' . $nama_file;

    if (move_uploaded_file($tmp_file, $tujuan)) {
        // Hapus foto lama jika ada
        if (!empty($foto_lama) && file_exists('images/' . $foto_lama)) {
            unlink('images/' . $foto_lama);
        }
        $foto_baru = $nama_file;
    }
}

// Update data calon
$sql = "UPDATE calon SET 
            no_calon = '$no_calon', 
            nama_calon = '$nama_calon', 
            foto = '$foto_baru', 
            visi = '$visi', 
            misi = '$misi' 
        WHERE id_calon = '$id_calon'";

if ($pdo->exec($sql)) {
    header("Location: kandidat.php?status=update");
} else {
    header("Location: kandidat.php?status=gagal");
}
exit;
?>