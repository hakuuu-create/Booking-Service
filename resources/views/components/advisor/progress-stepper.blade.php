@props(['status'])

@php
    $steps = ['pending', 'approved', 'on_progress', 'done'];
    $currentIdx = array_search($status, $steps);
    $labels = ['Booking Masuk', 'Disetujui', 'Dikerjakan', 'Selesai'];
@endphp

<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4">
        <div class="position-relative d-flex justify-content-between align-items-center">
            {{-- Garis Background --}}
            <div class="position-absolute top-50 start-0 w-100 translate-middle-y bg-light" style="height: 4px; z-index: 0;"></div>
            
            {{-- Garis Progress Merah --}}
            <div class="position-absolute top-50 start-0 translate-middle-y bg-danger transition-width" 
                 style="height: 4px; z-index: 0; width: {{ ($currentIdx / (count($steps)-1)) * 100 }}%;"></div>

            @foreach($steps as $index => $step)
                <div class="text-center position-relative z-1" style="width: 80px;">
                    <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2 fw-bold shadow-sm
                        {{ $index <= $currentIdx ? 'bg-danger text-white' : 'bg-white text-muted border' }}"
                        style="width: 40px; height: 40px; transition: all 0.3s;">
                        @if($index < $currentIdx) <i class="fas fa-check"></i>
                        @else {{ $index + 1 }}
                        @endif
                    </div>
                    <small class="fw-bold {{ $index <= $currentIdx ? 'text-danger' : 'text-muted' }}" style="font-size: 0.75rem;">
                        {{ $labels[$index] }}
                    </small>
                </div>
            @endforeach
        </div>
    </div>
</div>