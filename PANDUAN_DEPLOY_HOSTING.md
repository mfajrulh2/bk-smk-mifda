# 🌐 Panduan Push ke GitHub & Deploy ke Cloud Hosting Sekolah

Dokumen ini memandu Anda langkah demi langkah:
1. Melakukan **Push Project ke GitHub**
2. Menghubungkan ke **Domain Sekolah** (misal: `disiplin.sekolah.sch.id`)
3. Menjalankan aplikasi di **Cloud Hosting (cPanel / DirectAdmin / VPS)**

---

## 🚀 BAGIAN 1: Push Project ke GitHub

### Langkah 1: Buat Repository Baru di GitHub
1. Buka [github.com](https://github.com) dan masuk ke akun Anda.
2. Klik tombol **New** (Repository baru).
3. Beri nama repository, misalnya: `aplikasi-disiplin-sekolah`.
4. Pilih opsi **Private** (Direkomendasikan agar kode sistem sekolah tidak terbuka untuk umum).
5. Jangan centang *Add a README file* (karena proyek lokal sudah memiliki file).
6. Klik **Create repository**. Salin URL repository Anda (misal: `https://github.com/username/aplikasi-disiplin-sekolah.git`).

### Langkah 2: Jalankan Perintah Git di Komputer Anda
Buka Terminal / PowerShell di folder proyek ini (`E:\JOB\2026\11. November\APP PELANGGARAN`), lalu jalankan perintah berikut secara berurutan:

```bash
# 1. Inisialisasi Git lokal
git init

# 2. Tambahkan semua file proyek
git add .

# 3. Buat commit pertama
git commit -m "Inisialisasi aplikasi presensi dan poin pelanggaran siswa"

# 4. Ubah branch utama menjadi main
git branch -M main

# 5. Hubungkan ke repository GitHub Anda (ganti URL di bawah dengan URL repo Anda)
git remote add origin https://github.com/USERNAME/aplikasi-disiplin-sekolah.git

# 6. Push kode ke GitHub
git push -u origin main
```

---

## 🌐 BAGIAN 2: Menghubungkan Domain Sekolah & Hosting

Rekomendasi terbaik adalah membuat **Subdomain** khusus di domain sekolah Anda, misalnya:
👉 `disiplin.sekolah.sch.id` atau `presensi.sekolah.sch.id`

### Langkah 1: Buat Subdomain di cPanel Hosting
1. Masuk ke **cPanel** hosting sekolah Anda.
2. Cari menu **Domains** &rarr; **Subdomains** (atau *Domains* di cPanel versi modern).
3. Buat subdomain baru:
   - Subdomain: `disiplin` (atau `edisiplin`)
   - Domain: `sekolah.sch.id`
   - **Document Root (SANGAT PENTING)**: Arahkan ke folder `public` Laravel!
     - Contoh: `repositories/edisiplin/public` atau `public_html/disiplin/public`
     *(Alasan keamanan: Laravel mewajibkan folder `public` sebagai Document Root agar file sistem dan `.env` tidak bisa diakses langsung dari browser).*
4. Klik **Create**.

---

## 📦 BAGIAN 3: Deploy dari GitHub ke Hosting (Metode Paling Praktis)

Mayoritas Cloud Hosting modern (cPanel) memiliki fitur **Git Version Control**.

### Cara 1: Menggunakan cPanel "Git™ Version Control" (Direkomendasikan)
1. Di cPanel, cari dan buka menu **Git™ Version Control**.
2. Klik tombol **Create** di kanan atas.
3. Masukkan data:
   - **Clone URL**: Masukkan URL repository GitHub Anda.
   - **Repository Path**: Misal `repositories/edisiplin` (atau di luar `public_html`).
   - **Repository Name**: `edisiplin`
4. Klik **Create**. cPanel akan mengunduh kode dari GitHub.
5. Selanjutnya, setiap Anda melakukan perubahan dan *push* ke GitHub di komputer lokal, Anda cukup membuka menu ini di cPanel dan klik **Pull or Deploy** &rarr; **Update from Remote**. Aplikasi di hosting akan otomatis ter-update!

### Cara 2: Melalui Terminal SSH cPanel (Jika Tersedia)
Jika hosting Anda mengizinkan akses SSH:
```bash
# Masuk ke folder tujuan
cd ~
git clone https://github.com/USERNAME/aplikasi-disiplin-sekolah.git edisiplin
cd edisiplin

# Install dependensi PHP
composer install --no-dev --optimize-autoloader

# Salin file konfigurasi .env
cp .env.example .env
php artisan key:generate
```

---

## 🗄️ BAGIAN 4: Pengaturan Database MySQL di Hosting

Di komputer lokal saat ini aplikasi menggunakan SQLite untuk kemudahan pengujian. Di cloud hosting produksi, gunakan **MySQL** bawaan hosting:

1. **Buat Database MySQL di cPanel**:
   - Buka menu **MySQL® Databases** di cPanel.
   - Buat database baru, misal: `sekolah_edisiplin`.
   - Buat user database baru, misal: `sekolah_user` dengan password yang kuat.
   - Tambahkan user tersebut ke database dengan memberikan hak akses **ALL PRIVILEGES**.

2. **Edit File `.env` di Hosting**:
   Buka file `.env` di folder proyek hosting melalui **File Manager** cPanel (aktifkan opsi *Show Hidden Files* agar file `.env` terlihat):
   ```env
   APP_NAME="E-Disiplin Sekolah"
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://disiplin.sekolah.sch.id

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=sekolah_edisiplin
   DB_USERNAME=sekolah_user
   DB_PASSWORD=PasswordDatabaseAnda

   SESSION_DRIVER=database
   FILESYSTEM_DISK=public
   ```

3. **Jalankan Migrasi Database**:
   Melalui Terminal cPanel (atau fitur *Terminal* di cPanel):
   ```bash
   php artisan migrate --force
   php artisan db:seed --force
   php artisan storage:link
   ```
   *(Catatan: Jika hosting tidak menyediakan terminal SSH, Anda dapat menjalankan migrasi di lokal terlebih dahulu menggunakan MySQL lalu mengekspor filenya ke phpMyAdmin hosting).*

4. **Aktifkan SSL (HTTPS) Gratis**:
   Di cPanel, buka menu **SSL/TLS Status** dan klik **Run AutoSSL** untuk subdomain Anda agar domain otomatis aman dengan HTTPS (gembok hijau).
