<?php

/**
 * This file is part of phayne-io/php-dynamodb and is proprietary and confidential.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 * @see       https://github.com/phayne-io/php-dynamodb for the canonical source repository
 * @copyright Copyright (c) 2024-2025 Phayne Limited. (https://phayne.io)
 */

declare(strict_types=1);

namespace Phayne\DynamoDB\Operation\Item;

use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Marshaler;
use Override;
use Phayne\DynamoDB\Operation\AbstractOperation;
use Phayne\DynamoDB\Operation\ProvidesExpressionAttributeValue;
use Phayne\DynamoDB\Operation\ProvidesReturnConsumedCapacityOperation;
use Phayne\DynamoDB\Operation\Table\ProvidesTableOperation;

/**
 * Class AbstractItemOperation
 *
 * @package Phayne\DynamoDB\Operation\Item
 */
abstract class AbstractItemOperation extends AbstractOperation implements ItemOperationInterface
{
    use ProvidesTableOperation;
    use ProvidesExpressionAttributeValue;
    use ProvidesReturnConsumedCapacityOperation {
        ProvidesTableOperation::toArray as tableAwareTraitToArray;
        ProvidesExpressionAttributeValue::toArray as expressionAwareTraitToArray;
        ProvidesReturnConsumedCapacityOperation::toArray as returnConsumedCapacityAwareTraitToArray;
    }

    /**
     * Registers the DynamoDb client, Marshaler, table name, and primary key with this object.
     *
     * @param DynamoDbClient $client The DynamoDb client.
     * @param Marshaler $marshaler The Marshaler.
     * @param string $tableName The table name.
     * @param array|null $primaryKey The primary key.
     */
    public function __construct(
        DynamoDbClient $client,
        Marshaler $marshaler,
        string $tableName,
        protected ?array $primaryKey = null
    ) {
        parent::__construct($client, $marshaler);
        $this->setTableName($tableName);

        if (null !== $primaryKey) {
            $this->setPrimaryKey($primaryKey);
        }
    }

    #[Override]
    public function setPrimaryKey(array $primaryKey): self
    {
        $this->primaryKey = $this->marshaler->marshalItem($primaryKey);
        return $this;
    }

    #[Override]
    public function toArray(): array
    {
        $operation = $this->tableAwareTraitToArray();
        $operation += $this->returnConsumedCapacityAwareTraitToArray();
        $operation += $this->expressionAwareTraitToArray();
        $operation['Key'] = $this->primaryKey;
        return $operation;
    }
}
