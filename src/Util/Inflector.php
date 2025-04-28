<?php

/**
 * This file is part of phayne-io/php-dynamodb and is proprietary and confidential.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 * @see       https://github.com/phayne-io/php-dynamodb for the canonical source repository
 * @copyright Copyright (c) 2024-2025 Phayne Limited. (https://phayne.io)
 */

declare(strict_types=1);

namespace Phayne\DynamoDB\Util;

use Laminas\Filter\Word\DashToCamelCase;

use function is_string;

/**
 * Class Inflector
 *
 * @package Phayne\DynamoDB\Util
 */
final class Inflector
{
    public static function camelize(string $input): string
    {
        $filter = new DashToCamelCase();

        if (! is_string($value = $filter->filter($input))) {
            return $input;
        }

        return $value;
    }
}
