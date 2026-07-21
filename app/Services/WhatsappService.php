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
     * @param string $type e.g. "6 bulan", "3 bulan", "berakhir"
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
            'user_id' => auth()->id(),
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
     * Types: "6 bulan", "5 bulan", ..., "1 bulan", "berakhir"
     */
    protected function generateMessageTemplate($kerjaSama, $type)
    {
        $namaMitra = $kerjaSama->nama_mitra;
        $nomorMou = $kerjaSama->nomor_mou;
        $tanggalBerakhir = $kerjaSama->tanggal_berakhir ? $kerjaSama->tanggal_berakhir->format('d/m/Y') : '-';
        $picName = $kerjaSama->pic ?: 'Bapak/Ibu';

        if ($type === 'berakhir') {
            return "🚨 *PEMBERITAHUAN MASA BERLAKU BERAKHIR* 🚨\n\nYth. Bapak/Ibu *{$picName}*,\n\nKami menginformasikan bahwa dokumen kerja sama (MoU/MoA) antara sekolah kami dengan *{$namaMitra}* (No. MoU: {$nomorMou}) telah *BERAKHIR* pada tanggal *{$tanggalBerakhir}*.\n\nJika ingin mengajukan kerja sama baru atau perpanjangan kembali, silakan hubungi tim kami.\n\nTerima kasih atas kerja sama yang telah terjalin baik selama ini.\n\nSistem Informasi OneLink.";
        }

        if (str_contains($type, 'hari')) {
            $days = intval($type);
            return "⚠️ *PERINGATAN PENTING KERJA SAMA ({$days} HARI)* ⚠️\n\nYth. Bapak/Ibu *{$picName}*,\n\nDokumen kerja sama (MoU/MoA) antara sekolah kami dengan *{$namaMitra}* (No. MoU: {$nomorMou}) akan segera berakhir dalam *{$days} hari* pada tanggal *{$tanggalBerakhir}*.\n\nMohon segera konfirmasikan perihal status perpanjangan kerja sama ini agar kami dapat memproses administrasi yang diperlukan.\n\nTerima kasih atas kerja samanya.\n\nSistem Informasi OneLink.";
        }

        // Monthly warning (e.g. "6 bulan", "3 bulan", "1 bulan")
        $months = intval($type); // extracts the number from "3 bulan" etc.
        return "🔔 *PENGINGAT KERJA SAMA ({$months} BULAN)* 🔔\n\nYth. Bapak/Ibu *{$picName}*,\n\nKami menginformasikan bahwa dokumen kerja sama (MoU/MoA) antara sekolah kami dengan *{$namaMitra}* (No. MoU: {$nomorMou}) akan berakhir dalam *{$months} bulan* pada tanggal *{$tanggalBerakhir}*.\n\nApakah ada rencana untuk melanjutkan/memperpanjang kerja sama ini? Kami sangat senang jika bisa terus bersinergi.\n\nTerima kasih atas kerja samanya.\n\nSistem Informasi OneLink.";
    }

    /**
     * Kirim notifikasi WhatsApp ke Pelamar yang Diterima
     */
    public function sendPelamarDiterima($pelamar)
    {
        $settings = Setting::getSettings();
        if (!$settings->whatsapp_active || !$settings->fonnte_token || !$pelamar->nomor_telepon) {
            return false;
        }

        $recipient = $this->formatPhoneNumber($pelamar->nomor_telepon);
        $judulLoker = $pelamar->lowonganKerja ? $pelamar->lowonganKerja->judul : 'Lowongan Kerja';
        $namaPerusahaan = $pelamar->lowonganKerja ? $pelamar->lowonganKerja->nama_perusahaan : 'Perusahaan Mitra';

        $message = "🎉 *SELAMAT! LAMARAN ANDA DITERIMA* 🎉\n\nYth. Sdr/i *{$pelamar->nama_lengkap}*,\n\nSelamat! Berdasarkan hasil seleksi BKK, Anda dinyatakan *DITERIMA* untuk posisi *{$judulLoker}* di *{$namaPerusahaan}*.\n\nTim BKK / Perusahaan akan segera menghubungi Anda untuk tahap selanjutnya.\n\nSalam,\nBursa Kerja Khusus (BKK)";

        return $this->sendDirectMessage($recipient, $message, 'pelamar_diterima');
    }

    /**
     * Kirim notifikasi WhatsApp ke Pelamar yang Ditolak
     */
    public function sendPelamarDitolak($pelamar)
    {
        $settings = Setting::getSettings();
        if (!$settings->whatsapp_active || !$settings->fonnte_token || !$pelamar->nomor_telepon) {
            return false;
        }

        $recipient = $this->formatPhoneNumber($pelamar->nomor_telepon);
        $judulLoker = $pelamar->lowonganKerja ? $pelamar->lowonganKerja->judul : 'Lowongan Kerja';
        $namaPerusahaan = $pelamar->lowonganKerja ? $pelamar->lowonganKerja->nama_perusahaan : 'Perusahaan Mitra';

        $message = "Halo Sdr/i *{$pelamar->nama_lengkap}*,\n\nTerima kasih telah melamar untuk posisi *{$judulLoker}* di *{$namaPerusahaan}* melalui BKK.\n\nMohon maaf, saat ini Anda belum dapat diproses ke tahap selanjutnya karena kuota/posisi telah terpenuhi. Tetap semangat dan jangan ragu melamar di lowongan BKK berikutnya!\n\nSalam,\nBursa Kerja Khusus (BKK)";

        return $this->sendDirectMessage($recipient, $message, 'pelamar_ditolak');
    }

    /**
     * General direct message sender via Fonnte
     */
    protected function sendDirectMessage($recipient, $message, $type)
    {
        $settings = Setting::getSettings();
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
                if (isset($resData['status']) && $resData['status'] == true) {
                    $status = 'success';
                }
            }
        } catch (\Exception $e) {
            $responseBody = 'Error: ' . $e->getMessage();
            Log::error("Fonnte API Exception ({$type}): " . $e->getMessage());
        }

        WhatsappLog::create([
            'user_id' => auth()->id(),
            'type' => $type,
            'recipient' => $recipient,
            'message' => $message,
            'status' => $status,
            'response' => $responseBody,
        ]);

        return $status === 'success';
    }
}
