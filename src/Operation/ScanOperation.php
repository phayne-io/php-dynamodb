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
use Phayne\Collection\Collection;
use Phayne\Collection\CollectionInterface;
use Phayne\DynamoDB\Exception\ExceptionFactory;

/**
 * Class ScanOperation
 *
 * @package Phayne\DynamoDB\Operation
 */
class ScanOperation extends Search\AbstractSearchOperation
{
    #[Override]
    public function execute(): CollectionInterface
    {
        try {
            $results = $this->dynamoDbClient->scan($this->toArray());
            $rows = [];
            foreach ($results['Items'] as $item) {
                $rows[] = $this->marshaler->unmarshalItem($item);
            }
            return new Collection('array', $rows)->limit($this->offset);
        } catch (DynamoDbException $ex) {
            throw ExceptionFactory::factory($ex);
        }
    }
}
