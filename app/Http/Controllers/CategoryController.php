<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryRequest;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        return view('category', compact('categories'));
    }

    public function store(CategoryRequest $request)
    {
        $data = $request->only('name');

        Category::create($data);

        return redirect('/categories')->with('message', 'カテゴリーが作成されました');
    }

    public function update(CategoryRequest $request)
    {
        $category = $request->only('name');

        Category::find($request->id)->update($category);

        return redirect('/categories')->with('message', 'カテゴリーが更新されました');
    }
}
