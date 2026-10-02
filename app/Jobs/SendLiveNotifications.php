<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendLiveNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
	
	 public $user;
    public $liveId;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
           // evita serializar relações gigantes
        $this->user = $user->withoutRelations();
        $this->liveId = $liveId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
         \Log::info('START SENDING LIVE NOTIFICATIONS', [
            'live_id' => $this->liveId
        ]);

        // aqui entra a tua lógica de notificação
        // followers/subscribers loop
        // push notifications
        // emails
        // websocket events

        \Log::info('LIVE NOTIFICATIONS FINISHED');
    }
}
