<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Panggilan Orang Tua - {{ $summons->student?->name }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            color: #000;
            margin: 25px 40px;
            font-size: 11pt;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            border-bottom: 3px double #000;
            padding-bottom: 12px;
            margin-bottom: 25px;
        }
        .header h2 {
            margin: 0;
            font-size: 13pt;
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
            font-size: 9.5pt;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .meta-table td {
            vertical-align: top;
            font-size: 11pt;
        }
        .content {
            margin-bottom: 25px;
            text-align: justify;
        }
        .schedule-table {
            margin: 15px 0 15px 30px;
            width: 85%;
        }
        .schedule-table td {
            padding: 4px 0;
            vertical-align: top;
        }
        .violations-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        .violations-table th, .violations-table td {
            border: 1px solid #000;
            padding: 5px 8px;
            font-size: 10pt;
        }
        .violations-table th {
            background-color: #f2f2f2;
            text-align: center;
        }
        .signatures {
            width: 100%;
            margin-top: 40px;
        }
        .signatures td {
            text-align: center;
            width: 50%;
            vertical-align: top;
        }
        @media print {
            .no-print { display: none; }
            body { margin: 1.5cm 2cm; }
        }
    </style>
</head>
<body>

    <div class="no-print" style="margin-bottom: 20px; text-align: right;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #000; color: #fff; border: none; cursor: pointer; font-size: 10pt; border-radius: 6px; font-weight: bold;">
            🖨️ Cetak Surat Resmi / Simpan PDF
        </button>
    </div>

    <!-- Kop Surat Resmi -->
    <div class="header">
        <h2>PEMERINTAH DAERAH PROVINSI</h2>
        <h2>DINAS PENDIDIKAN DAN KEBUDAYAAN</h2>
        <h1>{{ $school->school_name }}</h1>
        <p>{{ $school->school_address }} &bull; Telepon: {{ $school->school_phone }}</p>
        <p>Laman: www.smkn1teladan.sch.id &bull; Pos-el: {{ $school->school_email }}</p>
    </div>

    <!-- Metadata Surat -->
    <table class="meta-table">
        <tr>
            <td style="width: 15%;">Nomor</td>
            <td style="width: 2%;">:</td>
            <td style="width: 48%;"><strong>{{ $summons->letter_number }}</strong></td>
            <td style="width: 35%; text-align: right;">Jakarta, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td>Lampiran</td>
            <td>:</td>
            <td>1 (Satu) Lembar Catatan Kedisiplinan</td>
            <td></td>
        </tr>
        <tr>
            <td>Perihal</td>
            <td>:</td>
            <td><strong><u>{{ $summons->summons_type }}</u></strong></td>
            <td></td>
        </tr>
    </table>

    <div style="margin-bottom: 20px;">
        Kepada Yth.<br>
        <strong>Bapak / Ibu Orang Tua / Wali Murid dari:</strong><br>
        <strong><u>{{ $summons->student?->name }}</u></strong> (Kelas: {{ $summons->student?->class?->name }})<br>
        di Tempat
    </div>

    <div class="content">
        <p>Dengan hormat,</p>
        <p>
            Sehubungan dengan perkembangan pembinaan kedisiplinan dan tata tertib siswa di {{ $school->school_name }}, kami memberitahukan bahwa putra/putri Bapak/Ibu tercatat telah memiliki akumulasi poin pelanggaran tata tertib sebesar <strong>{{ $summons->total_points_snapshot }} Poin</strong>.
        </p>
        <p>
            Guna menyelaraskan langkah pembinaan serta mencari solusi terbaik demi kelancaran proses pendidikan putra/putri kita bersama, dengan ini kami mengharapkan kehadiran Bapak/Ibu di sekolah pada:
        </p>

        <table class="schedule-table">
            <tr>
                <td style="width: 28%;"><strong>Hari, Tanggal</strong></td>
                <td style="width: 3%;">:</td>
                <td><strong>{{ $summons->summons_date->translatedFormat('l, d F Y') }}</strong></td>
            </tr>
            <tr>
                <td><strong>Waktu / Pukul</strong></td>
                <td>:</td>
                <td><strong>{{ substr($summons->summons_time, 0, 5) }} WIB</strong> (Tepat Waktu)</td>
            </tr>
            <tr>
                <td><strong>Tempat</strong></td>
                <td>:</td>
                <td>{{ $summons->meeting_place }}</td>
            </tr>
            <tr>
                <td><strong>Menemui</strong></td>
                <td>:</td>
                <td>Koordinator BK / Wali Kelas {{ $summons->student?->class?->name }}</td>
            </tr>
            <tr>
                <td><strong>Keperluan</strong></td>
                <td>:</td>
                <td>{{ $summons->reason }}</td>
            </tr>
        </table>

        <p>
            Mengingat pentingnya pertemuan koordinasi ini demi masa depan ananda, kami sangat mengharapkan Bapak/Ibu hadir tepat pada waktu yang telah ditentukan dan tidak diwakilkan.
        </p>
        <p>
            Demikian surat panggilan ini kami sampaikan. Atas perhatian dan kerja sama yang baik, kami ucapkan terima kasih.
        </p>
    </div>

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
                Koordinator Bimbingan Konseling (BK)<br><br><br><br><br><br>
                <strong><u>{{ $school->counseling_head_name }}</u></strong><br>
                NIP. {{ $school->counseling_head_nip }}
            </td>
        </tr>
    </table>

</body>
</html>
