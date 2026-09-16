<?php

namespace App\Notifications;

use App\Models\Project;
use Illuminate\Notifications\Notification;

class RevisionRequested extends Notification
{
    public function __construct(public Project $project, public string $note = '') {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => "UMKM meminta revisi untuk \"{$this->project->title}\".",
            'url' => route('student.submit-work', $this->project->id),
            'icon' => 'rotate-ccw',
            'tone' => 'warning',
        ];
    }
}
