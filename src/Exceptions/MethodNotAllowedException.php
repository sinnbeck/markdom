<?php

namespace Sinnbeck\Markdom\Exceptions;

use Exception;

class MethodNotAllowedException extends Exception
{
    protected $message = 'This method is not allowed';
}