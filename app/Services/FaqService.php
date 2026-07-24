<?php

namespace App\Services;

use App\Models\Faq;
use Illuminate\Pagination\LengthAwarePaginator;

class FaqService
{
    public function getPaginatedList(): LengthAwarePaginator
    {
        return Faq::orderBy('sort_order')->paginate(10);
    }

    public function create(array $data): Faq
    {
        return Faq::create($data);
    }

    public function update(Faq $faq, array $data): Faq
    {
        $faq->update($data);
        return $faq->fresh();
    }

    public function delete(Faq $faq): void
    {
        $faq->delete();
    }

    public function getActiveFaqs(): \Illuminate\Support\Collection
    {
        return Faq::active()->orderBy('sort_order')->get();
    }
}