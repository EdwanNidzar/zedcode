<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class HandbookArticle extends Model
{
    protected $fillable = [
        'handbook_category_id',
        'title',
        'slug',
        'content',
        'status',
        'sort_order',
        'author_id',
    ];

    // ─── Relations ──────────────────────────────────────────────

    public function category(): BelongsTo
    {
        return $this->belongsTo(HandbookCategory::class, 'handbook_category_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    // ─── Scopes ─────────────────────────────────────────────────

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    // ─── Helpers ────────────────────────────────────────────────

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    /**
     * Auto-generate slug dari title jika slug tidak diisi.
     */
    public static function generateSlug(string $title): string
    {
        $slug = Str::slug($title);
        $count = static::where('slug', 'like', "{$slug}%")->count();

        return $count ? "{$slug}-{$count}" : $slug;
    }

    /**
     * Ambil artikel sebelumnya dalam kategori yang sama (published).
     */
    public function previousArticle(): ?self
    {
        return static::where('handbook_category_id', $this->handbook_category_id)
            ->where('status', 'published')
            ->where('sort_order', '<', $this->sort_order)
            ->orderByDesc('sort_order')
            ->first();
    }

    /**
     * Ambil artikel berikutnya dalam kategori yang sama (published).
     * Jika tidak ada, cari artikel pertama dari kategori berikutnya.
     */
    public function nextArticle(): ?self
    {
        $next = static::where('handbook_category_id', $this->handbook_category_id)
            ->where('status', 'published')
            ->where('sort_order', '>', $this->sort_order)
            ->orderBy('sort_order')
            ->first();

        if ($next) {
            return $next;
        }

        // Coba dari kategori berikutnya
        $nextCategory = HandbookCategory::where('sort_order', '>', $this->category->sort_order)
            ->orderBy('sort_order')
            ->first();

        return $nextCategory?->publishedArticles()->first();
    }
}
