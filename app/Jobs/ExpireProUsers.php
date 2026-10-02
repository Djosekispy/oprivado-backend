<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ExpireProUsers
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        User::query()
            ->where('is_pro', true)
            ->whereNotNull('pro_expires_at')
            ->where('pro_expires_at', '<=', now())
            ->update([
                'is_pro' => false,
                'pro_started_at' => null,
                'pro_expires_at' => null,
            ]);
    }
}
