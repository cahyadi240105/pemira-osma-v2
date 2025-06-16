<?php
require_once 'auth/config.php';
require_once 'auth/function.php';
session_start();

// Validasi session login
if (!isset($_SESSION['user'])) {
    header("Location: login.php?status=belum_login");
    exit;
}

// Ambil ID user dan ID calon dari sesi dan URL
$id_user = $_SESSION['user']['id_users'];
$id_calon = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

// Validasi ID calon
if (!$id_calon) {
    header("Location: voting.php?status=id_tidak_valid");
    exit;
}

try {
    // Mulai transaksi database
    $pdo->beginTransaction();

    // Cek status vote user
    $stmt = $pdo->prepare("SELECT status_vote FROM users WHERE id_users = ? FOR UPDATE");
    $stmt->execute([$id_user]);
    $status = $stmt->fetchColumn();

    if ($status === 'sudah') {
        throw new Exception("Anda sudah melakukan voting sebelumnya");
    }

    // Ambil data kandidat
    $stmt = $pdo->prepare("
        SELECT jumlah_suara, checksum_suara, version 
        FROM calon 
        WHERE id_calon = ? 
        FOR UPDATE
    ");
    $stmt->execute([$id_calon]);
    $calon = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$calon) {
        throw new Exception("Kandidat tidak ditemukan");
    }

    // Verifikasi checksum suara sebelumnya
    $signKey = file_get_contents(__DIR__ . '/auth/keys/sign.key');
    $salt = 'SALT_RAHASIA'; // Simpan di config jika perlu
    $expectedChecksum = generateChecksum($calon['jumlah_suara'] . $salt, $signKey);

    if ($calon['checksum_suara'] && $expectedChecksum !== $calon['checksum_suara']) {
        throw new Exception("Data suara tidak valid (checksum tidak match)");
    }

    // Siapkan data untuk dienkripsi
    $voteData = [
        'vote_id' => generateUUIDv4(),
        'user_id' => $id_user,
        'calon_id' => $id_calon,
        'timestamp' => time(),
        'jumlah_suara_sebelumnya' => $calon['jumlah_suara']
    ];
    $plaintext = json_encode($voteData);

    // Enkripsi data
    $encryptKey = file_get_contents(__DIR__ . '/auth/keys/encrypt.key');
    $nonce = '';
    $encryptedVote = encryptData($plaintext, $encryptKey, $nonce);

    // Tanda tangan
    $signature = signData($encryptedVote, $signKey);

    // Buat checksum dan data_checksum
    $checksum = generateChecksum($encryptedVote);
    $data_checksum = generateChecksum($plaintext, $signKey);

    // Simpan ke vote_logs
    $stmt = $pdo->prepare("
        INSERT INTO vote_logs (
            id_vote,
            id_user,
            id_calon,
            waktu,
            encrypted_vote,
            nonce,
            signature,
            checksum,
            encrypted_data,
            data_checksum
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $voteData['vote_id'],
        $id_user,
        $id_calon,
        date('Y-m-d H:i:s'),
        $encryptedVote,
        $nonce,
        $signature,
        $checksum,
        $encryptedVote,
        $data_checksum
    ]);

    // Update jumlah suara
    $jumlah_baru = $calon['jumlah_suara'] + 1;
    $newChecksum = generateChecksum($jumlah_baru . $salt, $signKey);

    $stmt = $pdo->prepare("
        UPDATE calon SET 
            jumlah_suara = ?, 
            checksum_suara = ?, 
            version = version + 1,
            last_update = NOW()
        WHERE id_calon = ? AND version = ?
    ");
    $stmt->execute([$jumlah_baru, $newChecksum, $id_calon, $calon['version']]);

    if ($stmt->rowCount() === 0) {
        throw new Exception("Data kandidat berubah selama proses. Silakan coba lagi.");
    }

    // Update status user
    $stmt = $pdo->prepare("UPDATE users SET status_vote = 'sudah', last_vote_time = NOW() WHERE id_users = ?");
    $stmt->execute([$id_user]);

    $pdo->commit();
    header("Location: voting.php?status=berhasil_memilih");
    exit;
} catch (Exception $e) {
    $pdo->rollBack();
    error_log("Vote error: " . $e->getMessage());
    header("Location: voting.php?status=error&pesan=" . urlencode($e->getMessage()));
    exit;
}