<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Idea;

class CommentController extends Controller
{
    public function store(Request $request, $ideaId)
    {
        $idea = Idea::findOrFail($ideaId);
    $category = $idea->category;

    // Kiểm tra nếu đã quá ngày Final Closure Date
    if ($category->final_closure_date && now() > $category->final_closure_date) {
        return redirect()->back()->withErrors(['content' => 'The comment period for ideas in this category has ended.']);
    }
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        Comment::create([
            'user_id' => auth()->id(),
            'idea_id' => $ideaId,
            'content' => $request->content,
            'is_anonymous' => $request->has('is_anonymous'),
        ]);

        // Phần gửi Email cho tác giả ý tưởng sẽ thêm ở bước sau
        
        return back()->with('success', 'Your comment has been submitted!');
    }
}