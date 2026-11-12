<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PromotedPost extends Model
{
    protected $table = 'promoted_posts';

    protected $guarded = [];

    protected $casts = [
        'days' => 'integer',
        'daily_price' => 'float',
        'total_amount' => 'float',
        'impressions' => 'integer',
        'clicks' => 'integer',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'removed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Updates::class, 'update_id');
    }

    public function recordImpression(): void
    {
        if (AdClickImpression::recordPromotedPostEvent($this->id, 'impression', request()->ip())) {
            $this->increment('impressions');
        }
    }

    public function recordClick(): void
    {
        if (AdClickImpression::recordPromotedPostEvent($this->id, 'click', request()->ip())) {
            $this->increment('clicks');
        }
    }

    public function getCtrAttribute(): float
    {
        return $this->impressions > 0
            ? round(($this->clicks / $this->impressions) * 100, 2)
            : 0.0;
    }

    public function getRemainingDaysAttribute(): int
    {
        if ($this->status !== 'active' || ! $this->ends_at || $this->ends_at->isPast()) {
            return 0;
        }

        $secondsRemaining = $this->ends_at->getTimestamp() - now()->getTimestamp();

        return max(0, (int) ceil($secondsRemaining / 86400));
    }
}
