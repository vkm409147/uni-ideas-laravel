<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReactionController extends Controller
{
    public function vote(Request $request, $ideaId)
{
    $userId = auth()->id();
    $type = $request->type; // 1 hoặc -1

    // Kiểm tra xem đã vote chưa
    $existing = \App\Models\Reaction::where('user_id', $userId)
                                   ->where('idea_id', $ideaId)
                                   ->first();

    if ($existing) {
        if ($existing->type == $type) {
            $existing->delete(); // Nếu bấm lại cái cũ thì xóa vote (un-vote)
        } else {
            $existing->update(['type' => $type]); // Nếu đổi từ Up sang Down hoặc ngược lại
        }
    } else {
        \App\Models\Reaction::create([
            'user_id' => $userId,
            'idea_id' => $ideaId,
            'type' => $type
        ]);
    }

    return back();
}
}
