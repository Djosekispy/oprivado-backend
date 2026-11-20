<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Restrictions extends Model
{
    use HasFactory;

  public static function blockedUserIdsFor($userId)
  {
    return DB::table('restrictions')
      ->select('user_restricted')
      ->where('user_id', $userId)
      ->union(
        DB::table('restrictions')
          ->select('user_id')
          ->where('user_restricted', $userId)
      );
  }

  public static function existsBetween($firstUserId, $secondUserId)
  {
    return static::query()
      ->where(function ($query) use ($firstUserId, $secondUserId) {
        $query->where('user_id', $firstUserId)
          ->where('user_restricted', $secondUserId);
      })
      ->orWhere(function ($query) use ($firstUserId, $secondUserId) {
        $query->where('user_id', $secondUserId)
          ->where('user_restricted', $firstUserId);
      })
      ->exists();
  }

    protected $fillable = [
      'user_id',
      'user_restricted'
    ];

    public function user()
    {
      return $this->belongsTo(User::class)->first();
    }

    public function userRestricted()
    {
      return $this->belongsTo(User::class, 'user_restricted')->first();
    }
}
