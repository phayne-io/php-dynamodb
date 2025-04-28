<?php

/**
 * This file is part of phayne-io/php-dynamodb and is proprietary and confidential.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 * @see       https://github.com/phayne-io/php-dynamodb for the canonical source repository
 * @copyright Copyright (c) 2024-2025 Phayne Limited. (https://phayne.io)
 */

declare(strict_types=1);

namespace Phayne\DynamoDB\Enum;

use UnexpectedValueException;

/**
 * Enum Operator
 *
 * @package Phayne\DynamoDB\Enum
 */
enum Operator: string
{
    case EQ = 'EQ';
    case NE = 'NE';
    case LTE = 'LTE';
    case LT = 'LT';
    case GTE = 'GTE';
    case GT = 'GT';
    case BETWEEN = 'BETWEEN';
    case NOT_NULL = 'NOT_NULL';
    case IS_NULL = 'NULL';
    case NOT_CONTAINS = 'NOT_CONTAINS';
    case CONTAINS = 'CONTAINS';
    case BEGINS_WITH = 'BEGINS_WITH';

    public static function parseExpression(string | Operator $operator, string $key): string
    {
        if (is_string($operator)) {
            $operator = self::tryFrom($operator);

            if (null === $operator) {
                throw new UnexpectedValueException('The provided operator is not supported.');
            }
        }

        return match ($operator) {
            self::EQ => sprintf('%s = :%s', $key, $key),
            self::NE => sprintf('%s <> :%s', $key, $key),
            self::LTE => sprintf('%s <= :%s', $key, $key),
            self::LT => sprintf('%s < :%s', $key, $key),
            self::GTE => sprintf('%s >= :%s', $key, $key),
            self::GT => sprintf('%s > :%s', $key, $key),
            self::BETWEEN => sprintf('%s BETWEEN :%s', $key, $key),
            self::CONTAINS => sprintf('contains(%s, :%s)', $key, $key),
            self::NOT_CONTAINS => sprintf('NOT contains(%s, :%s)', $key, $key),
            self::BEGINS_WITH => sprintf('begins_with(%s, :%s)', $key, $key),
            default => throw new UnexpectedValueException('The provided operator is not supported.'),
        };
    }
}
