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
use Phayne\DynamoDB\Util\Inflector;

/**
 * Class BatchWriteItemOperation
 *
 * @package Phayne\DynamoDB\Operation
 */
class BatchWriteItemOperation extends Item\AbstractBatchItemOperation
{
    /**
     * Registers the DynamoDb client, Marshaler, and the mapping of tables and primary keys with this object.
     *
     * @param DynamoDbClient $client The DynamoDB client.
     * @param Marshaler $marshaler The Marshaler.
     * @param array $deleteItems OPTIONAL The table(s) and associated primary key attributes to use when deleting.
     * @param array $putItems OPTIONAL The table(s) and associated items to add.
     */
    public function __construct(
        DynamoDbClient $client,
        Marshaler $marshaler,
        array $deleteItems = [],
        array $putItems = []
    ) {
        parent::__construct($client, $marshaler);

        if (! empty($deleteItems)) {
            $this->setDeleteRequest($deleteItems);
        }

        if (! empty($putItems)) {
            $this->setPutRequest($putItems);
        }
    }

    /**
     * Registers the DeleteRequest options.
     *
     * @see BatchWriteItemOperation::setWriteRequest()
     * @param array $deleteItems The table(s) and associated primary key attributes to use when deleting.
     * @return BatchWriteItemOperation
     */
    public function setDeleteRequest(array $deleteItems): BatchWriteItemOperation
    {
        return $this->setWriteRequest('Delete', $deleteItems);
    }

    /**
     * Registers the PutRequest options.
     *
     * @see BatchWriteItemOperation::setWriteRequest()
     * @param array $putItems The table(s) and associated items to add.
     * @return BatchWriteItemOperation
     */
    public function setPutRequest(array $putItems): BatchWriteItemOperation
    {
        return $this->setWriteRequest('Put', $putItems);
    }

    /**
     * Registers request options.
     *
     * @param string $type The request type (Put or Delete).
     * @param array $requestItems The table(s) and associated options.
     * @return BatchWriteItemOperation
     */
    private function setWriteRequest(string $type, array $requestItems): BatchWriteItemOperation
    {
        $type = Inflector::camelize($type);
        $keyName = ($type == 'Put') ? 'Item' : 'Key';

        foreach ($requestItems as $tableName => $items) {
            if (!isset($this->requestItems[$tableName])) {
                $this->requestItems[$tableName] = [];
            }

            foreach ($items as $item) {
                $this->requestItems[$tableName][] = [
                    sprintf('%sRequest', $type) => [
                        $keyName => $this->marshaler->marshalItem($item)
                    ]
                ];
            }
        }
        return $this;
    }

    #[Override]
    public function execute(): bool
    {
        try {
            $this->dynamoDbClient->batchWriteItem($this->toArray());
            return true;
        } catch (DynamoDbException $exception) {
            throw ExceptionFactory::factory($exception);
        }
    }
}
