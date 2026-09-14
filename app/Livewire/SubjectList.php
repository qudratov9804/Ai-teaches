<?php

namespace App\Livewire;

use App\Models\Subject;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class SubjectList extends Component
{
    public string $search = '';

    public function render(): View
    {
        $subjects = Subject::query()
            ->where('is_open', true)
            ->where('is_active', true)
            ->when($this->search !== '', function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%');
            })
            ->with('teacher')
            ->withCount('topics')
            ->orderBy('name')
            ->get();

        return view('livewire.subject-list', [
            'subjects' => $subjects,
        ]);
    }
}
