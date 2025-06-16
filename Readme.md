
---

## 🗳️ PEMIRA-OSMA – Sistem Voting Online Organisasi Mahasiswa

**PEMIRA-OSMA** adalah sistem voting digital berbasis web yang dirancang untuk mendukung pemilihan ketua organisasi mahasiswa secara aman, transparan, dan efisien. Sistem ini merupakan sistem yang dibuat tanpa adanya keamanan algoritma dan merupakan sistem yang dibangun untuk sebagai bahan testing SQL Injection, XSS dan lain-lain

---

### 📌 Fitur Utama

* ✅ Autentikasi pemilih dengan token kriptografi
* ✅ Sistem voting 1 suara per pemilih
* ✅ Panel admin untuk kelola kandidat & pemilih
* ✅ Statistik hasil voting real-time

---

### 🛠️ Teknologi yang Digunakan

| Komponen         | Teknologi                                                                                                   |
| ---------------- | ----------------------------------------------------------------------------------------------------------- |
| Bahasa           | PHP Native                                                                                                  |
| Frontend         | Skydash Admin Template (Free)                                                                               |
| Database         | MySQL / MariaDB                                                                                             |

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
├── index.php           
└── README.md
....
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
4. Sesuaikan konfigurasi database dan kriptografi di:
   * `/auth/config.php`
5. Jalankan di server lokal seperti XAMPP atau Laragon.

---
#Pemira-Osma (Pemilihan Raya Organisasi Mahasiswa)
