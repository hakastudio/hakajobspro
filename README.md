# Hakajobspro — Tema WordPress Portal Lowongan Kerja

**Hakajobspro** adalah tema WordPress portal informasi lowongan kerja yang cepat, responsif di seluruh perangkat (Desktop, Tablet, Mobile), dilengkapi manajemen slot iklan Google AdSense (ON/OFF), pengaturan menu Header & Footer langsung, serta sistem pembaruan otomatis terintegrasi GitHub.

- **Theme Name:** Hakajobspro
- **Theme URI:** https://github.com/hakastudio/hakajobspro
- **Author:** Haka Digital Network
- **Author URI:** https://www.hakadigitalnetwork.cloud/
- **Version:** 1.1.6

---

## Fitur Utama

1. **Custom Post Type `loker` (`/lowongan/`) Terpisah dari Artikel Blog (`Tips Karier`)**
   - Dilengkapi Meta Box **Detail Lowongan** (Perusahaan, Website, Lokasi Detail, Tipe Kerja, Gaji Min/Maks, Pendidikan, Pengalaman, Deadline Pendaftaran, Cara Melamar, Link/Email Lamaran, dan Status Unggulan).
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
7. **Manajemen 8 Slot Iklan Google AdSense + Fitur ON/OFF (`inc/adsense.php`)**
   - Dapat diatur melalui **WP Admin -> Appearance -> Iklan AdSense** maupun **WP Customizer**:
     - Script `<head>` Google AdSense Auto Ads (ON/OFF)
     - Slot 1: Bawah Header (`header`)
     - Slot 2: Di Tengah Daftar Loker / In-Feed (`infeed`)
     - Slot 3: Atas Artikel Detail Lowongan (`single_top`)
     - Slot 4: Tengah Deskripsi Lowongan / In-Article (`single_mid`)
     - Slot 5: Bawah Artikel / Sebelum Tombol Lamar (`single_bottom`)
     - Slot 6: Sidebar Kanan Atas (`sidebar`)
     - Slot 7: Sidebar Kanan Bawah Sticky (`sidebar_bottom`)
     - Slot 8: Atas Footer (`footer`)
     - Ditambah Widget WordPress `Hakajobspro: Slot Iklan AdSense` untuk penambahan slot bebas di Sidebar Kanan.
8. **Pengaturan Menu Header & Footer Secara Langsung (`inc/menu-manager.php`)**
   - Dapat diatur langsung dari **WP Admin -> Appearance -> Menu Header & Footer** maupun **WP Customizer**.
9. **Proteksi Anti-Copas & Widget Loker Populer + Back to Top**
   - Melindungi konten dari klik kanan, blok teks, dan pintasan salin (`Ctrl+C`, `Ctrl+U`, `Ctrl+S`), dilengkapi Widget Loker Populer berdasarkan jumlah dilihat serta tombol kembali ke atas.
10. **Sistem Update Otomatis dari GitHub (`inc/theme-updater.php`)**
    - Terhubung langsung ke `https://github.com/hakastudio/hakajobspro`.

---

## Panduan Mengisi Meta Box (`Detail Lowongan`) & Penulisan Gaji

Untuk menerbitkan lowongan kerja baru, masuk ke menu **WP Admin -> Lowongan Kerja -> Tambah Baru**. Setelah mengisi Judul dan Isi Artikel di editor atas, gulir ke bawah ke kotak **Detail Lowongan**.

### 1. Cara Penulisan Gaji (`Gaji Minimum` & `Gaji Maksimum`)
Kolom **Gaji Minimum (Rp)** dan **Gaji Maksimum (Rp)** wajib diisi menggunakan **angka murni tanpa titik (`.`), tanpa koma (`,`), dan tanpa tulisan `Rp`**. Tema akan memformatnya secara otomatis menjadi singkatan Juta (`Jt`) di tampilan depan dan mengirim angka murninya ke Google Jobs Schema (`JobPosting`).

