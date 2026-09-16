<?php

namespace App\Notifications;

use App\Models\Project;
use Illuminate\Notifications\Notification;

class PaymentReleased extends Notification
{
    public function __construct(public Project $project, public int $amount) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Pembayaran Rp '.number_format($this->amount, 0, ',', '.')." masuk ke dompet Anda untuk \"{$this->project->title}\".",
            'url' => route('student.wallet'),
            'icon' => 'banknote',
            'tone' => 'success',
        ];
    }
}
