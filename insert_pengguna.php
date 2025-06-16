<?php
require_once 'auth/config.php';
require_once 'auth/function.php';

$username      = trim($_POST['username'] ?? '');
$nim           = trim($_POST['nim'] ?? '');
$nama_lengkap  = trim($_POST['nama_lengkap'] ?? '');

if (empty($username) || empty($nim) || empty($nama_lengkap)) {
    header("Location: pengguna.php?status=error_input");
    exit;
}

// Password otomatis = nim.nama_lengkap (tanpa spasi dan huruf kecil semua)
$raw_password = $nim . '.' .  $nama_lengkap;

// Buat salt dan hash
$salt       = bin2hex(random_bytes(16)); // panjang 32 karakter hex
$hashedPwd  = hash('sha3-256', $salt . $raw_password);

try {
    // Cek username duplikat
    $cek = $pdo->prepare("SELECT username FROM users WHERE username = :username");
    $cek->execute([':username' => $username]);

    if ($cek->rowCount() > 0) {
        header("Location: pengguna.php?status=username_sudah_ada");
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO users 
        (username, nim, password, pw_salt, nama_lengkap, role, status_vote)
        VALUES (:username, :nim, :password, :pw_salt, :nama_lengkap, :role, :status_vote)");

    $inserted = $stmt->execute([
        ':username'      => $username,
        ':nim'           => $nim,
        ':password'      => $hashedPwd,
        ':pw_salt'       => $salt,
        ':nama_lengkap'  => $nama_lengkap,
        ':role'          => 'user',
        ':status_vote'   => 'belum'
    ]);

    if ($inserted) {
        header("Location: pengguna.php?status=berhasil");
    } else {
        header("Location: pengguna.php?status=gagal");
    }
} catch (PDOException $e) {
    header("Location: pengguna.php?status=gagal");
}
exit;
