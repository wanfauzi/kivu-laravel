<?php

namespace App\Livewire\Umkm;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Profile extends Component
{
    public string $name = '';
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function mount()
    {
        $this->name = Auth::user()->name;
    }

    public function updateProfile()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'current_password' => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if ($this->password !== '') {
            if (!Hash::check($this->current_password, $user->password)) {
                $this->addError('current_password', 'Password saat ini salah.');
                return;
            }

            $user->update([
                'name' => $this->name,
                'password' => Hash::make($this->password),
            ]);

            $this->reset(['current_password', 'password', 'password_confirmation']);
        } else {
            $user->update(['name' => $this->name]);
        }

        session()->flash('success', 'Profil berhasil diperbarui.');
    }

    public function render()
    {
        return view('livewire.umkm.profile')
            ->layout('components.layouts.dashboard');
    }
}
