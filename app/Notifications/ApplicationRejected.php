<?php

namespace App\Notifications;

use App\Models\Project;
use Illuminate\Notifications\Notification;

class ApplicationRejected extends Notification
{
    public function __construct(public Project $project, public ?string $note = null) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => "Lamaran Anda untuk \"{$this->project->title}\" belum diterima.",
            'url' => route('student.my-applications'),
            'icon' => 'x',
            'tone' => 'danger',
        ];
    }
}
