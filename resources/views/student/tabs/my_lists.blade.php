<!-- قوائمي المخصصة -->
<div id="myProfileTabs" class="container-fluid mb-3">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="p-3 mb-4">{{ __('student.my_lists') }}</h3>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createListModal">
                    <i class="fas fa-plus me-2"></i>{{ __('student.create_new_list') }}
                </button>
            </div>

            {{-- قوائم المستخدم --}}
            @if(isset($userLists) && $userLists->count())
            <div class="row" id="listsContainer">
                @foreach($userLists as $list)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm list-card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">{{ $list->name }}</h6>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a data-id="{{ $list->id }}" class="dropdown-item" href="#" onclick="editList(this.dataset.id)">{{ __('student.edit') }}</a></li>
                                    <li><a data-id="{{ $list->id }}" class="dropdown-item" href="#" onclick="shareList(this.dataset.id)">{{ __('student.share') }}</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a data-id="{{ $list->id }}" class="dropdown-item text-danger" href="#" onclick="deleteList(this.dataset.id)">{{ __('student.delete') }}</a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="card-body">
                            <p class="card-text text-muted">{{ $list->description ?? __('student.no_description') }}</p>

                            {{-- عدد الدورات في القائمة --}}
                            <div class="mb-3">
                                <small class="text-muted">{{ __('student.courses_count') }}: {{ $list->courses_count ?? 0 }}</small>
                            </div>

                            {{-- معاينة الدورات --}}
                            @if($list->courses && $list->courses->count())
                            <div class="row">
                                @foreach($list->courses->take(3) as $course)
                                <div class="col-4">
                                    <div class="card-img-small mb-2" style="height: 60px; background-image: url('{{ asset('storage/' . $course->thumbnail) }}'); background-size: cover; background-position: center; border-radius: 4px;"></div>
                                </div>
                                @endforeach
                            </div>
                            @if($list->courses->count() > 3)
                            <small class="text-muted">{{ __('student.and_more', ['count' => $list->courses->count() - 3]) }}</small>
                            @endif
                            @endif
                        </div>

                        <div class="card-footer">
                            <div class="d-grid gap-2">
                                <a href="{{ route('student.lists.show', $list) }}" class="btn btn-outline-primary btn-sm">{{ __('student.view_list') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-list fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">{{ __('student.no_lists') }}</h4>
                <p class="text-muted">{{ __('student.no_lists_desc') }}</p>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createListModal">
                    {{ __('student.create_first_list') }}
                </button>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- مودال إنشاء قائمة جديدة --}}
<div class="modal fade" id="createListModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('student.create_new_list') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="createListForm">
                <div class="modal-body">
                    @csrf
                    <div class="mb-3">
                        <label for="listName" class="form-label">{{ __('student.list_name') }}</label>
                        <input type="text" class="form-control" id="listName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="listDescription" class="form-label">{{ __('student.description') }}</label>
                        <textarea class="form-control" id="listDescription" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="isPublic" name="is_public">
                            <label class="form-check-label" for="isPublic">
                                {{ __('student.make_public') }}
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('student.cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('student.create_list') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('createListForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        fetch('/student/lists', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('{{ __("student.create_list_failed") }}');
                }
            });
    });

    function editList(listId) {
        // تحرير القائمة
        console.log('Editing list:', listId);
    }

    function shareList(listId) {
        // مشاركة القائمة
        const shareUrl = `${window.location.origin}/lists/${listId}`;

        if (navigator.share) {
            navigator.share({
                title: '{{ __("student.my_course_list") }}',
                url: shareUrl
            });
        } else {
            navigator.clipboard.writeText(shareUrl).then(() => {
                alert('{{ __("student.link_copied") }}');
            });
        }
    }

    function deleteList(listId) {
        if (confirm('{{ __("student.confirm_delete_list") }}')) {
            fetch(`/student/lists/${listId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('{{ __("student.delete_failed") }}');
                    }
                });
        }
    }
</script>