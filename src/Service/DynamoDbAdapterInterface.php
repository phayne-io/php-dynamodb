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
use Phayne\Collection\CollectionInterface;
use RuntimeException;

/**
 * Interface DynamoDbAdapterInterface
 *
 * @package Phayne\DynamoDB\Service
 */
interface DynamoDbAdapterInterface
{
    /**
     * Creates a table.
     *
     * @param array $data The table options.
     * @param string|null $tableName OPTIONAL The name of the table to create.
     * @param array|null $options OPTIONAL The table options.
     * @return bool Whether the table creation was successful.
     * @throws RuntimeException Thrown when an error occurs during creation.
     * @see CreateTableOperation
     */
    public function createTable(array $data, ?string $tableName = '', ?array $options = []): bool;

    /**
     * Deletes a table.
     *
     * @param string|null $tableName OPTIONAL The name of the table to delete.
     * @return boolean Whether the table deletion was successful.
     * @throws RuntimeException Thrown when an error occurs during deletion.
     * @see DeleteItemOperation
     */
    public function deleteTable(?string $tableName = null): bool;

    /**
     * Returns information about a table.
     *
     * @param string|null $tableName OPTIONAL The name of the table to delete.
     * @return array The table data.
     * @throws RuntimeException Thrown when an error occurs during the existence check.
     * @see DescribeTableOperation
     */
    public function describeTable(?string $tableName = null): array;

    /**
     * Determines whether a table exists.
     *
     * @param string|null $tableName OPTIONAL The name of the table to delete.
     * @return boolean Whether the table exists.
     * @see ListTablesOperation
     */
    public function tableExists(?string $tableName = null): bool;

    /**
     * Returns an array of the existing tables.
     *
     * @return array An array of the existing tables.
     * @see ListTablesOperation
     */
    public function listTables(): array;

    /**
     * Specifies a client instance to be used during an operation
     *
     * @param DynamoDbClient $dynamoDbClient
     * @return DynamoDbAdapterInterface
     */
    public function useClient(DynamoDbClient $dynamoDbClient): DynamoDbAdapterInterface;

    /**
     * Specifies the table to be used during an operation.
     *
     * @param string $tableName The table name.
     * @return DynamoDbAdapterInterface An implementation of this interface.
     */
    public function useTable(string $tableName): DynamoDbAdapterInterface;

    /**
     * Retrieves all the items in a database table that meet the provided conditions.
     *
     * When an offset and limit are provided, the desired slice is returned.
     *
     * @param array $conditions The conditions.
     * @param integer $offset OPTIONAL The offset.
     * @param integer|null $limit OPTIONAL The limit.
     * @return CollectionInterface A collection of rows.
     * @throws RuntimeException Thrown when a query error occurs.
     * @see QueryOperation
     * @see DynamoDbAdapterInterface::useTable()
     */
    public function findWhere(array $conditions, int $offset = 0, ?int $limit = null): CollectionInterface;

    /**
     * Retrieves all the items in a database table.
     *
     * When an offset and limit are provided, the desired slice is returned.
     *
     * @param integer $offset OPTIONAL The offset.
     * @param integer|null $limit OPTIONAL The limit.
     * @param array $conditions OPTIONAL The conditions.
     * @return CollectionInterface A collection of rows.
     * @throws RuntimeException Thrown when a scan error occurs.
     * @see ScanOperation
     * @see DynamoDbAdapterInterface::useTable()
     */
    public function findAll(array $conditions = [], int $offset = 0, ?int $limit = null): CollectionInterface;

    /**
     * Retrieves an item or items from a table or tables.
     *
     * @param array $primaryKey The item primary key or a map of tables to primary keys.
     * @return array The item.
     * @throws RuntimeException Thrown when an operation error occurs.
     * @see GetItemOperation
     * @see DynamoDbAdapterInterface::useTable()
     */
    public function find(array $primaryKey): array;

    /**
     * Inserts an item into a table.
     *
     * @param array $data The item data.
     * @return bool Whether the item creation was successful.
     * @throws RuntimeException Thrown when an operation error occurs.
     * @see PutItemOperation
     * @see DynamoDbAdapterInterface::useTable()
     */
    public function insert(array $data): bool;

    /**
     * Updates an item in a table.
     *
     * @param array $primaryKey The primary key of the item to be updated.
     * @param array $data The update data.
     * @return bool Whether the item creation was successful.
     * @throws RuntimeException Thrown when an operation error occurs.
     * @see PutItemOperation
     * @see DynamoDbAdapterInterface::useTable()
     */
    public function update(array $primaryKey, array $data): bool;

    /**
     * Deletes an item from a table.
     *
     * @param array $primaryKey The item primary key.
     * @return bool Whether the item deletion was successful.
     * @throws RuntimeException Thrown when an operation error occurs.
     * @see DeleteItemOperation
     * @see DynamoDbAdapterInterface::useTable()
     */
    public function delete(array $primaryKey): bool;
}
