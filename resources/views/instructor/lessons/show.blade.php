@extends('layouts.instructor-wide')
@section ('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('instructor.courses.index') }}">{{ __('courses.courses') }}</a></li>
<li class="breadcrumb-item"><a href="{{ route('instructor.courses.edit', $lesson->course_id) }}">{{ $lesson->course->title }}</a></li>
<li class="breadcrumb-item active" aria-current="page">{{ $lesson->title }}</li>
@endsection
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">

            <!-- Lesson Header -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-{{ $lesson->lesson_type === 'video' ? 'video' : 'book' }} me-2"></i>
                            {{ $lesson->title }}
                        </h4>
                        <div>
                            <a href="{{ route('instructor.courses.edit', $lesson->course_id) }}" class="btn btn-sm btn-light">
                                <i class="fas fa-arrow-left me-1"></i>{{ __('courses.back_to_courses') }}
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Lesson Info -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">{{ __('courses.basic_information') }}</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td class="fw-bold">{{ __('courses.lesson_type') }}:</td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ ucfirst($lesson->lesson_type) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">{{ __('courses.description') }}:</td>
                                    <td>{{ $lesson->description ?? __('courses.no_description') }}</td>
                                </tr>
                                @if($lesson->lesson_type === 'video')
                                <tr>
                                    <td class="fw-bold">{{ __('courses.video_source') }}:</td>
                                    <td>
                                        <span class="badge bg-{{ $lesson->video_source === 'upload' ? 'success' : 'primary' }}">
                                            {{ ucfirst($lesson->video_source) }}
                                        </span>
                                    </td>
                                </tr>
                                @if($lesson->duration)
                                <tr>
                                    <td class="fw-bold">{{ __('courses.duration') }}:</td>
                                    <td>{{ $lesson->duration }} {{ __('courses.min') }}</td>
                                </tr>
                                @endif
                                @endif
                                <tr>
                                    <td class="fw-bold">{{ __('courses.order') }}:</td>
                                    <td>{{ $lesson->sort_order }}</td>
                                </tr>
                            </table>
                        </div>

                        <!-- Media Preview -->
                        <div class="col-md-6">
                            @if($lesson->lesson_type === 'video')
                            <h6 class="text-muted mb-3">
                                <i class="fas fa-video me-2"></i>{{ __('courses.video_preview') }}
                            </h6>

                            @if($lesson->video_source === 'upload' && $lesson->video_file)
                            <div class="card shadow-sm">
                                <video controls class="w-100" style="border-radius: 0.25rem;"
                                    @if($lesson->thumbnail)
                                    poster="{{ Storage::url($lesson->thumbnail) }}"
                                    @endif>
                                    <source src="{{ Storage::url($lesson->video_file) }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                            @elseif($lesson->video_source === 'url' && $lesson->video_url)
                            <div class="card shadow-sm overflow-hidden">
                                <div class="ratio ratio-16x9">
                                    <iframe src="{{ $lesson->video_url }}" allowfullscreen></iframe>
                                </div>
                            </div>
                            @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                {{ __('courses.no_video_available') }}
                            </div>
                            @endif

                            @elseif($lesson->lesson_type === 'article')
                            <h6 class="text-muted mb-3">
                                <i class="fas fa-image me-2"></i>{{ __('courses.article_poster') }}
                            </h6>

                            @if($lesson->thumbnail)
                            <div class="card shadow-sm overflow-hidden">
                                <img src="{{ Storage::url($lesson->thumbnail) }}"
                                    alt="{{ $lesson->title }}"
                                    class="w-100"
                                    style="object-fit: cover; max-height: 300px;">
                            </div>
                            @else
                            <div class="card shadow-sm">
                                <div class="card-body text-center py-5 bg-light">
                                    <i class="fas fa-book-open fa-4x text-muted mb-3"></i>
                                    <p class="text-muted">{{ __('courses.no_poster_available') }}</p>
                                </div>
                            </div>
                            @endif
                            @endif
                        </div>
                    </div>

                    <!-- Assignments Section -->
                    @if($lesson->assignments && $lesson->assignments->count() > 0)
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">
                            <i class="fas fa-tasks me-2"></i>{{ __('courses.assignments') }}
                            <span class="badge bg-warning text-dark">{{ $lesson->assignments->count() }}</span>
                        </h6>
                        <div class="row g-3">
                            @foreach($lesson->assignments as $assignment)
                            <div class="col-md-6">
                                <div class="card border-warning">
                                    <div class="card-body">
                                        <h6 class="card-title">{{ $assignment->title }}</h6>
                                        <p class="card-text small text-muted">{{ $assignment->description }}</p>
                                        <div class="d-flex justify-content-between">
                                            <span class="badge bg-info">
                                                <i class="far fa-calendar me-1"></i>
                                                Due: {{ $assignment->due_days }} days
                                            </span>
                                            <span class="badge bg-success">
                                                <i class="fas fa-star me-1"></i>
                                                Max Score: {{ $assignment->max_score }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Files Section -->
                    @if($lesson->files && $lesson->files->count() > 0)
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">
                            <i class="fas fa-download me-2"></i>{{ __('courses.downloadable_files') }}
                            <span class="badge bg-success">{{ $lesson->files->count() }}</span>
                        </h6>
                        <div class="list-group">
                            @foreach($lesson->files as $file)
                            <a href="{{ Storage::url($file->file_path) }}"
                                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                                download="{{ $file->original_name }}">
                                <div>
                                    <i class="fas fa-file-{{ $file->file_type }} me-2 text-primary"></i>
                                    <strong>{{ $file->original_name }}</strong>
                                    <small class="text-muted ms-2">({{ $file->getReadableSize() }})</small>
                                </div>
                                <span class="badge bg-primary">
                                    <i class="fas fa-download"></i>
                                </span>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('instructor.courses.edit', $lesson->course_id) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>{{ __('courses.back_to_courses') }}
                        </a>
                        <div>
                            <button class="btn btn-primary" onclick="alert('Edit feature coming soon!')">
                                <i class="fas fa-edit me-1"></i>{{ __('courses.edit_lesson') }}
                            </button>
                            <button class="btn btn-danger"
                                data-bs-toggle="modal"
                                data-bs-target="#deleteLessonModal"
                                data-lesson-id="{{ $lesson->id }}">
                                <i class="fas fa-trash me-1"></i>{{ __('courses.delete_lesson') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection