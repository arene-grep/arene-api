<?php

namespace App\Http\Controllers\API;

use App\Exceptions\Message;
use App\Http\Controllers\Controller;
use App\Http\Requests\SaveTradingCardGameRequest;
use App\Models\TradingCardGame;
use Exception;
use Illuminate\Http\Response;

class TradingCardGameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        return response(TradingCardGame::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SaveTradingCardGameRequest $request
     * @return Response
     */
    public function store(SaveTradingCardGameRequest $request): Response
    {
        $trading_card_game = new TradingCardGame();
        $trading_card_game->fill($request->validated())->save();
        return response($trading_card_game, Response::HTTP_CREATED);
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
            $trading_card_game = TradingCardGame::findOrFail($id);
            return response($trading_card_game);
        } catch (Exception) {
            return response(null, Response::HTTP_NO_CONTENT);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SaveTradingCardGameRequest $request
     * @param int $id
     * @return Response
     */
    public function update(SaveTradingCardGameRequest $request, int $id): Response
    {
        try {
            $trading_card_game = TradingCardGame::findOrFail($id);
            $trading_card_game->fill($request->validated())->save();
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
        try {
            $trading_card_game = TradingCardGame::findOrFail($id);
            TradingCardGame::destroy($id);
            $trading_card_game->products()->update(['trading_card_game_id' => null]);
            return response(null);
        } catch (Exception) {
            return response(Message::FAILED_DELETED, Response::HTTP_NOT_FOUND);
        }
    }
}
