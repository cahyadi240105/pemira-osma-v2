<?php

function isAdmin()
{
    return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
}

function countRows($pdo, $table)
{
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM {$table}");
    $stmt->execute();
    return $stmt->fetchColumn();
}

function countWhere($pdo, $table, $column, $value)
{
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM {$table} WHERE {$column} = ?");
    $stmt->execute([$value]);
    return $stmt->fetchColumn();
}

function countWhereMulti($pdo, $table, $condition)
{
    $columns = array_keys($condition);
    $values = array_values($condition);

    $where = implode(' AND ', array_map(fn($col) => "$col = ?", $columns));

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM {$table} WHERE $where");
    $stmt->execute($values);
    return $stmt->fetchColumn();
}

// UUID v4 Generator
function generateUUIDv4(): string {
    $data = random_bytes(16);
    $data[6] = chr((ord($data[6]) & 0x0f) | 0x40); // versi 4
    $data[8] = chr((ord($data[8]) & 0x3f) | 0x80); // variant 10
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

// Generate checksum (SHA3-256 atau HMAC SHA3-256 jika pakai kunci)
function generateChecksum(string $data, string $key = ''): string {
    return $key ? hash_hmac('sha3-256', $data, $key) : hash('sha3-256', $data);
}

// Enkripsi data dengan ChaCha20-Poly1305
function encryptData(string $plaintext, string $key, string &$nonce): string {
    $nonce = random_bytes(SODIUM_CRYPTO_AEAD_CHACHA20POLY1305_IETF_NPUBBYTES);
    return sodium_crypto_aead_chacha20poly1305_ietf_encrypt(
        $plaintext,
        '', // AAD
        $nonce,
        $key
    );
}

// Tanda tangan digital
function signData(string $data, string $privateKey): string {
    return sodium_crypto_sign_detached($data, $privateKey);
}

// Verifikasi tanda tangan
function verifySignature(string $data, string $signature, string $publicKey): bool {
    return sodium_crypto_sign_verify_detached($signature, $data, $publicKey);
}

// Dekripsi data terenkripsi
function decryptData(string $ciphertext, string $nonce, string $key): string {
    return sodium_crypto_aead_chacha20poly1305_ietf_decrypt(
        $ciphertext,
        '', // AAD
        $nonce,
        $key
    );
}

?>
