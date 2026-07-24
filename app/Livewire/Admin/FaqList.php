<?php

namespace App\Livewire\Admin;

use App\Models\Faq;
use App\Services\FaqService;
use Livewire\Component;
use Livewire\WithPagination;

class FaqList extends Component
{
    use WithPagination;

    public $editingId = null;
    public $question = '';
    public $answer = '';
    public $sort_order = 0;

    private FaqService $faqService;

    public function boot(FaqService $faqService): void
    {
        $this->faqService = $faqService;
    }

    protected $rules = [
        'question' => 'required|string|max:255',
        'answer' => 'required|string',
        'sort_order' => 'required|integer|min:0',
    ];

    public function create(): void
    {
        abort_unless(auth()->user()->isAdmin(), 403);
        $this->resetForm();
        $this->editingId = 'new';
    }

    public function edit(string $id): void
    {
        abort_unless(auth()->user()->isAdmin(), 403);
        $faq = Faq::findOrFail($id);
        $this->editingId = $id;
        $this->question = $faq->question;
        $this->answer = $faq->answer;
        $this->sort_order = $faq->sort_order;
    }

    public function save(): void
    {
        abort_unless(auth()->user()->isAdmin(), 403);
        $this->validate();

        if ($this->editingId === 'new') {
            $this->faqService->create([
                'question' => $this->question,
                'answer' => $this->answer,
                'sort_order' => $this->sort_order,
            ]);
            $this->dispatch('toast', message: 'FAQ berhasil ditambahkan.', type: 'success');
        } else {
            $faq = Faq::findOrFail($this->editingId);
            $this->faqService->update($faq, [
                'question' => $this->question,
                'answer' => $this->answer,
                'sort_order' => $this->sort_order,
            ]);
            $this->dispatch('toast', message: 'FAQ berhasil diperbarui.', type: 'success');
        }

        $this->resetForm();
    }

    public function delete(string $id): void
    {
        abort_unless(auth()->user()->isAdmin(), 403);
        $faq = Faq::findOrFail($id);
        $this->faqService->delete($faq);
        $this->dispatch('toast', message: 'FAQ berhasil dihapus.', type: 'success');
    }

    public function resetForm(): void
    {
        $this->editingId = null;
        $this->question = '';
        $this->answer = '';
        $this->sort_order = 0;
    }

    public function render()
    {
        $faqs = $this->faqService->getPaginatedList();
        return view('livewire.admin.faq-list', compact('faqs'));
    }
}