@extends('layouts.app')

@section('content')
<div class="container mt-4 pb-5">
    {{-- 1. BẮT ĐẦU KHỐI QUẢN TRỊ CHO QA MANAGER --}}
    @if(auth()->user()->role_id == 2)
    <div class="alert alert-light border shadow-sm mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h5 class="fw-bold mb-1 text-dark"><i class="fas fa-user-shield me-2"></i>QA Manager Control Panel</h5>
            <p class="small text-muted mb-0">Download all submissions and supporting documents after the final closure date.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('ideas.export') }}" class="btn btn-success btn-sm">
                <i class="fas fa-file-csv me-1"></i> Export CSV
            </a>
            <a href="{{ route('ideas.exportZIP') }}" class="btn btn-dark btn-sm">
                <i class="fas fa-file-archive me-1"></i> Export ZIP
            </a>
        </div>
    </div>
    @endif

    {{-- 2. TIÊU ĐỀ VÀ NÚT SUBMIT --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">Discover Ideas</h2>
        <a href="{{ route('ideas.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus me-1"></i> Submit New Idea
        </a>
    </div>

    {{-- 3. BẢNG DANH SÁCH --}}
    <div class="table-responsive">
        <table class="table table-hover shadow-sm bg-white align-middle border">
            <thead class="table-dark">
                <tr>
                    <th style="width: 45%;">Idea Content</th>
                    <th>Information</th>
                    <th>Category</th>
                    <th class="text-center">Docs</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ideas as $idea)
                <tr>
                    <td class="p-3">
                        <div class="mb-2">
                            <h5 class="fw-bold mb-1 text-primary">{{ $idea->title }}</h5>
                            <p class="small text-muted mb-2">{{ Str::limit($idea->description, 100) }}</p>
                        </div>

                        {{-- LIKE/DISLIKE --}}
                        <div class="d-flex gap-2 mb-3">
                            <form action="{{ route('ideas.vote', $idea->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="1">
                                <button type="submit" class="btn btn-sm {{ $idea->reactions->where('user_id', auth()->id())->where('type', 1)->count() ? 'btn-success' : 'btn-outline-success' }}">
                                    👍 {{ $idea->reactions->where('type', 1)->count() }}
                                </button>
                            </form>

                            <form action="{{ route('ideas.vote', $idea->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="-1">
                                <button type="submit" class="btn btn-sm {{ $idea->reactions->where('user_id', auth()->id())->where('type', -1)->count() ? 'btn-danger' : 'btn-outline-danger' }}">
                                    👎 {{ $idea->reactions->where('type', -1)->count() }}
                                </button>
                            </form>
                        </div>

                        {{-- COMMENTS --}}
                        <div class="border-top pt-2">
                            <a class="text-decoration-none small fw-bold" data-bs-toggle="collapse" href="#comments-{{ $idea->id }}">
                                <i class="fas fa-comments me-1"></i> Comments ({{ $idea->comments->count() }})
                            </a>
                            
                            <div class="collapse mt-2" id="comments-{{ $idea->id }}">
                                <div class="bg-light p-2 rounded mb-2" style="max-height: 150px; overflow-y: auto;">
                                    @foreach($idea->comments as $comment)
                                        <div class="small mb-2 border-bottom pb-1">
                                            <span class="fw-bold">{{ $comment->is_anonymous ? 'Anonymous' : $comment->user?->name }}:</span>
                                            <span>{{ $comment->content }}</span>
                                        </div>
                                    @endforeach
                                </div>

                                <form action="{{ route('comments.store', $idea->id) }}" method="POST">
                                    @csrf
                                    <div class="input-group input-group-sm">
                                        <input type="text" name="content" class="form-control" placeholder="Comment..." required>
                                        <button class="btn btn-primary" type="submit">Send</button>
                                    </div>
                                    <div class="form-check small mt-1">
                                        <input type="checkbox" name="is_anonymous" value="1" class="form-check-input" id="anon-{{ $idea->id }}">
                                        <label class="form-check-label text-muted" for="anon-{{ $idea->id }}">Anonymous</label>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </td>
                    
                    <td>
                        <div class="small fw-bold text-nowrap">{{ $idea->is_anonymous ? 'Anonymous' : $idea->user->name }}</div>
                        <div class="text-muted small">{{ $idea->created_at->format('d/m/Y H:i') }}</div>
                    </td>
                    
                    <td>
                        <span class="badge bg-info text-dark">{{ $idea->category->name }}</span>
                    </td>
                    
                    <td class="text-center">
                        @if($idea->file_path)
                            <a href="{{ asset('storage/' . $idea->file_path) }}" class="btn btn-sm btn-outline-dark" target="_blank">
                                <i class="fas fa-download"></i>
                            </a>
                        @else
                            <span class="text-muted small">N/A</span>
                        @endif
                    </td>

                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-1">
                            {{-- Edit: Chỉ tác giả --}}
                            @if(auth()->id() == $idea->user_id)
                                <a href="{{ route('ideas.edit', $idea->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                            @endif

                            {{-- Delete: Chỉ Admin --}}
                            @if(auth()->user()->role_id == 1)
                                <form action="{{ route('ideas.destroy', $idea->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @endif

                            {{-- Placeholder nếu không có quyền gì --}}
                            @if(auth()->id() != $idea->user_id && auth()->user()->role_id != 1)
                                <span class="text-muted">-</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- PHÂN TRANG --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $ideas->links() }}
    </div>
</div>
@endsection