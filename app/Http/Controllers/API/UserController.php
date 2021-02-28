<?php

namespace App\Http\Controllers\API;

use App\Exceptions\Message;
use App\Http\Controllers\Controller;
use App\Http\Requests\SaveUserRequest;
use App\Models\User;
use Auth;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $user = new User();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        return response(Message::SUCCESSFUL_REGISTER, Response::HTTP_CREATED);
    }

    public function login(Request $request)
    {
        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials))
            return response(Message::FAILED_LOGIN, Response::HTTP_UNAUTHORIZED);

        $user = User::where('email', $request->email)->first();

        $tokenResult = $user->createToken('authToken')->plainTextToken;

        return response(['token' => $tokenResult]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response(Message::SUCCESSFUL_LOGOUT);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        return response(User::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SaveUserRequest $request
     * @return Response
     */
    public function store(SaveUserRequest $request): Response
    {
        $user = new User();
        $user->fill($request->validated())->save();
        return response($user, Response::HTTP_CREATED);
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
            $user = User::findOrFail($id);
            return response($user);
        } catch (Exception) {
            return response(null, Response::HTTP_NO_CONTENT);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SaveUserRequest $request
     * @param int $id
     * @return Response
     */
    public function update(SaveUserRequest $request, int $id): Response
    {
        try {
            $user = User::findOrFail($id);
            $user->fill($request->validated())->save();
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
        if (User::destroy($id))
            return response(null);
        else
            return response(Message::FAILED_DELETED, Response::HTTP_NOT_FOUND);
    }
}
