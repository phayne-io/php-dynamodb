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
 * Enum Select
 *
 * @package Phayne\DynamoDB\Enum
 */
enum Select: string
{
    /**
     * Returns all the item attributes from the specified table or index.
     */
    case ALL_ATTRIBUTES = 'ALL_ATTRIBUTES';

    /**
     * Allowed only when querying an index. Retrieves all attributes that have been projected into the index.
     */
    case ALL_PROJECTED_ATTRIBUTES = 'ALL_PROJECTED_ATTRIBUTES';

    /**
     * Returns only the attributes listed in AttributesToGet.
     */
    case SPECIFIC_ATTRIBUTES = 'SPECIFIC_ATTRIBUTES';

    /**
     * Returns the number of matching items, rather than the matching items themselves.
     */
    case COUNT = 'COUNT';
}
