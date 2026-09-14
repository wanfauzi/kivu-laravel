<?php

namespace App\Livewire\Student;

use App\Models\Portfolio;
use App\Models\Review;
use App\Models\User;
use App\Services\StudentTrust;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Profile extends Component
{
    use WithFileUploads;

    public $ktm;
    public string $name = '';
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $bio = '';
    public string $skillsInput = '';

    public ?int $editingPortfolioId = null;
    public string $p_title = '';
    public string $p_description = '';
    public string $p_url = '';
    public $p_file;
    public bool $showPortfolioModal = false;

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->bio = (string) $user->bio;
        $this->skillsInput = implode(', ', $user->skills ?? []);
    }

    public function saveKtm()
    {
        $this->validate([
            'ktm' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $user = Auth::user();

        $extension = $this->ktm->getClientOriginalExtension();
        $path = $this->ktm->storeAs('ktm/' . $user->id, 'ktm_' . time() . '.' . $extension, 'local');

        if ($user->ktm_path && Storage::disk('local')->exists($user->ktm_path)) {
            Storage::disk('local')->delete($user->ktm_path);
        }

        $user->update(['ktm_path' => $path]);

        $this->reset('ktm');
        session()->flash('success', 'KTM berhasil diunggah. Menunggu verifikasi admin.');
    }

    public function updateProfile()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'skillsInput' => 'nullable|string|max:400',
            'current_password' => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $skills = $this->parseSkills($this->skillsInput);

        $user = Auth::user();

        $data = [
            'name' => $this->name,
            'bio' => $this->bio !== '' ? $this->bio : null,
            'skills' => $skills,
        ];

        if ($this->password !== '') {
            if (!Hash::check($this->current_password, $user->password)) {
                $this->addError('current_password', 'Password saat ini salah.');
                return;
            }
            $data['password'] = Hash::make($this->password);
            $this->reset(['current_password', 'password', 'password_confirmation']);
        }

        $user->update($data);
        session()->flash('success', 'Profil berhasil diperbarui.');
    }

    private function parseSkills(string $input): array
    {
        return collect(explode(',', $input))
            ->map(fn ($s) => trim($s))
            ->filter()
            ->unique()
            ->take(10)
            ->map(fn ($s) => mb_substr($s, 0, 30))
            ->values()
            ->all();
    }

    public function openPortfolioModal()
    {
        $this->resetPortfolioForm();
        $this->showPortfolioModal = true;
    }

    public function editPortfolio(int $id)
    {
        $item = Portfolio::where('student_id', Auth::id())->findOrFail($id);
        Gate::authorize('manage', [Portfolio::class, $item]);

        $this->editingPortfolioId = $id;
        $this->p_title = $item->title;
        $this->p_description = (string) $item->description;
        $this->p_url = (string) $item->url;
        $this->showPortfolioModal = true;
    }

    public function closePortfolioModal()
    {
        $this->showPortfolioModal = false;
        $this->resetPortfolioForm();
    }

    private function resetPortfolioForm(): void
    {
        $this->editingPortfolioId = null;
        $this->p_title = '';
        $this->p_description = '';
        $this->p_url = '';
        $this->reset('p_file');
    }

    public function savePortfolio()
    {
        Gate::authorize('manage', [Portfolio::class, null]);

        $this->validate([
            'p_title' => 'required|string|max:255',
            'p_description' => 'nullable|string|max:1000',
            'p_url' => 'nullable|url|max:255',
            'p_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
        ]);

        $existing = $this->editingPortfolioId
            ? Portfolio::where('student_id', Auth::id())->findOrFail($this->editingPortfolioId)
            : null;

        if (empty($this->p_url) && !$this->p_file && !$existing?->file_path) {
            $this->addError('p_url', 'Isi tautan URL atau unggah file.');
            return;
        }

        if (!$existing && Portfolio::where('student_id', Auth::id())->count() >= 12) {
            session()->flash('error', 'Maksimal 12 item portofolio.');
            return;
        }

        $data = [
            'title' => $this->p_title,
            'description' => $this->p_description !== '' ? $this->p_description : null,
            'url' => $this->p_url !== '' ? $this->p_url : null,
        ];

        if ($this->p_file) {
            if ($existing?->file_path) {
                Storage::disk('public')->delete($existing->file_path);
            }
            $data['file_path'] = $this->p_file->store('portfolio/' . Auth::id(), 'public');
        }

        if ($existing) {
            $existing->update($data);
        } else {
            Portfolio::create($data + ['student_id' => Auth::id()]);
        }

        $this->closePortfolioModal();
        session()->flash('success', 'Portofolio disimpan.');
    }

    public function deletePortfolio(int $id)
    {
        $item = Portfolio::where('student_id', Auth::id())->findOrFail($id);
        Gate::authorize('manage', [Portfolio::class, $item]);

        if ($item->file_path) {
            Storage::disk('public')->delete($item->file_path);
        }
        $item->delete();

        session()->flash('success', 'Item portofolio dihapus.');
    }

    public function render()
    {
        $user = User::whereKey(Auth::id())->first();
        $portfolios = Portfolio::where('student_id', Auth::id())->latest()->get();

        $reviews = Review::with('reviewer')
            ->where('reviewee_id', Auth::id())
            ->latest()
            ->get();

        return view('livewire.student.profile', [
            'user' => $user,
            'reviews' => $reviews,
            'avgRating' => $reviews->count() ? round($reviews->avg('rating'), 1) : null,
            'portfolios' => $portfolios,
            'trust' => StudentTrust::summary($user),
        ])->layout('components.layouts.dashboard');
    }
}
