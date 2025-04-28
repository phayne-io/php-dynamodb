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
use Phayne\DynamoDB\Enum\BillingMode;
use Phayne\DynamoDB\Enum\KeyType;
use Phayne\DynamoDB\Enum\ProjectionType;
use Phayne\DynamoDB\Exception\ExceptionFactory;

/**
 * Class CreateTableOperation
 *
 * @package Phayne\DynamoDB\Operation
 */
class CreateTableOperation extends Table\AbstractTableOperation
{
    /**
     * @var array Attributes describing the key schema.
     */
    private array $attributeDefinitions = [];

    /**
     * @var BillingMode The billing mode.
     */
    private BillingMode $billingMode = BillingMode::PROVISIONED;

    /**
     * @var array The global secondary indexes.
     */
    private array $globalSecondaryIndexes = [];

    /**
     * @var array The local secondary indexes.
     */
    private array $localSecondaryIndexes = [];

    /**
     * @var array The primary key.
     */
    private array $keySchema = [];

    /**
     * @var int The maximum number of strongly consistent reads consumed per second.
     */
    private int $readCapacityUnits = 5;

    /**
     * @var array The server-side encryption settings.
     */
    private array $sseSpecification = [];

    /**
     * @var array The stream specification.
     */
    private array $streamSpecification = [];

    /**
     * @var array The tags.
     */
    private array $tags = [];

    /**
     * @var int The maximum number of writes consumed per second.
     */
    private int $writeCapacityUnits = 5;

    /**
     * CreateTableRequest constructor.
     *
     * @param DynamoDbClient $client The DynamoDb client.
     * @param Marshaler $marshaler The Marshaler.
     * @param string $tableName The table name.
     * @param array|null $keySchema OPTIONAL The key schema.
     */
    public function __construct(
        DynamoDbClient $client,
        Marshaler $marshaler,
        string $tableName,
        ?array $keySchema = null
    ) {
        parent::__construct($client, $marshaler, $tableName);

        if (null !== $keySchema) {
            $this->setKeySchema($keySchema);
        }
    }

    /**
     * Registers the key schema and attribute definitions.
     *
     * The key schema argument should be an associative array with the following keys:
     *
     * $keySchema = [
     *      'MyAttribute' => [ // this is the name of your attribute
     *          'S', // this must be one of the AttributeTypes constants
     *          'HASH' // this must be one of the KeyTypes constants
     *     ]
     * ];
     *
     * This method will use the information available in the provided array to build the 'KeySchema' and
     * 'AttributeDefinitions' arrays needed for table creation requests.
     *
     * @param array $keySchema The key schema.
     * @return CreateTableOperation This object.
     */
    public function setKeySchema(array $keySchema): CreateTableOperation
    {
        foreach ($keySchema as $name => $data) {
            $this->keySchema[] = [
                'AttributeName' => $name,
                'KeyType' => $data[1]
            ];
            $this->attributeDefinitions[] = [
                'AttributeName' => $name,
                'AttributeType' => $data[0]
            ];
        }
        return $this;
    }

    /**
     * Registers the partition key.
     *
     * @param string $name The name of the partition key.
     * @param string $attributeType The attribute type.
     * @return CreateTableOperation This object.
     */
    public function setPartitionKey(string $name, string $attributeType): CreateTableOperation
    {
        $this->setKeySchema([
            $name => [$attributeType, KeyType::HASH]
        ]);
        return $this;
    }

    /**
     * Registers the sort key.
     *
     * @param string $name The name of the sort key.
     * @param string $attributeType The attribute type.
     * @return CreateTableOperation This object.
     */
    public function setSortKey(string $name, string $attributeType): CreateTableOperation
    {
        $this->setKeySchema([
            $name => [$attributeType, KeyType::RANGE]
        ]);
        return $this;
    }

    /**
     * Registers the maximum number of strongly consistent reads consumed per second.
     *
     * @param integer $readCapacityUnits The maximum number of strongly consistent reads consumed per second.
     * @return CreateTableOperation This object.
     */
    public function setReadCapacityUnits(int $readCapacityUnits): CreateTableOperation
    {
        $this->readCapacityUnits = $readCapacityUnits;
        return $this;
    }

    /**
     * Registers the maximum number of writes consumed per second.
     *
     * @param integer $writeCapacityUnits The maximum number of writes consumed per second.
     * @return CreateTableOperation This object.
     */
    public function setWriteCapacityUnits(int $writeCapacityUnits): CreateTableOperation
    {
        $this->writeCapacityUnits = $writeCapacityUnits;
        return $this;
    }

    /**
     * Registers the billing mode.
     *
     * @param BillingMode $billingMode The billing mode.
     * @return CreateTableOperation This object.
     */
    public function setBillingMode(BillingMode $billingMode): CreateTableOperation
    {
        $this->billingMode = $billingMode;
        return $this;
    }

    /**
     * Registers the server-side encryption settings.
     *
     * @param bool $isEnabled Whether SSE is enabled.
     * @param string|null $masterKeyId OPTIONAL The ID of the master key.
     * @return CreateTableOperation This object.
     */
    public function setSSESpecification(bool $isEnabled, ?string $masterKeyId = null): CreateTableOperation
    {
        $sseSpecification = [];

        if ($isEnabled) {
            $sseSpecification = [
                'Enabled' => $isEnabled,
                'SSEType' => 'KMS'
            ];

            if (null !== $masterKeyId) {
                $sseSpecification['KMSMasterKeyId'] = $masterKeyId;
            }
        }
        $this->sseSpecification = $sseSpecification;

        return $this;
    }

    /**
     * Adds a global secondary index.
     *
     * @see CreateTableOperation::addSecondaryIndex()
     * @param string $indexName The index name.
     * @param array $keySchema The key schema.
     * @param array $projection The projection.
     * @param array|null $provisionedThroughput OPTIONAL The provisioned throughput.
     * @return CreateTableOperation This object.
     */
    public function addGlobalSecondaryIndex(
        string $indexName,
        array $keySchema,
        array $projection,
        ?array $provisionedThroughput = null
    ): CreateTableOperation {
        return $this->addSecondaryIndex('global', $indexName, $keySchema, $projection, $provisionedThroughput);
    }

    /**
     * Adds a local secondary index.
     *
     * @see CreateTableOperation::addSecondaryIndex()
     * @param string $indexName The index name.
     * @param array $keySchema The key schema.
     * @param array $projection The projection.
     * @return CreateTableOperation This object.
     */
    public function addLocalSecondaryIndex(
        string $indexName,
        array $keySchema,
        array $projection
    ): CreateTableOperation {
        return $this->addSecondaryIndex('local', $indexName, $keySchema, $projection);
    }

    /**
     * Registers the stream specification.
     *
     * @param bool $isEnabled Whether the stream specification is enabled.
     * @param string|null $viewType OPTIONAL The stream view type.
     * @return CreateTableOperation This object.
     */
    public function setStreamSpecification(bool $isEnabled, ?string $viewType = null): CreateTableOperation
    {
        $this->streamSpecification = [
            'StreamEnabled' => $isEnabled
        ];

        if ($isEnabled && null !== $viewType) {
            $this->streamSpecification['StreamViewType'] = $viewType;
        }

        return $this;
    }

    /**
     * Registers a tag.
     *
     * @param string $key The tag key.
     * @param string $value The tag value.
     * @return CreateTableOperation This object.
     */
    public function addTag(string $key, string $value): CreateTableOperation
    {
        $this->tags[] = [
            'Key' => $key,
            'Value' => $value
        ];
        return $this;
    }

    #[Override]
    public function execute(): bool
    {
        try {
            $this->dynamoDbClient->createTable($this->toArray());
            //$this->client->waitUntil('TableExists', ['TableName' => $this->toArray()['TableName']]);
            return true;
        } catch (DynamoDbException $exception) {
            throw ExceptionFactory::factory($exception);
        }
    }

    #[Override]
    public function toArray(): array
    {
        $operation = parent::toArray();
        $operation['AttributeDefinitions'] = $this->attributeDefinitions;
        $operation['BillingMode'] = $this->billingMode->value;

        if (!empty($this->globalSecondaryIndexes)) {
            $operation['GlobalSecondaryIndexes'] = $this->globalSecondaryIndexes;
        }

        if (!empty($this->localSecondaryIndexes)) {
            $operation['LocalSecondaryIndexes'] = $this->localSecondaryIndexes;
        }
        $operation['KeySchema'] = $this->keySchema;
        $operation['ProvisionedThroughput'] = [
            'ReadCapacityUnits' => $this->readCapacityUnits,
            'WriteCapacityUnits' => $this->writeCapacityUnits,
        ];

        if (!empty($this->sseSpecification)) {
            $operation['SSESpecification'] = $this->sseSpecification;
        }

        if (!empty($this->streamSpecification)) {
            $operation['StreamSpecification'] = $this->streamSpecification;
        }

        if (!empty($this->tags)) {
            $operation['Tags'] = $this->tags;
        }

        return $operation;
    }

    /**
     * Adds a secondary index.
     *
     * @see CreateTableOperation::addSecondaryIndex()
     * @param string $indexType The index type.
     * @param string $indexName The index name.
     * @param array $keySchema The key schema.
     * @param array $projection The projection.
     * @param array|null $provisionedThroughput OPTIONAL The provisioned throughput.
     * @return CreateTableOperation This object.
     */
    private function addSecondaryIndex(
        string $indexType,
        string $indexName,
        array $keySchema,
        array $projection,
        ?array $provisionedThroughput = null
    ): CreateTableOperation {
        $index = [
            'IndexName' => $indexName,
            'KeySchema' => [],
            'Projection' => [],
        ];
        $index['Projection']['ProjectionType'] = $projection['type'] ?? ProjectionType::ALL;

        if (array_key_exists('attributes', $projection)) {
            $index['Projection']['NonKeyAttributes'] = $projection['attributes'];
        }

        foreach ($keySchema as $field => $key) {
            $index['KeySchema'][] = [
                'AttributeName' => $field,
                'KeyType' => $key[1]
            ];
            $hasAttribute = !empty(array_filter($this->attributeDefinitions, function (array $definition) use ($field) {
                return $definition['AttributeName'] === $field;
            }));
            if (false === $hasAttribute) {
                $this->attributeDefinitions[] = [
                    'AttributeName' => $field,
                    'AttributeType' => $key[0]
                ];
            }
        }
        switch ($indexType) {
            case 'local':
                $this->localSecondaryIndexes[] = $index;
                break;
            case 'global':
                $index['ProvisionedThroughput'] = [
                    'ReadCapacityUnits' => null !== $provisionedThroughput
                        ? $provisionedThroughput[0]
                        : $this->readCapacityUnits,
                    'WriteCapacityUnits' => null !== $provisionedThroughput
                        ? $provisionedThroughput[1]
                        : $this->writeCapacityUnits,
                ];
                $this->globalSecondaryIndexes[] = $index;
                break;
        }
        return $this;
    }
}
