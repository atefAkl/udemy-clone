@props([
'course',
])
<div>
    <div style="position: relative;">
        <div class="sticky" style="position: sticky; top: 0; width: 18rem; border-radius: 1rem; overflow: hidden;">

            <img src="{{asset('images/course_id_1.webp')}}" alt="" width="100%">
            {{$course->title}}
        </div>
    </div>
    <!-- <video
        id="my-video"
        class="video-js vjs-default-skin shadow rounded"
        controls
        preload="auto"
        width="500"
        height="300"
        poster="{{asset('images/platform-logo.jpg')}}"
        data-setup="{}">
        <source src="{{asset('images/GF_2026.mp4')}}" type="video/mp4">
        <track kind="subtitles" src="{{asset('images/GF_2026.mp4')}}" srclang="en" label="English">
    </video> -->
</div>