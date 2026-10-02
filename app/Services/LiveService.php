<?php

namespace App\Services;

use App\Models\LiveStreamings;
use App\Helper;

class LiveService
{
    public function countActiveLives()
    {
        $query = LiveStreamings::whereType('normal')
			->where('live_streamings.updated_at', '>', now()->subMinutes(5))
			->where('status', '0')
			->whereHas('user', function ($q) {
				$q->where('blocked_countries', 'NOT LIKE', '%' . request()->header('CF-IPCountry', 'AO') . '%');
			});

		$count = $query->count();

		return $count;
    }
}