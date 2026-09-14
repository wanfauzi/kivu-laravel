<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;

class Users extends Component
{
    use WithPagination;

    public string $search = '';
    public string $role = '';
    public string $status = '';

    public ?int $pendingUserId = null;
    public string $pendingAction = '';
    public bool $confirming = false;
    public ?int $viewingKtmId = null;

    protected $queryString = ['search', 'role', 'status'];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedRole(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function confirmAction(int $id, string $action)
    {
        $target = User::findOrFail($id);
        Gate::authorize($action, $target);
        $this->pendingUserId = $id;
        $this->pendingAction = $action;
        $this->confirming = true;
    }

    public function cancelAction()
    {
        $this->pendingUserId = null;
        $this->pendingAction = '';
        $this->confirming = false;
    }

    public function runAction()
    {
        $target = User::findOrFail($this->pendingUserId);
        Gate::authorize($this->pendingAction, $target);

        switch ($this->pendingAction) {
            case 'verifyKtm':
                $target->update(['status' => 'active', 'status_before_suspend' => null, 'student_verified_at' => now()]);
                session()->flash('success', 'KTM diverifikasi, pengguna diaktifkan.');
                break;

            case 'revokeKtm':
                $target->update(['status' => 'pending_ktm', 'status_before_suspend' => null, 'student_verified_at' => null]);
                session()->flash('success', 'Verifikasi KTM dibatalkan, pengguna kembali menunggu.');
                break;

            case 'suspend':
                $target->update([
                    'status' => 'suspended',
                    'status_before_suspend' => $target->status === 'pending_ktm' ? 'pending_ktm' : 'active',
                ]);
                session()->flash('success', 'Pengguna disuspend.');
                break;

            case 'activate':
                $target->update([
                    'status' => $target->status_before_suspend ?? 'active',
                    'status_before_suspend' => null,
                ]);
                session()->flash('success', 'Pengguna diaktifkan kembali.');
                break;
        }

        $this->cancelAction();
    }

    public function viewKtm(int $id)
    {
        $this->viewingKtmId = $id;
    }

    public function closeViewKtm()
    {
        $this->viewingKtmId = null;
    }

    public function render()
    {
        $query = User::query();

        if ($this->search !== '') {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        if (in_array($this->role, ['student', 'umkm', 'admin'], true)) {
            $query->where('role', $this->role);
        }

        if (in_array($this->status, ['active', 'pending_ktm', 'suspended'], true)) {
            $query->where('status', $this->status);
        }

        return view('livewire.admin.users', [
            'users' => $query->latest()->paginate(10),
        ])->layout('components.layouts.dashboard');
    }
}