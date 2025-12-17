@props(['type' => 'submit', 'icon' => null])

<button type="{{ $type }}" {{ $attributes->merge(['class' => 'btn btn-danger rounded-pill fw-bold px-4 shadow-sm']) }} 
    style="background: linear-gradient(135deg, #cc0000 0%, #990000 100%); border: none; transition: transform 0.2s;">
    
    @if($icon)
        <i class="fas {{ $icon }} me-2"></i>
    @endif
    
    {{ $slot }}
</button>

<style>
    button[type="{{ $type }}"]:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(204, 0, 0, 0.3) !important;
    }
</style>