<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'image',
        'status',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($article) {
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->title);
            }
        });

        static::deleting(function ($article) {
            if ($article->image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($article->image);
            }
        });

        static::updating(function ($article) {
            if ($article->isDirty('image')) {
                $oldImage = $article->getOriginal('image');
                if ($oldImage) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($oldImage);
                }
            }
        });
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'Published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }
}
