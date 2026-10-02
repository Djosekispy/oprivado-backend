<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;

class ResetFeaturedProfiles extends Command
{
   /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'featured:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset featured profiles after 20 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando reset de perfis destacados...');

        $twentyDaysAgo = Carbon::now()->subDays(20);

        $count = User::where('featured', 'yes')
            ->where('featured_date', '<=', $twentyDaysAgo)
			->where('role', '!=', 'admin')
            ->update([
                'featured' => 'no',
                'featured_date' => null
            ]);

        $this->info("✅ {$count} perfil(is) destacado(s) removido(s) com sucesso.");
        
        \Log::info("Cron featured:reset executada - {$count} perfis resetados.");
    }
}
