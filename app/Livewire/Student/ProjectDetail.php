<?php

namespace App\Livewire\Student;

use App\Models\Application;
use App\Models\Project;
use App\Notifications\ApplicationReceived;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProjectDetail extends Component
{
    use WithFileUploads;

    public Project $project;

    public string $message = '';

    public ?int $bid_amount = null;

    public $lamaranFile = null;

    public bool $showModal = false;

    public bool $hasApplied = false;

    public function mount(Project $project)
    {
        $this->project = $project->load(['owner', 'category', 'skills']);
        $existing = Application::where('project_id', $project->id)
            ->where('student_id', Auth::id())->first();
        $this->hasApplied = $existing && $existing->status !== 'WITHDRAWN';
    }

    private function timelineSteps(): array
    {
        $status = $this->project->status;

        if ($status === 'CANCELLED') {
            return [
                ['label' => 'Dibuka', 'description' => 'Proyek dipublikasikan ke mahasiswa', 'state' => 'done'],
                ['label' => 'Dibatalkan', 'description' => 'Proyek dibatalkan oleh UMKM', 'state' => 'error'],
            ];
        }

        $order = ['OPEN', 'IN_PROGRESS', 'SUBMITTED', 'COMPLETED'];
        $labels = [
            'OPEN' => ['Dibuka', 'Menunggu lamaran mahasiswa'],
            'IN_PROGRESS' => ['Dikerjakan', 'Mahasiswa sedang mengerjakan proyek'],
            'SUBMITTED' => ['Menunggu Review', 'Hasil dikirim dan ditinjau UMKM'],
            'COMPLETED' => ['Selesai', 'Pembayaran diterima mahasiswa'],
        ];

        $current = array_search($status, $order, true);

        $steps = [];
        foreach ($order as $index => $key) {
            $steps[] = [
                'label' => $labels[$key][0],
                'description' => $labels[$key][1],
                'state' => $index < $current ? 'done' : ($index === $current ? 'current' : 'upcoming'),
            ];
        }

        return $steps;
    }

    public function openModal()
    {
        if ($this->hasApplied) {
            session()->flash('error', 'Anda sudah melamar proyek ini.');

            return;
        }
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function apply()
    {
        $minBid = $this->project->hasBudgetRange() ? $this->project->min_budget : 10000;
        $maxBid = $this->project->hasBudgetRange() ? $this->project->max_budget : $this->project->budget;

        $this->validate([
            'message' => 'nullable|string|max:1000',
            'bid_amount' => "required|integer|min:{$minBid}|max:{$maxBid}",
            'lamaranFile' => 'required|file|max:10240|mimes:pdf,doc,docx,zip,jpg,jpeg,png',
        ], [
            'bid_amount.min' => 'Penawaran minimal Rp '.number_format($minBid, 0, ',', '.').'.',
            'bid_amount.max' => $this->project->hasBudgetRange()
                ? 'Penawaran maksimal Rp '.number_format($maxBid, 0, ',', '.').'.'
                : 'Penawaran melebihi budget UMKM Rp '.number_format($maxBid, 0, ',', '.').'.',
            'bid_amount.required' => 'Penawaran harga wajib diisi.',
            'lamaranFile.required' => 'Berkas lamaran wajib diunggah.',
        ]);

        if ($this->hasApplied) {
            session()->flash('error', 'Anda sudah melamar proyek ini.');
            $this->showModal = false;

            return;
        }

        if (! Gate::allows('apply', [Application::class, $this->project])) {
            session()->flash('error', 'Anda tidak dapat melamar proyek ini.');
            $this->showModal = false;

            return;
        }

        $filePath = null;
        if ($this->lamaranFile) {
            $filePath = $this->lamaranFile->store('applications/'.Auth::id(), 'local');
        }

        $existing = Application::where('project_id', $this->project->id)
            ->where('student_id', Auth::id())
            ->first();

        if ($existing && $existing->status === 'WITHDRAWN') {
            if ($filePath && $existing->file_path) {
                Storage::disk('local')->delete($existing->file_path);
            }
            $existing->update([
                'status' => 'PENDING',
                'message' => $this->message,
                'bid_amount' => $this->bid_amount,
                'file_path' => $filePath ?? $existing->file_path,
            ]);
        } else {
            Application::create([
                'project_id' => $this->project->id,
                'student_id' => Auth::id(),
                'status' => 'PENDING',
                'message' => $this->message,
                'bid_amount' => $this->bid_amount,
                'file_path' => $filePath,
            ]);
        }

        $this->hasApplied = true;
        $this->showModal = false;

        $this->project->owner?->notify(new ApplicationReceived($this->project, Auth::user()));

        session()->flash('success', 'Lamaran berhasil dikirim!');
        $this->dispatch('toast', message: 'Lamaran berhasil dikirim!');
    }

    public function render()
    {
        return view('livewire.student.project-detail', [
            'timeline' => $this->timelineSteps(),
        ])->layout('components.layouts.dashboard');
    }
}
