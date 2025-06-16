<?php
require_once 'auth/config.php';
require_once 'auth/function.php';

function log_integrity_check($message)
{
    $log = date('[Y-m-d H:i:s]') . ' ' . $message . PHP_EOL;
    file_put_contents(__DIR__ . '/logs/integrity_checks.log', $log, FILE_APPEND);
}

try {
    // 1. Setup
    if (!is_dir(__DIR__ . '/logs')) mkdir(__DIR__ . '/logs');
    $signKey = file_get_contents(__DIR__ . '/auth/keys/sign.key');
    $issuesFound = 0;

    // 2. Verifikasi checksum suara
    $calons = $pdo->query("SELECT id_calon, nama_calon, jumlah_suara, checksum_suara FROM calon")->fetchAll();

    foreach ($calons as $calon) {
        $correctChecksum = hash_hmac('sha256', $calon['jumlah_suara'], $signKey);

        if ($calon['checksum_suara'] !== $correctChecksum) {
            $issuesFound++;
            log_integrity_check("Checksum tidak valid untuk calon ID {$calon['id_calon']} ({$calon['nama_calon']})");
        }
    }

    // 3. Verifikasi konsistensi jumlah suara
    $inconsistent = $pdo->query("
        SELECT c.id_calon, c.nama_calon, c.jumlah_suara, COUNT(v.id_calon) as real_count
        FROM calon c
        LEFT JOIN vote_logs v ON c.id_calon = v.id_calon
        GROUP BY c.id_calon
        HAVING c.jumlah_suara != COUNT(v.id_calon)
    ")->fetchAll();

    foreach ($inconsistent as $row) {
        $issuesFound++;
        log_integrity_check("Jumlah suara tidak konsisten untuk {$row['nama_calon']} (DB: {$row['jumlah_suara']}, Real: {$row['real_count']})");
    }

    // 4. Hasil akhir
    if ($issuesFound > 0) {
        $msg = "Ditemukan $issuesFound masalah integritas data";
        log_integrity_check($msg);
        // Kirim notifikasi (email/telegram/dll)
    } else {
        log_integrity_check("Semua data valid");
    }
} catch (Exception $e) {
    log_integrity_check("ERROR: " . $e->getMessage());
}
