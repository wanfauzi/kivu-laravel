<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\User;

class StudentTrust
{
    public static function summary(User $student): array
    {
        $completedProjects = Transaction::where('student_id', $student->id)
            ->where('type', 'payment')
            ->where('status', 'SUCCESS')
            ->whereNotNull('project_id')
            ->distinct()
            ->count('project_id');

        $reviews = $student->relationLoaded('reviewsReceived')
            ? $student->reviewsReceived
            : $student->reviewsReceived()->get();

        $ratingAvg = $reviews->count() ? round($reviews->avg('rating'), 1) : null;

        return [
            'verified' => $student->isVerifiedStudent(),
            'rating_avg' => $ratingAvg,
            'reviews_count' => $reviews->count(),
            'completed_projects' => $completedProjects,
            'member_since' => $student->created_at,
        ];
    }
}
