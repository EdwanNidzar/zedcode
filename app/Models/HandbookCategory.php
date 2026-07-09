<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class HandbookCategory extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'icon',
        'sort_order',
    ];

    // ─── Relations ──────────────────────────────────────────────

    public function articles(): HasMany
    {
        return $this->hasMany(HandbookArticle::class)->orderBy('sort_order');
    }

    public function publishedArticles(): HasMany
    {
        return $this->hasMany(HandbookArticle::class)
            ->where('status', 'published')
            ->orderBy('sort_order');
    }

    // ─── Scopes ─────────────────────────────────────────────────

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    // ─── Helpers ────────────────────────────────────────────────

    /**
     * Auto-generate slug dari name jika slug tidak diisi.
     */
    public static function generateSlug(string $name): string
    {
        $slug = Str::slug($name);
        $count = static::where('slug', 'like', "{$slug}%")->count();

        return $count ? "{$slug}-{$count}" : $slug;
    }
}
