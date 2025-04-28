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

/**
 * Enum KeyType
 *
 * @package Phayne\DynamoDB\Enum
 */
enum KeyType: string
{
    /**
     * The partition key; also known as the HASH attribute.
     */
    case HASH = 'HASH';

    /**
     * The sort key; also known as the range attribute.
     */
    case RANGE = 'RANGE';
}
