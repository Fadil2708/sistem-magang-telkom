<?php

namespace App\Livewire\Admin;

use App\Models\Faq;
use Livewire\Component;
use Livewire\WithPagination;

class FaqList extends Component
{
    use WithPagination;

    public $editingId = null;
    public $question = '';
    public $answer = '';
    public $sort_order = 0;

    public function create(): void
    {
        $this->resetForm();
        $this->editingId = 'new';
    }

    public function edit(string $id): void
    {
        $faq = Faq::findOrFail($id);
        $this->editingId = $id;
        $this->question = $faq->question;
        $this->answer = $faq->answer;
        $this->sort_order = $faq->sort_order;
    }

    public function save(): void
    {
        $this->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'sort_order' => 'required|integer|min:0',
        ]);

        if ($this->editingId === 'new') {
            Faq::create([
                'question' => $this->question,
                'answer' => $this->answer,
                'sort_order' => $this->sort_order,
            ]);
            $this->dispatch('toast', message: 'FAQ berhasil ditambahkan.', type: 'success');
        } else {
            Faq::findOrFail($this->editingId)->update([
                'question' => $this->question,
                'answer' => $this->answer,
                'sort_order' => $this->sort_order,
            ]);
            $this->dispatch('toast', message: 'FAQ berhasil diperbarui.', type: 'success');
        }

        $this->resetForm();
    }

    public function toggleActive(string $id): void
    {
        $faq = Faq::findOrFail($id);
        $faq->update(['is_active' => !$faq->is_active]);
        $status = $faq->fresh()->is_active ? 'ditayangkan' : 'disembunyikan';
        $this->dispatch('toast', message: "FAQ berhasil {$status}.", type: 'success');
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->question = '';
        $this->answer = '';
        $this->sort_order = 0;
    }

    public function render()
    {
        return view('livewire.admin.faq-list', [
            'faqs' => Faq::ordered()->paginate(20),
        ]);
    }
}
