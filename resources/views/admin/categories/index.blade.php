@extends('layouts.app')

@section('content')
<div class="container mt-4 pb-5">
    {{-- PHẦN TIÊU ĐỀ TRANG & CÁC NÚT TẢI DỮ LIỆU --}}
    <div class="row mb-4 align-items-end">
        <div class="col-md-6">
            <h3 class="fw-bold text-dark mb-0">Portfolio Management</h3>
            <p class="text-muted mb-0">Manage portfolio categories and set deadlines</p>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <div class="btn-group shadow-sm">
                <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
            <i class="fas fa-plus-circle me-1"></i> Create New Category
        </button>
                <a href="{{ route('ideas.export') }}" class="btn btn-success">
                    <i class="fas fa-file-csv me-1"></i> Export CSV
                </a>
                <a href="{{ route('ideas.exportZIP') }}" class="btn btn-warning">
                    <i class="fas fa-file-archive me-1"></i> Download ZIP Files
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- BÊN PHẢI: DANH SÁCH DANH MỤC --}}
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold text-dark"><i class="fas fa-list me-2"></i>Existing Categories</h6>
                    <span class="badge bg-light text-primary border">{{ count($categories) }} categories</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr class="small text-uppercase text-muted">
                                <th class="ps-3">Name</th>
                                <th class="text-center">Ideas</th>
                                <th>Submission Deadline</th>
                                <th class="text-end pe-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $cat)
<tr>
    <td class="ps-3 fw-bold">{{ $cat->name }}</td>
    <td class="text-center">
        <span class="badge bg-secondary rounded-pill">{{ $cat->ideas_count }}</span>
    </td>
    <td>
        <small class="text-muted d-block">Submission Deadline: {{ \Carbon\Carbon::parse($cat->closure_date)->format('d/m/Y H:i') }}</small>
        <small class="text-muted d-block">Comment Deadline: {{ \Carbon\Carbon::parse($cat->final_closure_date)->format('d/m/Y H:i') }}</small>
    </td>
    <td class="text-end pe-3">
    <div class="btn-group">
        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $cat->id }}">
            <i class="fas fa-edit"></i> Edit
        </button>

        @if($cat->ideas_count == 0)
        <form action="{{ route('categories.destroy', $cat->id) }}" method="POST" class="ms-1">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete?')">
                <i class="fas fa-trash"></i>
            </button>
        </form>
        @endif
    </div>

    <div class="modal fade text-start" id="editModal{{ $cat->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow">
                <form action="{{ route('categories.update', $cat->id) }}" method="POST">
                    @csrf
                    @method('PUT') 
                    
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Edit Category</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
    
    @if ($errors->any())
        <div class="alert alert-danger py-2 shadow-sm">
            <ul class="mb-0 small">
                @foreach ($errors->all() as $error)
                    <li><i class="fas fa-exclamation-circle me-1"></i> {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    </div>

    <div class="mb-3">
        <label class="form-label fw-bold">Category Name</label>
        <input type="text" name="name" class="form-control" value="{{ $cat->name }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label fw-bold text-danger">Submission deadline</label>
        <input type="datetime-local" name="closure_date" class="form-control" 
               value="{{ \Carbon\Carbon::parse($cat->closure_date)->format('Y-m-d\TH:i') }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label fw-bold text-warning">Comment deadline</label>
        <input type="datetime-local" name="final_closure_date" class="form-control" 
               value="{{ \Carbon\Carbon::parse($cat->final_closure_date)->format('Y-m-d\TH:i') }}" required>
    </div>
</div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary px-4">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</td>
@endforeach
                        </tbody>
                    </table>
                </div>
                @if(count($categories) == 0)
                    <div class="text-center py-5">
                        <i class="fas fa-folder-open fa-3x text-light mb-3"></i>
                        <p class="text-muted">No categories have been created yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
</div> <div class="modal fade" id="createCategoryModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered"> {{-- Thêm class này để hiện ra giữa màn hình cho đẹp --}}
            <div class="modal-content border-0 shadow-lg">
                <form action="{{ route('categories.store') }}" method="POST">
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="createModalLabel">Create New Category</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        {{-- Copy các ô Input từ cột bên trái cũ của bạn vào đây --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">CATEGORY NAME</label>
                            <input type="text" name="name" class="form-control" placeholder="E.g.: Learning Environment" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted text-danger">SUBMISSION DEADLINE (CLOSURE)</label>
                            <input type="datetime-local" name="closure_date" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted text-warning">COMMENT DEADLINE (FINAL)</label>
                            <input type="datetime-local" name="final_closure_date" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary px-4">Save Category <i class="fas fa-save ms-1"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection