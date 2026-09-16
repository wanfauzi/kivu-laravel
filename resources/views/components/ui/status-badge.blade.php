@props(['value' => '', 'kind' => 'project', 'dot' => false])

@php
    $map = [
        'project' => [
            'DRAFT' => ['Belum Dibayar', 'warning'],
            'OPEN' => ['Terbuka', 'success'],
            'IN_PROGRESS' => ['Dikerjakan', 'primary'],
            'SUBMITTED' => ['Menunggu Review', 'warning'],
            'COMPLETED' => ['Selesai', 'info'],
            'CANCELLED' => ['Dibatalkan', 'neutral'],
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
            'RECORDED' => ['Tercatat', 'warning'],
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

<x-ui.badge :variant="$variant" :dot="$dot" {{ $attributes }}>{{ $label }}</x-ui.badge>
