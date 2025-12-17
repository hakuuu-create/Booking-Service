@props(['title' => null, 'icon' => null, 'headerAction' => null])

<div {{ $attributes->merge(['class' => 'card border-0 shadow-sm rounded-4 overflow-hidden']) }}>
    @if($title)
        <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold m-0 text-dark">
                @if($icon) <i class="fas {{ $icon }} text-danger me-2"></i> @endif
                {{ $title }}
            </h6>
            @if($headerAction)
                <div>{{ $headerAction }}</div>
            @endif
        </div>
    @endif

    <div class="card-body p-4">
        {{ $slot }}
    </div>
</div>