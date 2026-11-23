@echo off
title Push Project ke GitHub
echo ========================================================
echo       PUSH APLIKASI E-DISIPLIN KE GITHUB REPOSITORY
echo ========================================================
echo.

:: Cek apakah git sudah diinisialisasi
if not exist ".git" (
    echo Menginisialisasi Git Repository lokal...
    git init
    git branch -M main
)

echo Memeriksa perubahan berkas...
git add .

set /p commit_msg="Masukkan pesan commit (tekan Enter untuk default 'Update sistem'): "
if "%commit_msg%"=="" set commit_msg=Update sistem dan fitur presensi bulanan

git commit -m "%commit_msg%"

:: Cek apakah remote origin sudah ada
git remote get-url origin >nul 2>&1
if %errorlevel% neq 0 (
    echo.
    echo Remote repository GitHub belum dihubungkan.
    set /p repo_url="Masukkan URL Repository GitHub Anda (contoh: https://github.com/user/edisiplin.git): "
    if not "%repo_url%"=="" (
        git remote add origin %repo_url%
    ) else (
        echo URL kosong, proses dibatalkan.
        pause
        exit /b
    )
)

echo.
echo Melakukan Push ke GitHub (branch: main)...
git push -u origin main

echo.
echo ========================================================
echo Selesai! Jika berhasil, kode Anda kini sudah ada di GitHub.
echo ========================================================
pause
