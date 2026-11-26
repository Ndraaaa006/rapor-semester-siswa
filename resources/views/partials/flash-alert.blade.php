@if (session('success'))
    <div class="no-print alert alert-success alert-dismissible fade show rounded-3 small fw-semibold mb-0" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('error'))
    <div class="no-print alert alert-danger alert-dismissible fade show rounded-3 small fw-semibold mb-0" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if ($errors->any())
    <div class="no-print alert alert-danger alert-dismissible fade show rounded-3 small fw-semibold mb-0" role="alert">
        {{ $errors->first() }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
