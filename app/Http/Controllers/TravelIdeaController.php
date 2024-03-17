<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TravelIdea;

class TravelIdeaController extends Controller
{
    public function index()
    {
        $travelIdeas = TravelIdea::paginate(10); // 获取所有旅游想法，分页，每页显示10条数据 
        return view('travelIdeas.show', compact('travelIdeas'));
    }

    public function create()
    {
        return view('travelIdeas.create_idea');
    }

    public function store(Request $request)
    {
        // 验证请求数据
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'destination' => 'required|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'tags' => 'nullable|string' // 标签是可选的
        ]);

        // 创建新的旅游想法记录
        $travelIdea = new TravelIdea($validatedData);
        $travelIdea->user_name = auth()->user()->name; // 假设用户已经登录
        $travelIdea->save(); // 保存记录到数据库

        // 重定向到旅游想法列表页面，并显示消息
        return redirect()->route('travel_ideas.index')->with('success', 'Travel idea added successfully.');
    }
}
