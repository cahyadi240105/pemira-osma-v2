<?php
$dir = __DIR__ . '/auth/keys';
if (!is_dir($dir)) {
    if (!mkdir($dir, 0700, true)) {
        die("Gagal membuat direktori keys");
    }
}

// 1. Generate encryption key
$encKey = random_bytes(SODIUM_CRYPTO_AEAD_CHACHA20POLY1305_IETF_KEYBYTES);
if (file_put_contents("$dir/encrypt.key", $encKey, LOCK_EX) === false) {
    die("Gagal menulis encrypt.key");
}

// 2. Generate signature keys
$keypair = sodium_crypto_sign_keypair();
$secretKey = sodium_crypto_sign_secretkey($keypair);
$publicKey = sodium_crypto_sign_publickey($keypair);

if (file_put_contents("$dir/sign.key", $secretKey, LOCK_EX) === false) {
    die("Gagal menulis sign.key");
}

if (file_put_contents("$dir/verify.key", $publicKey, LOCK_EX) === false) {
    die("Gagal menulis verify.key");
}

// Set permission
chmod("$dir/encrypt.key", 0600);
chmod("$dir/sign.key", 0600);
chmod("$dir/verify.key", 0600);

// Verifikasi
echo "Kunci berhasil digenerate ulang:\n";
echo "- encrypt.key: " . filesize("$dir/encrypt.key") . " bytes\n";
echo "- sign.key: " . filesize("$dir/sign.key") . " bytes\n";
echo "- verify.key: " . filesize("$dir/verify.key") . " bytes\n";

// Contoh output hex (16 karakter pertama)
echo "\nSample (hex):\n";
echo "encrypt: " . bin2hex(substr($encKey, 0, 16)) . "...\n";
echo "sign: " . bin2hex(substr($secretKey, 0, 16)) . "...\n";
echo "verify: " . bin2hex(substr($publicKey, 0, 16)) . "...\n";
