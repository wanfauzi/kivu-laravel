<?php

namespace App\Notifications;

use App\Models\Project;
use App\Models\User;
use Illuminate\Notifications\Notification;

class ApplicationReceived extends Notification
{
    public function __construct(public Project $project, public User $student) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => "{$this->student->name} melamar proyek \"{$this->project->title}\".",
            'url' => route('umkm.manage-applicants', $this->project->id),
            'icon' => 'file-text',
            'tone' => 'info',
        ];
    }
}
