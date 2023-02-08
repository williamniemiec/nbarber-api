<?php

namespace App\Exceptions;

use Exception;

/**
 * Should be thrown when an object has not been found.
 */
class ObjectNotFoundException extends Exception
{
    public function errorMessage() {
        return 'There is no object with such id: '.$this->getMessage();
      }
}
