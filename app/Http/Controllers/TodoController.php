<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\TodoRequest;

class TodoController extends Controller
{
    public function index()
    {
        $todos = Todo::with('category')->get();
        $categories=Category::all();

        return view('index', compact('todos', 'categories'));
    }

    public function store(TodoRequest $request)
    {
        $todo = $request->only(['category_id', 'content']);

        Todo::create($todo);

        return redirect('/')
            ->with('message', 'Todoを作成しました');
    }

    public function update(Request $request)
    {
        $todo = $request->only(['content']);

        Todo::find($request->id)->update($todo);

        return redirect('/')
            ->with('message', 'Todoを更新しました');
    }

    public function destroy(Request $request)
    {
        Todo::find($request->id)->delete();

        return redirect('/')
            ->with('message', 'Todoを削除しました');
    }

    public function search(Request $request)
    {
        $todos = Todo::with('category')->CategorySearch($request->category_id)->KeywordSearch($request->keyword)->get();
        $categories = Category::all();

        return view('index', compact('todos', 'categories'));
    }
}
