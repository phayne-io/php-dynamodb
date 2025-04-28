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
 * Enum ReturnConsumedCapacityOption
 *
 * @package Phayne\DynamoDB\Enum
 */
enum ReturnConsumedCapacityOption: string
{
    /**
     * The response will include the aggregate ConsumedCapacity for the operation, together with ConsumedCapacity for
     * each table and secondary index that was accessed.
     */
    case INDEXES = 'INDEXES';

    /**
     * The response will include only the aggregate ConsumedCapacity for the operation.
     */
    case TOTAL = 'TOTAL';

    /**
     * No ConsumedCapacity details will be included in the response.
     */
    case NONE = 'NONE';
}
