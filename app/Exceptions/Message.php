<?php

namespace App\Exceptions;

class Message
{
    const FAILED_UPDATE = ['error' => "the resource you're trying to update doesn't exist"];
    const FAILED_DELETED = ['error' => "the resource you're trying to delete doesn't exist"];
    const FAILED_LOGIN = ['message' => 'Incorrect username or password'];
    const SUCCESSFUL_LOGOUT = ['message' => 'Logout Successful'];
    const SUCCESSFUL_REGISTER = ['message' => 'User successfully created'];
}
