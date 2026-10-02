<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Categories extends Model
{
	protected $table = 'categories';
	protected $guarded = [];
	public $timestamps = false;

	public function users()
	{
		return $this->hasMany(User::class)->where('status','active');
	}
}