| Kondisi Gaji | Isi di Kolom `Gaji Minimum (Rp)` | Isi di Kolom `Gaji Maksimum (Rp)` | Hasil Tampilan Otomatis di Website |
| :--- | :--- | :--- | :--- |
| **Rentang Gaji (Range)** | `3200000` | `4500000` | **Rp 3,2 Jt - Rp 4,5 Jt** |
| **Gaji Bulat Jutaan** | `3000000` | `5000000` | **Rp 3 Jt - Rp 5 Jt** |
| **Gaji Pasti (1 Angka)** | `3500000` | `3500000` | **Rp 3,5 Jt** |
| **Hanya Gaji Minimal** | `3000000` | *(Kosongkan)* | **Rp 3 Jt +** |
| **Hanya Gaji Maksimal** | *(Kosongkan)* | `4000000` | **Hingga Rp 4 Jt** |
| **Gaji Di Bawah 1 Juta (Part-time/Harian)** | `850000` | *(Kosongkan)* | **Rp 850.000 +** |
| **Gaji Dirahasiakan / Sesuai UMK** | *(Kosongkan)* | *(Kosongkan)* | *(Baris gaji disembunyikan otomatis)* |

---

### 2. Tabel Panduan Lengkap Seluruh Kolom Meta Box (`Detail Lowongan`)

> **Pembaruan v1.1.7 (Anti-Double & Terpisah)**: Kolom **Kategori Pekerjaan**, **Pendidikan**, **Tipe Pekerjaan**, dan **Lokasi Kota** kini telah dipisahkan sepenuhnya menjadi **Kotak Centang (Checkbox)** di **Sidebar Kanan** agar tidak tercampur dan tidak terjadi input ganda (*double*) di Meta Box tengah.

| Nama Kolom di Meta Box Tengah | Meta Key Database | Cara Pengisian & Contoh | Fungsi Otomatis |
| :--- | :--- | :--- | :--- |
| **Nama Perusahaan** | `_loker_perusahaan` | Ketik nama resmi perusahaan, contoh: `PT Sumber Alfaria Trijaya Tbk` | Otomatis membuat halaman **Arsip Riwayat Perusahaan** (`/perusahaan/pt-sumber-alfaria-trijaya-tbk/`) dan mengisi `hiringOrganization` di Schema Google Jobs. |
| **Website Perusahaan** | `_loker_website` | Gunakan `https://`, contoh: `https://alfamart.co.id` *(Kosongkan jika tidak ada)* | Ditampilkan sebagai link situs perusahaan di halaman loker & arsip perusahaan. |
| **Lokasi Detail / Alamat** | `_loker_lokasi_detail` | Ketik alamat atau kecamatan/kota spesifik, contoh: `Pondok Ungu, Bekasi Utara, Jawa Barat` | Ditampilkan pada kotak informasi Lokasi di halaman detail lowongan. |
| **Pengalaman Kerja** | `_loker_pengalaman` | Contoh: `Fresh Graduate / Minimal 1 Tahun` | Ditampilkan di tabel info detail loker & ringkasan Salin Teks Saluran WhatsApp Admin. |
| **Gaji Minimum (Rp)** | `_loker_gaji_min` | Ketik angka saja tanpa titik/Rp, contoh: `3200000` | Diformat otomatis menjadi `Rp 3,2 Jt` dan masuk ke Schema `baseSalary`. |
| **Gaji Maksimum (Rp)** | `_loker_gaji_max` | Ketik angka saja tanpa titik/Rp, contoh: `4500000` | Digabung menjadi rentang `Rp 3,2 Jt - Rp 4,5 Jt`. |
| **Batas Pendaftaran** | `_loker_deadline` | Pilih tanggal dari kalender (`YYYY-MM-DD`), contoh: `2026-11-30` | Mengaktifkan fitur **Auto-Close Otomatis**. Saat melewati tanggal ini, loker otomatis berstatus **DITUTUP** dan tombol lamar dinonaktifkan. |
| **Link Melamar / Email** | `_loker_link_lamar` | Isi link formulir (`https://...`), WhatsApp (`https://wa.me/62812...`), atau email (`mailto:hrd@perusahaan.com`) | Menjadi tujuan utama saat pelamar menekan tombol merah **Lamar Sekarang**. |
| **Cara Melamar** | `_loker_cara_melamar` | Tulis instruksi singkat pengiriman berkas lamaran | Ditampilkan pada bagian bawah artikel di bawah subjudul **Cara Melamar**. |
| **Tandai sebagai Unggulan** | `_loker_featured` | Pilih `Ya` atau `Tidak` | Jika dipilih `Ya`, loker akan tampil di seksi **Lowongan Unggulan** halaman depan dan mendapat lencana **UNGGULAN**. |

---

