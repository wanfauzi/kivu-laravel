<?php

namespace App\Livewire\Public;

use App\Models\Review;
use App\Models\User;
use App\Services\StudentTrust;
use Livewire\Component;

class TalentProfile extends Component
{
    public User $student;

    public function mount(User $user)
    {
        if ($user->role !== 'student') {
            abort(404);
        }
        $this->student = $user;
    }

    public function render()
    {
        $portfolios = $this->student->portfolios()->get();
        $reviews = Review::with('reviewer')
            ->where('reviewee_id', $this->student->id)
            ->latest()
            ->get();

        $trust = StudentTrust::summary($this->student);

        $description = $this->student->bio
            ?: 'Profil mahasiswa talent KIVU — '.$trust['completed_projects'].' proyek selesai, '
                .($trust['rating_avg'] ? $trust['rating_avg'].' rating rata-rata' : 'belum ada rating').'.';

        return view('livewire.public.talent-profile', [
            'student' => $this->student,
            'portfolios' => $portfolios,
            'reviews' => $reviews,
            'trust' => $trust,
        ])->layout('layouts.public', [
            'title' => $this->student->name.' — Talent KIVU',
            'description' => $description,
        ]);
    }
}
