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

/**
 * Trait ProvidesLimitOperation
 *
 * @package Phayne\DynamoDB\Operation\Search
 */
trait ProvidesLimitOperation
{
    /**
     * @var int The result offset.
     */
    protected int $offset = 0;

    /**
     * @var int|null The result limit.
     */
    protected ?int $limit = null;

    /**
     * Registers the result offset with this object.
     *
     * @param integer $offset The result offset.
     * @return static An implementation of this trait.
     */
    final public function setOffset(int $offset): static
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * Registers the result limit with this object.
     *
     * @param integer|null $limit The result limit.
     * @return static An implementation of this trait.
     */
    final public function setLimit(?int $limit): static
    {
        $this->limit = $limit;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'Limit' => $this->offset + $this->limit
        ];
    }
}
