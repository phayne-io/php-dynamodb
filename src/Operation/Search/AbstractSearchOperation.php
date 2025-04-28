<?php

/**
 * This file is part of phayne-io/php-dynamodb and is proprietary and confidential.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 * @see       https://github.com/phayne-io/php-dynamodb for the canonical source repository
 * @copyright Copyright (c) 2024-2025 Phayne Limited. (https://phayne.io)
 */

declare(strict_types=1);

namespace Phayne\DynamoDB\Operation\Search;

use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Marshaler;
use Override;
use Phayne\DynamoDB\Operation\AbstractOperation;
use Phayne\DynamoDB\Operation\ProvidesExpressionAttributeValue;
use Phayne\DynamoDB\Operation\ProvidesReturnConsumedCapacityOperation;
use Phayne\DynamoDB\Operation\Table\ProvidesTableOperation;

/**
 * Class AbstractSearchOperation
 *
 * @package Phayne\DynamoDB\Operation\Search
 */
abstract class AbstractSearchOperation extends AbstractOperation implements SearchOperationInterface
{
    use ProvidesTableOperation;
    use ProvidesLimitOperation;
    use ProvidesReturnConsumedCapacityOperation;
    use ProvidesExpressionAttributeValue {
        ProvidesTableOperation::toArray as tableAwareTraitToArray;
        ProvidesLimitOperation::toArray as limitAwareTraitToArray;
        ProvidesExpressionAttributeValue::toArray as expressionAwareTraitToArray;
        ProvidesReturnConsumedCapacityOperation::toArray as returnConsumedCapacityAwareTraitToArray;
    }

    /**
     * @var boolean Whether the read should be consistent.
     */
    private bool $consistentRead = false;

    /**
     * @var string The name of a secondary index to request against.
     */
    private string $indexName = '';

    /**
     * @var string The attributes to retrieve from the specified table or index.
     */
    private string $projectionExpression = '';

    /**
     * @var string The attributes to be returned into the result.
     */
    private string $select = '';

    public function __construct(DynamoDbClient $dynamoDbClient, Marshaler $marshaler, string $tableName = '')
    {
        parent::__construct($dynamoDbClient, $marshaler);
        $this->tableName = $tableName;
    }

    #[Override]
    final public function setConsistentRead(bool $consistentRead): SearchOperationInterface
    {
        $this->consistentRead = $consistentRead;
        return $this;
    }

    #[Override]
    final public function setIndexName(string $indexName): SearchOperationInterface
    {
        $this->indexName = $indexName;
        return $this;
    }

    #[Override]
    final public function setSelect(string $select): SearchOperationInterface
    {
        $this->select = $select;
        return $this;
    }

    #[Override]
    final public function setProjectionExpression(string $projectionExpression): SearchOperationInterface
    {
        $this->projectionExpression = $projectionExpression;
        return $this;
    }

    #[Override]
    public function toArray(): array
    {
        $operation = $this->tableAwareTraitToArray();

        if ($this->offset || $this->limit) {
            $operation += $this->limitAwareTraitToArray();
        }
        $operation += $this->returnConsumedCapacityAwareTraitToArray();
        $operation += $this->expressionAwareTraitToArray();
        $operation['ConsistentRead'] = $this->consistentRead;

        if (! empty($this->indexName)) {
            $operation['IndexName'] = $this->indexName;
        }

        if (! empty($this->select)) {
            $operation['Select'] = $this->select;
        }

        if (! empty($this->projectionExpression)) {
            $operation['ProjectionExpression'] = $this->projectionExpression;
        }

        return $operation;
    }
}
