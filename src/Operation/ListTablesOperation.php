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

use Override;

/**
 * Class ListTablesOperation
 *
 * @package Phayne\DynamoDB\Operation
 */
final class ListTablesOperation extends Table\AbstractTableOperation
{
    use Search\ProvidesLimitOperation {
        Search\ProvidesLimitOperation::toArray as limitAwareTraitToArray;
    }

    /**
     * @var string The name of the last table in the current page of results.
     */
    protected string $lastEvaluatedTableName = '';

    /**
     * Registers the name of table to be used as the last in the current page of results.
     *
     * @param string $lastEvaluatedTableName The name of the last table in the current page of results.
     * @return ListTablesOperation This object.
     */
    public function setLastEvaluatedTableName(string $lastEvaluatedTableName): ListTablesOperation
    {
        $this->lastEvaluatedTableName = $lastEvaluatedTableName;
        return $this;
    }

    #[Override]
    public function execute(): ?array
    {
        $tables = $this->dynamoDbClient->listTables($this->toArray());
        return $tables['TableNames'];
    }

    #[Override]
    public function toArray(): array
    {
        $operation = parent::toArray();
        unset($operation['TableName']);

        if ($this->lastEvaluatedTableName) {
            $operation['LastEvaluatedTableName'] = $this->lastEvaluatedTableName;
        }

        if ($this->offset || $this->limit) {
            $operation += $this->limitAwareTraitToArray();
        }

        return $operation;
    }
}
