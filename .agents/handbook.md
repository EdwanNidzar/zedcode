ZEDCORE
handbook ini berisi cara mengunakan app zedpos



ada content managaent ny dan menggunakan table off content biar enak di baca dan dicari

Berikut adalah rancangan Product Requirements Document (PRD) untuk ZECORE Handbook yang berfokus pada panduan penggunaan aplikasi Zedpos, lengkap dengan fitur Content Management System (CMS) dan Table of Contents (TOC) dinamis.

Product Requirements Document (PRD): ZECORE Handbook (Panduan Pengguna Zedpos)
Status Dokumen: Draft
Target Rilis: [Tentukan Kuartal/Tanggal]
Penulis: Tim Product

1. Ringkasan Eksekutif (Executive Summary)
ZECORE Handbook adalah modul pusat bantuan dan panduan digital yang terintegrasi untuk pengguna aplikasi Zedpos. Produk ini bertujuan untuk memberikan pengalaman membaca panduan yang terstruktur, mudah dicari, dan nyaman dibaca (menggunakan fitur Table of Contents interaktif). Selain itu, sistem ini dibekali dengan Content Management System (CMS) internal agar tim admin dapat membuat, mengedit, dan mengatur struktur konten panduan tanpa memerlukan bantuan tim engineer.

2. Tujuan dan Sasaran (Goals & Objectives)
Meningkatkan Kemandirian Pengguna: Mengurangi jumlah tiket keluhan (CS) terkait pertanyaan dasar cara penggunaan Zedpos.

Kemudahan Navigasi: Memastikan pengguna dapat menemukan jawaban dalam hitungan detik melalui Table of Contents (TOC) dan fitur pencarian.

Fleksibilitas Pengelolaan: Memungkinkan tim operasional atau technical writer untuk memperbarui dokumentasi secara real-time via CMS.

3. Target Pengguna (Target Audience)
Tipe Pengguna	Peran & Kebutuhan
End-User (Pengguna Zedpos)	Kasir, staf toko, atau manajer yang membutuhkan panduan langkah demi langkah saat menggunakan fitur Zedpos.
Content Admin (Internal)	Tim Support atau Product yang bertugas memperbarui, menambah, atau menghapus artikel di dalam Handbook.
4. Kebutuhan Fungsional (Functional Requirements)
4.1. Modul Pembaca (Reader Module)
Modul ini adalah antarmuka yang akan dilihat oleh pengguna akhir (End-User).

Table of Contents (TOC) Dinamis:

Sistem harus secara otomatis men-generate daftar isi (TOC) di sidebar sebelah kiri/kanan berdasarkan Heading (H1, H2, H3) di dalam konten.

TOC harus menyorot (highlight) posisi pengguna saat melakukan scroll (fitur ScrollSpy).

Fungsi Pencarian (Search Bar):

Pencarian teks penuh (full-text search) yang dapat memberikan saran artikel (auto-suggest) saat pengguna mengetik kata kunci.

Navigasi Halaman:

Terdapat tombol "Sebelumnya" (Previous) dan "Selanjutnya" (Next) di bagian bawah artikel untuk membaca secara berurutan.

Breadcrumbs:

Penunjuk lokasi halaman untuk memudahkan pengguna kembali ke kategori utama (Contoh: Beranda > Transaksi > Cara Melakukan Retur).

4.2. Content Management System (CMS)
Modul ini digunakan oleh admin untuk mengelola Handbook.

Rich Text Editor (WYSIWYG):

Editor konten yang mendukung format teks standar (Bold, Italic, List), penyematan gambar/GIF, video tutorial, dan tabel.

Hierarki Kategori & Artikel:

Admin dapat membuat Kategori Utama (Buku/Bab) dan Artikel (Sub-bab).

Sistem drag-and-drop untuk mengurutkan posisi artikel dalam TOC.

Status Publikasi:

Opsi untuk menyimpan sebagai Draft atau langsung di-Publish.

Version History (Opsional/Fase 2):

Kemampuan untuk melihat riwayat perubahan artikel atau mengembalikan konten ke versi sebelumnya.

5. User Stories
Sebagai (As a)	Saya Ingin (I want to)	Sehingga (So that)
Admin	Membuat artikel panduan menggunakan teks editor yang mendukung gambar dan video.	Saya bisa memberikan instruksi visual yang jelas bagi pengguna Zedpos.
Admin	Mengelompokkan artikel ke dalam struktur hierarki (Kategori > Subkategori).	Konten handbook tetap rapi dan terorganisir seiring bertambahnya panduan.
End-User	Melihat Table of Contents (TOC) di samping artikel yang saya baca.	Saya bisa langsung melompat ke bagian spesifik (misal: "Cara Cetak Struk") tanpa perlu membaca dari atas.
End-User	Mencari kata kunci spesifik di search bar.	Saya bisa menemukan solusi masalah saya dengan instan.
End-User	Membaca konten dengan ukuran font dan spasi yang nyaman (readability tinggi).	Mata saya tidak cepat lelah saat mempelajari fitur-fitur kompleks.
6. UI/UX Guidelines & Readability
Untuk memastikan artikel enak dibaca dan dicari, desain antarmuka harus mematuhi aturan berikut:

