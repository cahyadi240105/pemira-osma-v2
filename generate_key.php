<?php
// Pastikan extension sodium terinstall
if (!extension_loaded('sodium')) {
    die("Error: Ekstensi Sodium tidak terinstall. Diperlukan untuk kriptografi modern.");
}

// Direktori penyimpanan
$dir = __DIR__ . '/auth/keys';
if (!is_dir($dir)) {
    if (!mkdir($dir, 0700, true)) {
        die("Error: Gagal membuat direktori keys");
    }
}

// Di kode voting Anda
try {
    $keyDir = __DIR__ . '/auth/keys/';
    if (!is_dir($keyDir)) {
        throw new Exception("Direktori kunci tidak ditemukan");
    }

    $encKeyPath = $keyDir . 'encrypt.key';
    if (!file_exists($encKeyPath)) {
        throw new Exception("File kunci enkripsi tidak ditemukan");
    }

    $encryptionKey = file_get_contents($encKeyPath);
    if ($encryptionKey === false) {
        throw new Exception("Gagal membaca file kunci");
    }

    if (strlen($encryptionKey) !== SODIUM_CRYPTO_AEAD_CHACHA20POLY1305_IETF_KEYBYTES) {
        error_log("Kunci tidak valid. Ukuran: " . strlen($encryptionKey) . " byte, Harus: " .
            SODIUM_CRYPTO_AEAD_CHACHA20POLY1305_IETF_KEYBYTES . " byte");
        throw new Exception("Ukuran kunci enkripsi tidak valid");
    }

    // Lanjutkan dengan enkripsi...
} catch (Exception $e) {
    error_log("Error enkripsi: " . $e->getMessage());
    header("Location: voting.php?status=error_sistem");
    exit;
}
