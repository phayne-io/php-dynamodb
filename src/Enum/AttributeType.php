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
 * Enum AttributeType
 *
 * @package Phayne\DynamoDB\Enum
 */
enum AttributeType: string
{
    /**
     * The string attribute type.
     */
    case STRING = 'S';

    /**
     * The number attribute type.
     */
    case NUMBER = 'N';

    /**
     * The binary attribute type.
     */
    case BINARY = 'B';
}
