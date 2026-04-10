# 💰 Saku-Raya API 

Backend sistem manajemen saldo dan transfer antar nasabah. Dibangun dengan **Laravel 11** dan diproteksi ketat menggunakan **Laravel Sanctum**.

## 🚀 Fitur Utama
- **Secure Authentication**: Sistem login menggunakan Laravel Sanctum untuk menghasilkan Bearer Token.
- **Smart Top Up**: Fitur pengisian saldo otomatis yang terikat langsung dengan user yang sedang login.
- **Atomic Transfer**: Proses kirim uang antar user menggunakan `DB::transaction` untuk menjamin keamanan data dan mencegah saldo bocor.
- **Validation Guard**: Proteksi input data yang ketat dan respon error JSON yang informatif (plus sedikit roasting kalau saldo lo nol). 😏

## 🛠️ Tech Stack
- **Framework**: Laravel 11 (PHP 8.x)
- **Security**: Laravel Sanctum
- **Database**: PostgreSQL
- **Environment**: Docker / Laravel Sail
- **Testing**: Postman

## 📡 API Endpoints

| Method | Endpoint | Auth | Deskripsi |
| :--- | :--- | :--- | :--- |
| `POST` | `/api/login` | No | Mendapatkan Access Token (Tiket VIP) |
| `GET` | `/api/user` | Yes | Mengambil data profil user yang sedang login |
| `POST` | `/api/top-up` | Yes | Menambah saldo ke akun sendiri |
| `POST` | `/api/transfer` | Yes | Mengirim uang ke user lain (via `recipient_account`) |

## ⚙️ Cara Menjalankan
1. Clone repo ini.
2. Jalankan `./vendor/bin/sail up -d`.
3. Jalankan migrasi: `./vendor/bin/sail artisan migrate`.
4. Gunakan Postman untuk nembak API-nya.

---
*Built for learning purposes* 🚀🔥