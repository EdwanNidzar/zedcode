<?php

namespace App\Livewire\Handbook;

use App\Models\HandbookArticle;
use App\Models\HandbookCategory;
use Livewire\Component;

class HandbookReader extends Component
{
    // ─── State ──────────────────────────────────────────────────

    public ?HandbookArticle $article = null;

    public ?HandbookCategory $activeCategory = null;

    public string $search = '';

    public string $categorySlug = '';

    public string $articleSlug = '';

    // ─── Mount ──────────────────────────────────────────────────

    public function mount(string $category = '', string $article = ''): void
    {
        $this->categorySlug = $category;
        $this->articleSlug = $article;

        if ($category && $article) {
            $this->article = HandbookArticle::with(['category', 'author'])
                ->where('slug', $article)
                ->published()
                ->firstOrFail();

            $this->activeCategory = $this->article->category;
        }
    }

    // ─── Actions ────────────────────────────────────────────────

    public function selectArticle(int $articleId): void
    {
        $this->article = HandbookArticle::with(['category', 'author'])
            ->where('id', $articleId)
            ->published()
            ->firstOrFail();

        $this->activeCategory = $this->article->category;
        $this->search = '';

        // Reset scroll ke atas via JS
        $this->dispatch('article-changed');
    }

    public function updatedSearch(): void
    {
        // Search reaktif — query terjadi di computed property
    }

    // ─── Computed Properties ────────────────────────────────────

    public function getCategoriesProperty()
    {
        return HandbookCategory::with(['publishedArticles'])
            ->ordered()
            ->get();
    }

    public function getSearchResultsProperty()
    {
        if (strlen($this->search) < 2) {
            return collect();
        }

        return HandbookArticle::with('category')
            ->published()
            ->where(function ($q) {
                $q->where('title', 'like', "%{$this->search}%")
                    ->orWhere('content', 'like', "%{$this->search}%");
            })
            ->limit(10)
            ->get();
    }

    public function getPreviousArticleProperty(): ?HandbookArticle
    {
        return $this->article?->previousArticle();
    }

    public function getNextArticleProperty(): ?HandbookArticle
    {
        return $this->article?->nextArticle();
    }

    // ─── Render ─────────────────────────────────────────────────

    public function render()
    {
        return view('livewire.handbook.handbook-reader', [
            'categories' => $this->categories,
            'searchResults' => $this->searchResults,
            'prevArticle' => $this->previousArticle,
            'nextArticle' => $this->nextArticle,
        ])->layout('components.app-layout', ['title' => 'Handbook Zedpos']);
    }
}
