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
use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;
use Override;
use Phayne\DynamoDB\Exception\ExceptionFactory;

/**
 * Class BatchGetItemOperation
 *
 * @package Phayne\DynamoDB\Operation
 */
class BatchGetItemOperation extends Item\AbstractBatchItemOperation
{
    /**
     * Registers the DynamoDb client, Marshaler, and the mapping of tables and primary keys with this object.
     *
     * @param DynamoDbClient $client The DynamoDB client.
     * @param Marshaler $marshaler The Marshaler.
     * @param array $primaryKeys The tables and primary keys.
     */
    public function __construct(DynamoDbClient $client, Marshaler $marshaler, array $primaryKeys)
    {
        parent::__construct($client, $marshaler);
        $this->setPrimaryKeys($primaryKeys);
    }

    public function setPrimaryKeys(array $primaryKeys): self
    {
        $requestItems = [];
        foreach ($primaryKeys as $tableName => $keys) {
            $requestItems[$tableName] = ['Keys' => []];
            foreach ($keys as $key) {
                $requestItems[$tableName]['Keys'][] = $this->marshaler->marshalItem($key);
            }
        }
        $this->setRequestItems($requestItems);
        return $this;
    }

    #[Override]
    public function execute(): array
    {
        try {
            $items = [];
            $result = $this->dynamoDbClient->batchGetItem($this->toArray());

            if (is_array($result['Responses'])) {
                foreach ($result['Responses'] as $itemArrays) {
                    $items = array_map(fn($itemArray) => $this->marshaler->unmarshalItem($itemArray), $itemArrays);
                }
            }
            return $items;
        } catch (DynamoDbException $exception) {
            throw ExceptionFactory::factory($exception);
        }
    }
}
