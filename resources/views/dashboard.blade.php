@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold m-0"><i class="fas fa-lightbulb text-warning me-2"></i>Latest Ideas</h4>
            <a href="{{ route('ideas.create') }}" class="btn btn-primary btn-sm rounded-pill px-3">
                <i class="fas fa-plus me-1"></i> Submit Idea
            </a>
        </div>

        @foreach($ideas as $idea)
        <div class="card mb-3 border-0 shadow-sm hover-shadow transition">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width:40px; height:40px;">
                        {{ strtoupper(substr($idea->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold">{{ $idea->user->name }}</h6>
                        <small class="text-muted">{{ $idea->created_at->diffForHumans() }} • <span class="badge bg-light text-dark">{{ $idea->category->name }}</span></small>
                    </div>
                </div>
                <p class="card-text">{{ Str::limit($idea->content, 200) }}</p>
                
                <hr class="my-3 opacity-25">
                
                <div class="d-flex justify-content-between align-items-center">
                    <div class="btn-group">
                        <button class="btn btn-sm btn-outline-secondary border-0"><i class="far fa-thumbs-up me-1"></i> {{ $idea->reactions_count ?? 0 }}</button>
                        <button class="btn btn-sm btn-outline-secondary border-0"><i class="far fa-comment me-1"></i> {{ $idea->comments_count ?? 0 }} Comments</button>
                    </div>
                    <a href="{{ route('ideas.show', $idea->id) }}" class="btn btn-sm btn-link text-decoration-none">View Details</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="col-md-4">
        <div class="card bg-gradient-primary text-white p-4 mb-4" style="background: linear-gradient(45deg, #4e73df, #224abe); border-radius: 15px;">
            <h5 class="fw-bold">Welcome, {{ auth()->user()->name }}!</h5>
            <p class="small mb-0 opacity-75">Please contribute your creative ideas to help our school develop further.</p>
        </div>

        <div class="card shadow-sm p-3">
            <h6 class="fw-bold border-bottom pb-2 mb-3">Important Deadlines</h6>
            <div class="d-flex mb-2">
                <div class="me-3 text-primary"><i class="fas fa-calendar-alt"></i></div>
                <div>
                    <small class="text-muted d-block">Submit Ideas:</small>
                    <span class="fw-bold small text-danger">30/03/2026</span>
                </div>
            </div>
            <div class="d-flex">
                <div class="me-3 text-success"><i class="fas fa-clock"></i></div>
                <div>
                    <small class="text-muted d-block">Close Comments:</small>
                    <span class="fw-bold small text-danger">15/04/2026</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection