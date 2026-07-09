<?php

namespace Database\Seeders;

use App\Models\HandbookArticle;
use App\Models\HandbookCategory;
use App\Models\User;
use Illuminate\Database\Seeder;

class HandbookSeeder extends Seeder
{
    /**
     * Seed data handbook dari konten panduan Zedpos yang sudah ada.
     * Konten disimpan sebagai HTML agar langsung ditampilkan via Quill/prose.
     */
    public function run(): void
    {
        // Ambil user pertama sebagai author (biasanya Super Admin)
        $author = User::first();

        if (! $author) {
            $this->command->warn('Tidak ada user ditemukan. Jalankan UserSeeder terlebih dahulu.');

            return;
        }

        // ── 1. Buat Kategori ─────────────────────────────────────

        $categories = [
            [
                'name' => 'Memulai Zedpos',
                'slug' => 'memulai-zedpos',
                'icon' => '🚀',
                'sort_order' => 1,
            ],
            [
                'name' => 'Manajemen Produk',
                'slug' => 'manajemen-produk',
                'icon' => '📦',
                'sort_order' => 2,
            ],
            [
                'name' => 'Transaksi Kasir',
                'slug' => 'transaksi-kasir',
                'icon' => '🛒',
                'sort_order' => 3,
            ],
            [
                'name' => 'Laporan & Analitik',
                'slug' => 'laporan-dan-analitik',
                'icon' => '📊',
                'sort_order' => 4,
            ],
            [
                'name' => 'Pusat Bantuan & FAQ',
                'slug' => 'pusat-bantuan-faq',
                'icon' => '💡',
                'sort_order' => 5,
            ],
        ];

        foreach ($categories as $catData) {
            HandbookCategory::firstOrCreate(['slug' => $catData['slug']], $catData);
        }

        $cat = fn (string $slug) => HandbookCategory::where('slug', $slug)->first();

        // ── 2. Buat Artikel ──────────────────────────────────────

        $articles = [

            // ─── Memulai Zedpos ───────────────────────────────
            [
                'handbook_category_id' => $cat('memulai-zedpos')->id,
                'title' => 'Login ke Aplikasi',
                'slug' => 'login-ke-aplikasi',
                'status' => 'published',
                'sort_order' => 1,
                'author_id' => $author->id,
                'content' => '<p>Untuk mengakses Zedpos, pastikan Anda telah memiliki kredensial akun dari manajer toko Anda.</p>
<ol>
<li>Buka aplikasi <strong>Zedpos</strong> di tablet atau mesin kasir Anda.</li>
<li>Masukkan <strong>Email/Username</strong> dan <strong>Password</strong> Anda.</li>
<li>(Opsional) Masukkan PIN kasir jika fitur keamanan ganda diaktifkan.</li>
<li>Tekan tombol <strong>Masuk</strong>.</li>
</ol>
<blockquote><p>💡 <strong>Tips:</strong> Jika Anda lupa password, klik tautan "Lupa Password" di layar login dan ikuti instruksi yang dikirimkan ke email Anda.</p></blockquote>',
            ],
            [
                'handbook_category_id' => $cat('memulai-zedpos')->id,
                'title' => 'Mengenal Antarmuka Dashboard',
                'slug' => 'mengenal-antarmuka-dashboard',
                'status' => 'published',
                'sort_order' => 2,
                'author_id' => $author->id,
                'content' => '<p>Setelah login, Anda akan melihat layar utama yang terbagi menjadi beberapa area penting:</p>
<ul>
<li><strong>Menu Kiri (Sidebar):</strong> Tempat Anda melakukan navigasi antar fitur — Kasir, Produk, Laporan, Pengaturan.</li>
<li><strong>Area Tengah:</strong> Area kerja utama tempat Anda memilih produk, melihat laporan, atau mengelola data.</li>
<li><strong>Keranjang (Sisi Kanan):</strong> Tempat daftar produk yang sedang dibeli oleh pelanggan tampil secara real-time.</li>
</ul>
<h2>Shortcut Berguna</h2>
<p>Beberapa shortcut keyboard yang membantu pekerjaan kasir:</p>
<ul>
<li><code>F2</code> — Fokus ke kotak pencarian produk</li>
<li><code>F9</code> — Buka layar pembayaran</li>
<li><code>Esc</code> — Kembali / tutup modal</li>
</ul>',
            ],

            // ─── Manajemen Produk ─────────────────────────────
            [
                'handbook_category_id' => $cat('manajemen-produk')->id,
                'title' => 'Menambah Produk Baru',
                'slug' => 'menambah-produk-baru',
                'status' => 'published',
                'sort_order' => 1,
                'author_id' => $author->id,
                'content' => '<p>Sebagai Admin atau Manajer, Anda dapat mengelola daftar barang yang dijual langsung melalui sistem CMS internal Zedpos.</p>
<ol>
<li>Buka menu <strong>Produk &gt; Daftar Produk</strong> di sidebar kiri.</li>
<li>Klik tombol <strong>+ Tambah Produk</strong> di pojok kanan atas.</li>
<li>Isi informasi produk:
<ul>
<li>Nama Produk</li>
<li>SKU / Barcode</li>
<li>Harga Jual &amp; Harga Modal</li>
<li>Kategori Produk</li>
</ul>
</li>
<li>Unggah foto produk (Opsional).</li>
<li>Klik <strong>Simpan</strong>.</li>
</ol>',
            ],
            [
                'handbook_category_id' => $cat('manajemen-produk')->id,
                'title' => 'Mengelola Kategori Produk',
                'slug' => 'mengelola-kategori-produk',
                'status' => 'published',
                'sort_order' => 2,
                'author_id' => $author->id,
                'content' => '<p>Kategori membantu kasir mencari produk lebih cepat saat transaksi berlangsung.</p>
<ol>
<li>Buka menu <strong>Produk &gt; Kategori</strong>.</li>
<li>Klik <strong>+ Kategori Baru</strong>.</li>
<li>Ketikkan nama kategori (contoh: <em>Minuman Dingin</em>, <em>Makanan Ringan</em>).</li>
<li>Klik <strong>Simpan</strong>.</li>
</ol>
<blockquote><p>📌 Gunakan nama kategori yang singkat dan deskriptif agar mudah ditemukan kasir saat transaksi cepat.</p></blockquote>',
            ],
            [
                'handbook_category_id' => $cat('manajemen-produk')->id,
                'title' => 'Update Stok dan Harga',
                'slug' => 'update-stok-dan-harga',
                'status' => 'published',
                'sort_order' => 3,
                'author_id' => $author->id,
                'content' => '<blockquote><p>⚠️ <strong>Peringatan:</strong> Pastikan tidak ada transaksi yang sedang berjalan di kasir lain saat Anda melakukan pembaruan stok dalam jumlah besar.</p></blockquote>
<ol>
<li>Di menu <strong>Daftar Produk</strong>, cari produk yang ingin diubah menggunakan kolom pencarian.</li>
<li>Klik ikon pensil (Edit) di sebelah kanan nama produk.</li>
<li>Sesuaikan angka pada kolom <strong>Stok Tersedia</strong> atau <strong>Harga Jual</strong>.</li>
<li>Simpan perubahan.</li>
</ol>
<h2>Update Massal</h2>
<p>Untuk update stok dalam jumlah besar, gunakan fitur <strong>Import Excel</strong> yang tersedia di halaman Daftar Produk &gt; Tombol "Import".</p>',
            ],

            // ─── Transaksi Kasir ──────────────────────────────
            [
                'handbook_category_id' => $cat('transaksi-kasir')->id,
                'title' => 'Melakukan Penjualan',
                'slug' => 'melakukan-penjualan',
                'status' => 'published',
                'sort_order' => 1,
                'author_id' => $author->id,
                'content' => '<p>Berikut langkah-langkah melakukan transaksi penjualan di Zedpos:</p>
<ol>
<li>Buka menu <strong>Kasir</strong> (Menu utama saat login).</li>
<li>Pilih produk dengan salah satu cara:
<ul>
<li><strong>Tap pada gambar produk</strong> di layar.</li>
<li>Atau <strong>Scan barcode</strong> menggunakan scanner.</li>
<li>Atau gunakan <strong>Fitur Pencarian</strong> di bagian atas layar.</li>
</ul>
</li>
<li>Produk yang dipilih akan otomatis masuk ke Keranjang di sebelah kanan.</li>
</ol>
<h2>Mengubah Jumlah Item</h2>
<p>Di dalam keranjang, klik angka kuantitas pada produk untuk mengubahnya secara langsung, atau gunakan tombol <strong>+</strong> / <strong>-</strong> di samping item.</p>',
            ],
            [
                'handbook_category_id' => $cat('transaksi-kasir')->id,
                'title' => 'Menambahkan Diskon',
                'slug' => 'menambahkan-diskon',
                'status' => 'published',
                'sort_order' => 2,
                'author_id' => $author->id,
                'content' => '<p>Zedpos mendukung dua jenis diskon: per-item dan per-transaksi.</p>
<h2>Diskon Per Item</h2>
<ol>
<li>Klik pada produk di dalam keranjang yang ingin diberi diskon.</li>
<li>Pilih jenis diskon: <strong>Persentase (%)</strong> atau <strong>Nominal (Rp)</strong>.</li>
<li>Masukkan angka diskon dan klik <strong>Terapkan</strong>.</li>
</ol>
<h2>Diskon Per Transaksi</h2>
<ol>
<li>Klik tombol <strong>Diskon Transaksi</strong> di bawah total belanja.</li>
<li>Pilih jenis dan nilai diskon.</li>
<li>Klik <strong>Terapkan</strong>.</li>
</ol>
<blockquote><p>💡 Diskon per transaksi akan mengurangi total belanja secara keseluruhan, bukan per-item.</p></blockquote>',
            ],
            [
                'handbook_category_id' => $cat('transaksi-kasir')->id,
                'title' => 'Pembayaran dan Cetak Struk',
                'slug' => 'pembayaran-dan-cetak-struk',
                'status' => 'published',
                'sort_order' => 3,
                'author_id' => $author->id,
                'content' => '<p>Setelah semua produk masuk ke keranjang, selesaikan transaksi dengan langkah berikut:</p>
<ol>
<li>Klik tombol <strong>Bayar</strong> di bagian bawah keranjang.</li>
<li>Pilih metode pembayaran:
<ul>
<li>💵 Tunai</li>
<li>💳 Kartu Debit / Kredit</li>
<li>📱 QRIS</li>
<li>🎁 e-Wallet (GoPay, OVO, Dana)</li>
</ul>
</li>
<li>Jika <strong>Tunai</strong>, masukkan jumlah uang yang diterima. Sistem akan otomatis menghitung kembalian.</li>
<li>Klik <strong>Selesaikan Pembayaran</strong>.</li>
<li>Jendela konfirmasi muncul. Pilih:
<ul>
<li><strong>Cetak Struk</strong> — via printer thermal</li>
<li><strong>Kirim via Email</strong> — masukkan email pelanggan</li>
<li><strong>Kirim via WhatsApp</strong> — masukkan nomor HP pelanggan</li>
</ul>
</li>
</ol>',
            ],

            // ─── Laporan ─────────────────────────────────────
            [
                'handbook_category_id' => $cat('laporan-dan-analitik')->id,
                'title' => 'Laporan Penjualan Harian',
                'slug' => 'laporan-penjualan-harian',
                'status' => 'published',
                'sort_order' => 1,
                'author_id' => $author->id,
                'content' => '<p>Pantau performa toko Anda hari ini dengan laporan penjualan real-time.</p>
<ol>
<li>Navigasi ke menu <strong>Laporan &gt; Penjualan</strong>.</li>
<li>Atur filter tanggal ke "Hari Ini".</li>
<li>Anda akan melihat ringkasan:
<ul>
<li>Total Pendapatan Kotor</li>
<li>Total Transaksi</li>
<li>Metode Pembayaran Terbanyak</li>
<li>Produk Terlaris</li>
</ul>
</li>
<li>Laporan ini dapat diekspor ke <strong>Excel</strong> atau <strong>PDF</strong> dengan menekan tombol <strong>Ekspor</strong>.</li>
</ol>
<h2>Filter Periode</h2>
<p>Gunakan filter tanggal custom untuk melihat laporan periode tertentu — mingguan, bulanan, atau rentang tanggal bebas.</p>',
            ],
            [
                'handbook_category_id' => $cat('laporan-dan-analitik')->id,
                'title' => 'Laporan Inventaris & Stok',
                'slug' => 'laporan-inventaris-stok',
                'status' => 'published',
                'sort_order' => 2,
                'author_id' => $author->id,
                'content' => '<p>Monitor ketersediaan stok dan dapatkan peringatan ketika produk hampir habis.</p>
<ol>
<li>Masuk ke menu <strong>Laporan &gt; Stok Barang</strong>.</li>
<li>Lihat daftar produk yang stoknya hampir habis (ditandai dengan warna merah).</li>
<li>Anda dapat mencetak daftar ini untuk keperluan restock ke supplier.</li>
</ol>
<h2>Notifikasi Stok Minimum</h2>
<p>Atur batas minimum stok di <strong>Pengaturan &gt; Notifikasi</strong>. Sistem akan otomatis mengirim notifikasi ketika stok produk mencapai batas minimum yang Anda tentukan.</p>',
            ],

            // ─── FAQ ─────────────────────────────────────────
            [
                'handbook_category_id' => $cat('pusat-bantuan-faq')->id,
                'title' => 'Struk Tidak Tercetak',
                'slug' => 'struk-tidak-tercetak',
                'status' => 'published',
                'sort_order' => 1,
                'author_id' => $author->id,
                'content' => '<p>Jika struk gagal tercetak setelah transaksi, ikuti langkah troubleshooting berikut:</p>
<h2>Cek Kondisi Printer</h2>
<ul>
<li>Pastikan printer thermal sudah <strong>menyala</strong>.</li>
<li>Periksa apakah <strong>kertas struk tidak habis</strong>.</li>
<li>Pastikan kertas terpasang dengan orientasi yang benar (sisi mengkilap menghadap atas).</li>
</ul>
<h2>Cek Koneksi</h2>
<ul>
<li>Periksa koneksi <strong>Bluetooth</strong> atau <strong>kabel USB</strong> antara printer dan perangkat Zedpos.</li>
<li>Coba putuskan dan sambungkan kembali koneksi.</li>
</ul>
<h2>Test Print</h2>
<p>Masuk ke <strong>Pengaturan &gt; Perangkat &gt; Printer</strong>, lalu klik tombol <strong>Test Print</strong> untuk memastikan printer berfungsi normal.</p>
<blockquote><p>📞 Jika masalah berlanjut, hubungi tim support Zedpos di support@zedpos.id atau telepon 0800-123-ZEDPOS.</p></blockquote>',
            ],
            [
                'handbook_category_id' => $cat('pusat-bantuan-faq')->id,
                'title' => 'Aplikasi Berjalan Lambat',
                'slug' => 'aplikasi-berjalan-lambat',
                'status' => 'published',
                'sort_order' => 2,
                'author_id' => $author->id,
                'content' => '<p>Jika aplikasi Zedpos terasa lambat atau lag, coba solusi berikut secara berurutan:</p>
<h2>Solusi Cepat</h2>
<ol>
<li>Pastikan <strong>koneksi internet</strong> Anda stabil (minimal 5 Mbps untuk operasi normal).</li>
<li>Coba <strong>restart aplikasi</strong> Zedpos.</li>
<li>Bersihkan <strong>cache aplikasi</strong> melalui Pengaturan perangkat Anda.</li>
</ol>
<h2>Solusi Lanjutan</h2>
<ul>
<li>Restart perangkat (tablet atau komputer kasir).</li>
<li>Pastikan penyimpanan perangkat tidak penuh (minimal sisakan 2GB free space).</li>
<li>Update aplikasi Zedpos ke versi terbaru.</li>
</ul>
<blockquote><p>⚠️ Jika masalah terjadi berulang kali di jam sibuk, pertimbangkan upgrade paket internet ke yang lebih stabil.</p></blockquote>',
            ],
        ];

        foreach ($articles as $articleData) {
            HandbookArticle::firstOrCreate(
                ['slug' => $articleData['slug']],
                $articleData
            );
        }

        $this->command->info('✅ Handbook seeder berhasil dijalankan — '.count($articles).' artikel dari '.count($categories).' kategori.');
    }
}
