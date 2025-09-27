@props([
'src',
'head',
'alt',
'text',
'button_text',
'btn_link'
])

<div id="ads-component" class="card" style="width: 100%">
    <div class="card-img-top">
        <img src="{{$src}}" alt="{{$alt}}" style="height: 50px; display: block; margin: 1rem; width: calc(100% - 2rem); object-fit: contain;">
    </div>
    <div class="card-body">
        <h5 class="card-title">{{$head}}</h5>
        <p class="card-text">{{$text}}</p>
        <a href="{{$btn_link}}" class="btn btn-outline-primary">{{$button_text}}</a>
    </div>
</div>