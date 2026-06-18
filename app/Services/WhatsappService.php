<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\WhatsappLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsappService
{
    /**
     * Send a WhatsApp notification message via Fonnte.
     *
     * @param \App\Models\KerjaSama $kerjaSama
     * @param string $type warning_30, warning_14, warning_7, expired
     * @return bool
     */
    public function sendNotification($kerjaSama, $type)
    {
        $settings = Setting::getSettings();

        // If WA integration is inactive or incomplete, skip sending
        if (!$settings->whatsapp_active || !$settings->fonnte_token) {
            return false;
        }

        $recipientRaw = $kerjaSama->nomor_telepon;
        if (!$recipientRaw) {
            Log::warning("Skipped sending WhatsApp for Kerja Sama ID {$kerjaSama->id} because partner phone number is empty.");
            return false;
        }

        $recipient = $this->formatPhoneNumber($recipientRaw);
        $message = $this->generateMessageTemplate($kerjaSama, $type);

        $status = 'failed';
        $responseBody = null;

        try {
            $response = Http::withHeaders([
                'Authorization' => $settings->fonnte_token
            ])->timeout(10)->post('https://api.fonnte.com/send', [
                'target' => $recipient,
                'message' => $message,
            ]);

            $responseBody = $response->body();
            
            if ($response->successful()) {
                $resData = json_decode($responseBody, true);
                // Fonnte API usually returns a status field like "status": true
                if (isset($resData['status']) && $resData['status'] == true) {
                    $status = 'success';
                } else {
                    $status = 'failed';
                }
            } else {
                $status = 'failed';
            }
        } catch (\Exception $e) {
            $status = 'failed';
            $responseBody = 'Error: ' . $e->getMessage();
            Log::error('Fonnte API WhatsApp Sending Exception: ' . $e->getMessage());
        }

        // Save log
        WhatsappLog::create([
            'kerja_sama_id' => $kerjaSama->id,
            'type' => $type,
            'recipient' => $recipient,
            'message' => $message,
            'status' => $status,
            'response' => $responseBody,
        ]);

        return $status === 'success';
    }

    /**
     * Format Indonesian phone numbers into international format (628...)
     */
    protected function formatPhoneNumber($number)
    {
        // Remove non-numeric characters
        $cleaned = preg_replace('/[^0-9]/', '', $number);

        // If starts with 0, replace with 62
        if (strpos($cleaned, '0') === 0) {
            $cleaned = '62' . substr($cleaned, 1);
        }

        return $cleaned;
    }

    /**
     * Generate dynamic WhatsApp message templates in Bahasa Indonesia.
     */
    protected function generateMessageTemplate($kerjaSama, $type)
    {
        $namaMitra = $kerjaSama->nama_mitra;
        $nomorMou = $kerjaSama->nomor_mou;
        $tanggalBerakhir = $kerjaSama->tanggal_berakhir ? $kerjaSama->tanggal_berakhir->format('d/m/Y') : '-';
        $picName = $kerjaSama->pic ?: 'Bapak/Ibu';

        switch ($type) {
            case 'warning_30':
                return "🔔 *PENGINGAT KERJA SAMA (30 HARI)* 🔔\n\nYth. Bapak/Ibu *{$picName}*,\n\nKami menginformasikan bahwa dokumen kerja sama (MoU/MoA) antara sekolah kami dengan *{$namaMitra}* (No. MoU: {$nomorMou}) akan berakhir dalam *30 hari* pada tanggal *{$tanggalBerakhir}*.\n\nApakah ada rencana untuk melanjutkan/memperpanjang kerja sama ini? Kami sangat senang jika bisa terus bersinergi.\n\nTerima kasih atas kerja samanya.\n\nSistem Informasi SIM-MoU.";
            
            case 'warning_14':
                return "🔔 *PENGINGAT KERJA SAMA (14 HARI)* 🔔\n\nYth. Bapak/Ibu *{$picName}*,\n\nKami mengingatkan kembali bahwa dokumen kerja sama (MoU/MoA) antara sekolah kami dengan *{$namaMitra}* (No. MoU: {$nomorMou}) akan berakhir dalam *14 hari* pada tanggal *{$tanggalBerakhir}*.\n\nJika diperlukan proses administrasi perpanjangan, mohon berkenan menginformasikan lebih lanjut.\n\nTerima kasih atas kerja samanya.\n\nSistem Informasi SIM-MoU.";
            
            case 'warning_7':
                return "⚠️ *PERINGATAN PENTING KERJA SAMA (7 HARI)* ⚠️\n\nYth. Bapak/Ibu *{$picName}*,\n\nDokumen kerja sama (MoU/MoA) antara sekolah kami dengan *{$namaMitra}* (No. MoU: {$nomorMou}) akan segera berakhir dalam *7 hari* pada tanggal *{$tanggalBerakhir}*.\n\nMohon segera konfirmasikan perihal status perpanjangan kerja sama ini agar kami dapat memproses administrasi yang diperlukan.\n\nTerima kasih atas kerja samanya.\n\nSistem Informasi SIM-MoU.";
            
            case 'expired':
                return "🚨 *PEMBERITAHUAN MASA BERLAKU BERAKHIR* 🚨\n\nYth. Bapak/Ibu *{$picName}*,\n\nKami menginformasikan bahwa dokumen kerja sama (MoU/MoA) antara sekolah kami dengan *{$namaMitra}* (No. MoU: {$nomorMou}) telah *BERAKHIR* pada tanggal *{$tanggalBerakhir}*.\n\nJika ingin mengajukan kerja sama baru atau perpanjangan kembali, silakan hubungi tim kami.\n\nTerima kasih atas kerja sama yang telah terjalin baik selama ini.\n\nSistem Informasi SIM-MoU.";
            
            default:
                return "Yth. Bapak/Ibu {$picName}, menginformasikan bahwa status dokumen kerja sama dengan {$namaMitra} ({$nomorMou}) saat ini adalah {$type}.";
        }
    }
}
