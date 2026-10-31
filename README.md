# Hakajobspro — Tema WordPress Portal Lowongan Kerja

**Hakajobspro** adalah tema WordPress portal informasi lowongan kerja yang cepat, responsif di seluruh perangkat (Desktop, Tablet, Mobile), dan dilengkapi sistem pembaruan otomatis terintegrasi GitHub.

- **Theme Name:** Hakajobspro
- **Theme URI:** https://github.com/hakastudio/hakajobspro
- **Author:** Haka Digital Network
- **Author URI:** https://www.hakadigitalnetwork.cloud/
- **Version:** 1.0.0

---

## Fitur Utama

1. **Custom Post Type `loker` (`/lowongan/`) Terpisah dari Artikel Blog (`Tips Karier`)**
   - Dilengkapi 12 kolom Meta Box Detail Lowongan (Perusahaan, Logo, Website, Gaji Min/Maks, Periode Gaji, Deadline Pendaftaran, Pendidikan, Pengalaman, Cara Melamar, Link/Email/WA Lamaran, Status Unggulan, dan Status Ditutup).
2. **Arsip & Riwayat Lowongan Perusahaan (`/perusahaan/nama-perusahaan/`)**
   - Menampilkan profil perusahaan beserta seluruh riwayat lowongan yang pernah dipublikasikan (filter: Semua Riwayat, Masih Buka, Sudah Ditutup).
3. **Jadwal Penutupan Lowongan Otomatis (Auto-Close Deadline)**
   - Lowongan yang melewati tanggal batas akhir pendaftaran otomatis ditandai **DITUTUP / CLOSED**, tombol lamar dinonaktifkan, dan peringatan penutupan ditampilkan.
4. **Schema `JobPosting` (Google Jobs JSON-LD) Anti-Bentrok SEO**
   - Menghasilkan struktur JSON-LD `JobPosting` otomatis dan mendeteksi Yoast SEO / Rank Math agar tidak terjadi duplikasi schema.
5. **Alat Admin — Siarkan Loker ke Saluran WhatsApp**
   - Bilah khusus Admin di halaman detail lowongan & tabel WP Admin untuk menyalin ringkasan poin lowongan (Tipe Pekerjaan, Posisi, Lokasi, Pendidikan, Pengalaman, Gaji, Deadline, Link) tanpa menyertakan isi paragraf artikel.
6. **Kotak Ajakan Gabung Saluran WhatsApp (WA Channel CTA)**
   - Tampil otomatis di bagian akhir artikel lowongan kerja.
7. **Proteksi Anti-Copas & Widget Loker Populer + Back to Top**
   - Melindungi konten dari klik kanan, blok teks, dan pintasan salin (`Ctrl+C`, `Ctrl+U`, `Ctrl+S`), dilengkapi Widget Loker Populer berdasarkan jumlah dilihat serta tombol kembali ke atas.
8. **Pengaturan Penuh via WP Admin Customizer**
   - Warna tema, warna bar menu, jenis & ukuran font, ukuran logo Desktop & Mobile, lebar halaman, teks hero, kontak, media sosial, dan footer dapat diatur langsung dari **Appearance -> Customize -> Pengaturan Tema Hakajobspro**.
9. **Sistem Update Otomatis dari GitHub (`inc/theme-updater.php`)**
   - Terhubung langsung ke `https://github.com/hakastudio/hakajobspro`.

---

## Cara Menggunakan Sistem Update Otomatis di WordPress

1. Setiap kali Anda melakukan perubahan pada tema di komputer, ubah nomor versi pada baris `Version:` di file `style.css` (contoh: dari `1.0.0` menjadi `1.0.1`).
2. Push perubahan tersebut ke branch `main` di repositori GitHub `https://github.com/hakastudio/hakajobspro`:
   ```bash
   git add .
   git commit -m "Release versi 1.0.1"
   git push origin main
   ```
3. Buka Dasbor WordPress Anda:
   - Masuk ke **Dashboard -> Updates**, **Appearance -> Themes**, atau **Appearance -> Update Tema (GitHub)**.
   - Klik **Cek Pembaruan dari GitHub Sekarang** (atau tunggu pengecekan berkala otomatis).
   - Klik **Perbarui Tema Sekarang** untuk memperbarui tema secara langsung dari Dasbor WordPress tanpa perlu mengunggah ulang file ZIP secara manual.
