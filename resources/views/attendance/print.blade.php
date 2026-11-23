<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekapitulasi Presensi Bulanan Siswa - {{ $selectedClass->name }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            color: #000;
            margin: 20px;
            font-size: 11pt;
            line-height: 1.3;
        }
        .header {
            text-align: center;
            border-bottom: 3px double #000;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            font-size: 14pt;
            text-transform: uppercase;
        }
        .header h1 {
            margin: 4px 0;
            font-size: 16pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .header p {
            margin: 2px 0;
            font-size: 9pt;
        }
        .title {
            text-align: center;
            margin-bottom: 15px;
        }
        .title h3 {
            margin: 0;
            text-decoration: underline;
            font-size: 13pt;
            text-transform: uppercase;
        }
        .title p {
            margin: 3px 0;
            font-size: 10pt;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th, td {
            padding: 5px 6px;
            font-size: 10pt;
        }
        th {
            background-color: #f2f2f2;
            text-align: center;
        }
        .text-center { text-align: center; }
        .signatures {
            width: 100%;
            margin-top: 35px;
        }
        .signatures td {
            border: none;
            text-align: center;
            width: 50%;
            font-size: 11pt;
        }
        @media print {
            .no-print { display: none; }
            @page { margin: 1.5cm; }
        }
    </style>
</head>
<body>

    <div class="no-print" style="margin-bottom: 15px; text-align: right;">
        <button onclick="window.print()" style="padding: 8px 16px; background: #000; color: #fff; border: none; cursor: pointer; font-size: 10pt; border-radius: 4px;">
            🖨️ Cetak / Simpan PDF
        </button>
    </div>

    <!-- Kop Sekolah -->
    <div class="header">
        <h2>PEMERINTAH DAERAH PROVINSI</h2>
        <h2>DINAS PENDIDIKAN DAN KEBUDAYAAN</h2>
        <h1>{{ $school->school_name }}</h1>
        <p>{{ $school->school_address }} &bull; Telp: {{ $school->school_phone }} &bull; Email: {{ $school->school_email }}</p>
    </div>

    <!-- Judul Dokumen -->
    <div class="title">
        <h3>REKAPITULASI PRESENSI BULANAN SISWA</h3>
        <p>Kelas: <strong>{{ $selectedClass->name }}</strong> &bull; Periode: <strong>{{ $monthName }} {{ $year }}</strong> &bull; Hari Efektif: <strong>{{ $effectiveDays }} Hari</strong></p>
    </div>

    <!-- Tabel Rekap -->
    <table>
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th>Nama Siswa</th>
                <th style="width: 85px;">NISN</th>
                <th style="width: 35px;">L/P</th>
                <th style="width: 50px;">Efektif</th>
                <th style="width: 45px;">Hadir</th>
                <th style="width: 45px;">S (Sakit)</th>
                <th style="width: 45px;">I (Izin)</th>
                <th style="width: 45px;">A (Alpa)</th>
                <th style="width: 60px;">% Hadir</th>
                <th style="width: 100px;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recapData as $idx => $r)
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td>{{ $r['student']->name }}</td>
                    <td class="text-center">{{ $r['student']->nisn }}</td>
                    <td class="text-center">{{ $r['student']->gender }}</td>
                    <td class="text-center">{{ $r['effective_days'] }}</td>
                    <td class="text-center"><strong>{{ $r['hadir'] }}</strong></td>
                    <td class="text-center">{{ $r['sakit'] }}</td>
                    <td class="text-center">{{ $r['izin'] }}</td>
                    <td class="text-center">{{ $r['alpa'] }}</td>
                    <td class="text-center"><strong>{{ $r['percentage'] }}%</strong></td>
                    <td>{{ $r['notes'] ?: '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p style="font-size: 9pt; margin-top: -15px; margin-bottom: 25px;">
        <em>Keterangan: H = Hadir, S = Sakit, I = Izin, A = Alpa/Tanpa Keterangan</em>
    </p>

    <!-- Tanda Tangan -->
    <table class="signatures">
        <tr>
            <td>
                Mengetahui,<br>
                Kepala Sekolah<br><br><br><br><br>
                <strong><u>{{ $school->principal_name }}</u></strong><br>
                NIP. {{ $school->principal_nip }}
            </td>
            <td>
                Jakarta, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                Wali Kelas {{ $selectedClass->name }}<br><br><br><br><br>
                <strong><u>{{ $selectedClass->homeroomTeacher?->name ?? '....................................' }}</u></strong><br>
                NIP. {{ $selectedClass->homeroomTeacher?->identifier_number ?? '....................................' }}
            </td>
        </tr>
    </table>

</body>
</html>
