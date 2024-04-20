<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TravelIdea;
use App\Models\Comment;
use App\Models\CityCode;

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

        // 检查目的地是否有效
        $city = CityCode::where('city', 'like', '%' . $validatedData['destination'] . '%')->first();
        if (!$city) {
            // 如果是AJAX请求，返回错误信息的JSON响应
            if ($request->ajax()) {
                return response()->json(['error' => 'The selected destination is invalid.'], 422);
            }
            // 如果不是AJAX请求，执行重定向操作并带有错误信息
            return redirect()->back()->withErrors(['destination' => 'The selected destination is invalid.']);
        }

        // 创建新的旅游想法记录
        $travelIdea = new TravelIdea($validatedData);
        $travelIdea->user_name = auth()->user()->name; // 假设用户已经登录
        $travelIdea->save(); // 保存记录到数据库

        // 如果是AJAX请求，返回成功信息的JSON响应
        if ($request->ajax()) {
            return response()->json(['success' => 'Travel idea added successfully.']);
        }

        // 重定向到旅游想法列表页面，并显示消息
        return redirect()->route('travel_ideas.index')->with('success', 'Travel idea added successfully.');
    }
    public function showUserIdeas()
    {
        // 假设已经有了登陆用户
        $username = auth()->user()->name;

        // 根据用户名来获取旅游想法
        $userIdeas = TravelIdea::where('user_name', $username)->get();

        // 传递这些想法到视图
        return view('travelIdeas.edit', compact('userIdeas'));
    }
    // 显示edit页面
    public function edit($idea)
    {
        // 假设您的模型主键已经设置为 'idea_id'
        $idea = TravelIdea::findOrFail($idea);
        // 将想法数据传递到编辑视图
        return view('travelIdeas.edit_idea', compact('idea'));
    }

    // 更改ideas
    public function update(Request $request, $id)
    {
        // 直接通过 $id 找到对应的旅游想法
        $idea = TravelIdea::findOrFail($id);

        // 安全检查：确保当前登录的用户是想法的创建者
        if ($idea->user_name !== auth()->user()->name) {
            abort(403, 'Unauthorized action.');
        }

        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'destination' => 'required|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'tags' => 'nullable|string' // 标签是可选的
        ]);

        $idea->update($validatedData);

        return redirect()->route('travel_ideas.index')->with('success', 'Idea updated successfully');
    }

}
