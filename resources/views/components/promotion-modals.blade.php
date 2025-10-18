{{-- Banner URL Modal --}}
<div class="modal fade" id="bannerUrlModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{__('courses.enter_banner_url')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="url" class="form-control" id="bannerUrlModalInput" placeholder="https://example.com/image.jpg">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('courses.cancel')}}</button>
                <button type="button" class="btn btn-primary" id="submitBannerUrl">{{__('courses.submit')}}</button>
            </div>
        </div>
    </div>
</div>

{{-- Video URL Modal --}}
<div class="modal fade" id="videoUrlModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{__('courses.enter_video_url')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="url" class="form-control" id="videoUrlModalInput" placeholder="https://example.com/video.mp4">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('courses.cancel')}}</button>
                <button type="button" class="btn btn-primary" id="submitVideoUrl">{{__('courses.submit')}}</button>
            </div>
        </div>
    </div>
</div>