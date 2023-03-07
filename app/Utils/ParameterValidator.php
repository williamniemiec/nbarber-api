<?php
/*
 * Copyright (c) William Niemiec.
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */

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
