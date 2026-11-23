<?php

namespace App\Services;

use App\Models\ParentSummons;
use App\Models\SchoolSetting;
use App\Models\StudentViolation;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Format phone number into international standard 628xxxx
     */
    public static function formatPhoneNumber(?string $number): ?string
    {
        if (!$number) return null;

        $clean = preg_replace('/[^0-9]/', '', $number);
        if (str_starts_with($clean, '08')) {
            $clean = '628' . substr($clean, 2);
        } elseif (str_starts_with($clean, '8')) {
            $clean = '628' . substr($clean, 1);
        }

        return $clean;
    }

    /**
     * Build WhatsApp Web message link
     */
    public static function buildWebLink(string $phoneNumber, string $message): string
    {
        $phone = self::formatPhoneNumber($phoneNumber);
        return 'https://wa.me/' . $phone . '?text=' . urlencode($message);
    }

    /**
     * Format Violation Notification Message
     */
    public static function formatViolationMessage(StudentViolation $violation): string
    {
        $school = SchoolSetting::getSettings();
        $student = $violation->student;
        $class = $student->class?->name ?? '-';
        $vType = $violation->violationType?->name ?? 'Pelanggaran';
        $category = $violation->violationType?->category?->name ?? 'Umum';
        $points = $violation->points;
        $totalPoints = $student->net_points;
        $date = \Carbon\Carbon::parse($violation->violation_date)->translatedFormat('d F Y');

        $message = "📢 *PEMBERITAHUAN KEDISIPLINAN SISWA*\n";
        $message .= "🏛️ *{$school->school_name}*\n\n";
        $message .= "Kepada Yth. Bapak/Ibu Orang Tua / Wali dari:\n";
        $message .= "• *Nama Siswa:* {$student->name}\n";
        $message .= "• *NISN / Kelas:* {$student->nisn} / {$class}\n\n";
        $message .= "Dengan ini kami menginformasikan bahwa siswa tersebut telah tercatat melakukan pelanggaran tata tertib sekolah:\n";
        $message .= "• *Tanggal Kejadian:* {$date}\n";
        $message .= "• *Kategori:* {$category}\n";
        $message .= "• *Jenis Pelanggaran:* {$vType}\n";
        $message .= "• *Poin Pelanggaran:* +{$points} Poin\n";
        if ($violation->notes) {
            $message .= "• *Keterangan Guru:* {$violation->notes}\n";
        }
        $message .= "• *Total Akumulasi Poin Siswa:* *{$totalPoints} Poin*\n\n";

        if ($totalPoints >= 50) {
            $message .= "⚠️ *PERHATIAN:* Akumulasi poin siswa telah mencapai batas penanganan khusus Bimbingan Konseling (BK).\n\n";
        }

        $message .= "Mohon bimbingan dan pendampingan Bapak/Ibu di rumah demi kebaikan dan kedisiplinan putra/putri kita bersama.\n\n";
        $message .= "Terima kasih atas kerja samanya.\n";
        $message .= "_Tim Ketertiban & Bimbingan Konseling {$school->school_name}_";

        return $message;
    }

    /**
     * Format Parent Summons Message
     */
    public static function formatSummonsMessage(ParentSummons $summons): string
    {
        $school = SchoolSetting::getSettings();
        $student = $summons->student;
        $class = $student->class?->name ?? '-';
        $summonsDate = \Carbon\Carbon::parse($summons->summons_date)->translatedFormat('l, d F Y');

        $message = "✉️ *UNDANGAN RESMI PEMANGGILAN ORANG TUA / WALI SISWA*\n";
        $message .= "🏛️ *{$school->school_name}*\n";
        $message .= "Nomor Surat: {$summons->letter_number}\n\n";
        $message .= "Kepada Yth. Orang Tua / Wali dari:\n";
        $message .= "• *Nama Siswa:* {$student->name}\n";
        $message .= "• *Kelas:* {$class}\n";
        $message .= "• *Akumulasi Poin:* {$summons->total_points_snapshot} Poin\n\n";
        $message .= "Sehubungan dengan perkembangan pembinaan dan kedisiplinan siswa di sekolah, kami mengharapkan kehadiran Bapak/Ibu pada:\n\n";
        $message .= "🗓️ *Hari/Tanggal:* {$summonsDate}\n";
        $message .= "⏰ *Waktu:* Pukul " . substr($summons->summons_time, 0, 5) . " WIB\n";
        $message .= "📍 *Tempat:* {$summons->meeting_place}\n";
        $message .= "📋 *Keperluan:* {$summons->reason}\n\n";
        $message .= "Mengingat pentingnya koordinasi ini demi masa depan ananda, kami sangat mengharapkan kehadiran Bapak/Ibu tepat waktu.\n\n";
        $message .= "Wassalamu'alaikum Wr. Wb.\n";
        $message .= "*Koordinator Bimbingan Konseling (BK)*\n";
        $message .= "{$school->counseling_head_name}";

        return $message;
    }

    /**
     * Optional direct API send (if Fonnte or custom gateway token is set)
     */
    public static function sendViaGateway(string $phoneNumber, string $message): bool
    {
        $school = SchoolSetting::getSettings();
        if (empty($school->wa_gateway_token)) {
            return false;
        }

        $endpoint = $school->wa_gateway_endpoint ?: 'https://api.fonnte.com/send';
        $formattedPhone = self::formatPhoneNumber($phoneNumber);

        try {
            $response = Http::withHeaders([
                'Authorization' => $school->wa_gateway_token,
            ])->post($endpoint, [
                'target' => $formattedPhone,
                'message' => $message,
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('WA Gateway Error: ' . $e->getMessage());
            return false;
        }
    }
}
