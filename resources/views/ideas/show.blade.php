@extends('layouts.app')

@section('content')
<div class="container mt-4 pb-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('ideas.index') }}">Ideas</a></li>
            <li class="breadcrumb-item active">Idea Details</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h2 class="fw-bold text-dark">{{ $idea->title }}</h2>
                        <span class="badge bg-info text-dark">{{ $idea->category->name }}</span>
                    </div>

                    <div class="d-flex align-items-center mb-4 text-muted small">
                        <div class="me-3">
                            <i class="fas fa-user me-1"></i> {{ $idea->is_anonymous ? 'Anonymous' : $idea->user->name }}
                        </div>
                        <div>
                            <i class="fas fa-calendar-alt me-1"></i> {{ $idea->created_at->format('d/m/Y H:i') }}
                        </div>
                    </div>

                    <div class="idea-description mb-4" style="line-height: 1.8; white-space: pre-wrap;">
                        {{ $idea->description }}
                    </div>

                    @if($idea->file_path)
                    <div class="p-3 border rounded bg-light d-flex align-items-center justify-content-between">
                        <div>
                            <i class="fas fa-file-pdf fa-2x text-danger me-3"></i>
                            <span class="fw-bold">Supporting Document</span>
                        </div>
                        <a href="{{ asset('storage/' . $idea->file_path) }}" class="btn btn-outline-primary btn-sm" target="_blank">
                            <i class="fas fa-download"></i> Download
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Comments ({{ $idea->comments->count() }})</h5>
                </div>
                <div class="card-body">
                    @forelse($idea->comments as $comment)
                        <div class="d-flex mb-3 pb-3 border-bottom">
                            <div class="flex-shrink-0">
                                <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    {{ $comment->is_anonymous ? '?' : strtoupper(substr($comment->user->name, 0, 1)) }}
                                </div>
                            </div>
                            <div class="ms-3">
                                <div class="fw-bold">{{ $comment->is_anonymous ? 'Người dùng ẩn danh' : $comment->user->name }}</div>
                                <div class="text-muted small mb-1">{{ $comment->created_at->diffForHumans() }}</div>
                                <div>{{ $comment->content }}</div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-muted py-4">No comments yet. Be the first to comment!</p>
                    @endforelse

                    <form action="{{ route('comments.store', $idea->id) }}" method="POST" class="mt-4">
                        @csrf
                        <div class="mb-3">
                            <textarea name="content" class="form-control" rows="3" placeholder="Write a comment..." required></textarea>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="form-check">
                                <input type="checkbox" name="is_anonymous" value="1" class="form-check-input" id="anonShow">
                                <label class="form-check-label small text-muted" for="anonShow">Anonymous Comment</label>
                            </div>
                            <button type="submit" class="btn btn-primary px-4">Submit Comment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4 bg-primary text-white text-center p-4">
                <h6 class="text-uppercase small mb-3">Interaction Count</h6>
                <div class="d-flex justify-content-around">
                    <div>
                        <h3 class="mb-0">👍 {{ $idea->reactions->where('type', 1)->count() }}</h3>
                        <small>Agree</small>
                    </div>
                    <div>
                        <h3 class="mb-0">👎 {{ $idea->reactions->where('type', -1)->count() }}</h3>
                        <small>Disagree</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection