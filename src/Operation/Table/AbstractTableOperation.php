<?php

/**
 * This file is part of phayne-io/php-dynamodb and is proprietary and confidential.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 * @see       https://github.com/phayne-io/php-dynamodb for the canonical source repository
 * @copyright Copyright (c) 2024-2025 Phayne Limited. (https://phayne.io)
 */

declare(strict_types=1);

namespace Phayne\DynamoDB\Operation\Table;

use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Marshaler;
use Override;
use Phayne\DynamoDB\Operation\AbstractOperation;

/**
 * Class AbstractTableOperation
 *
 * @package Phayne\DynamoDB\Operation\Table
 */
abstract class AbstractTableOperation extends AbstractOperation implements TableOperationInterface
{
    use ProvidesTableOperation {
        ProvidesTableOperation::toArray as tableAwareTraitToArray;
    }

    public function __construct(DynamoDbClient $client, Marshaler $marshaler, ?string $tableName = null)
    {
        parent::__construct($client, $marshaler);

        if (null !== $tableName) {
            $this->setTableName($tableName);
        }
    }

    #[Override]
    public function toArray(): array
    {
        return $this->tableAwareTraitToArray();
    }
}
