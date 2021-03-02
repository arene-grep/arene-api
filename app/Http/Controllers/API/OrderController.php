<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Exceptions\Message;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Buy;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Exception;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        return response(Order::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request): Response
    {
        $order = new Order();
        $order->user_id = $request->user()->id;
        $order->save();

        if ($request->input('buys')) {
            $buys = $request->input('buys');

            foreach($buys as $buy)
                $buy['order_id'] = $order->id;

            Buy::insert($buys);
        }
        return response($order, Response::HTTP_CREATED);
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
            $order = Order::findOrFail($id);
            return response($order);
        } catch (Exception) {
            return response(null, Response::HTTP_NO_CONTENT);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateOrderRequest $request
     * @param int $id
     * @return Response
     */
    //TODO check later if I should completely remove this function and its route
    public function update(UpdateOrderRequest $request, int $id): Response
    {
        try {
            $order = Order::findOrFail($id);
            $order->fill($request->validated())->save();
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
        if (Order::destroy($id))
            return response(null);
        else
            return response(Message::FAILED_DELETED, Response::HTTP_NOT_FOUND);
    }
}
