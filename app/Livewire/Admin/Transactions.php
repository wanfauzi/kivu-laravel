<?php

namespace App\Livewire\Admin;

use App\Models\Transaction;
use Livewire\Component;
use Livewire\WithPagination;

class Transactions extends Component
{
    use WithPagination;

    public string $search = '';

    public string $type = '';

    public string $status = '';

    protected $queryString = ['search', 'type', 'status'];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedType(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Transaction::with(['student', 'project']);

        if ($this->search !== '') {
            $query->where(function ($q) {
                $q->whereHas('student', function ($s) {
                    $s->where('name', 'like', '%'.$this->search.'%');
                })->orWhereHas('project', function ($p) {
                    $p->where('title', 'like', '%'.$this->search.'%');
                });
            });
        }

        if (in_array($this->type, ['payment', 'withdrawal', 'refund'], true)) {
            $query->where('type', $this->type);
        }

        if (in_array($this->status, ['RECORDED', 'SUCCESS', 'REJECTED'], true)) {
            $query->where('status', $this->status);
        }

        return view('livewire.admin.transactions', [
            'transactions' => $query->latest()->paginate(10),
        ])->layout('components.layouts.dashboard');
    }
}
