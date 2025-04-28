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
use Phayne\DynamoDB\Enum\Operator;
use Phayne\DynamoDB\Exception\ExceptionFactory;
use UnexpectedValueException;

/**
 * Class UpdateItemOperation
 *
 * @package Phayne\DynamoDB\Operation
 */
class UpdateItemOperation extends Item\AbstractItemOperation
{
    protected string $expressionFieldName = 'UpdateExpression';

    protected string $expressionSeparator = ', ';

    /**
     * Registers the DynamoDb client, Marshaler, table name, and item data with this object.
     *
     * @param DynamoDbClient $client The DynamoDb client.
     * @param Marshaler $marshaler The Marshaler.
     * @param string $tableName The table name.
     * @param array $primaryKey The primary key of the item to update.
     * @param array $updateData The update data.
     * @throws UnexpectedValueException Thrown when an invalid operator is provided.
     */
    public function __construct(
        DynamoDbClient $client,
        Marshaler $marshaler,
        string $tableName,
        array $primaryKey,
        array $updateData
    ) {
        parent::__construct($client, $marshaler, $tableName, $primaryKey);
        $updateDataArray = [];

        foreach ($updateData as $key => $options) {
            if (!isset($options['operator'])) {
                $updateDataArray[$key] = [
                    'operator' => Operator::EQ,
                    'value' => $options
                ];
            } else {
                $updateDataArray[$key] = $options;
            }
        }

        $this->setExpression($updateDataArray);
    }

    #[Override]
    public function execute(): bool
    {
        try {
            $this->dynamoDbClient->updateItem($this->toArray());
            return true;
        } catch (DynamoDbException $exception) {
            throw ExceptionFactory::factory($exception);
        }
    }

    #[Override]
    public function toArray(): array
    {
        $operation = parent::toArray();
        if (! empty($this->expression)) {
            $operation[$this->expressionFieldName] = 'SET ' . $operation['UpdateExpression'];
        }
        return $operation;
    }
}
