<?php
require_once 'auth/config.php';

$id_user       = $_POST['id_users'] ?? '';
$username      = trim($_POST['username'] ?? '');
$nama_lengkap  = trim($_POST['nama_lengkap'] ?? '');

// Validasi input dasar (optional bisa ditambah)
if (empty($id_user) || empty($username) || empty($nama_lengkap)) {
        header("Location: pengguna.php?status=error_input");
        exit;
}

// prepared statement untuk keamanan
$stmt = $pdo->prepare("
    UPDATE users SET 
        username = :username,
        nama_lengkap = :nama_lengkap
    WHERE id_users = :id_users
");

$updated = $stmt->execute([
        ':username'     => htmlspecialchars($username),
        ':nama_lengkap' => htmlspecialchars($nama_lengkap),
        ':id_users'      => $id_user
]);

if ($updated) {
        header("Location: pengguna.php?status=update");
} else {
        header("Location: pengguna.php?status=gagal");
}
exit;
