@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-calendar-alt me-2"></i> Academic Year Closure Dates</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="form-label fw-bold">Closure Date (Idea Submission)</label>
                        <input type="datetime-local" name="closure_date" class="form-control" 
                               value="{{ $closure_date ? date('Y-m-d\TH:i', strtotime($closure_date)) : '' }}" required>
                        <div class="form-text">Sau ngày này, nhân viên không thể nộp ý tưởng mới.</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Final Closure Date (Comments)</label>
                        <input type="datetime-local" name="final_closure_date" class="form-control" 
                               value="{{ $final_closure_date ? date('Y-m-d\TH:i', strtotime($final_closure_date)) : '' }}" required>
                        <div class="form-text">Sau ngày này, chức năng bình luận sẽ bị khóa hoàn toàn.</div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save me-2"></i> Update Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection