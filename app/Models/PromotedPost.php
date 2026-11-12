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

    public function getRemainingDaysAttribute(): int
    {
        if ($this->status !== 'active') {
            return 0;
        }

        return max(0, (int) now()->diffInDays($this->ends_at, false));
    }
}
