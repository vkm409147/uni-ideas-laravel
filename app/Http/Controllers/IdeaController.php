<?php


namespace App\Http\Controllers;

use App\Models\Idea;
use App\Models\User;
use App\Notifications\NewIdeaNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class IdeaController extends Controller
{
    public function edit($id)
{
    $idea = Idea::findOrFail($id);

    // Chốt chặn bảo mật: Chỉ tác giả mới được sửa
    if (auth()->id() !== $idea->user_id) {
        abort(403, 'Bạn không có quyền sửa ý tưởng này!');
    }

    $categories = Category::all(); // Để hiện lại danh sách chọn category
    return view('ideas.edit', compact('idea', 'categories'));
}

/**
 * Update the specified idea in storage.
 */
public function update(Request $request, $id)
{
    $idea = Idea::findOrFail($id);

    if (auth()->id() !== $idea->user_id) {
        abort(403);
    }

    $request->validate([
        'title' => 'required|max:255',
        'description' => 'required',
        'category_id' => 'required|exists:categories,id',
    ]);

    $idea->update([
        'title' => $request->title,
        'description' => $request->description,
        'category_id' => $request->category_id,
    ]);

    return redirect()->route('ideas.index')->with('success', 'Idea updated successfully!');
}

/**
 * Remove the specified idea from storage.
 */
public function destroy($id)
{
    $idea = Idea::findOrFail($id);

    // Chỉ Admin (Role 1) mới được xóa
    if (auth()->user()->role_id != 1) {
        abort(403, 'Chỉ Admin mới có quyền xóa!');
    }

    $idea->delete();

    return redirect()->route('ideas.index')->with('success', 'Idea deleted successfully!');
}
    public function show($id)
{
    // Lấy idea cùng với các thông tin liên quan (người đăng, danh mục, bình luận)
    $idea = Idea::with(['user', 'category', 'comments.user'])->findOrFail($id);
    
    return view('ideas.show', compact('idea'));
}

    public function index()
{
    $ideas = Idea::with(['user', 'category', 'comments.user', 'reactions']) // Thêm comments và reactions vào đây
                 ->latest()
                 ->paginate(5);
    return view('ideas.index', compact('ideas'));
}
    public function store(Request $request)
    {
        $category = Category::findOrFail($request->category_id);

    // Kiểm tra nếu đã quá ngày Closure Date
    if ($category->closure_date && now() > $category->closure_date) {
        return redirect()->back()->withErrors(['category_id' => 'Unfortunately, the deadline for submitting ideas has passed for this category!']);
    }
        // 1. Validate dữ liệu (Bao gồm bắt buộc đồng ý T&C và file)
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'category_id' => 'required|exists:categories,id',
            'terms' => 'accepted', // Bắt buộc tích chọn Terms and Conditions
            'document' => 'nullable|mimes:pdf,doc,docx,zip|max:5120', // Tối đa 5MB
        ]);

        // 2. Xử lý upload file (nếu có)
        $filePath = null;
        if ($request->hasFile('document')) {
            $fileName = time() . '_' . $request->file('document')->getClientOriginalName();
            $filePath = $request->file('document')->storeAs('uploads/ideas', $fileName, 'public');
        }

        // 3. Lưu vào Database
        $idea = Idea::create([
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'user_id' => auth()->id(),
            'department_id' => auth()->user()->department_id,
            'file_path' => $filePath,
            'is_anonymous' => $request->has('is_anonymous'),
        ]);

        // 4. Gửi Email cho QA Coordinator của phòng ban đó
        $coordinator = User::where('role_id', 3) // Role 3 là QA Coordinator
                           ->where('department_id', auth()->user()->department_id)
                           ->get();

        Notification::send($coordinator, new NewIdeaNotification($idea));

        return redirect()->back()->with('success', 'Your idea has been successfully submitted.!');
    }

    

public function create()
{
    // Lấy tất cả danh mục để hiển thị trong dropdown (select box)
    $categories = \App\Models\Category::all();

    // Trả về view tạo ý tưởng (Bạn cần đảm bảo file này đã tồn tại)
    return view('ideas.create', compact('categories'));
}

     
}