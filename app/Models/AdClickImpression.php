<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdClickImpression extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function recordPromotedPostEvent(int $campaignId, string $event, string $ip): bool
    {
        // The legacy event table has no foreign key; the event prefix separates campaign IDs from ad IDs.
        $type = 'promotion_' . $event;

        if ($event === 'click') {
            static::create([
                'advertisings_id' => $campaignId,
                'type' => $type,
                'ip' => $ip,
            ]);

            return true;
        }

        $recentEvent = static::where('advertisings_id', $campaignId)
            ->where('type', $type)
            ->where('ip', $ip)
            ->where('created_at', '>=', now()->subDay())
            ->exists();

        if ($recentEvent) {
            return false;
        }

        static::create([
            'advertisings_id' => $campaignId,
            'type' => $type,
            'ip' => $ip,
        ]);

        return true;
    }
}
