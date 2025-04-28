<?php

/**
 * This file is part of phayne-io/php-dynamodb and is proprietary and confidential.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 * @see       https://github.com/phayne-io/php-dynamodb for the canonical source repository
 * @copyright Copyright (c) 2024-2025 Phayne Limited. (https://phayne.io)
 */

declare(strict_types=1);

namespace Phayne\DynamoDB\Service;

use Aws\DynamoDb\DynamoDbClient;
use Override;
use Phayne\Collection\CollectionInterface;
use Phayne\DynamoDB\Operation;

use function array_key_exists;
use function array_map;
use function in_array;
use function is_array;
use function strtolower;

/**
 * Class DynamoDbAdapter
 *
 * @package Phayne\DynamoDB\Service
 */
class DynamoDbAdapter implements DynamoDbAdapterInterface
{
    private ?string $tableName = null;

    public function __construct(private readonly Operation\OperationFactory $operationFactory)
    {
    }

    #[Override]
    public function createTable(array $data, ?string $tableName = '', ?array $options = []): bool
    {
        $tableName = (null === $tableName ? ($this->tableName ?: '') : $tableName);
        return $this->operationFactory->factory('create-table', $tableName, $data)->execute();
    }

    #[Override]
    public function deleteTable(?string $tableName = null): bool
    {
        $tableName = (null === $tableName ? ($this->tableName ?: '') : $tableName);
        return $this->operationFactory->factory('delete-table', $tableName)->execute();
    }

    #[Override]
    public function describeTable(?string $tableName = null): array
    {
        $tableName = (null === $tableName ? ($this->tableName ?: '') : $tableName);
        return $this->operationFactory->factory('describe-table', $tableName)->execute();
    }

    #[Override]
    public function tableExists(?string $tableName = null): bool
    {
        $tableName = (null === $tableName ? ($this->tableName ?: '') : $tableName);
        $tables = $this->listTables();

        if (empty($tables)) {
            return false;
        }

        return in_array(
            strtolower($tableName),
            array_map('strtolower', $tables)
        );
    }

    #[Override]
    public function listTables(): array
    {
        return $this->operationFactory->factory('list-tables')->execute();
    }

    #[Override]
    public function useTable(string $tableName): DynamoDbAdapterInterface
    {
        $this->tableName = $tableName;
        return $this;
    }

    #[Override]
    public function useClient(DynamoDbClient $dynamoDbClient): DynamoDbAdapterInterface
    {
        $this->operationFactory->withClient($dynamoDbClient);
        return $this;
    }

    #[Override]
    public function findWhere(array $conditions, int $offset = 0, ?int $limit = null): CollectionInterface
    {
        /** @var Operation\QueryOperation $operation */
        $operation = $this->operationFactory->factory('query', $this->tableName, $conditions);

        return $operation
            ->setOffset($offset)
            ->setLimit($limit)
            ->execute();
    }

    #[Override]
    public function findAll(array $conditions = [], int $offset = 0, ?int $limit = null): CollectionInterface
    {
        /** @var Operation\ScanOperation $operation */
        $operation = $this->operationFactory->factory('scan', $this->tableName);
        if (
            array_key_exists('fields', $conditions) &&
            is_array($conditions['fields']) &&
            ! empty($conditions['fields'])
        ) {
            $operation->setProjectionExpression(implode(', ', $conditions['fields']));
        }
        if (
            array_key_exists('expression', $conditions) &&
            is_array($conditions['expression']) &&
            ! empty($conditions['expression'])
        ) {
            $operation->setExpression($conditions['expression']);
        }
        return $operation
            ->setOffset($offset)
            ->setLimit($limit)
            ->execute();
    }

    #[Override]
    public function find(array $primaryKey): array
    {
        foreach ($primaryKey as $value) {
            if (is_array($value)) {
                return $this->operationFactory->factory('batch-get-item', $primaryKey)->execute();
            }
        }
        return $this->operationFactory->factory('get-item', $this->tableName, $primaryKey)->execute();
    }

    #[Override]
    public function insert(array $data): bool
    {
        return $this->operationFactory->factory('put-item', $this->tableName, $data)->execute();
    }

    #[Override]
    public function update(array $primaryKey, array $data): bool
    {
        return $this->operationFactory->factory('update-item', $this->tableName, $primaryKey, $data)->execute();
    }

    #[Override]
    public function delete(array $primaryKey): bool
    {
        return $this->operationFactory->factory('delete-item', $this->tableName, $primaryKey)->execute();
    }
}
