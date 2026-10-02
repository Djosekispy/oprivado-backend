<?php

namespace App\Models;

use App\Helper;
use Illuminate\Database\Eloquent\Model;
use App\Models\Categories;

class Updates extends Model
{
	protected $guarded = [];
	public $timestamps = false;

	public function user()
	{
		return $this->belongsTo(User::class)->first();
	}

	public function creator()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public function media()
	{
		return $this->hasMany(Media::class)->where('status', 'active')->orderBy('id','asc');
	}

	public function likes()
	{
		return $this->hasMany(Like::class)->where('status', '1');
	}

	public function comments()
	{
		return $this->hasMany(Comments::class);
	}

	public function replies()
	{
		return $this->hasMany(Replies::class);
	}

	public function bookmarks()
	{
		return $this->belongsToMany(User::class, 'bookmarks','updates_id','user_id');
	}

	public function totalComments()
	{
		$post = $this->withCount(['comments', 'replies'])->whereId($this->id)->get();

		return number_format($post[0]->comments_count + $post[0]->replies_count);
	}

	public function videoViews()
	{
		return $this->hasMany(VideoViews::class);
	}

	public function scopeGetSelectRelations($query)
	{
		return $query->select('updates.id',
                'updates.title',
                'updates.description',
                'updates.user_id',
                'updates.date',
                'updates.locked',
                'updates.fixed_post',
                'updates.price',
                'updates.status',
                'updates.video_views',
				'updates.scheduled_date',
                )
          ->with([
            'creator:id,name,username,avatar,content_type,hide_name,verified_id,plan,free_subscription,cover,allow_comments', 
            'creator.plans:user_id,name,status', 
            'media', 
            'comments:id,updates_id',
			'likes:id,updates_id,status',
			 'categories:id,name,slug'
		  ]);
	}

	/**
     * Categorias do post (muitos para muitos)
     */
    public function categories()
    {
        return $this->belongsToMany(Categories::class, 'category_update', 'update_id', 'category_id')
                    ->withTimestamps(); // se sua pivot tiver created_at/updated_at
    }

    /**
     * Verifica se o post tem uma categoria específica
     */
    public function hasCategory($categoryId)
    {
        return $this->categories()->where('id', $categoryId)->exists();
    }

    /**
     * Verifica se é conteúdo adulto (categoria 15)
     */
    public function isAdultContent()
    {
        return $this->hasCategory(15);
    }
	
	public function scopeSelectPostsFields($query)
	{
	  return $query->select('updates.id',
		'updates.title',
		'updates.description',
		'updates.user_id',
		'updates.date',
		'updates.locked',
		'updates.fixed_post',
		'updates.price',
		'updates.status',
		'updates.video_views',
		'updates.scheduled_date'
	  );
	}

	public function scopeVerifyCountryBlocking($query)
	{
		$query->with(['creator' => fn ($sql) => 
			$sql->where('blocked_countries', 'NOT LIKE', '%'.Helper::userCountry().'%')
		]);
	}
}
