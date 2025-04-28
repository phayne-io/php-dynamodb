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

use Phayne\DynamoDB\Operation\OperationInterface;

/**
 * Interface ItemOperationInterface
 *
 * @package Phayne\DynamoDB\Operation\Item
 */
interface ItemOperationInterface extends OperationInterface
{
    /**
     * Registers the operation's primary key with this object.
     *
     * @param array $primaryKey The primary key values to be used when retrieving items.
     * @return self This object.
     */
    public function setPrimaryKey(array $primaryKey): self;
}
