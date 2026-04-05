<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Idea; 
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use Illuminate\Support\Facades\Response;

class ExportController extends Controller
{
    public function exportCsv()
    {
        $fileName = 'ideas_export_' . date('Y-m-d') . '.csv';
        
        
$ideas = Idea::with(['user.department', 'category']) 
             ->withSum('reactions as total_score', 'type')
             ->get();

        $headers = [
            "Content-type"        => "text/csv; charset=utf-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['ID', 'Tiêu đề', 'Mô tả', 'Tác giả', 'Phòng ban', 'Danh mục', 'Điểm Vote', 'Ngày đăng'];

        $callback = function() use ($ideas, $columns) {
            $file = fopen('php://output', 'w');
            
            // Thêm BOM (Byte Order Mark) để Excel không bị lỗi font tiếng Việt
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); 
            
            // Ghi dòng tiêu đề cột
            fputcsv($file, $columns);

            // Ghi từng dòng dữ liệu
            foreach ($ideas as $idea) {
                fputcsv($file, [
                    $idea->id,
                    $idea->title,
                    strip_tags($idea->description), // Loại bỏ thẻ HTML nếu có
                    $idea->is_anonymous ? 'Ẩn danh' : ($idea->user->name ?? 'N/A'),
                    $idea->department->name ?? 'N/A',
                    $idea->category->name ?? 'N/A',
                    $idea->votes_sum_vote_type ?? 0,
                    $idea->created_at,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
    // 2. Hàm xuất ZIP (thêm vào đây)
    public function exportZip()
    {
        $zipFileName = 'documents_backup_' . date('Y-m-d') . '.zip';
        $zip = new ZipArchive;
        $publicPath = public_path($zipFileName);

        if ($zip->open($publicPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            // Lấy tất cả file trong thư mục storage/app/public/uploads/ideas
            $files = Storage::disk('public')->files('uploads/ideas');

            foreach ($files as $file) {
                $filePath = storage_path('app/public/' . $file);
                
                
                if (file_exists($filePath)) {
                    $relativeNameInZipFile = basename($file);
                    $zip->addFile($filePath, $relativeNameInZipFile);
                }
            }
            $zip->close();
        }

        // download() sẽ đẩy file về máy người dùng, 
        // deleteFileAfterSend(true) sẽ xóa file zip tạm trên server sau khi tải xong
        return response()->download($publicPath)->deleteFileAfterSend(true);
    }
}