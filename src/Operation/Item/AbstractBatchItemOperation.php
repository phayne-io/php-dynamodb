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

use Override;
use Phayne\DynamoDB\Operation\AbstractOperation;
use Phayne\DynamoDB\Operation\ProvidesReturnConsumedCapacityOperation;

/**
 * Class AbstractBatchItemOperation
 *
 * @package Phayne\DynamoDB\Operation\Item
 */
abstract class AbstractBatchItemOperation extends AbstractOperation implements BatchItemOperationInterface
{
    use ProvidesReturnConsumedCapacityOperation {
        ProvidesReturnConsumedCapacityOperation::toArray as returnConsumedCapacityAwareOperationTraitToArray;
    }

    /**
     * The request items.
     *
     * @var array
     */
    protected array $requestItems = [];

    #[Override]
    final public function setRequestItems(array $requestItems): self
    {
        $this->requestItems = $requestItems;
        return $this;
    }

    #[Override]
    public function toArray(): array
    {
        $operation = $this->returnConsumedCapacityAwareOperationTraitToArray();
        $operation['RequestItems'] = $this->requestItems;
        return $operation;
    }
}
