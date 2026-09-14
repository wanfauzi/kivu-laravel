@props(['value' => '', 'kind' => 'project'])

@php
    $map = [
        'project' => [
            'OPEN' => ['Terbuka', 'success'],
            'IN_PROGRESS' => ['Dikerjakan', 'blue'],
            'SUBMITTED' => ['Menunggu Review', 'warning'],
            'COMPLETED' => ['Selesai', 'brand'],
            'CANCELLED' => ['Dibatalkan', 'error'],
        ],
        'application' => [
            'PENDING' => ['Menunggu', 'warning'],
            'ACCEPTED' => ['Diterima', 'success'],
            'REJECTED' => ['Ditolak', 'error'],
            'WITHDRAWN' => ['Dibatalkan', 'neutral'],
        ],
        'submission' => [
            'SUBMITTED' => ['Terkirim', 'warning'],
            'APPROVED' => ['Disetujui', 'success'],
            'REVISION' => ['Perlu Revisi', 'error'],
        ],
        'dispute' => [
            'OPEN' => ['Menunggu', 'warning'],
            'RESOLVED' => ['Diputuskan', 'success'],
            'REJECTED' => ['Ditolak', 'error'],
            'CANCELLED' => ['Dibatalkan', 'neutral'],
        ],
        'transaction' => [
            'RECORDED' => ['Tercatat', 'blue'],
            'SUCCESS' => ['Berhasil', 'success'],
            'REJECTED' => ['Ditolak', 'error'],
        ],
        'withdrawal' => [
            'PENDING' => ['Menunggu', 'warning'],
            'APPROVED' => ['Disetujui', 'success'],
            'REJECTED' => ['Ditolak', 'error'],
            'CANCELLED' => ['Dibatalkan', 'neutral'],
        ],
    ];

    $entry = $map[$kind][$value] ?? [ucfirst(strtolower((string) $value)), 'neutral'];
    $label = $entry[0];
    $variant = $entry[1];
@endphp

<x-ui.pill :tone="$variant" {{ $attributes }}>{{ $label }}</x-ui.pill>