@echo off
title Menjalankan Aplikasi Presensi & Disiplin Siswa
echo ========================================================
echo   E-DISIPLIN: APLIKASI PRESENSI & POIN PELANGGARAN SISWA
echo ========================================================
echo.
echo Menjalankan server aplikasi di http://localhost:8000 ...
echo Tekan Ctrl + C di jendela ini jika ingin menghentikan aplikasi.
echo.

start http://localhost:8000

"C:\xampp\php\php.exe" artisan serve --port=8000
pause
