@props(['name', 'label', 'type' => 'text', 'value' => '', 'placeholder' => '', 'icon' => null, 'readonly' => false])

<div class="mb-3">
    @if($label)
        <label for="{{ $name }}" class="form-label fw-bold text-secondary small text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">
            {{ $label }}
        </label>
    @endif

    <div class="input-group has-validation">
        @if($icon)
            <span class="input-group-text bg-white border-end-0 text-danger">
                <i class="fas {{ $icon }}"></i>
            </span>
        @endif
        
        <input 
            type="{{ $type }}" 
            name="{{ $name }}" 
            id="{{ $name }}" 
            value="{{ old($name, $value) }}"
            class="form-control {{ $icon ? 'border-start-0 ps-0' : '' }} @error($name) is-invalid @enderror"
            placeholder="{{ $placeholder }}"
            {{ $readonly ? 'readonly style=background-color:#f8f9fa' : '' }}
            {{ $attributes }}
        >
        
        @error($name)
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>