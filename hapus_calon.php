<?php
require_once 'auth/config.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_calon = (int) $_GET['id'];

    try {
        $stmt = $pdo->prepare("DELETE FROM calon WHERE id_calon = ?");
        $stmt->execute([$id_calon]);

        if ($stmt->rowCount()) {
            header("Location: kandidat.php?status=hapus"); // sesuai: bg-danger, pesan: Data berhasil dihapus!
        } else {
            header("Location: kandidat.php?status=gagal"); // sesuai: bg-danger, pesan: Terjadi kesalahan. Silakan coba lagi!
        }
    } catch (PDOException $e) {
        error_log("Kesalahan saat menghapus calon: " . $e->getMessage());
        header("Location: kandidat.php?status=gagal"); // sesuai: bg-danger
    }
} else {
    // ID tidak dikirim atau tidak valid
    header("Location: kandidat.php?status=gagal"); 
}
exit;
