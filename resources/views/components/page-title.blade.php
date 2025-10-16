@props([
'title',
'description',
'btn_url',
'btn_text',
'icon'
])
<div class="d-flex justify-content-between py-2 px-3 mb-3 rounded primary-bg shadow-sm" style="">
    <div class="col col-auto">
        <h1 class="fs-5 m-0">{{ $title }}</h1>
        <p class="mb-0">{{ $description }}</p>
    </div>
    <div class="col col-auto d-flex align-items-center">
        <a href="{{ $btn_url }}" class="border primary-dark-bg py-1 px-2 rounded" data-bs-toggle="tooltip" data-bs-title="{{ $btn_text }}">
            <i class="{{$icon ?? 'fa fa-home'}} text-light"></i>
        </a>
    </div>
</div>