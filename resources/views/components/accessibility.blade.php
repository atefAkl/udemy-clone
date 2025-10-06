<section>
    <h4>{{__('courses.accessibility_dismissable_message.heading')}}</h4>
    <x-dismissable-note paragraph_text="{{__('courses.accessibility_dismissable_message.content')}}" btn_text="{{__('labels.dismiss')}}" />

</section>

<section>

    @forelse(__('courses.accessibility_tips_subtitles') as $title => $content)
    <h5>{{ $title }}</h5>
    <p>{{ $content }}</p>
    @empty
    @endforelse
</section>
<hr>
<section>
    <div class="accordion" id="accessibilityAccordion">
        @forelse(__('courses.accessibility_checklish') as $item)
        <div class="accordion-item">
            <h2 class="accordion-header" id="heading{{ $loop->iteration }}">
                <button class="accordion-button @if(!$loop->first) collapsed @endif" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $loop->iteration }}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="collapse{{ $loop->iteration }}">
                    {{ $item['title'] }}
                </button>
            </h2>
            <div id="collapse{{ $loop->iteration }}" class="accordion-collapse collapse @if($loop->first) show @endif" aria-labelledby="heading{{ $loop->iteration }}" data-bs-parent="#accessibilityAccordion">
                <div class="accordion-body">
                    <ul>
                        @foreach($item['items'] as $listItem)
                        <li>{{ $listItem }}</li>
                        @endforeach
                    </ul>
                    <p>{{ $item['note']['text'] }} <a href="{{ $item['note']['url'] }}" target="_blank">{{ $item['note']['anchor_text'] }}</a></p>
                    <hr>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="{{ $item['check_input']['name'] }}" id="{{ $item['check_input']['name'] }}">
                        <label class="form-check-label" for="{{ $item['check_input']['name'] }}">
                            {{ $item['check_input']['label'] }}
                        </label>
                    </div>
                </div>
            </div>
        </div>
        @empty

        @endforelse

        <h4>{{__('courses.resources_title')}}</h4>
        <ul>
            @foreach (__('courses.accessibility_tips_resources') as $resource)
            <li data-bs-toggle="tooltip" data-bs-title="{{$resource[2]}}"><a href="{{ $resource[1] }}"><b>{{ $resource[0] }}</b></a></li>
            @endforeach
        </ul>

    </div>
</section>