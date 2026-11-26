@props([
    'label',
    'value',
    'meta' => null,
    'href' => null,
    'featured' => false,
])

<div class="{{ $featured ? 'rd-kpi-featured' : 'rd-card' }} p-4 h-100 d-flex flex-column justify-content-between">
    <div class="d-flex align-items-start justify-content-between gap-2 mb-3">
        <span class="fw-semibold small {{ $featured ? 'text-white' : 'text-dark' }}">
            {{ $label }}
        </span>
        @if ($href)
            <a href="{{ $href }}" class="rd-kpi-btn" title="Lihat detail">
                <i class="bi bi-arrow-up-right small"></i>
            </a>
        @endif
    </div>

    <div class="fs-2 fw-bold lh-1 mb-3 {{ $featured ? 'text-white' : 'text-dark' }}">
        {{ $value }}
    </div>

    @if ($meta)
        <div class="small d-flex align-items-center gap-1 {{ $featured ? 'text-white-50' : 'text-muted' }}">
            <i class="bi bi-info-circle"></i>
            <span>{{ $meta }}</span>
        </div>
    @endif
</div>
