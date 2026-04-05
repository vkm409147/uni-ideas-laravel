@extends('layouts.app') {{-- Kế thừa từ resources/views/layouts/app.blade.php --}}

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-header text-white py-3" style="background: linear-gradient(45deg, #4e73df, #224abe);">
                    <h5 class="mb-0"><i class="fas fa-lightbulb me-2"></i> Contribute new ideas</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('ideas.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Tiêu đề --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                                   value="{{ old('title') }}" required placeholder="Example: Improve Wi-Fi system...">
                        </div>

                        {{-- Nội dung --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Description <span class="text-danger">*</span></label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                      rows="6" required placeholder="Detailed description of the solution...">{{ old('description') }}</textarea>
                        </div>

                        {{-- Danh mục --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Category <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                <option value="">-- Select appropriate category --</option>
                                @foreach($categories as $cat)
                                    @php $isExpired = $cat->closure_date && $cat->closure_date->isPast(); @endphp
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }} {{ $isExpired ? 'disabled' : '' }}>
                                        {{ $cat->name }} {{ $isExpired ? '-- [HẾT HẠN]' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- File đính kèm --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">Supporting Documents (Optional)</label>
                            <div class="input-group">
                                <input type="file" name="document" class="form-control">
                                <label class="input-group-text bg-light"><i class="fas fa-file-upload"></i></label>
                            </div>
                        </div>

                        {{-- Tùy chọn ẩn danh & Điều khoản --}}
                        <div class="row bg-light rounded p-3 mb-4 mx-0 border" style="border-style: dashed !important;">
                            <div class="col-md-6 border-end">
                                <div class="form-check form-switch mt-1">
                                    <input type="checkbox" name="is_anonymous" value="1" class="form-check-input" id="anon">
                                    <label class="form-check-label fw-bold" for="anon">Submit under anonymous name</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check mt-1">
                                    <input type="checkbox" name="terms" value="1" class="form-check-input" id="terms" required>
                                    <label class="form-check-label text-danger fw-bold" for="terms">Agree to Terms and Conditions</label>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('ideas.index') }}" class="btn btn-link text-secondary text-decoration-none">
    <i class="fas fa-arrow-left me-1"></i> Back to Ideas
</a>
                            <div>
                                <button type="reset" class="btn btn-outline-secondary me-2 px-4">Reset</button>
                                <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">Submit Idea</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection