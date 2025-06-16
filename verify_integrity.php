<?php
require_once 'auth/config.php';
require_once 'auth/function.php';

header('Content-Type: application/json');

try {
    // 1. Verifikasi struktur tabel
    checkTableStructure($pdo);

    // 2. Verifikasi jumlah suara vs checksum
    $results = [];
    $calons = $pdo->query("SELECT id_calon, nama_calon, jumlah_suara, checksum_suara FROM calon")->fetchAll();

    if (empty($calons)) {
        throw new Exception("Data calon tidak ditemukan");
    }

    $signKey = file_get_contents(__DIR__ . '/auth/keys/sign.key');
    if (!$signKey) {
        throw new Exception("Kunci tanda tangan tidak ditemukan");
    }

    foreach ($calons as $calon) {
        $calculatedChecksum = hash_hmac('sha256', $calon['jumlah_suara'], $signKey);
        $valid = ($calculatedChecksum === $calon['checksum_suara']);

        $results['calons'][] = [
            'id' => $calon['id_calon'],
            'nama_calon' => $calon['nama_calon'],
            'jumlah_suara' => $calon['jumlah_suara'],
            'checksum_valid' => $valid,
            'calculated_checksum' => $calculatedChecksum,
            'stored_checksum' => $calon['checksum_suara']
        ];
    }

    // 3. Verifikasi vote log vs jumlah suara
    $voteCounts = $pdo->query("
        SELECT c.id_calon, c.nama_calon, c.jumlah_suara, COUNT(v.id_vote) as count_vote 
        FROM calon c
        LEFT JOIN vote_logs v ON c.id_calon = v.id_calon
        GROUP BY c.id_calon
    ")->fetchAll();

    foreach ($voteCounts as $row) {
        $results['vote_counts'][] = [
            'id' => $row['id_calon'],
            'nama_calon' => $row['nama_calon'],
            'jumlah_suara' => $row['jumlah_suara'],
            'count_vote' => $row['count_vote'],
            'match' => ($row['jumlah_suara'] == $row['count_vote'])
        ];
    }

    // 4. Verifikasi data terenkripsi (sampel acak)
    $sampleVotes = $pdo->query("
        SELECT id_vote, id_calon, encrypted_vote, nonce 
        FROM vote_logs 
        ORDER BY RAND() LIMIT 3
    ")->fetchAll();

    $encKey = file_get_contents(__DIR__ . '/auth/keys/encrypt.key');
    if (!$encKey) {
        throw new Exception("Kunci enkripsi tidak ditemukan");
    }

    foreach ($sampleVotes as $vote) {
        $decrypted = sodium_crypto_aead_chacha20poly1305_ietf_decrypt(
            $vote['encrypted_vote'],
            '',
            $vote['nonce'],
            $encKey
        );

        $results['sample_votes'][] = [
            'id_vote' => $vote['id_vote'],
            'id_calon' => $vote['id_calon'],
            'data' => $decrypted ? json_decode($decrypted, true) : null,
            'valid' => (bool)$decrypted
        ];
    }

    echo json_encode([
        'status' => 'success',
        'data' => $results,
        'timestamp' => time(),
        'checks' => [
            'calons_checked' => count($calons),
            'votes_checked' => count($voteCounts),
            'samples_checked' => count($sampleVotes)
        ]
    ], JSON_PRETTY_PRINT);
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error: ' . $e->getMessage(),
        'advice' => 'Periksa struktur tabel dan pastikan semua kolom yang diperlukan ada'
    ], JSON_PRETTY_PRINT);
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ], JSON_PRETTY_PRINT);
}

function checkTableStructure($pdo)
{
    $requiredColumns = [
        'calon' => ['id_calon', 'nama_calon', 'jumlah_suara', 'checksum_suara'],
        'vote_logs' => ['id_vote', 'id_calon', 'encrypted_vote', 'nonce']
    ];

    foreach ($requiredColumns as $table => $columns) {
        $stmt = $pdo->query("DESCRIBE $table");
        $existingColumns = array_column($stmt->fetchAll(), 'Field');

        foreach ($columns as $column) {
            if (!in_array($column, $existingColumns)) {
                throw new Exception("Kolom $column tidak ditemukan di tabel $table");
            }
        }
    }
}
