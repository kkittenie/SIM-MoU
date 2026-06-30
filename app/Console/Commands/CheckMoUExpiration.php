<?php

namespace App\Console\Commands;

use App\Models\KerjaSama;
use App\Models\Notification;
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
            $diffDays = $today->diffInDays($endDate, false);

            $type = null;

            // 1. Check monthly warning (from 6 months down to 1 month before expiration)
            for ($m = 1; $m <= 6; $m++) {
                if ($endDate->copy()->subMonths($m)->isSameDay($today)) {
                    $type = "{$m} bulan";
                    break;
                }
            }

            // 2. Weekly warning: under 30 days, every 7 days (e.g. 23, 16, 9, 2 days left)
            if (!$type && $diffDays < 30 && $diffDays > 0 && $diffDays % 7 === 2) {
                $type = "{$diffDays} hari";
            } 
            
            // 3. Expired: exactly today
            if (!$type && $diffDays === 0) {
                $type = 'berakhir';
            }

            if ($type) {
                // Check if notification already exists
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

                    // Send WhatsApp automatically if enabled
                    $whatsappService = new WhatsappService();
                    $whatsappService->sendNotification($ks, $type);

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
        if ($type === 'berakhir') {
            return "MoU dengan {$ks->nama_mitra} Telah Berakhir";
        }

        return "MoU dengan {$ks->nama_mitra} Berakhir dalam {$type}";
    }

    /**
     * Get message body for notification
     */
    protected function getNotificationMessage($ks, $type)
    {
        $dateStr = $ks->tanggal_berakhir ? $ks->tanggal_berakhir->format('d/m/Y') : '-';
        
        if ($type === 'berakhir') {
            return "Kerja sama No. {$ks->nomor_mou} dengan {$ks->nama_mitra} telah berakhir hari ini tanggal {$dateStr}. Status telah diperbarui otomatis menjadi Berakhir.";
        }

        return "Kerja sama No. {$ks->nomor_mou} dengan {$ks->nama_mitra} akan berakhir dalam {$type} pada {$dateStr}. Mohon siapkan peninjauan kembali.";
    }
}
