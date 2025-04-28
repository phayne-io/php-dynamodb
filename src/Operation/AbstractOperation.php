<?php

/**
 * This file is part of phayne-io/php-dynamodb and is proprietary and confidential.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 * @see       https://github.com/phayne-io/php-dynamodb for the canonical source repository
 * @copyright Copyright (c) 2024-2025 Phayne Limited. (https://phayne.io)
 */

declare(strict_types=1);

namespace Phayne\DynamoDB\Operation;

use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Marshaler;

/**
 * Class AbstractOperation
 *
 * @package Phayne\DynamoDB\Operation
 */
abstract class AbstractOperation implements OperationInterface
{
    public function __construct(public readonly DynamoDbClient $dynamoDbClient, public readonly Marshaler $marshaler)
    {
    }
}
