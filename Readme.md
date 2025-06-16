---
## 🗳️ PEMIRA-OSMA – Sistem Voting Online Organisasi Mahasiswa

PEMIRA-OSMA adalah sistem e-voting digital berbasis web yang dirancang untuk mendukung pemilihan ketua organisasi mahasiswa secara aman, transparan, dan efisien. Sistem ini kini telah diperkuat dengan algoritma kriptografi modern untuk mencegah serangan seperti SQL Injection, XSS, replay attack, dan pemalsuan identitas.

---

### 🔐 Keamanan Kriptografi

PEMIRA-OSMA kini dilindungi dengan sistem keamanan canggih:

* 🔒 **AES-GCM / ChaCha20-Poly1305**: untuk enkripsi simetris data sensitif.
* 🧾 **SHA3-256**: untuk hashing aman seperti password, token, dan integritas data.
* ✍️ **Ed25519**: untuk tanda tangan digital yang cepat dan aman.
* 🪪 **Paseto (Platform-Agnostic Security Tokens)**: sebagai alternatif JWT yang lebih aman untuk token autentikasi dan otorisasi.
* 🔑 **Manajemen Kunci Enkripsi**: sistem ini membangkitkan dan mengelola kunci enkripsi secara lokal dan aman, menyimpan public key untuk verifikasi tanda tangan dan mengenkripsi data pada saat pendaftaran dan voting.

---

### 📌 Fitur Utama

* ✅ Autentikasi pemilih dengan token Paseto terenkripsi
* ✅ Sistem voting 1 suara per pemilih, didukung tanda tangan digital Ed25519
* ✅ Panel admin untuk kelola kandidat & pemilih
* ✅ Statistik hasil voting real-time
* ✅ Validasi proteksi XSS bawaan

---

### 🛠️ Teknologi yang Digunakan

| Komponen | Teknologi                                                          |
| -------- | ------------------------------------------------------------------ |
| Bahasa   | PHP Native                                                         |
| Frontend | Skydash Admin Template (Free)                                      |
| Database | MySQL / MariaDB                                                    |
| Keamanan | AES-GCM / ChaCha20-Poly1305, SHA3-256, Ed25519, Paseto             |

---

### 🧱 Struktur Direktori

```
/pemira-osma
├── /css/            
├── /docs/            
├── /fonts/      
├── /images/           
├── /js/             
├── /pages/
├── /partials/
├── /scss/
├── /vendor/
├── /vendors/               
├── /auth/           # Berisi logika autentikasi dan konfigurasi keamanan
├── /crypto/         # Berisi fungsi enkripsi, dekripsi, tanda tangan
├── index.php           
└── README.md
```

---

### 🔧 Konfigurasi Keamanan

Pastikan untuk mengatur kunci kriptografi pada file berikut:

* `/auth/config.php` — konfigurasi database dan pengaturan umum
* `/crypto/keys.php` — lokasi public/private key Ed25519
* `/crypto/encryption.php` — AES/ChaCha encryption helper
* `/crypto/paseto.php` — helper untuk encoding dan decoding token Paseto

Contoh pengaturan kunci:

```php
// crypto/keys.php
return [
    'ed25519_public_key' => base64_decode('...'),
    'ed25519_private_key' => base64_decode('...'),
    'aes_key' => base64_decode('...'),
    'paseto_key' => base64_decode('...'), // untuk local mode
];
```

---

### 🚀 Cara Menjalankan

1. Clone proyek:

   ```bash
   git clone https://github.com/cahyadi240105/pemira-osma.git
   ```

2. Masuk ke folder:

   ```bash
   cd pemira-osma
   ```

3. Import file SQL ke database lokal Anda.

4. Konfigurasikan database dan kriptografi di:

   * `/auth/config.php`
   * `/crypto/keys.php` (buat jika belum ada)

5. Jalankan proyek pada server lokal seperti XAMPP atau Laragon.

---

### ⚠️ Catatan Tambahan

> **Jangan** membagikan file `keys.php` ke publik atau commit ke repositori. Gunakan file `.gitignore` untuk menghindari kebocoran kunci enkripsi.

---

### 📣 #Pemira-Osma – Pemilihan Raya Organisasi Mahasiswa

Sistem ini dibangun untuk mendukung proses demokrasi kampus berbasis teknologi yang **terbuka, adil, dan aman**.
