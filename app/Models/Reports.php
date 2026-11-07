<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ReportSubmitted;

class Reports extends Model
{

  protected $guarded = [];
  const UPDATED_AT = null;

  protected static function boot()
  {
    parent::boot();

    static::created(function ($report) {
      try {
        Notification::route('mail', config('mail.report_recipient'))
          ->notify(new ReportSubmitted($report));
      } catch (\Throwable $exception) {
        Log::error('Failed to send report notification email.', [
          'report_id' => $report->id,
          'error' => $exception->getMessage(),
        ]);
      }
    });
  }

  public function user()
  {
    return $this->belongsTo(User::class, 'user_id');
  }

  public function userReported()
  {
    return $this->belongsTo(User::class, 'report_id');
  }

  public function updates()
  {
    return $this->belongsTo(Updates::class, 'report_id');
  }

  public function products()
  {
    return $this->belongsTo(Products::class, 'report_id');
  }

  public function live()
  {
    return $this->belongsTo(User::class, 'report_id');
  }
}
