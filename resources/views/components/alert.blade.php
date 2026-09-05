@if($errors->any())
<div class="alert alert-danger shadow-sm border-0 mb-4 rounded-3">
    <div class="d-flex align-items-center mb-2">
        <i class="fas fa-exclamation-circle me-2"></i>
        <span class="fw-bold">يرجى تصحيح الأخطاء التالية:</span>
    </div>
    <ul class="mb-0 ps-3">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@if(session('success'))
<div class="alert alert-success shadow-sm border-0 mb-4 rounded-3">
    <i class="fas fa-check-circle me-2"></i>
    {{ session('success') }}
</div>
@endif