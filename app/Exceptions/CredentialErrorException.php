<?php

namespace App\Exceptions;

use Exception;

class CredentialErrorException extends Exception
{
    public function __construct($message = null, $code = 404)
    {
        $message = $message ?: 'Please check the credentials';
        parent::__construct($message, $code);
    }
}
