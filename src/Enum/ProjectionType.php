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
 * Enum ProjectionType
 *
 * @package Phayne\DynamoDB\Enum
 */
enum ProjectionType: string
{
    /**
     * All the table attributes are projected into the index.
     *
     * @var string
     */
    case ALL = 'ALL';

    /**
     * Only the index and primary keys are projected into the index.
     */
    case KEYS_ONLY = 'KEYS_ONLY';

    /**
     * Only the specified table attributes are projected into the index.
     */
    case INCLUDES = 'INCLUDES';
}
