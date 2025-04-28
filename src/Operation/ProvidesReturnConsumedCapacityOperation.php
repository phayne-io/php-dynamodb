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

use Phayne\DynamoDB\Enum\ReturnConsumedCapacityOption;

/**
 * Trait ProvidesReturnConsumedCapacityOperation
 *
 * @package Phayne\DynamoDB\Operation
 */
trait ProvidesReturnConsumedCapacityOperation
{
    protected ReturnConsumedCapacityOption $returnConsumedCapacity = ReturnConsumedCapacityOption::NONE;

    final public function setReturnConsumedCapacity(ReturnConsumedCapacityOption $returnConsumedCapacity): static
    {
        $this->returnConsumedCapacity = $returnConsumedCapacity;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'ReturnConsumedCapacity' => $this->returnConsumedCapacity->value
        ];
    }
}
