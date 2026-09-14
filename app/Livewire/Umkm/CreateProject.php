<?php

namespace App\Livewire\Umkm;

use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CreateProject extends Component
{
    public string $title = '';
    public string $description = '';
    public int $budget = 0;

    public function store()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'budget' => 'required|numeric|min:100000',
        ]);

        Project::create([
            'owner_id' => Auth::id(),
            'title' => $this->title,
            'description' => $this->description,
            'budget' => $this->budget,
            'status' => 'OPEN',
        ]);

        session()->flash('success', 'Proyek berhasil dibuat!');
        return redirect()->route('umkm.my-projects');
    }

    public function render()
    {
        return view('livewire.umkm.create-project')
            ->layout('components.layouts.dashboard');
    }
}
