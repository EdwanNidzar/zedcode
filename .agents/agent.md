# AGENTS.md - Zedcore Project

## Dev environment tips
- Gunakan `php artisan make:...` untuk men-generate *boilerplate* (contoh: `php artisan make:model LeaveRequest -mc` untuk membuat Model, Migration, sekaligus Controller).
- Jalankan `composer install` untuk dependensi *backend* (PHP) dan `npm install && npm run dev` untuk menjalankan Vite dan me-compile aset *frontend* (Tailwind CSS di dalam Blade).
- Pastikan file `.env` sudah dikonfigurasi dengan kredensial database lokal (MySQL/PostgreSQL) sebelum menjalankan `php artisan migrate` atau `php artisan migrate:fresh --seed`.
- Jika Anda mengubah *Role* atau *Permission* di Spatie, pastikan selalu menjalankan `php artisan permission:cache-reset` agar cache hak akses diperbarui.
- Jika ada *view* Blade, rute, atau konfigurasi yang tidak terbaca perubahannya, jalankan `php artisan optimize:clear`.

## Testing instructions
- Konfigurasi CI/CD (GitHub Actions) dapat ditemukan di dalam folder `.github/workflows/`.
- Jalankan `php artisan test` dari *root directory* untuk mengeksekusi seluruh pengujian (Feature dan Unit test menggunakan PHPUnit/Pest).
- Untuk menjalankan tes spesifik pada satu fungsi atau kelas, gunakan perintah filter: `php artisan test --filter "<NamaTestAtauClass>"`.
- Perbaiki semua *error* logika atau kegagalan tes sampai seluruh hasil terminal berwarna hijau (*passed*).
- Setelah memodifikasi kode PHP, jalankan Laravel Pint dengan perintah `./vendor/bin/pint` (atau `php artisan pint`) untuk menstandarisasi gaya penulisan kode (PSR-12) dan memastikan tidak ada *linting error*.
- Wajib menambahkan atau memperbarui skrip pengujian untuk setiap modifikasi kode, meskipun tidak ada yang meminta. (Misal: Uji fungsionalitas pengajuan cuti baru).

## PR instructions
- Format Judul: `[<Modul>] <Deskripsi Singkat>` (Contoh: `[Leave] Tambahkan validasi sisa cuti tahunan` atau `[Auth] Ubah tampilan halaman login`).
- Wajib menjalankan `php artisan pint` (untuk merapikan kode) dan `php artisan test` (untuk memastikan tidak ada yang *break*) sebelum membuat *commit* baru.

## Anti-Hallucination Rules (WAJIB DIIKUTI)
- **Baca sebelum modifikasi**: Sebelum mengubah file apapun, WAJIB baca isinya terlebih dahulu menggunakan tools yang tersedia. Jangan pernah menulis ulang berdasarkan asumsi.
- **Verifikasi keberadaan file**: Jangan pernah mengasumsikan sebuah file, class, atau fungsi sudah ada. Selalu verifikasi dengan melihat direktori (`list_dir`) atau membaca file langsung.
- **Cek duplikasi sebelum menambah**: Sebelum menambahkan fungsi, relasi, atau kolom baru, pastikan belum ada yang identik di file yang sama. (Kasus nyata: duplikasi `itemRequests()` di `User.php`).
- **Sinkronkan Model & Migration**: Setiap kali membuat atau memodifikasi Model, selalu cek migration yang berkaitan agar kolom di `$fillable` sinkron dengan kolom di tabel database.
- **Jangan generate yang tidak diminta**: Jangan membuat kode, file, atau fitur untuk modul yang belum secara eksplisit diminta oleh user.
- **Konfirmasi jika tidak yakin**: Jika tidak yakin apakah sebuah package sudah terinstall atau konfigurasi sudah ada, cek `composer.json` atau file konfigurasi yang relevan terlebih dahulu — jangan asumsikan.
- **Scope perubahan seminimal mungkin**: Hanya ubah bagian kode yang relevan dengan permintaan. Jangan "refactor" bagian lain yang tidak diminta.
- **Laporkan jika menemukan inkonsistensi**: Jika saat membaca kode ditemukan bug atau inkonsistensi yang tidak terkait dengan tugas, laporkan ke user — jangan diam-diam mengubahnya tanpa konfirmasi.