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
use Phayne\Collection\Collection;
use Phayne\Collection\CollectionInterface;
use Phayne\DynamoDB\Enum\Operator;
use Phayne\DynamoDB\Exception\ExceptionFactory;
use UnexpectedValueException;

/**
 * Class QueryOperation
 *
 * @package Phayne\DynamoDB\Operation
 */
class QueryOperation extends Search\AbstractSearchOperation
{
    /**
     * @var string The condition that specifies the key values for items to be retrieved.
     */
    private string $keyConditionExpression = '';

    /**
     * @var bool Whether to scan forward.
     */
    private bool $scanIndexForward = false;

    /**
     * QueryRequest constructor.
     *
     * @param DynamoDbClient $dynamoDbClient The DynamoDb client.
     * @param Marshaler $marshaler The Marshaler.
     * @param string $tableName The table name.
     * @param array $keyConditions OPTIONAL The key conditions.
     * @throws UnexpectedValueException
     */
    public function __construct(
        DynamoDbClient $dynamoDbClient,
        Marshaler $marshaler,
        string $tableName,
        array $keyConditions = []
    ) {
        parent::__construct($dynamoDbClient, $marshaler, $tableName);

        if (! empty($keyConditions)) {
            foreach ($keyConditions as $key => $condition) {
                switch ($key) {
                    case 'index':
                        $this->setIndexName($condition);
                        break;
                    case 'partition':
                        $this->setPartitionKeyConditionExpression(
                            $condition['name'],
                            $condition['value']
                        );
                        break;
                    case 'sort':
                        $this->setSortKeyConditionExpression(
                            $condition['name'],
                            $condition['operator'],
                            $condition['value']
                        );
                        break;
                    default:
                        $this->setExpression([
                            $key => $condition,
                        ]);
                        break;
                }
            }
        }
    }

    /**
     * Sets condition expression for the partition key.
     *
     * @param string $keyName The name of the partition key.
     * @param mixed $value The desired value.
     * @return QueryOperation This object.
     * @throws UnexpectedValueException Thrown when an unsupported operator is requested.
     */
    public function setPartitionKeyConditionExpression(string $keyName, mixed $value): QueryOperation
    {
        $partitionKeyConditionExpression = Operator::parseExpression(Operator::EQ, $keyName);
        $this->addExpressionAttributeValue($keyName, $value);
        $this->keyConditionExpression = $partitionKeyConditionExpression;
        return $this;
    }

    /**
     * Sets condition expressions for the sort key.
     *
     * @param string $keyName The name of the sort key.
     * @param string $operator The operator.
     * @param mixed $value The desired value.
     * @return QueryOperation This object.
     * @throws UnexpectedValueException Thrown when an unsupported operator is requested.
     */
    public function setSortKeyConditionExpression(string $keyName, string $operator, mixed $value): QueryOperation
    {
        $sortKeyConditionExpression = Operator::parseExpression($operator, $keyName);
        $this->addExpressionAttributeValue($keyName, $value);
        $this->keyConditionExpression .= ' AND ' . $sortKeyConditionExpression;
        return $this;
    }

    /**
     * Registers the ScanIndexForward value with this object.
     *
     * @param boolean $scanIndexForward Whether to scan forward.
     * @return QueryOperation This object.
     */
    public function setScanIndexForward(bool $scanIndexForward): QueryOperation
    {
        $this->scanIndexForward = $scanIndexForward;
        return $this;
    }

    #[Override]
    public function execute(): CollectionInterface
    {
        try {
            $results = $this->dynamoDbClient->query($this->toArray());
            $rows = [];
            foreach ($results['Items'] as $item) {
                $rows[] = $this->marshaler->unmarshalItem($item);
            }
            return new Collection('array', $rows)->limit($this->offset);
        } catch (DynamoDbException $exception) {
            throw ExceptionFactory::factory($exception);
        }
    }

    #[Override]
    public function toArray(): array
    {
        $operation = parent::toArray();
        $operation['ScanIndexForward'] = $this->scanIndexForward;

        if (! empty($this->keyConditionExpression)) {
            $operation['KeyConditionExpression'] = $this->keyConditionExpression;
        }

        return $operation;
    }
}
