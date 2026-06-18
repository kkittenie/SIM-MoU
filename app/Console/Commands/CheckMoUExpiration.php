<?php

namespace App\Console\Commands;

use App\Models\KerjaSama;
use App\Models\Notification;
use App\Models\WhatsappLog;
use App\Services\WhatsappService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckMoUExpiration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mou:check-expiration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check MoU expiration dates and generate notifications/WhatsApp alerts';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting MoU expiration check...');
        $today = Carbon::today();
        
        // Fetch all partnerships
        $partnerships = KerjaSama::all();
        
        $notificationsCreated = 0;

        foreach ($partnerships as $ks) {
            $endDate = Carbon::parse($ks->tanggal_berakhir);
            $diffDays = $today->diffInDays($endDate, false); // false allows negative values if already expired

            $type = null;

            // Determine check thresholds
            if ($diffDays === 30) {
                $type = 'warning_30';
            } elseif ($diffDays === 14) {
                $type = 'warning_14';
            } elseif ($diffDays === 7) {
                $type = 'warning_7';
            } elseif ($diffDays <= 0) {
                $type = 'expired';
            }

            if ($type) {
                // 1. Database Notification
                $notifExists = Notification::where('kerja_sama_id', $ks->id)
                    ->where('type', $type)
                    ->exists();

                if (!$notifExists) {
                    $title = $this->getNotificationTitle($ks, $type);
                    $message = $this->getNotificationMessage($ks, $type);

                    Notification::create([
                        'kerja_sama_id' => $ks->id,
                        'type' => $type,
                        'title' => $title,
                        'message' => $message,
                        'is_read' => false,
                    ]);

                    $notificationsCreated++;
                }
            }
        }

        $this->info("Completed! Created {$notificationsCreated} new notification(s).");
    }

    /**
     * Get title for notification
     */
    protected function getNotificationTitle($ks, $type)
    {
        switch ($type) {
            case 'warning_30':
                return "MoU dengan {$ks->nama_mitra} Berakhir dalam 30 Hari";
            case 'warning_14':
                return "MoU dengan {$ks->nama_mitra} Berakhir dalam 14 Hari";
            case 'warning_7':
                return "Peringatan: MoU dengan {$ks->nama_mitra} Berakhir dalam 7 Hari";
            case 'expired':
                return "MoU dengan {$ks->nama_mitra} Telah Kadaluarsa";
            default:
                return "Pemberitahuan Kerja Sama";
        }
    }

    /**
     * Get message body for notification
     */
    protected function getNotificationMessage($ks, $type)
    {
        $dateStr = $ks->tanggal_berakhir ? $ks->tanggal_berakhir->format('d/m/Y') : '-';
        switch ($type) {
            case 'warning_30':
                return "Kerja sama No. {$ks->nomor_mou} dengan {$ks->nama_mitra} akan berakhir pada {$dateStr}. Mohon siapkan peninjauan kembali.";
            case 'warning_14':
                return "Kerja sama No. {$ks->nomor_mou} dengan {$ks->nama_mitra} akan berakhir pada {$dateStr}. Siapkan perpanjangan berkas.";
            case 'warning_7':
                return "Kerja sama No. {$ks->nomor_mou} dengan {$ks->nama_mitra} tinggal 7 hari lagi! Berakhir pada {$dateStr}. Hubungi PIC: {$ks->pic} ({$ks->nomor_telepon}).";
            case 'expired':
                return "Kerja sama No. {$ks->nomor_mou} dengan {$ks->nama_mitra} telah kadaluarsa sejak tanggal {$dateStr}.";
            default:
                return "Detail kerja sama telah diperbarui.";
        }
    }
}
