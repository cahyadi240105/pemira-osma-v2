<?php
session_start();
require_once 'auth/config.php';
require_once 'auth/function.php';

if (!isAdmin()) {
    header("Location: index.php?status=unauthorized");
    exit;
}

try {
    $pdo->beginTransaction();

    // Hapus semua log voting
    $pdo->exec("DELETE FROM vote_logs");

    // Reset jumlah suara dan checksum kandidat
    $salt = "SALT_RAHASIA";
    $stmt = $pdo->query("SELECT id_calon FROM calon");
    while ($row = $stmt->fetch()) {
        $newChecksum = hash_hmac('sha256', '0' . $salt, file_get_contents(__DIR__ . '/auth/keys/sign.key'));
        $pdo->prepare("UPDATE calon SET jumlah_suara = 0, checksum_suara = ?, version = 0 WHERE id_calon = ?")
            ->execute([$newChecksum, $row['id_calon']]);
    }

    // Reset status vote user (kecuali admin)
    $pdo->exec("UPDATE users SET status_vote = 'belum', last_vote_time = NULL WHERE role != 'admin'");

    $pdo->commit();

    // Set session flag agar tombol disembunyikan
    $_SESSION['sudah_reset'] = true;

    header("Location: index.php?status=reset_sukses");
} catch (Exception $e) {
    $pdo->rollBack();
    error_log("RESET ERROR: " . $e->getMessage());
    header("Location: index.php?status=reset_gagal");
}
exit;
