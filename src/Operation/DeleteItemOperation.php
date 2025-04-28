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

use Aws\DynamoDb\Exception\DynamoDbException;
use Override;
use Phayne\DynamoDB\Exception\ExceptionFactory;

/**
 * Class DeleteItemOperation
 *
 * @package Phayne\DynamoDB\Operation
 */
class DeleteItemOperation extends Item\AbstractItemOperation
{
    #[Override]
    public function execute(): bool
    {
        try {
            $this->dynamoDbClient->deleteItem($this->toArray());
            return true;
        } catch (DynamoDbException $exception) {
            throw ExceptionFactory::factory($exception);
        }
    }
}
