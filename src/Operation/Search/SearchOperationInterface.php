<?php

/**
 * This file is part of phayne-io/php-dynamodb and is proprietary and confidential.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 * @see       https://github.com/phayne-io/php-dynamodb for the canonical source repository
 * @copyright Copyright (c) 2024-2025 Phayne Limited. (https://phayne.io)
 */

declare(strict_types=1);

namespace Phayne\DynamoDB\Operation\Search;

use Phayne\DynamoDB\Operation\OperationInterface;

/**
 * Interface SearchOperationInterface
 *
 * @package Phayne\DynamoDB\Operation\Search
 */
interface SearchOperationInterface extends OperationInterface
{
    /**
     * Registers the read consistency model setting with this object.
     *
     * @param boolean $consistentRead Whether the read should be consistent.
     * @return SearchOperationInterface This object.
     */
    public function setConsistentRead(bool $consistentRead): SearchOperationInterface;

    /**
     * Registers the name of the secondary index to use.
     *
     * @param string $indexName The name of the secondary index to use.
     * @return SearchOperationInterface This object.
     */
    public function setIndexName(string $indexName): SearchOperationInterface;

    /**
     * Registers the attributes to retrieve from the specified table or index.
     *
     * @param string $projectionExpression The attributes to retrieve from the specified table or index.
     * @return SearchOperationInterface This object.
     */
    public function setProjectionExpression(string $projectionExpression): SearchOperationInterface;

    /**
     * Registers the attributes to be return in the result.
     *
     * @param string $select The attributes to be return in the result.
     * @return SearchOperationInterface This object.
     */
    public function setSelect(string $select): SearchOperationInterface;
}
