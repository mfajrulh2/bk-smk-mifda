# 📚 Petunjuk Penggunaan & Dokumentasi Sistem E-Disiplin
### Aplikasi Web Rekapitulasi Presensi & Poin Pelanggaran Siswa

Aplikasi ini dibangun menggunakan **Laravel** dan didesain khusus untuk kebutuhan sekolah (SMP/SMA/SMK) di Indonesia. Sistem ini mengintegrasikan pencatatan kehadiran harian, penegakan kedisiplinan dan poin tata tertib, pemutihan poin pembinaan BK, penerbitan surat panggilan orang tua resmi, serta portal pemantauan untuk orang tua siswa.

---

## 🚀 Cara Menjalankan Aplikasi

### Cara 1: Menggunakan File Pintasan (Paling Mudah)
Cukup klik dua kali (double-click) pada file:
👉 **`jalankan-aplikasi.bat`**

Browser akan otomatis terbuka dan mengarahkan Anda ke alamat:  
**`http://localhost:8000`**

### Cara 2: Melalui Terminal / Command Prompt
Buka Terminal / Command Prompt di folder proyek ini dan jalankan perintah:
```powershell
"C:\xampp\php\php.exe" artisan serve
```

---

## 👥 Akun Demo Pengujian (Siap Pakai)

Semua akun berikut telah terdaftar dan memiliki data sampel aktif:

| Peran (Role) | Username / Login | Kata Sandi | Kegunaan & Hak Akses |
| :--- | :--- | :--- | :--- |
| **👑 Administrator TU** | `admin` | `admin123` | Akses penuh seluruh modul, pengaturan master sekolah, kelas, siswa, guru, jenis pelanggaran, aturan SP, dan impor data. |
| **🧠 Guru BK / Kesiswaan** | `guru_bk` | `bk123` | Akses tindak lanjut pembinaan, konseling, penerbitan Surat Panggilan Ortu (SP 1, SP 2, SP 3), dan pemutihan poin. |
| **📋 Wali Kelas** | `wali_kelas` | `guru123` | Akses input presensi harian kelas binaan, pelaporan kasus pelanggaran, dan rekapitulasi kehadiran kelas. |
| **👨‍🏫 Guru Piket / Pengajar** | `guru_piket` | `guru123` | Akses pencatatan pelanggaran siswa di area sekolah dan presensi harian. |
| **🎓 Portal Siswa & Ortu** | `siswa1` (atau NISN: `0081234567`) | `siswa123` | Portal khusus siswa & orang tua untuk memantau rekap kehadiran %, catatan pelanggaran, dan surat panggilan. |

---

## 🌟 Fitur Unggulan yang Telah Diterapkan

### 1. 📋 Rekapitulasi Presensi Siswa
- **Lembar Presensi Harian Cepat**: Guru/wali kelas dapat memilih kelas dan tanggal, lalu mengisi status kehadiran (Hadir, Terlambat, Sakit, Izin, Alpa).
- **Tombol Cepat "Set Semua Hadir"**: Mempercepat pengisian absensi hanya dengan 1 klik.
- **Rekapitulasi Bulanan**: Menghitung otomatis persentase kehadiran siswa (% Hadir).
- **Ekspor Excel (.CSV UTF-8)**: Unduh data rekap kehadiran yang dapat dibuka langsung di Microsoft Excel dengan format rapi.
- **Cetak Laporan Presensi Resmi**: Dilengkapi Kop Surat Sekolah dan kolom tanda tangan Wali Kelas serta Kepala Sekolah.

### 2. ⚠️ Sistem Poin Pelanggaran & Tata Tertib Siswa
- **Master Jenis Pelanggaran & Bobot Poin**: Pelanggaran dikelompokkan ke dalam kategori (Kedisiplinan & Waktu, Kerapian & Atribut, Etika & Sopan Santun, Pelanggaran Berat/Norma Hukum).
- **Pencatatan Kejadian**: Pencatatan tanggal, waktu kejadian, kronologi, guru pelapor, serta **unggah foto barang bukti**.
- **Ambang Batas Sanksi (Thresholds)**:
  - **15 Poin**: Peringatan Lisan & Pembinaan Wali Kelas
  - **30 Poin**: Surat Peringatan I (SP 1) & Bimbingan BK
  - **50 Poin**: Surat Peringatan II (SP 2) & Pemanggilan Orang Tua I
  - **75 Poin**: Surat Peringatan III (SP 3) & Pemanggilan Orang Tua Terakhir
  - **100 Poin**: Skorsing / Sidang Dewan Guru / Dikembalikan ke Orang Tua
- **Sistem Pemutihan Poin (Restoration)**: Pengurangan poin aktif jika siswa menunjukkan perubahan perilaku positif, rajin konseling, atau berprestasi.

### 3. ✉️ Surat Panggilan Orang Tua Otomatis (SP)
- **Deteksi Otomatis Siswa Berisiko**: Menampilkan peringatan ketika poin siswa telah melewati batas sanksi.
- **Penomoran Otomatis**: Format nomor surat resmi otomatis (`001/SP-BK/SMK/XI/2026`).
- **Template Surat Resmi Siap Cetak**: Dilengkapi kop surat sekolah, jadwal kehadiran, ruangan pertemuan, alasan pemanggilan, serta tanda tangan resmi Kepala Sekolah dan Koordinator BK.
- **Notulensi Pertemuan**: Pencatatan hasil kehadiran orang tua dan komitmen bersama di ruang BK.

### 4. 💬 Notifikasi WhatsApp Orang Tua
- **Direct Link WhatsApp Web & Mobile**: Otomatis membuat tautan `wa.me` dengan format pesan resmi yang rapi berisi identitas siswa, rincian pelanggaran, dan akumulasi poin.
- **Dukungan API Gateway**: Dilengkapi pengaturan token API (seperti Fonnte/Wablas) jika sekolah ingin mengaktifkan pengiriman otomatis di latar belakang.

### 5. 📥 Impor Data Siswa Sekaligus
- Tersedia fitur **Impor Siswa dari File CSV/Excel** di menu *Master Data Siswa*.
- Tersedia tombol **Unduh Template Excel** untuk format kolom yang benar (NISN, NIS, Nama, Gender, Kelas, Nama Ortu, No WA Ortu, Alamat).

### 6. 🌐 Portal Publik Cek Siswa (Tanpa Perlu Login)
- Orang tua dapat membuka halaman:  
  👉 **`http://localhost:8000/cek-siswa`**
- Cukup masukkan 10 digit NISN siswa untuk melihat ringkasan presensi dan poin tata tertib ananda secara real-time.
