<?php

function renderStatusBadge(string $status): string
{
    $status = strtolower($status);
    $palette = [
        'active' => 'success',
        'inactive' => 'warning',
        'pending' => 'secondary',
        'upcoming' => 'info',
        'ongoing' => 'primary',
        'completed' => 'success',
    ];

    $class = $palette[$status] ?? 'secondary';

    return '<span class="badge badge-' . htmlspecialchars($class) . '">' . htmlspecialchars(ucfirst($status)) . '</span>';
}

function formatDate(string $date): string
{
    if (empty($date)) {
        return '—';
    }

    return date('d M Y', strtotime($date));
}

function formatTime(string $time): string
{
    if (empty($time)) {
        return '—';
    }

    return date('h:i A', strtotime($time));
}
