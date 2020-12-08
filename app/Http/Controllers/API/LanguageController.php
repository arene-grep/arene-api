<?php

namespace App\Http\Controllers\API;

use App\Exceptions\Message;
use App\Http\Controllers\Controller;
use App\Http\Requests\SaveLanguageRequest;
use App\Models\Language;
use Exception;
use Illuminate\Http\Response;

class LanguageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return response(Language::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SaveLanguageRequest $request
     * @return Response
     */
    public function store(SaveLanguageRequest $request)
    {
        $language = new Language();
        $language->fill($request->validated())->save();
        return response($language, Response::HTTP_CREATED);
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
            $language = Language::findOrFail($id);
            return response($language);
        } catch (Exception $exception) {
            return response(null, Response::HTTP_NO_CONTENT);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SaveLanguageRequest $request
     * @param int $id
     * @return Response
     */
    public function update(SaveLanguageRequest $request, int $id)
    {
        try {
            $language = Language::findOrFail($id);
            $language->fill($request->validated())->save();
            return response(null);
        } catch (Exception $exception) {
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
            $language = Language::findOrFail($id);
            Language::destroy($id);
            $language->products()->update(['language_id' => null]);
            return response(null);
        } catch (Exception $exception) {
            return response(Message::FAILED_DELETED, Response::HTTP_NOT_FOUND);
        }
    }
}
