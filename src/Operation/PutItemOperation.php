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

use function array_merge;

/**
 * Class PutItemOperation
 *
 * @package Phayne\DynamoDB\Operation
 */
class PutItemOperation extends Item\AbstractItemOperation
{
    /**
     * @var array $itemData The item data.
     */
    private array $itemData = [];

    /**
     * Registers the DynamoDb client, Marshaler, table name, and item data with this object.
     *
     * @param DynamoDbClient $client The DynamoDb client.
     * @param Marshaler $marshaler The Marshaler.
     * @param string $tableName The table name.
     * @param array $itemData The item data.
     */
    public function __construct(DynamoDbClient $client, Marshaler $marshaler, string $tableName, array $itemData)
    {
        parent::__construct($client, $marshaler, $tableName);
        $this->setItemData($itemData);
    }

    /**
     * Registers the item data with this object.
     *
     * @param array $item The item data.
     * @return PutItemOperation This object.
     */
    public function setItemData(array $item): PutItemOperation
    {
        $this->itemData = $this->marshaler->marshalItem($item);
        return $this;
    }

    #[Override]
    public function execute(): bool
    {
        try {
            $this->dynamoDbClient->putItem($this->toArray());
            return true;
        } catch (DynamoDbException $ex) {
            throw ExceptionFactory::factory($ex);
        }
    }

    #[Override]
    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'Item' => $this->itemData,
        ]);
    }
}
