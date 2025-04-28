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

/**
 * Interface OperationInterface
 *
 * @package Phayne\DynamoDB\Operation
 */
interface OperationInterface
{
    public function execute(): mixed;

    public function toArray(): array;
}
