<?php

namespace App\Notifications;

use App\Models\Project;
use Illuminate\Notifications\Notification;

class ApplicationAccepted extends Notification
{
    public function __construct(public Project $project) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => "Selamat! Lamaran Anda untuk \"{$this->project->title}\" diterima.",
            'url' => route('student.my-applications'),
            'icon' => 'circle-check',
            'tone' => 'success',
        ];
    }
}
