@props([
'title',
'description',
'btn_url',
'btn_text',
'icon'
])
<div class="d-flex justify-content-between py-1 px-3 mb-3 rounded primary-gradient-bg" style="">
    <div class="col col-auto">
        <h1 class="fs-5 m-0 text-white">{{ $title }}</h1>
        <p class="mb-0 text-white">{{ $description }}</p>
    </div>
    <div class="col col-auto d-flex align-items-center">
        <a href="{{ $btn_url }}" class="btn btn-outline-light">
            <i class="{{$icon}} me-2"></i>
            {{ $btn_text }}
        </a>
    </div>
</div>