<?php


namespace App\Helpers;

use App\Models\User;

class ApiHeaders
{
    static function getToken()
    {
        $user = User::factory()->create();
        return $user->createToken('authToken')->plainTextToken;
    }

    static function getAuth()
    {
        return [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . ApiHeaders::getToken()
        ];
    }

    static function getGuest()
    {
        return ['Accept' => 'application/json'];
    }
}
