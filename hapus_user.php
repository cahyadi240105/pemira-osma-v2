<?php
require_once 'auth/config.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_user = (int) $_GET['id'];

    try {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id_users = ?");
        $stmt->execute([$id_user]);

        if ($stmt->rowCount() > 0) {
            header("Location: pengguna.php?status=hapus"); // ✅ Data berhasil dihapus!
        } else {
            header("Location: pengguna.php?status=gagal"); // ❌ Tidak ada baris terhapus
        }
    } catch (PDOException $e) {
        error_log("Gagal menghapus user: " . $e->getMessage());
        header("Location: pengguna.php?status=gagal");
    }
} else {
    header("Location: pengguna.php?status=gagal"); // Tidak valid juga diarahkan ke gagal
}
exit;