### 3. Empat Kotak Taksonomi Terpisah di Sidebar Kanan Editor WordPress
Sebelum menekan tombol **Terbitkan (Publish)**, cukup **centang (checkbox)** pada 4 kotak yang sudah dipisahkan di **Sidebar Kanan**:
1. **Kategori Pekerjaan (`loker_kategori`)**: Khusus bidang profesi, contoh `Administrasi & Perkantoran`, `IT & Teknologi`, `Pemasaran & Sales`, `Keuangan & Perbankan`, `Teknik & Industri`, `Operator & Produksi`, `BUMN & Instansi`.
2. **Pendidikan (`loker_pendidikan`)**: Khusus jenjang pendidikan (bisa centang lebih dari satu), contoh `SMA / SMK`, `D3`, `S1 / D4`, `D3 / S1`, `SMP`, `S2`, `Semua Jenjang`.
3. **Tipe Pekerjaan (`loker_tipe`)**: Khusus jenis ikatan kerja, contoh `Full-time`, `Part-time`, `Kontrak`, `Magang`, `Freelance`, `Remote`.
4. **Lokasi / Daerah (`loker_lokasi`)**: Khusus kota/kabupaten penempatan, contoh `Bandar Lampung`, `Metro`, `Bekasi`, `Jakarta` (agar loker muncul saat dicari lewat dropdown **Semua Lokasi** di Beranda).
5. **Gambar Unggulan (*Featured Image*)**: Unggah logo perusahaan ukuran persegi **400 x 400 px** (PNG/JPG/WebP).

---

## 4. Contoh Format Penulisan Isi Artikel Lowongan Kerja

Usahakan terdapat minimal **2 paragraf pembuka** sebelum daftar poin (`<ul><li>`) agar **Slot Iklan AdSense Tengah Deskripsi (Slot 4)** dapat disisipkan secara otomatis setelah paragraf ke-2:

```html
<h2>Tentang Perusahaan</h2>
<p>PT Contoh Perusahaan Indonesia merupakan perusahaan yang bergerak di bidang distribusi dan ritel konsumen berskala nasional yang memiliki jaringan cabang di wilayah Lampung dan Sumatera Bagian Selatan.</p>

<p>Saat ini PT Contoh Perusahaan Indonesia sedang membuka kesempatan berkarier bagi kandidat profesional lulusan SMA/SMK, D3, hingga S1 untuk menempati posisi strategis dengan rincian kualifikasi sebagai berikut.</p>

<h2>Deskripsi Pekerjaan</h2>
<ul>
  <li>Mengelola pencatatan administrasi harian dan laporan keluar-masuk barang secara akurat.</li>
  <li>Melakukan koordinasi rutin dengan tim operasional dan kepala cabang terkait jadwal pengiriman.</li>
  <li>Memastikan seluruh dokumen penagihan dan arsip perusahaan tersimpan sesuai prosedur standar (SOP).</li>
</ul>

<h2>Kualifikasi & Persyaratan</h2>
<ul>
  <li>Pria atau Wanita, usia maksimal 28 tahun.</li>
  <li>Pendidikan minimal SMA/SMK sederajat atau D3/S1 semua jurusan.</li>
  <li>Menguasai pengoperasian komputer minimal Microsoft Office (Word & Excel).</li>
  <li>Teliti, jujur, komunikatif, dan mampu bekerja secara individu maupun tim.</li>
  <li>Bersedia ditempatkan di kantor cabang Bandar Lampung.</li>
</ul>

<h2>Berkas Lamaran yang Disiapkan</h2>
<ul>
  <li>Surat Lamaran Kerja dan Daftar Riwayat Hidup (CV) terbaru.</li>
  <li>Scan Ijazah terakhir dan Transkrip Nilai.</li>
  <li>Scan KTP, KK, dan Pas Foto terbaru.</li>
</ul>
```

---

## Cara Menggunakan Sistem Update Otomatis di WordPress

1. Setiap kali Anda melakukan perubahan pada tema di komputer, ubah nomor versi pada baris `Version:` di file `style.css` (contoh: dari `1.1.5` menjadi `1.1.6`).
2. Push perubahan tersebut ke branch `main` di repositori GitHub `https://github.com/hakastudio/hakajobspro`:
   ```bash
   git add .
   git commit -m "Release versi 1.1.6"
   git push origin main
   ```
3. Buka Dasbor WordPress Anda:
   - Masuk ke **Dashboard -> Updates**, **Appearance -> Themes**, atau **Appearance -> Update Tema (GitHub)**.
   - Klik **Cek Pembaruan dari GitHub Sekarang**.
   - Klik **Perbarui Tema Sekarang** untuk memperbarui tema secara langsung dari Dasbor WordPress.
