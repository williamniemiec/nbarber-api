<?php

namespace App\Exceptions;

use Exception;

/**
 * Should be thrown when a data integrity error happens.
 */
class DataIntegrityException extends Exception
{
    public function errorMessage() {
        return $this->getMessage();
      }
}
