<?php

namespace App\Exceptions;

class Message
{
    const FAILED_UPDATE = ['error' => "the resource you're trying to update doesn't exist"];
    const FAILED_DELETED = ['error' => "the resource you're trying to delete doesn't exist"];
}
