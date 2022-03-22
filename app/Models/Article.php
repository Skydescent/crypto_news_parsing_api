<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Article extends Model
{
    protected $guarded = [];

    public function scopeOldestByTheme($query, $theme)
    {
        $query->where('theme', $theme)->oldest('published_at')->limit(1);
    }

    public function source(): BelongsTo
    {
        return $this->belongsTo(ArticleSource::class);
    }

}
