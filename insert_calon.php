<?php
require_once 'auth/config.php';
require_once 'auth/function.php';

$id_calon   = $_POST['id_calon'] ?? '';
$no_calon   = trim($_POST['no_calon'] ?? '');
$nama_calon = trim($_POST['nama_calon'] ?? '');
$visi       = trim($_POST['visi'] ?? '');
$misi       = trim($_POST['misi'] ?? '');

if (empty($id_calon) || empty($no_calon) || empty($nama_calon) || empty($visi) || empty($misi)) {
    header("Location: kandidat.php?status=error_input");
    exit;
}

// Ambil data lama
$stmt = $pdo->prepare("SELECT foto FROM calon WHERE id_calon = ?");
$stmt->execute([$id_calon]);
$calon = $stmt->fetch();

if (!$calon) {
    header("Location: kandidat.php?status=gagal");
    exit;
}

$foto_lama = $calon['foto'];
$foto_baru = $foto_lama;

if (!empty($_FILES['foto']['name'])) {
    $foto_name = basename($_FILES['foto']['name']);
    $foto_tmp  = $_FILES['foto']['tmp_name'];
    $foto_size = $_FILES['foto']['size'];
    $foto_dir  = 'images/';
    $file_ext  = strtolower(pathinfo($foto_name, PATHINFO_EXTENSION));
    $safe_ext  = ['jpg', 'jpeg', 'png', 'webp'];
    $safe_mime = ['image/jpeg', 'image/png', 'image/webp'];
    $max_size  = 2 * 1024 * 1024;

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime  = finfo_file($finfo, $foto_tmp);
    finfo_close($finfo);

    if (!in_array($file_ext, $safe_ext)) {
        header("Location: kandidat.php?status=ekstensi_tidak_diperbolehkan");
        exit;
    }

    if (!in_array($mime, $safe_mime)) {
        header("Location: kandidat.php?status=mime_invalid");
        exit;
    }

    if ($foto_size > $max_size) {
        header("Location: kandidat.php?status=ukuran_terlalu_besar");
        exit;
    }

    $new_filename = uniqid('calon_', true) . '.' . $file_ext;
    $foto_dest = $foto_dir . $new_filename;

    if (move_uploaded_file($foto_tmp, $foto_dest)) {
        // Hapus foto lama
        if (!empty($foto_lama) && file_exists($foto_dir . $foto_lama)) {
            unlink($foto_dir . $foto_lama);
        }
        $foto_baru = $new_filename;
    } else {
        header("Location: kandidat.php?status=gagal_upload");
        exit;
    }
}

// Update data calon
$stmt = $pdo->prepare("UPDATE calon SET 
    no_calon = :no_calon, 
    nama_calon = :nama_calon, 
    foto = :foto, 
    visi = :visi, 
    misi = :misi 
    WHERE id_calon = :id_calon");

$result = $stmt->execute([
    ':no_calon'   => htmlspecialchars($no_calon),
    ':nama_calon' => htmlspecialchars($nama_calon),
    ':foto'       => $foto_baru,
    ':visi'       => htmlspecialchars($visi),
    ':misi'       => htmlspecialchars($misi),
    ':id_calon'   => $id_calon
]);

if ($result) {
    header("Location: kandidat.php?status=update");
} else {
    header("Location: kandidat.php?status=gagal");
}
exit;