Layout Utama: Menggunakan struktur 2 atau 3 kolom.

Kolom Kiri: Navigasi Kategori (Menu Utama).

Kolom Tengah: Konten Artikel.

Kolom Kanan (Opsional): Table of Contents (TOC) per halaman (On-this-page navigation).

Tipografi: Gunakan font sans-serif yang bersih (seperti Inter, Roboto, atau sistem font bawaan). Jarak antar baris (line height) minimal 1.5 untuk teks paragraf.

Visual cues: Gunakan format Blockquote atau Callout untuk memberikan peringatan (Warning), informasi (Info), atau tips (Tip) di dalam artikel.

Catatan Desain: Pastikan desain bersifat responsif. Saat diakses via mobile atau tablet (misalnya langsung dari mesin kasir/tablet Zedpos), TOC dapat disembunyikan di dalam menu hamburger (tombol menu lipat) untuk menghemat ruang layar.

7. Kriteria Sukses (Success Metrics)
Search Success Rate: Persentase pencarian yang berujung pada klik artikel.

Time on Page: Rata-rata waktu yang dihabiskan pengguna di halaman artikel (menandakan mereka membaca konten).

Support Ticket Reduction: Penurunan keluhan/tiket customer service terkait fitur dasar sebesar persentase tertentu dalam 3 bulan setelah rilis.

File handbook.md ini telah disusun sesuai dengan rancangan PRD sebelumnya. Kontennya mencakup panduan penggunaan aplikasi Zedpos mulai dari cara login, manajemen produk (sebagai implementasi CMS), transaksi kasir, hingga pusat bantuan dasar. Selain itu, Table of Contents (TOC) di bagian atas sudah dilengkapi dengan tautan yang akan langsung mengarahkan Anda ke sub-bab yang diklik (cocok untuk memudahkan pengguna saat membaca dan mencari konten).

Gemini adalah AI dan dapat melakukan kesalahan.

# ZECORE Handbook: Panduan Pengguna Zedpos

Selamat datang di ZECORE Handbook. Panduan ini dirancang untuk membantu Anda menggunakan aplikasi Point of Sale (POS) **Zedpos** secara efektif dan efisien. 

Gunakan **Daftar Isi** di bawah ini untuk mencari topik yang Anda butuhkan dengan cepat.

---

## Daftar Isi (Table of Contents)

