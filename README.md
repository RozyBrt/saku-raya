# 💰 Saku-Raya API

Backend sistem manajemen saldo dan transfer antar nasabah. Dibangun dengan **Laravel 11**, dilengkapi **Laravel Sanctum** untuk authentication, dan didokumentasikan menggunakan **L5-Swagger**.

## 🚀 Ringkasan
Saku-Raya API adalah backend untuk sistem e-wallet sederhana dengan fitur login, top-up, transfer, dan histori transaksi. Fokusnya adalah pada keamanan, validasi input, dan dokumentasi API yang bisa diuji langsung lewat Swagger UI.

## 🛠️ Tech Stack
- **Framework**: Laravel 11
- **Authentication**: Laravel Sanctum (Bearer Token)
- **API Documentation**: L5-Swagger
- **Database**: PostgreSQL
- **Runtime**: Docker / Laravel Sail
- **Testing**: API bisa diuji via Swagger UI atau Postman

## 🔐 Testing Credentials
Gunakan akun hasil seeder untuk tes cepat:
- **Email**: `test@example.com`
- **Password**: `password`

> Akun ini dibuat langsung dari `database/seeders/DatabaseSeeder.php`.

## 📡 API Documentation
Akses dokumentasi Swagger di:

`http://localhost/api/documentation`

Di sana kamu bisa melihat semua endpoint, payload request, dan mencoba API langsung dengan Bearer token.

## 📌 Endpoint Utama
| Method | Endpoint | Auth | Deskripsi |
| :--- | :--- | :--- | :--- |
| `POST` | `/api/login` | No | Login dan dapatkan access token |
| `GET` | `/api/user` | Yes | Ambil profil user yang sedang login |
| `GET` | `/api/check-status` | Yes | Cek status API / koneksi user |
| `POST` | `/api/top-up` | Yes | Tambah saldo akun sendiri |
| `POST` | `/api/transfer` | Yes | Transfer antar user menggunakan `recipient_account` |
| `GET` | `/api/transactions` | Yes | Ambil riwayat transaksi user |

## ⚙️ Setup Instructions
1. Clone repo ini.
2. Jalankan container Sail:
   ```bash
   ./vendor/bin/sail up -d
   ```
3. Jalankan migrasi fresh dengan seeder:
   ```bash
   ./vendor/bin/sail artisan migrate:fresh --seed
   ```
4. Generate dokumentasi Swagger:
   ```bash
   ./vendor/bin/sail artisan l5-swagger:generate
   ```
5. Buka Swagger UI:
   ```
   http://localhost/api/documentation
   ```

## ✅ Catatan Senior Backend
- Struktur API dibangun untuk workflow autentikasi token-based.
- Semua endpoint VIP dilindungi `bearerAuth` di Swagger.
- Transfer menggunakan `recipient_account` dan saldo penerima bertambah, bukan cuma dikurangi.
- Dokumentasi otomatis sudah tersedia via L5-Swagger.

---
_Saku-Raya dibuat untuk menunjukkan kemampuan integrasi Laravel, Sanctum, dan dokumentasi API profesional._