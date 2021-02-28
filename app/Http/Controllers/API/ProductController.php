<?php

namespace App\Http\Controllers\API;

use App\Exceptions\Message;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Exception;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        return response(Product::filter()->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreProductRequest $request
     * @return Response
     */
    public function store(StoreProductRequest $request): Response
    {
        $product = new Product();
        $product->fill($request->validated())->save();
        return response($product, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show(int $id): Response
    {
        try {
            $category = Product::findOrFail($id);
            return response($category);
        } catch (Exception) {
            return response(null, Response::HTTP_NO_CONTENT);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateProductRequest $request
     * @param int $id
     * @return Response
     */
    public function update(UpdateProductRequest $request, int $id): Response
    {
        try {
            $product = Product::findOrFail($id);
            $product->fill($request->validated())->save();
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
    public function destroy(int $id): Response
    {
        if (Product::destroy($id))
            return response(null);
        else
            return response(Message::FAILED_DELETED, Response::HTTP_NOT_FOUND);
    }
}