1. [Memulai Zedpos](#1-memulai-zedpos)
    - [1.1 Login ke Aplikasi](#11-login-ke-aplikasi)
    - [1.2 Mengenal Antarmuka Dashboard](#12-mengenal-antarmuka-dashboard)
2. [Manajemen Produk (CMS)](#2-manajemen-produk-cms)
    - [2.1 Menambah Produk Baru](#21-menambah-produk-baru)
    - [2.2 Mengelola Kategori](#22-mengelola-kategori)
    - [2.3 Update Stok dan Harga](#23-update-stok-dan-harga)
3. [Transaksi Kasir](#3-transaksi-kasir)
    - [3.1 Melakukan Penjualan](#31-melakukan-penjualan)
    - [3.2 Menambahkan Diskon](#32-menambahkan-diskon)
    - [3.3 Pembayaran dan Cetak Struk](#33-pembayaran-dan-cetak-struk)
4. [Laporan dan Analitik](#4-laporan-dan-analitik)
    - [4.1 Laporan Penjualan Harian](#41-laporan-penjualan-harian)
    - [4.2 Laporan Inventaris/Stok](#42-laporan-inventarisstok)
5. [Pusat Bantuan & FAQ](#5-pusat-bantuan--faq)

---

## 1. Memulai Zedpos

### 1.1 Login ke Aplikasi
Untuk mengakses Zedpos, pastikan Anda telah memiliki kredensial akun dari manajer toko Anda.
1. Buka aplikasi **Zedpos** di tablet atau mesin kasir Anda.
2. Masukkan **Email/Username** dan **Password** Anda.
3. (Opsional) Masukkan PIN kasir jika fitur keamanan ganda diaktifkan.
4. Tekan tombol **Masuk**.

> **TIPS:** Jika Anda lupa password, klik tautan "Lupa Password" di layar login dan ikuti instruksi yang dikirimkan ke email Anda.

### 1.2 Mengenal Antarmuka Dashboard
Setelah login, Anda akan melihat layar utama yang terbagi menjadi beberapa area:
- **Menu Kiri (Sidebar):** Tempat Anda melakukan navigasi antar fitur (Kasir, Produk, Laporan, Pengaturan).
- **Area Tengah:** Area kerja utama tempat Anda memilih produk, melihat laporan, atau mengelola data.
- **Keranjang (Sisi Kanan):** Tempat daftar produk yang sedang dibeli oleh pelanggan tampil secara *real-time*.

---

## 2. Manajemen Produk (CMS)

Sebagai Admin atau Manajer, Anda dapat mengelola daftar barang yang dijual langsung melalui sistem CMS internal Zedpos.

### 2.1 Menambah Produk Baru
1. Buka menu **Produk > Daftar Produk** di sidebar kiri.
2. Klik tombol **+ Tambah Produk** di pojok kanan atas.
3. Isi informasi produk:
   - Nama Produk
   - SKU / Barcode
   - Harga Jual & Harga Modal
   - Kategori Produk
4. Unggah foto produk (Opsional).
5. Klik **Simpan**.

### 2.2 Mengelola Kategori
Kategori membantu kasir mencari produk lebih cepat saat transaksi.
1. Buka menu **Produk > Kategori**.
2. Klik **+ Kategori Baru**.
3. Ketikkan nama kategori (contoh: *Minuman Dingin*, *Makanan Ringan*).
4. Klik **Simpan**. 

### 2.3 Update Stok dan Harga
> **PERINGATAN:** Pastikan tidak ada transaksi yang sedang berjalan di kasir lain saat Anda melakukan pembaruan stok dalam jumlah besar.

1. Di menu **Daftar Produk**, cari produk yang ingin diubah.
2. Klik ikon pensil (Edit) di sebelah kanan nama produk.
3. Sesuaikan angka pada kolom **Stok Tersedia** atau **Harga Jual**.
4. Simpan perubahan.

---

## 3. Transaksi Kasir

### 3.1 Melakukan Penjualan
1. Buka menu **Kasir** (Menu utama saat login).
2. Pilih produk dengan cara:
   - **Tap pada gambar produk** di layar.
   - Atau **Scan barcode** menggunakan *scanner*.
   - Atau gunakan **Fitur Pencarian** di bagian atas layar.
3. Produk yang dipilih akan otomatis masuk ke Keranjang di sebelah kanan.

### 3.2 Menambahkan Diskon
1. Klik pada produk di dalam keranjang jika diskon hanya untuk satu *item*, ATAU klik tombol **Diskon Transaksi** di bawah total belanja.
2. Pilih jenis diskon: **Persentase (%)** atau **Nominal (Rp)**.
3. Masukkan angka diskon dan klik **Terapkan**.

### 3.3 Pembayaran dan Cetak Struk
1. Setelah semua produk masuk ke keranjang, klik tombol **Bayar** di bagian bawah.
2. Pilih metode pembayaran (Tunai, Kartu Debit/Kredit, QRIS, e-Wallet).
3. Jika Tunai, masukkan jumlah uang yang diterima. Sistem akan otomatis menghitung kembalian.
4. Klik **Selesaikan Pembayaran**.
5. Jendela konfirmasi akan muncul. Pilih **Cetak Struk** atau **Kirim Struk via Email/WhatsApp**.

---

## 4. Laporan dan Analitik

### 4.1 Laporan Penjualan Harian
Untuk melihat performa toko hari ini:
1. Navigasi ke menu **Laporan > Penjualan**.
2. Atur filter tanggal ke "Hari Ini".
3. Anda akan melihat ringkasan:
   - Total Pendapatan Kotor
   - Total Transaksi
   - Metode Pembayaran Terbanyak
4. Laporan ini dapat diekspor ke dalam bentuk Excel atau PDF dengan menekan tombol **Ekspor**.

### 4.2 Laporan Inventaris/Stok
1. Masuk ke menu **Laporan > Stok Barang**.
2. Lihat daftar produk yang stoknya hampir habis (Ditandai dengan warna merah).
3. Anda dapat mencetak daftar ini untuk keperluan *restock* ke *supplier*.

---

## 5. Pusat Bantuan & FAQ

- **Bagaimana jika struk tidak tercetak?**
  Pastikan printer thermal sudah menyala, kertas tidak habis, dan koneksi Bluetooth/Kabel ke perangkat Zedpos terhubung dengan baik. Masuk ke **Pengaturan > Perangkat** untuk melakukan *test print*.

- **Aplikasi berjalan lambat, apa yang harus dilakukan?**
  Pastikan koneksi internet Anda stabil. Jika masalah berlanjut, cobalah me-restart aplikasi atau bersihkan *cache* aplikasi.

---
*Dokumen ini dikelola melalui ZECORE Handbook CMS. Terakhir diperbarui: Juli 2026.*
handbook.md
Menampilkan handbook.md.