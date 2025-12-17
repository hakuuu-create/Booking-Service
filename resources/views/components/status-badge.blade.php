@props(['status'])

@php
    $styles = [
        'pending'     => ['bg' => 'bg-warning-subtle', 'text' => 'text-warning-emphasis', 'icon' => 'fa-clock'],
        'approved'    => ['bg' => 'bg-info-subtle', 'text' => 'text-info-emphasis', 'icon' => 'fa-check-circle'],
        'on_progress' => ['bg' => 'bg-primary-subtle', 'text' => 'text-primary-emphasis', 'icon' => 'fa-cog fa-spin'],
        'done'        => ['bg' => 'bg-success-subtle', 'text' => 'text-success-emphasis', 'icon' => 'fa-check-double'],
        'cancelled'   => ['bg' => 'bg-danger-subtle', 'text' => 'text-danger-emphasis', 'icon' => 'fa-times-circle'],
    ];

    $style = $styles[strtolower($status)] ?? ['bg' => 'bg-secondary-subtle', 'text' => 'text-secondary', 'icon' => 'fa-question'];
    $label = str_replace('_', ' ', ucfirst($status));
@endphp

<span class="badge rounded-pill border {{ $style['bg'] }} {{ $style['text'] }} px-3 py-2 fw-bold">
    <i class="fas {{ $style['icon'] }} me-1"></i> {{ $label }}
</span>