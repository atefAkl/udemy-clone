@extends('layouts.instructor-wide')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('instructor.courses.index') }}">{{ __('courses.courses') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('instructor.courses.edit', $quiz->section->course_id) }}">{{ $quiz->section->course->title }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $quiz->title }}</li>
                </ol>
            </nav>

            <!-- Quiz Header -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-clipboard-question me-2"></i>
                            {{ $quiz->title }}
                        </h4>
                        <div>
                            <a href="{{ route('instructor.courses.edit', $quiz->section->course_id) }}" class="btn btn-sm btn-light">
                                <i class="fas fa-arrow-left me-1"></i>{{ __('courses.back_to_courses') }}
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Quiz Info -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">{{ __('courses.quiz_information') }}</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td class="fw-bold" style="width: 40%;">{{ __('courses.quiz_type') }}:</td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ __('courses.' . $quiz->quiz_type) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">{{ __('courses.description') }}:</td>
                                    <td>{{ $quiz->description ?? __('courses.no_description') }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">{{ __('courses.duration') }}:</td>
                                    <td>
                                        @if($quiz->duration_minutes)
                                            {{ $quiz->duration_minutes }} {{ __('courses.min') }}
                                        @else
                                            <span class="text-muted">{{ __('courses.leave_empty_no_limit') }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">{{ __('courses.pass_percentage') }}:</td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            {{ $quiz->pass_percentage }}%
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">{{ __('courses.max_attempts') }}:</td>
                                    <td>{{ $quiz->max_attempts }} {{ __('courses.attempts_allowed') }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">{{ __('courses.randomize_questions') }}:</td>
                                    <td>
                                        @if($quiz->randomize_questions)
                                            <span class="badge bg-success">{{ __('courses.yes') }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ __('courses.no') }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">{{ __('courses.show_results_after_completion') }}:</td>
                                    <td>
                                        @if($quiz->show_results)
                                            <span class="badge bg-success">{{ __('courses.yes') }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ __('courses.no') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <!-- Quiz Stats -->
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">{{ __('courses.quiz_statistics') }}</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="card border-success">
                                        <div class="card-body text-center">
                                            <i class="fas fa-question-circle fa-3x text-success mb-2"></i>
                                            <h3 class="mb-0">{{ $quiz->questions->count() }}</h3>
                                            <p class="text-muted mb-0">{{ __('courses.questions') }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card border-primary">
                                        <div class="card-body text-center">
                                            <i class="fas fa-star fa-3x text-primary mb-2"></i>
                                            <h3 class="mb-0">{{ $quiz->totalPoints }}</h3>
                                            <p class="text-muted mb-0">{{ __('courses.points') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Questions Section -->
                    @if($quiz->questions && $quiz->questions->count() > 0)
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">
                            <i class="fas fa-list-ol me-2"></i>{{ __('courses.quiz_questions') }}
                            <span class="badge bg-success">{{ $quiz->questions->count() }}</span>
                        </h6>

                        <div class="accordion" id="questionsAccordion">
                            @foreach($quiz->questions as $index => $question)
                            <div class="accordion-item mb-2">
                                <h2 class="accordion-header" id="heading{{ $question->id }}">
                                    <button class="accordion-button {{ $index === 0 ? '' : 'collapsed' }}" type="button" 
                                        data-bs-toggle="collapse" data-bs-target="#collapse{{ $question->id }}" 
                                        aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" aria-controls="collapse{{ $question->id }}">
                                        <div class="d-flex align-items-center w-100">
                                            <span class="badge bg-secondary me-3">Q{{ $index + 1 }}</span>
                                            <span class="me-3">
                                                @if($question->question_type === 'multiple_choice')
                                                    <i class="fas fa-list text-primary"></i>
                                                @elseif($question->question_type === 'true_false')
                                                    <i class="fas fa-check-double text-info"></i>
                                                @else
                                                    <i class="fas fa-edit text-warning"></i>
                                                @endif
                                            </span>
                                            <span class="flex-grow-1">{{ $question->question_text }}</span>
                                            <span class="badge bg-primary ms-3">{{ $question->points }} pts</span>
                                        </div>
                                    </button>
                                </h2>
                                <div id="collapse{{ $question->id }}" 
                                     class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" 
                                     aria-labelledby="heading{{ $question->id }}" 
                                     data-bs-parent="#questionsAccordion">
                                    <div class="accordion-body">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <p class="text-muted small mb-2">
                                                    <strong>{{ __('courses.question_type') }}:</strong>
                                                    <span class="badge bg-secondary">
                                                        {{ __('courses.' . $question->question_type) }}
                                                    </span>
                                                </p>

                                                @if($question->question_type === 'multiple_choice')
                                                    <h6 class="mt-3 mb-2">{{ __('courses.answer_options') }}:</h6>
                                                    <div class="list-group">
                                                        @foreach($question->answers as $answer)
                                                        <div class="list-group-item {{ $answer->is_correct ? 'list-group-item-success' : '' }}">
                                                            <div class="d-flex align-items-center">
                                                                @if($answer->is_correct)
                                                                    <i class="fas fa-check-circle text-success me-2"></i>
                                                                @else
                                                                    <i class="far fa-circle text-muted me-2"></i>
                                                                @endif
                                                                <span>{{ $answer->answer_text }}</span>
                                                                @if($answer->is_correct)
                                                                    <span class="badge bg-success ms-auto">{{ __('courses.correct_answer') }}</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        @endforeach
                                                    </div>

                                                @elseif($question->question_type === 'true_false')
                                                    <h6 class="mt-3 mb-2">{{ __('courses.correct_answer') }}:</h6>
                                                    @php
                                                        $correctAnswer = $question->answers->where('is_correct', true)->first();
                                                    @endphp
                                                    <div class="alert alert-success">
                                                        <i class="fas fa-check-circle me-2"></i>
                                                        <strong>{{ ucfirst($correctAnswer->answer_text ?? 'N/A') }}</strong>
                                                    </div>

                                                @elseif($question->question_type === 'fill_blank')
                                                    <h6 class="mt-3 mb-2">{{ __('courses.correct_answer') }}:</h6>
                                                    @php
                                                        $correctAnswer = $question->answers->where('is_correct', true)->first();
                                                    @endphp
                                                    <div class="alert alert-success">
                                                        <i class="fas fa-check-circle me-2"></i>
                                                        <strong>{{ $correctAnswer->answer_text ?? 'N/A' }}</strong>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @else
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle me-2"></i>
                        {{ __('courses.no_questions_yet') }}
                    </div>
                    @endif
                </div>

                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('instructor.courses.edit', $quiz->section->course_id) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>{{ __('courses.back_to_courses') }}
                        </a>
                        <div>
                            <button class="btn btn-primary" onclick="alert('Edit feature coming soon!')">
                                <i class="fas fa-edit me-1"></i>{{ __('courses.edit_quiz') }}
                            </button>
                            <button class="btn btn-danger"
                                data-bs-toggle="modal"
                                data-bs-target="#deleteQuizModal"
                                data-quiz-id="{{ $quiz->id }}"
                                data-quiz-title="{{ $quiz->title }}">
                                <i class="fas fa-trash me-1"></i>{{ __('courses.delete_quiz') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
