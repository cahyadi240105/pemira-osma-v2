<?php
require 'auth/config.php';
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

function generateUsername($nama, $pdo)
{
    $base = strtolower(preg_replace('/[^a-z]/', '', str_replace(' ', '', $nama)));
    $username = $base . rand(100, 999);

    // Pastikan tidak duplikat
    $check = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $check->execute([$username]);
    while ($check->fetchColumn() > 0) {
        $username = $base . rand(100, 999);
        $check->execute([$username]);
    }

    return $username;
}

if (isset($_FILES['file_excel']['tmp_name'])) {
    $file = $_FILES['file_excel']['tmp_name'];
    $spreadsheet = IOFactory::load($file);
    $sheet = $spreadsheet->getActiveSheet()->toArray();

    try {
        $pdo->beginTransaction();

        foreach (array_slice($sheet, 1) as $row) {
            $nim  = trim($row[0]);
            $nama = trim($row[1]);
            if (empty($nim) || empty($nama)) continue;

            $username     = generateUsername($nama, $pdo);
            $namaKecil    = strtolower(str_replace(' ', '', $nama)); // nama disatukan & kecil semua
            $raw_password = $nim . '.' . $namaKecil;
            $salt         = bin2hex(random_bytes(16));
            $hashedPwd    = hash('sha3-256', $salt . $raw_password);

            $stmt = $pdo->prepare("INSERT INTO users
                (username, nim, password, pw_salt, nama_lengkap, role, status_vote)
                VALUES (?, ?, ?, ?, ?, 'user', 'belum')");
            $stmt->execute([$username, $nim, $hashedPwd, $salt, $nama]);
        }

        $pdo->commit();
        header("Location: pengguna.php?status=berhasil");
    } catch (Exception $e) {
        $pdo->rollBack();
        header("Location: pengguna.php?status=gagal");
    }
    exit;
}
