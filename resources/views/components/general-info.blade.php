@props([
'course',
'categories',
'action',
])
<div class="p-3">
    <form action="{{$action}}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="input-group mb-3">
            <label for="title" class="input-group-text">{{__('courses.course_title')}}</label>
            <input type="text" class="form-control" id="title" name="title" value="{{old('title',$course->title)}}">
        </div>
        <div class="input-group mb-3">
            <label for="subtitle" class="input-group-text">{{__('courses.subtitle')}}</label>
            <input type="text" class="form-control" id="subtitle" name="subtitle" value="{{old('subtitle',$course->subtitle)}}">
        </div>
        <div class="form-floating mb-3">
            <textarea name="short_description" placeholder="{{__('courses.enter_short_description')}}" id="short_description"
                class="form-control">{{old('short_description',$course->short_description)}}</textarea>
            <label for="short_description">{{__('courses.short_description')}}</label>
        </div>
        <div class="form-floating mb-3">
            <textarea name="description" placeholder="{{__('courses.enter_course_description')}}" id="description" class="form-control">{{$course->description}}</textarea>
            <label for="description">{{__('courses.course_description')}}</label>
        </div>

        <div class="row">
            <div class="col col-12 col-md-6">
                <div class="input-group mb-3">
                    <label for="category" class="input-group-text">{{__('courses.category')}}</label>
                    <select name="category_id" id="category" class="form-select">
                        <option value="">{{__('courses.select_category')}}</option>
                        @foreach ($categories as $category)
                        <option value="{{$category->id}}" @if ($category->id == $course->category_id) selected @endif>{{$category->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col col-12 col-md-6">
                <div class="input-group mb-3">
                    <label for="language" class="input-group-text">{{__('courses.language')}}</label>
                    <select name="language" id="language" class="form-select">
                        <option value="">{{__('courses.select_language')}}</option>
                        @foreach (__('courses.languages') as $key => $language)
                        <option value="{{$key}}" @if ($key==$course->language) selected @endif>{{$language}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col col-12 col-md-6">
                <div class="input-group mb-3">
                    <label for="level" class="input-group-text">{{__('courses.level')}}</label>
                    <select name="level" id="level" class="form-select">
                        <option value="">{{__('courses.select_level')}}</option>
                        @foreach (__('courses.audience_levels') as $key => $level)
                        <option value="{{$key}}" @if ($key==$course->level) selected @endif>{{$level}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col col-12 col-md-6">
                <div class="input-group mb-3">
                    <label for="language" class="input-group-text">{{__('courses.price')}}</label>
                    <input type="number" name="price" id="price" class="form-control" value="{{$course->price}}">
                    <label data-bs-toggle="tooltip" data-bstitle="{{__('courses.price_input_tips')}}" class="input-group-text"><i class="fa fa-info-circle"></i></label>
                </div>
            </div>
        </div>


        <fieldset class="mt-4 pt-4 pb-0">
            <legend>{{__('courses.calculated_fields')}}</legend>
            <style>
                .card-body {
                    border: 1px solid #ccc;
                    border-radius: 5px;
                    background-color: #fff;
                    padding: 1rem;
                    margin-bottom: 1rem;
                    transition: all 0.3s ease-in-out;
                    text-align: center;
                }

                .card-body h3 {
                    font-weight: bold;
                }

                .card-body:hover {
                    box-shadow: 0 0 5px 2px rgba(0, 0, 0, 0.2);
                }
            </style>
            <div class="row">
                <div class="col col-12 col-sm-6 col-md-3">
                    <div class="card-body p-3">
                        <h3>15<sup>hrs</sup></h3>
                        {{__('courses.duration')}}
                    </div>
                </div>
                <div class="col col-12 col-sm-6 col-md-3">
                    <div class="card-body p-3">
                        <h3>4</h3>
                        {{__('courses.total_units')}}
                    </div>
                </div>
                <div class="col col-12 col-sm-6 col-md-3">
                    <div class="card-body p-3">
                        <h3>23</h3>
                        {{__('courses.total_lessons')}}
                    </div>
                </div>
                <div class="col col-12 col-sm-6 col-md-3">
                    <div class="card-body p-3">
                        <h3>4000<sup>+</sup></h3>
                        {{__('courses.total_enrollments')}}
                    </div>
                </div>
                <div class="col col-12 col-sm-6 col-md-3">
                    <div class="card-body p-3">
                        <h3>Yes</h3>
                        {{__('courses.has_certificate')}}
                    </div>
                </div>
                <div class="col col-12 col-sm-6 col-md-3">
                    <div class="card-body p-3">
                        <h3>Yes</h3>
                        {{__('courses.has_quizes')}}
                    </div>
                </div>
                <div class="col col-12 col-sm-6 col-md-3">
                    <div class="card-body p-3">
                        <h3>Yes</h3>
                        {{__('courses.has_training')}}
                    </div>
                </div>
                <div class="col col-12 col-sm-6 col-md-3">
                    <div class="card-body p-3">
                        <h3><i class="fa-solid fa-infinity"></i></h3>
                        {{__('courses.access_type')}}
                    </div>
                </div>
            </div>
        </fieldset>

        <div class="card-footer border-1 border-top mt-3">
            <div class="btns d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-sm text-muted disabled">{{__('labels.previous')}}</button>
                <button type="reset" class="btn btn-sm btn-outline-warning">{{__('labels.reset')}}</button>
                <button type="submit" class="btn btn-sm btn-outline-success">{{__('labels.update')}}</button>
                <button type="button" class="btn btn-sm btn-outline-primary">{{__('labels.next')}}</button>
            </div>
        </div>
    </form>
</div>