<?php

namespace App\Notifications;

use App\Models\Project;
use App\Models\User;
use Illuminate\Notifications\Notification;

class WorkSubmitted extends Notification
{
    public function __construct(public Project $project, public User $student) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => "{$this->student->name} mengirim hasil untuk \"{$this->project->title}\".",
            'url' => route('umkm.select-winner', $this->project->id),
            'icon' => 'upload',
            'tone' => 'info',
        ];
    }
}
