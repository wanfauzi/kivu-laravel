<?php

namespace App\Livewire\Umkm;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Profile extends Component
{
    public string $name = '';

    public string $business_name = '';

    public string $current_password = '';

    public string $password = '';

    public string $password_confirmation = '';

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->business_name = (string) $user->business_name;
    }

    public function updateProfile()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'business_name' => 'nullable|string|max:255',
            'current_password' => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        $data = [
            'name' => $this->name,
            'business_name' => $this->business_name !== '' ? $this->business_name : null,
        ];

        if ($this->password !== '') {
            if (! Hash::check($this->current_password, $user->password)) {
                $this->addError('current_password', 'Password saat ini salah.');

                return;
            }

            $data['password'] = Hash::make($this->password);
            $this->reset(['current_password', 'password', 'password_confirmation']);
        }

        $user->update($data);

        session()->flash('success', 'Profil berhasil diperbarui.');
    }

    public function render()
    {
        return view('livewire.umkm.profile')
            ->layout('components.layouts.dashboard');
    }
}
