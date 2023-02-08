<?php

namespace App\Utils;
use InvalidArgumentException;

/**
 * Responsible for validating parameters.
 */
class ParameterValidator
{
    public static function validateRequiredParameter($value, string $label): void
    {
        if (!$value) {
            throw new InvalidArgumentException('Missing required parameter: '.$label);
        }
    }
}
