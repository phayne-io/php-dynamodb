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
 * Enum BillingMode
 *
 * @package Phayne\DynamoDB\Enum
 */
enum BillingMode: string
{
    /**
     * AWS recommends using PROVISIONED for predictable workloads. PROVISIONED sets the billing mode to Provisioned
     * Mode.
     */
    case PROVISIONED = 'PROVISIONED';

    /**
     * AWS recommends using PAY_PER_REQUEST for unpredictable workloads. PAY_PER_REQUEST sets the billing mode to
     * On-Demand Mode.
     */
    case PAY_PER_REQUEST = 'PAY_PER_REQUEST';
}
