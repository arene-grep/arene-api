<?php

namespace App\Http\Controllers\API;

use App\Exceptions\Message;
use App\Http\Controllers\Controller;
use App\Http\Requests\SaveCategoryRequest;
use App\Models\Category;
use Exception;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return response(Category::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SaveCategoryRequest $request
     * @return Response
     */
    public function store(SaveCategoryRequest $request)
    {
        $category = new Category();
        $category->fill($request->validated())->save();
        return response($category, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show(int $id)
    {
        try {
            $category = Category::findOrFail($id);
            return response($category);
        } catch (Exception) {
            return response(null, Response::HTTP_NO_CONTENT);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SaveCategoryRequest $request
     * @param int $id
     * @return Response
     */
    public function update(SaveCategoryRequest $request, int $id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->fill($request->validated())->save();
            return response(null);
        } catch (Exception) {
            return response(Message::FAILED_UPDATE, Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy(int $id)
    {
        try {
            $category = Category::findOrFail($id);
            Category::destroy($id);
            $category->products()->update(['category_id' => null]);
            return response(null);
        } catch (Exception) {
            return response(Message::FAILED_DELETED, Response::HTTP_NOT_FOUND);
        }
    }
}
