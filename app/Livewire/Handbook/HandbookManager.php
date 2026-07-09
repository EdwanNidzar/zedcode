<?php

namespace App\Livewire\Handbook;

use App\Models\HandbookArticle;
use App\Models\HandbookCategory;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class HandbookManager extends Component
{
    // ─── UI State ───────────────────────────────────────────────

    public string $activeTab = 'articles'; // 'articles' | 'categories'

    public string $editorMode = 'list';     // 'list' | 'create' | 'edit'

    public string $search = '';

    // ─── Article Form ────────────────────────────────────────────

    public ?int $editingArticleId = null;

    public string $articleTitle = '';

    public string $articleContent = '';

    public string $articleStatus = 'draft';

    public ?int $articleCategoryId = null;

    public int $articleSortOrder = 0;

    // ─── Category Form ───────────────────────────────────────────

    public ?int $editingCategoryId = null;

    public string $categoryName = '';

    public string $categoryIcon = '';

    public int $categorySortOrder = 0;

    // ─── Validation Rules ────────────────────────────────────────

    protected function articleRules(): array
    {
        return [
            'articleTitle' => ['required', 'string', 'max:255'],
            'articleCategoryId' => ['required', 'exists:handbook_categories,id'],
            'articleContent' => ['nullable', 'string'],
            'articleStatus' => ['required', 'in:draft,published'],
            'articleSortOrder' => ['integer', 'min:0'],
        ];
    }

    protected function categoryRules(): array
    {
        return [
            'categoryName' => ['required', 'string', 'max:100'],
            'categoryIcon' => ['nullable', 'string', 'max:255'],
            'categorySortOrder' => ['integer', 'min:0'],
        ];
    }

    // ─── Lifecycle ───────────────────────────────────────────────

    public function mount(): void
    {
        $this->articleSortOrder = HandbookArticle::max('sort_order') + 1;
        $this->categorySortOrder = HandbookCategory::max('sort_order') + 1;
    }

    // ─── Article Actions ─────────────────────────────────────────

    public function createArticle(): void
    {
        $this->resetArticleForm();
        $this->articleSortOrder = HandbookArticle::max('sort_order') + 1;
        $this->editorMode = 'create';
        $this->dispatch('init-quill', content: '');
    }

    public function editArticle(int $id): void
    {
        $article = HandbookArticle::findOrFail($id);

        $this->editingArticleId = $article->id;
        $this->articleTitle = $article->title;
        $this->articleContent = $article->content ?? '';
        $this->articleStatus = $article->status;
        $this->articleCategoryId = $article->handbook_category_id;
        $this->articleSortOrder = $article->sort_order;
        $this->editorMode = 'edit';

        $this->dispatch('init-quill', content: $article->content ?? '');
    }

    public function saveArticle(): void
    {
        $this->validate($this->articleRules());

        $data = [
            'handbook_category_id' => $this->articleCategoryId,
            'title' => $this->articleTitle,
            'content' => $this->articleContent,
            'status' => $this->articleStatus,
            'sort_order' => $this->articleSortOrder,
            'author_id' => Auth::id(),
        ];

        if ($this->editingArticleId) {
            $article = HandbookArticle::findOrFail($this->editingArticleId);
            $data['slug'] = $article->slug; // Slug tidak diubah saat edit
            $article->update($data);
            session()->flash('success', "Artikel \"{$article->title}\" berhasil diperbarui.");
        } else {
            $data['slug'] = HandbookArticle::generateSlug($this->articleTitle);
            HandbookArticle::create($data);
            session()->flash('success', "Artikel \"{$this->articleTitle}\" berhasil dibuat.");
        }

        $this->resetArticleForm();
        $this->editorMode = 'list';
    }

    public function deleteArticle(int $id): void
    {
        $article = HandbookArticle::findOrFail($id);
        $title = $article->title;
        $article->delete();
        session()->flash('success', "Artikel \"{$title}\" berhasil dihapus.");
    }

    public function toggleArticleStatus(int $id): void
    {
        $article = HandbookArticle::findOrFail($id);
        $article->status = $article->status === 'published' ? 'draft' : 'published';
        $article->save();
    }

    public function cancelArticleEdit(): void
    {
        $this->resetArticleForm();
        $this->editorMode = 'list';
    }

    // ─── Category Actions ────────────────────────────────────────

    public function createCategory(): void
    {
        $this->resetCategoryForm();
        $this->categorySortOrder = HandbookCategory::max('sort_order') + 1;
        $this->editorMode = 'create';
    }

    public function editCategory(int $id): void
    {
        $category = HandbookCategory::findOrFail($id);

        $this->editingCategoryId = $category->id;
        $this->categoryName = $category->name;
        $this->categoryIcon = $category->icon ?? '';
        $this->categorySortOrder = $category->sort_order;
        $this->editorMode = 'edit';
    }

    public function saveCategory(): void
    {
        $this->validate($this->categoryRules());

        $data = [
            'name' => $this->categoryName,
            'icon' => $this->categoryIcon ?: null,
            'sort_order' => $this->categorySortOrder,
        ];

        if ($this->editingCategoryId) {
            $category = HandbookCategory::findOrFail($this->editingCategoryId);
            $category->update($data);
            session()->flash('success', "Kategori \"{$category->name}\" berhasil diperbarui.");
        } else {
            $data['slug'] = HandbookCategory::generateSlug($this->categoryName);
            HandbookCategory::create($data);
            session()->flash('success', "Kategori \"{$this->categoryName}\" berhasil dibuat.");
        }

        $this->resetCategoryForm();
        $this->editorMode = 'list';
    }

    public function deleteCategory(int $id): void
    {
        $category = HandbookCategory::findOrFail($id);

        if ($category->articles()->count() > 0) {
            session()->flash('error', 'Kategori tidak dapat dihapus karena masih memiliki artikel.');

            return;
        }

        $name = $category->name;
        $category->delete();
        session()->flash('success', "Kategori \"{$name}\" berhasil dihapus.");
    }

    public function cancelCategoryEdit(): void
    {
        $this->resetCategoryForm();
        $this->editorMode = 'list';
    }

    // ─── Tab Switching ───────────────────────────────────────────

    public function switchTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->editorMode = 'list';
        $this->search = '';
        $this->resetArticleForm();
        $this->resetCategoryForm();
    }

    // ─── Reset Helpers ───────────────────────────────────────────

    private function resetArticleForm(): void
    {
        $this->editingArticleId = null;
        $this->articleTitle = '';
        $this->articleContent = '';
        $this->articleStatus = 'draft';
        $this->articleCategoryId = null;
        $this->articleSortOrder = 0;
        $this->resetValidation();
    }

    private function resetCategoryForm(): void
    {
        $this->editingCategoryId = null;
        $this->categoryName = '';
        $this->categoryIcon = '';
        $this->categorySortOrder = 0;
        $this->resetValidation();
    }

    // ─── Computed Properties ────────────────────────────────────

    public function getArticlesProperty()
    {
        return HandbookArticle::with('category')
            ->when($this->search, fn ($q) => $q->where('title', 'like', "%{$this->search}%")
            )
            ->orderBy('handbook_category_id')
            ->orderBy('sort_order')
            ->get();
    }

    public function getCategoriesProperty()
    {
        return HandbookCategory::withCount('articles')
            ->ordered()
            ->get();
    }

    // ─── Render ─────────────────────────────────────────────────

    public function render()
    {
        return view('livewire.handbook.handbook-manager', [
            'articles'   => $this->articles,
            'categories' => $this->categories,
        ])->layout('components.app-layout', ['title' => 'Kelola Handbook']);
    }
}
