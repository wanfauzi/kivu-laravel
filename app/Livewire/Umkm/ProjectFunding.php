<?php

namespace App\Livewire\Umkm;

use App\Livewire\Traits\GeneratesQr;
use App\Models\Project;
use App\Models\ProjectFund;
use App\Models\PaymentSimulation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;

class ProjectFunding extends Component
{
    use GeneratesQr;

    public Project $project;
    public bool $showPaymentModal = false;
    public ?PaymentSimulation $paymentSimulation = null;
    public ?string $qrDataUrl = null;

    public function mount(Project $project)
    {
        if ($project->owner_id !== Auth::id() || $project->status !== 'DRAFT') {
            abort(403);
        }
        $this->project = $project;
    }

    public function initiateFunding()
    {
        $existing = PaymentSimulation::where('project_id', $this->project->id)
            ->where('status', 'PENDING')
            ->first();

        if ($existing) {
            $this->paymentSimulation = $existing;
            $this->qrDataUrl = $this->generateQrDataUrl($existing->reference);
            $this->showPaymentModal = true;
            return;
        }

        $reference = 'QR-'.strtoupper(Str::random(8));
        $sim = PaymentSimulation::create([
            'project_id' => $this->project->id,
            'student_id' => null, // Funding saat project DRAFT, belum ada student
            'amount' => $this->project->budget,
            'reference' => $reference,
            'status' => 'PENDING',
        ]);
        $this->paymentSimulation = $sim;
        $this->qrDataUrl = $this->generateQrDataUrl($reference);
        $this->showPaymentModal = true;
    }

    public function simulateFundingPaid()
    {
        if (!$this->paymentSimulation || $this->paymentSimulation->status !== 'PENDING') return;

        DB::transaction(function () {
            $this->paymentSimulation->update(['status' => 'PAID', 'paid_at' => now()]);
            
            ProjectFund::create([
                'project_id' => $this->project->id,
                'umkm_id' => Auth::id(),
                'amount' => $this->project->budget,
                'reference' => $this->paymentSimulation->reference,
                'status' => 'LOCKED',
                'funded_at' => now(),
            ]);

            $this->project->update(['status' => 'OPEN']);
        });

        session()->flash('success', 'Proyek berhasil dipublikasikan!');
        return redirect()->route('umkm.my-projects');
    }

    public function closeFunding()
    {
        if ($this->paymentSimulation && $this->paymentSimulation->status === 'PENDING') {
            $this->paymentSimulation->delete();
        }
        $this->paymentSimulation = null;
        $this->showPaymentModal = false;
    }

    public function render()
    {
        return view('livewire.umkm.project-funding')->layout('components.layouts.dashboard');
    }
}
