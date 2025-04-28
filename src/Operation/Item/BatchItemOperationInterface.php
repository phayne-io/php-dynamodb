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
 * Interface BatchItemOperationInterface
 *
 * @package Phayne\DynamoDB\Operation\Item
 */
interface BatchItemOperationInterface extends OperationInterface
{
    public function setRequestItems(array $requestItems): self;
}
