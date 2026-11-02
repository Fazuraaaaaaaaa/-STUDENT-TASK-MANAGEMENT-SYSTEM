# TASKORA — Sistem Manajemen Tugas Akademik Siswa & Guru

TASKORA adalah platform produktivitas akademik berbasis web yang menghubungkan guru dan siswa dalam satu ekosistem terpadu. Aplikasi ini dirancang sebagai SaaS pendidikan modern dengan antarmuka bersih, alur kerja andal, dan otorisasi berbasis peran (Role-Based Access Control) yang ketat di sisi backend.

---

## 1. Tumpukan Teknologi

- **Backend:** Laravel 13, PHP 8.5+, Eloquent ORM, Laravel Authorization Policies
- **Database:** SQLite (default lokal) / MySQL kompatibel
- **Frontend:** Blade Templates, Tailwind CSS, Lucide Icons, Alpine.js
- **Zona Waktu:** `Asia/Jakarta` (WIB)
- **Bahasa Antarmuka:** Bahasa Indonesia (`APP_LOCALE=id`)

---

## 2. Akun Demo Pengujian

Semua akun demo telah dilengkapi data akademik riil:

| Peran | Nama | Email | Kata Sandi | Keterangan |
|---|---|---|---|---|
| **Guru** | Budi Santoso | `teacher@taskora.test` | `password123` | NIP: `TCH-001`, pengajar 2 kelas |
| **Siswa 1** | Rafa Ardian | `student1@taskora.test` | `password123` | NIS: `STU-001`, terdaftar di 2 kelas |
| **Siswa 2** | Siti Rahayu | `student2@taskora.test` | `password123` | NIS: `STU-002`, terdaftar di XII RPL 1 |
| **Siswa 3** | Dimas Putra | `student3@taskora.test` | `password123` | NIS: `STU-003`, terdaftar di 2 kelas |

> **Catatan Keamanan:** Kredensial demo ini hanya untuk pengujian lokal (`APP_ENV=local`). Ganti sebelum produksi.

---

## 3. Menjalankan di Localhost

```bash
# 1. Pasang dependensi PHP
composer install

# 2. Salin environment dan generate key
cp .env.example .env
php artisan key:generate

# 3. Jalankan migrasi dan seeder data demo
php artisan migrate:fresh --seed

# 4. Jalankan server Laravel lokal
php artisan serve --host=127.0.0.1 --port=8000
```

Akses melalui peramban: [http://localhost:8000](http://localhost:8000)

---

## 4. Opsi Database MySQL

Untuk menggunakan MySQL, sesuaikan file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=taskora
DB_USERNAME=root
DB_PASSWORD=password_anda
```
Lalu jalankan `php artisan migrate:fresh --seed`.

---

## 5. Pengingat Batas Waktu (Scheduler)

Aplikasi memiliki perintah pengingat tenggat otomatis untuk siswa yang belum mengumpulkan tugas:
```bash
# Jalankan manual:
php artisan taskora:send-deadline-reminders

# Jalankan scheduler lokal di background:
php artisan schedule:work
```

---

## 6. Pengujian Otomatis (Testing)

Jalankan test suite PHPUnit untuk memverifikasi seluruh fitur:
```bash
php artisan test --without-tty --testdox
```
Hasil pengujian: **11 tests passed, 33 assertions passed (100% OK)**.

---

## 7. Fitur yang Berfungsi Penuh

1. **Autentikasi & Hak Akses:** Login multi-peran, pencegahan akses silang, proteksi CSRF, Laravel Policies.
2. **Dasbor Peran:** Dasbor guru dan siswa dengan kalkulasi metrik riil dari database.
3. **Manajemen Kelas:** Buat kelas, regenerasi kode undangan, gabung kelas via kode, keluarkan siswa.
4. **Mata Pelajaran:** CRUD mata pelajaran terkait kelas dengan validasi kepemilikan guru.
5. **Manajemen Tugas:** Buat tugas draf/terbit, tenggat jam/tanggal, izin keterlambatan dan kirim ulang.
6. **Pengumpulan Tugas:** Upload berkas fisik (PDF, DOCX, ZIP, gambar maks 10 MB), pencatatan versi berkas.
7. **Penilaian & Umpan Balik:** Input nilai, umpan balik, tindakan terbitkan nilai atau minta revisi, pencatatan histori nilai.
8. **Kalender Tugas:** Kalender bulanan interaktif, navigasi bulan, filter mata pelajaran, daftar tenggat.
9. **Pusat Notifikasi:** Pusat notifikasi riil untuk tugas baru, pengingat tenggat, pengumpulan, dan nilai.
10. **Profil & Keamanan:** Ubah nama, email, dan ganti kata sandi terverifikasi kata sandi lama.
