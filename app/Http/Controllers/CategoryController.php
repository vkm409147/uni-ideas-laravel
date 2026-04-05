<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class CategoryController extends Controller
{
    public function dashboard()
{
    // Thống kê số ý tưởng theo từng Phòng ban
    $stats = \App\Models\Department::withCount('ideas')->get();

    // Chuẩn bị dữ liệu cho Biểu đồ (Chart.js)
    $labels = $stats->pluck('name');
    $data = $stats->pluck('ideas_count');

    return view('admin.dashboard', compact('stats', 'labels', 'data'));
}
    public function exportZIP()
{
    $zipFileName = 'all_documents_' . date('Y-m-d') . '.zip';
    $zip = new \ZipArchive;
    $path = public_path($zipFileName); // Tạo file tạm ở thư mục public

    // Lấy tất cả các ý tưởng có file đính kèm
    $ideas = \App\Models\Idea::whereNotNull('file_path')->get();

    if ($zip->open($path, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
        foreach ($ideas as $idea) {
            // Đường dẫn thực tế của file trong thư mục storage
            $filePath = storage_path('app/public/' . $idea->file_path);
            
            if (file_exists($filePath)) {
                // Thêm file vào ZIP, đổi tên file theo tiêu đề ý tưởng để dễ phân biệt
                $extension = pathinfo($filePath, PATHINFO_EXTENSION);
                $newName = str_replace(' ', '_', $idea->title) . '.' . $extension;
                $zip->addFile($filePath, $newName);
            }
        }
        $zip->close();
    }

    // Trả về file ZIP để tải và xóa file tạm sau khi gửi xong
    return response()->download($path)->deleteFileAfterSend(true);
}
public function exportCSV()
{
    $fileName = 'ideas_export_' . date('Y-m-d') . '.csv';
    $ideas = \App\Models\Idea::with(['user.department', 'category'])->get();
    $headers = [
        "Content-type"        => "text/csv; charset=utf-8",
        "Content-Disposition" => "attachment; filename=$fileName",
        "Pragma"              => "no-cache",
        "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
        "Expires"             => "0"
    ];

    $columns = ['Title', 'Author', 'Category', 'Description', 'Created At'];

    $callback = function() use($ideas, $columns) {
        $file = fopen('php://output', 'w'); 
        // Thêm BOM để hiển thị đúng tiếng Việt trong Excel
        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
        fputcsv($file, ['Title', 'Author', 'Department', 'Category', 'Description', 'Created At']);
        foreach ($ideas as $idea) {
            fputcsv($file, [
                $idea->title,
                $idea->is_anonymous ? 'Anonymous' : $idea->user->name,
                $idea->user->department->name ?? 'N/A',
                $idea->category->name,
                $idea->description,
                $idea->created_at
            ]);
        }
        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}
public function index()
    {
        $categories = Category::withCount('ideas')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories,name',
            'closure_date' => 'required|date',
            'final_closure_date' => 'required|date|after:closure_date',
        ]);

        Category::create($request->all());
        return back()->with('success', 'New category added!');
    }
    public function destroy($id)
{
    
    $category = Category::findOrFail($id);

    // Kiểm tra xem có idea nào đang dùng category này không
    if ($category->ideas()->count() > 0) {
        return back()->with('error', 'Cannot delete category that has ideas!');
    }

    $category->delete();
    return back()->with('success', 'Category deleted successfully.');
    
}
public function update(Request $request, $id)
{
    $category = \App\Models\Category::findOrFail($id);

    $request->validate([
        'name' => 'required|unique:categories,name,' . $id,
        'closure_date' => 'required|date',
        // Hạn bình luận (final) BẮT BUỘC phải sau Hạn nộp (closure)
        'final_closure_date' => 'required|date|after:closure_date',
    ], [
        'final_closure_date.after' => 'Final closure date must be after the closure date!',
    ]);

    $category->update($request->all());

    return back()->with('success', 'Category updated successfully!');
}
}