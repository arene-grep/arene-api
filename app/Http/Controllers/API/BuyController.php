<?php

namespace App\Http\Controllers\API;

use App\Exceptions\Message;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBuyRequest;
use App\Http\Requests\UpdateBuyRequest;
use App\Models\Buy;
use http\Exception;
use Illuminate\Http\Response;

class BuyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        return response(Buy::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreBuyRequest $request
     * @return Response
     */
    public function store(StoreBuyRequest $request): Response
    {
        $buy = new Buy();
        $buy->fill($request->validated())->save();
        return response($buy, Response::HTTP_CREATED);
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
            $buy = Buy::findOrFail($id);
            return response($buy);
        } catch (Exception) {
            return response(null, Response::HTTP_NO_CONTENT);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateBuyRequest $request
     * @param int $id
     * @return Response
     */
    public function update(UpdateBuyRequest $request, int $id): Response
    {
        try {
            $buy = Buy::findOrFail($id);
            $buy->fill($request->validated())->save();
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
        if (Buy::destroy($id))
            return response(null);
        else
            return response(Message::FAILED_DELETED, Response::HTTP_NOT_FOUND);
    }
}
