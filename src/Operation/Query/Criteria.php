<?php

/**
 * This file is part of phayne-io/php-dynamodb and is proprietary and confidential.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 * @see       https://github.com/phayne-io/php-dynamodb for the canonical source repository
 * @copyright Copyright (c) 2024-2025 Phayne Limited. (https://phayne.io)
 */

declare(strict_types=1);

namespace Phayne\DynamoDB\Operation\Query;

/**
 * Class Criteria
 *
 * @package Phayne\DynamoDB\Operation\Query
 */
final readonly class Criteria
{
    private array $criteria;

    public function __construct(Criterion ...$criteria)
    {
        $this->criteria = $criteria;
    }

    public static function from(Criterion ...$criteria): Criteria
    {
        return new self(...$criteria);
    }

    public function toArray(): array
    {
        $criteria = [];
        foreach ($this->criteria as $criterion) {
            $criteria[$criterion->field] = [
                'operator' => $criterion->operator->value,
                'value' => $criterion->value,
            ];
        }

        return $criteria;
    }
}
