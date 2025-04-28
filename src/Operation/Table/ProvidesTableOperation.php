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

/**
 * Trait ProvidesTableOperation
 *
 * @package Phayne\DynamoDB\Operation\Table
 */
trait ProvidesTableOperation
{
    protected string $tableName = '';

    final public function setTableName(string $tableName): static
    {
        $this->tableName = $tableName;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'TableName' => $this->tableName,
        ];
    }
}
